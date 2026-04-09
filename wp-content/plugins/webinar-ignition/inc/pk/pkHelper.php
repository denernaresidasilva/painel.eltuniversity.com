<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * What it should do:
 * 1. get a list of webinars to return to paykickstart campaign
 * 2. verify that ipn request is valid
 * 3. register user for webinar and send them to thank you page.
 * */

class PK_HELPER {

	private $secret_key;
	private $post_data = array();
	private $plugin_dir = '';
	private $dbtable = '';
	private $results;
	private $test_mode;

	public function __construct( $post_data, $plugin_dir, $test_mode = false ) {
		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition_wi';
		$this->secret_key = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM  %s LIMIT %d", $table_db_name, 1 ), OBJECT )->keyused;
		$this->post_data = $post_data;
		$this->plugin_dir = $plugin_dir;
		$this->test_mode = $test_mode;
	}

	// todo delete this
	public function webinarignition_for_testing_generate_verification_code() {
		$paramStrArr = array();
		$paramStr = null;

		foreach ( $this->post_data as $key => $value ) {
			// Ignore if it is encrypted key
			if ( $key == 'verification_code' ) {
				continue;
			}
			if ( ! $key || ! $value ) {
				continue;
			}
			$paramStrArr[] = trim( (string) $value );
		}

		ksort( $paramStrArr, SORT_STRING );
		$paramStr = implode( '|', $paramStrArr );
		$encKey = hash_hmac( 'sha1', $paramStr, $this->secret_key );
		return $encKey;
	}

	/*
	|--------------------------------------------------------------------------
	| Fetch Webinars
	|--------------------------------------------------------------------------
	*/
	private function webinarignition_fetch_all_webinars() {
		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition';
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table_db_name"
			),
			OBJECT
		);
		$allWebinars = array();
		foreach ( $results as $result ) {

			// Get Date // Date
			$ID = $result->ID;
			$allWebinars[ $ID ] = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
		}

		return $allWebinars;
	}

	private function webinarignition_filter_webinars( $allWebinars ) {
		$webinars = array();
		foreach ( $allWebinars as $key => $w ) {
			$id = ! empty( $w->id ) ? $w->id : $key;
			$webinar_type = $w->webinar_date === 'AUTO' ? 'evergreen' : 'live';

			// initialize and overwrite variables at the start of each loop iteration
			$formatted_webinar_date = '';
			$webinar_timestamp = '';
			$formatted_current_time_z = '';
			$current_timestamp_z = '';

			$live_webinar_is_in_past = false;
			if ( $webinar_type === 'live' && ! empty( $w->webinar_timezone ) && ! empty( $w->webinar_date ) && ! empty( $w->webinar_start_time ) ) {

				// webinar date is in mm-dd-yyyy format so I am replacing - with / so that strtotime will recognise that format.
				$webinar_slashed_date = str_replace( '-', '/', $w->webinar_date );
				$webinar_timestamp = strtotime( $webinar_slashed_date . ' ' . $w->webinar_start_time );
				$formatted_webinar_date = gmdate( 'd-m-Y H:i:s', $webinar_timestamp );

				// current time by webinar timezone
				$current_time_z = new DateTime( strtotime( time() ), new DateTimeZone( $w->webinar_timezone ) );
				$formatted_current_time_z = $current_time_z->format( 'd-m-Y H:i:s' ); // e.g 26-01-2017 16:40:56
				$current_timestamp_z = strtotime( $formatted_current_time_z );

				$live_webinar_is_in_past = ( $current_timestamp_z > $webinar_timestamp ) ? true : false;
			}

			// Don't add live webinars that have a webinar_date that is in the past and has no replay video.
			if ( $webinar_type === 'live' && empty( $w->replay_video ) && $live_webinar_is_in_past ) {
				continue;
			}
			// only accept evergreen webinars with a fixed schedule type
			if ( $webinar_type === 'evergreen' && $w->lp_schedule_type !== 'fixed' ) {
				continue;
			}

			$webinars[ $id ] = array( 'webinar_id' => $id );
			$webinars[ $id ]['webinar_type'] = $webinar_type;

			if ( $webinar_type === 'live' ) {
				$webinars[ $id ]['in_past'] = $live_webinar_is_in_past;
				$webinars[ $id ]['has_replay_video'] = ! empty( $w->replay_video ) ? true : false;
				// not needed at this time
				// $webinars[$id]['current_datetime_by_timezone'] = $formatted_current_time_z;
				// $webinars[$id]['formatted_webinar_datetime'] = $formatted_webinar_date;
				// $webinars[$id]['current_timestamp_by_timezone'] = $current_timestamp_z;
				// $webinars[$id]['webinar_timestamp'] =  $webinar_timestamp;
			} else {
				$webinars[ $id ]['schedule_type'] = $w->lp_schedule_type;
				$webinars[ $id ]['fixed_timezone'] = $w->auto_timezone_fixed;
				$webinars[ $id ]['fixed_date'] = $w->auto_date_fixed;
				$webinars[ $id ]['fixed_time'] = $w->auto_time_fixed;
			}

			$select = array(
				'webinar_desc',
				'webinar_date',
				'webinar_start_time',
				'webinar_timezone',
				'webinar_host',
			);
			foreach ( $select as $column ) {
				if ( isset( $w->{$column} ) ) {
					$columnName = $column === 'webinar_desc' ? 'webinar_title' : $column;
					$webinars[ $id ][ $columnName ] = $w->{$column};
				}
			}
		}//end foreach

		return $webinars;
	}

	public function webinarignition_fetch_webinars() {
		if ( ! $this->webinarignition_is_valid_request() ) {
			return array(
				'status' => 'failed',
				'message' => 'invalid request',
			);
		}

		$allWebinars = $this->webinarignition_fetch_all_webinars();
		$webinars = $this->webinarignition_filter_webinars( $allWebinars );

		if ( ! empty( $webinars ) ) {
			return array(
				'message' => 'success',
				'webinars' => $webinars,
			// 'all' => $allWebinars, // for testing
			);
		}

		return array( 'message' => 'There are no webinars' );
	}

	/*
	|--------------------------------------------------------------------------
	| Register User For Webinar
	|--------------------------------------------------------------------------
	*/
	public function webinarignition_register_user_for_webinar() {
		if ( ! $this->webinarignition_is_valid_request() ) {
			return array(
				'status' => 'failed',
				'message' => 'invalid request',
			);
		}

		return $this->register();
	}

	private function webinarignition_user_is_registered( $webinar_id, $user_email ) {
		global $wpdb;
		$query = $wpdb->prepare(
			"SELECT ID FROM $table WHERE email = %s AND app_id = %d", 
			$user_email, 
			$webinar_id
		);	}

	/*
	|--------------------------------------------------------------------------
	| Register
	|--------------------------------------------------------------------------
	*/
	private function register() {
		global $wpdb;
		$this->dbtable = ( $this->post_data['webinar_type'] === 'live' ) ? $wpdb->prefix . 'webinarignition_leads' : $wpdb->prefix . 'webinarignition_leads_evergreen';
		$this->results = WebinarignitionManager::webinarignition_get_webinar_data( $this->post_data['webinar_id'] );

		$post = $this->post_data;
		$post['id'] = $post['webinar_id'];
		$post['name'] = ! empty( $post['name'] ) ? $post['name'] : '';
		$post['phone'] = ! empty( $post['phone'] ) ? $post['phone'] : '';
		$post['email'] = $post['buyer_email'];

		if ( $this->webinarignition_user_is_registered( $post['id'], $post['email'] ) ) {
			return array(
				'status' => 'failed',
				'message' => 'email address is already registered',
			);
		}

		if ( $post['webinar_type'] === 'live' ) {
			$status = $this->webinarignition_register_live( $post );
		} else {
			$status = $this->webinarignition_register_evergreen( $post );
		}

		if ( $status === false ) {
			return array(
				'status' => 'failed',
				'message' => 'user could not be registered',
			);
		} else {
			return array(
				'status' => 'success',
				'message' => 'user has been registered for webinar',
			);
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Register Live
	|--------------------------------------------------------------------------
	*/
	private function webinarignition_register_live( $post ) {
		global $wpdb;
		$results = $this->results;
		$user_data = array(
			'app_id' => $post['webinar_id'],
			'name' => $post['name'],
			'email' => $post['email'],
			'phone' => $post['phone'],
			'event' => 'No',
			'replay' => 'No',
			'created' => gmdate( 'F j, Y' ),
		);

		$wpdb->insert(
			$this->dbtable,
			$user_data,
			array(
				'%s', // app_id
				'%s', // name
				'%s', // email
				'%s', // phone
				'%s', // event
				'%s', // replay
				'%s'  // created
			)
		);

		if ( ! $lid = $wpdb->insert_id ) {
			return false;
		}

		$lead_details_string = "Name: {$post['name']}\nEmail: {$post['email']}\n";
		if ( isset( $post['phone'] ) && $post['phone'] != 'undefined' ) {
			$lead_details_string .= "Phone: {$post['phone']}";
		}
		WebinarIgnition_Logs::add( "New Lead Added\n$lead_details_string\n\nFiring registration email", $post['id'], WebinarIgnition_Logs::LIVE_EMAIL );

		// TODO merge evergreen and live email templates into a single method
		// ADD TO MAILING LIST
		$emailBody = $results->email_signup_body;

		// Shortcode :: DATE
		$liveWebbyDate = explode( '-', $results->webinar_date );
		$autoDate = $liveWebbyDate[2] . '-' . $liveWebbyDate[0] . '-' . $liveWebbyDate[1];

		$date_format = get_option( 'date_format' );
		$autoDate_format = gmdate( $date_format, strtotime( $autoDate ) );

		// Final Step = Translate Months
		$months = $results->auto_translate_months;
		$days = $results->auto_translate_days;

		$autoDate_format = webinarignition_translate_dm( $months, $autoDate_format );
		$autoDate_format = webinarignition_translate_dm( $days, $autoDate_format, 'days' );

		// Replace
		$emailBody = str_replace( '{DATE}', $autoDate_format . ' @ ' . webinarignition_get_time_tz( $results->webinar_start_time, $results->webinar_timezone, $results->time_format, $results->time_suffix ), $emailBody );

		$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $results, $lid, $emailBody );

		$this->webinarignition_send_registration_email( $emailBody, $results, $post );

		return true;
	}

	/*
	|--------------------------------------------------------------------------
	| Register Evergreen
	|--------------------------------------------------------------------------
	*/
	private function webinarignition_register_evergreen( $post ) {
		global $wpdb;
		$results = $this->results;

		$webinarLength = $results->auto_video_length;

		// don't know why this is set to empty string (copied code from callback.php leaving as is for now)
		$setCheckInstant = '';

		// this never gets set to yes so will remove it later
		$instant = 'no';

		$lead_timezone = new DateTimeZone( $post['timezone'] );

		if ( $post['evergreen_start_date'] == 'instant-access' ) {
			$dateTime = new DateTime( 'now', $lead_timezone );
		} elseif ( ! empty( $post['evergreen_start_date'] ) && ! empty( $post['evergreen_start_time'] ) ) {
			$dateTime = DateTime::createFromFormat( 'm-d-Y H:i', $post['evergreen_start_date'] . ' ' . $post['evergreen_start_time'] );
		} else {
			return false;
		}

		$webinar_start_date   = $dateTime->format( 'Y-m-d' );
		$webinar_start_time   = $dateTime->format( 'H:i' );

		// They choose to watch replay
		// $webinar_start_time = date( 'H:i', strtotime( $webinar_start_time."+0 hours" ) );
		$webinar_start_datetime = $webinar_start_date . ' ' . $webinar_start_time;

		// Get & Set Dates For Emails...
		$format = 'Y-m-d H:i';
				$date_picked_and_live = gmdate( $format, strtotime( $webinar_start_datetime ) );
		$date_1_day_before = gmdate( $format, strtotime( $webinar_start_datetime . ' -1 days' ) );
		$date_1_hour_before = gmdate( $format, strtotime( $webinar_start_datetime . ' -1 hours' ) );
		$date_after_live = gmdate( $format, strtotime( $webinar_start_datetime . " +$webinarLength minutes" ) );
		$date_1_day_after = gmdate( $format, strtotime( $webinar_start_datetime . ' +1 days' ) );

		$user_data = array(
			'app_id' => $post['id'],
			'name' => $post['name'],
			'email' => $post['email'],
			'phone' => $post['phone'],
			'lead_timezone' => $post['timezone'],
			'trk1' => 'Optin',
			'trk3' => ! empty( $post['ip'] ) ? $post['ip'] : '',
			'trk8' => $instant,
			'event' => 'No',
			'replay' => 'No',
			'created' => gmdate( 'F j, Y' ),
			'date_picked_and_live' => $date_picked_and_live,
			'date_1_day_before' => $date_1_day_before,
			'date_1_hour_before' => $date_1_hour_before,
			'date_after_live' => $date_after_live,
			'date_1_day_after' => $date_1_day_after,
			'date_picked_and_live_check' => $setCheckInstant,
			'date_1_day_before_check' => $setCheckInstant,
			'date_1_hour_before_check' => $setCheckInstant,
			'date_after_live_check' => $setCheckInstant,
		);

		$wpdb->insert( 
			$this->dbtable, 
			$user_data,
			array(
				'%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
				'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
			)
		);

		if ( ! $lid = $wpdb->insert_id ) {
			return false;
		}

		// TODO merge evergreen and live email templates into a single method
		$emailBody = $results->email_signup_body;

		$date_format = get_option( 'date_format' );
		$autoDate_format = gmdate( $date_format, strtotime( $webinar_start_date ) );

		// Final Step = Translate Months
		$months = $results->auto_translate_months;
		$days = $results->auto_translate_days;

		$autoDate_format = webinarignition_translate_dm( $months, $autoDate_format );
		$autoDate_format = webinarignition_translate_dm( $days, $autoDate_format, 'days' );

		$email_template_date = $autoDate_format . ' @ ' . webinarignition_get_time_tz( $webinar_start_time, $post['timezone'], $results->time_format, $results->time_suffix );
		$emailBody = str_replace( '{DATE}', $email_template_date, $emailBody );

		$emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $results, $lid, $emailBody );

		$this->webinarignition_send_registration_email( $emailBody, $results, $post );

		return true;
	}

	private function webinarignition_send_registration_email($emailBody, $results, $post) {
		require_once $this->plugin_dir . 'inc/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
	
		// Use WordPress HTTP API to check SMTP availability
		$args = array(
			'timeout'     => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'user-agent'  => 'WordPress/' . $GLOBALS['wp_version'] . '; ' . home_url(),
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
			'body'        => null,
			'compress'    => false,
			'decompress'  => true,
			'sslverify'   => true,
			'stream'      => false,
			'filename'    => null
		);
	
		$response = wp_remote_get("http://{$results->smtp_host}:{$results->smtp_port}", $args);
		$smtp_avail = !is_wp_error($response);
	
		if ($smtp_avail) {
			$mail->isSMTP();
			$mail->Host = $results->smtp_host;
			$mail->SMTPAuth = true;
			$mail->Username = $results->smtp_user;
			$mail->Password = $results->smtp_pass;
			$mail->SMTPSecure = $results->transfer_protocol ? $results->transfer_protocol : 'tls';
			$mail->From = $results->smtp_email;
			$mail->FromName = $results->smtp_name;
			$mail->Port = !empty($results->smtp_port) ? $results->smtp_port : 25;
		} else {
			$mail->isSendmail();
			$mail->setFrom($results->smtp_email, $results->smtp_name);
		}
	
		$mail->WordWrap = 50;
		$mail->IsHTML(true);
		$mail->Subject = $results->email_signup_sbj;
		$mail->Body = $emailBody;
		$mail->AddAddress($post['email'], $post['name']);
	
		if (!$mail->send()) {
			$headers = array(
				'MIME-Version' => '1.0',
				'Content-type' => 'text/html; charset=iso-8859-1',
				'From' => $results->smtp_email,
				'Reply-To' => $results->smtp_email,
				'X-Mailer' => 'PHP/' . phpversion()
			);
	
			if (!wp_mail($post['email'], $results->email_signup_sbj, $emailBody, $headers)) {
				WebinarIgnition_Logs::add("Registration email could not be sent to {$post['email']}", WebinarIgnition_Logs::LIVE_EMAIL);
			} else {
				WebinarIgnition_Logs::add('Registration email has been sent.', $post['id'], WebinarIgnition_Logs::LIVE_EMAIL);
			}
		}
	}
	

	/*
	|--------------------------------------------------------------------------
	| Validate Request
	|--------------------------------------------------------------------------
	*/
	private function webinarignition_is_valid_request() {
		// return $this->secret_key;
		if ( $this->test_mode === true ) {
			return true;
		}

		$paramStrArr = array();
		$paramStr = null;

		foreach ( $this->post_data as $key => $value ) {
			// Ignore if it is encrypted key
			if ( $key == 'verification_code' ) {
				continue;
			}
			if ( ! $key || ! $value ) {
				continue;
			}
			$paramStrArr[] = trim( (string) $value );
		}

		ksort( $paramStrArr, SORT_STRING );
		$paramStr = implode( '|', $paramStrArr );
		$encKey = hash_hmac( 'sha1', $paramStr, trim( $this->secret_key ) );

		return $encKey == $this->post_data['verification_code'];
	}
}
