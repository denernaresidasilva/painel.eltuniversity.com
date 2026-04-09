<?php
use Twilio\Rest\Client;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ####################################
//
// Check If Current Is Within Range Of Email Date
//
// ####################################


function webinarignition_dt_check( $start_date, $end_date, $date_from_db ) {
	// Convert to timestamp
	$start_ts = strtotime( $start_date );
	$end_ts = strtotime( $end_date );
	$user_ts = strtotime( $date_from_db );

	// Check that user date is between start & end
	if ( ( $user_ts >= $start_ts ) && ( $user_ts <= $end_ts ) ) {
		return 'yes';
	} else {
		return 'no';
	}
}

// ####################################
//
// Send Live-Webinar Email Notification
//
// ####################################
// --------------------------------------------------------------------------------------
function webinarignition_send_email( $ID, $num, $webinar_data ) {
	if ( $webinar_data->{'email_notiff_' . $num} == 'off' ) {
		return;
	}
	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format' );
	if ( ! empty( $webinar_data->time_format ) && ( $webinar_data->time_format == '12hour' || $webinar_data->time_format == '24hour' ) ) { // old formats
			$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
		$time_format    = $webinar_data->time_format;

		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition_leads';
		$queery = $wpdb->prepare("SELECT * FROM {$table_db_name} WHERE app_id = %s", $ID );

		$list = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %s", $ID ), OBJECT);

		$body = '';

	$Subject  = $webinar_data->{'email_notiff_sbj_' . $num};
	$Subject  = str_replace( '{TITLE}', $webinar_data->webinar_desc, $Subject );
	if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( $webinar_data->use_new_email_signup_template == 'yes' ) ) ) {

		// use new templates
		$webinar_data->emailheading     = $webinar_data->{'email_notiff_' . $num . '_heading'};
		$webinar_data->emailpreview     = $webinar_data->{'email_notiff_' . $num . '_preview'};
		$webinar_data->bodyContent      = $webinar_data->{'email_notiff_body_' . $num};
		$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_notiff_' . $num . '_footer' ) && $webinar_data->{'show_or_hide_local_notiff_' . $num . '_footer'} == 'show' ) ? $webinar_data->{'local_notiff_' . $num . '_footer'} : '';

		$email                     = new WI_Emails();
		$getBodyEmail              = $email->webinarignition_build_email( $webinar_data );

	} else {

		$emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
		$getBodyEmail = $emailHead;
		$getBodyEmail .= $webinar_data->{'email_notiff_body_' . $num};
		$getBodyEmail .= '</html>';

	}
	if ( ! empty( $webinar_data->webinar_lang ) ) {
		switch_to_locale( $webinar_data->webinar_lang );
		unload_textdomain( 'webinar-ignition' );
		load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
	}
	$translated_date      = webinarignition_get_localized_date( $webinar_data );
		$webinarTZ			  = isset($webinar_data->webinar_timezone) ? webinarignition_convert_utc_to_tzid($webinar_data->webinar_timezone) : '';
		$timeonly             = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) ) ? false : true;
		$body 				  = $getBodyEmail;
		$errs                 = 0;
		$mesg                 = '';

		webinarignition_test_smtp_options();

	foreach ( $list as $lead ) {
		$is_unsubscribed_to_all = get_option( 'lead_data_restricted_mails'.$lead->email, false );
		$webinar_restricted_emails = get_option( 'webinar_data_restricted_mails'.$ID, array() );
		

		if($is_unsubscribed_to_all || in_array($lead->email, $webinar_restricted_emails)){
			continue;
		}
		$bdy = $body;
		$bdy = str_replace( '{FIRSTNAME}', $lead->name, $bdy );
		$additional_params = ( ( $webinar_data->paid_status == 'paid' ) ? md5( $webinar_data->paid_code ) : '' );
		$unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
								<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
								<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
							</p>';
		
		$webinarignition_unsubscribe_links					= absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );

		// Check if the setting value is 1
		if (1 === absint($webinarignition_unsubscribe_links)) {
			$bdy .= $unsubscribe_text;
		}
		$is_lead_protected          = ! empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;

		if ( $is_lead_protected ) {
			$ics_lid = $lead->hash_ID;
		} else {
			$ics_lid = $lead->ID;
		}
		$thankyou_URL      = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
		$googleCalendarURL = add_query_arg(
					array(
						'googlecalendar' => '1', // This line ensures '=' is included
						'lid'             => $ics_lid,
						'webinar'             => '',
					),
					$thankyou_URL
				);

				ob_start();
				?>
				<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php echo esc_url_raw( $googleCalendarURL ); ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
					<i class="icon-google-plus"></i>
					<?php
					webinarignition_display(
						$webinar_data->ty_calendar_google,
						__( 'Add to Google Calendar', 'webinar-ignition' )
					);
					?>
				</a>
				<?php
				$wi_calendar_url = ob_get_clean();
		$bdy = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead->ID, $bdy, $additional_params );
		
		// Add Unsubscribe Text
		
		$mesg = "Added {$lead->name} ({$lead->email}) to email recipient list\n";

		WebinarIgnition_Logs::add( $mesg, $ID, WebinarIgnition_Logs::LIVE_EMAIL );		
		$event_data = [
			'id'		  	=> $ics_lid,
			'webdata'       => $webinar_data,
		];
		$ics_file = generate_ics_file($event_data);
	


		$unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
		$unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
		$unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead->ID, $unsubscribe_next_header_url );
		$unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead->ID, $unsubscribe_all_header_url );
	
		$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option('webinarignition_email_templates_from_name', get_option('blogname')) . ' <' . get_option('webinarignition_email_templates_from_email', get_option('admin_email')) . '>',
		'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
		'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>',
		);
		$webinar_duration =  isset( $webinar_data->webinar_start_duration ) ? sanitize_text_field($webinar_data->webinar_start_duration ) : 60;
		$duration_txt = esc_html(
		sprintf(
				/* translators: %s: Webinar duration in minutes. */
				__( 'Duration: %s minutes', 'webinar-ignition' ),
				esc_html( $webinar_duration )
			)
		);

		$attachments = [$ics_file];
		if($num == 2 || $num == 1){
			$webinar_email_timezone = $lead->lead_browser_and_os ? $lead->lead_browser_and_os : $webinar_data->webinar_timezone;
			$original_time = $webinar_data->webinar_start_time; // e.g., '2025-05-07 15:00:00'
			$original_timezone = new DateTimeZone($webinar_data->webinar_timezone); // e.g., 'Asia/Riyadh'
			$target_timezone = new DateTimeZone($webinar_email_timezone); // e.g., 'Asia/Karachi'
			
			// Create DateTime object in original timezone
			$date = new DateTime($original_time, $original_timezone);
			
			// Convert to target timezone
			$date->setTimezone($target_timezone);
			
			// Format using provided format
			$converted_time = $date->format($time_format);
			$bdy = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( $converted_time, $time_format, $webinar_email_timezone, false, $timeonly ).' '.$wi_calendar_url.'<br>'.$duration_txt, $bdy );
			try {
				if ( ! wp_mail( $lead->email, $Subject, $bdy, $headers, $attachments ) ) {
					WebinarIgnition_Logs::add( "ERROR:: Email could not be sent to {$lead->email}.", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
				} else {
					WebinarIgnition_Logs::add( __( 'Mail Sent.', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );
				}
			} catch ( Exception $e ) {
				WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinar-ignition' ) . " {$lead->email}.", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
			}
		}else{
			$webinar_email_timezone = $lead->lead_browser_and_os ? $lead->lead_browser_and_os : $webinar_data->webinar_timezone;
			$original_time = $webinar_data->webinar_start_time; // e.g., '2025-05-07 15:00:00'
			$original_timezone = new DateTimeZone($webinar_data->webinar_timezone); // e.g., 'Asia/Riyadh'
			$target_timezone = new DateTimeZone($webinar_email_timezone); // e.g., 'Asia/Karachi'
			
			// Create DateTime object in original timezone
			$date = new DateTime($original_time, $original_timezone);
			
			// Convert to target timezone
			$date->setTimezone($target_timezone);
			
			// Format using provided format
			$converted_time = $date->format($time_format);
			if($num == 5 || $num == 4){
				$bdy = str_replace( 'Date: Join us live on {DATE}', '', $bdy );
				
			}else{
				$bdy = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( $converted_time, $time_format, $webinar_email_timezone, false, $timeonly ), $bdy );
			}
			try {
				if ( ! wp_mail( $lead->email, $Subject, $bdy, $headers) ) {
					WebinarIgnition_Logs::add( "ERROR:: Email could not be sent to {$lead->email}.", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
				} else {
					WebinarIgnition_Logs::add( __( 'Mail Sent.', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );
				}
			} catch ( Exception $e ) {
				WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinar-ignition' ) . " {$lead->email}.", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
			}
		}
		
	}//end foreach

		return true;
}
// --------------------------------------------------------------------------------------




// ####################################
//
// Send TXT Notification
//
// ####################################
function webinarignition_send_txt( $results ) {
	// LOOP THROUGH EMAILS HERE ::
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_leads';
	$app_id = intval($results->id);

	// Prepare and execute the query with error handling
	$leads = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $table_db_name WHERE app_id = %d", 
			$app_id
		),
		OBJECT
	);
	// Loop Through Each Lead & Send ::
	// Send TXT Messages
	$AccountSid = $results->twilio_id;
	$AuthToken  = $results->twilio_token;

	$client = new Client( $AccountSid, $AuthToken );

	$MSG = $results->twilio_msg;
	// Shortcode {LINK}
	$txt_sent = false;

	foreach ( $leads as $lead ) {
		if ( $lead->phone == 'undefined' || $lead->phone == '' ) {

		} else {
			$txt_sent = true;
			try {
				$additional_params = ( ( $results->paid_status == 'paid' ) ? md5( $results->paid_code ) : '' );

				$MSG = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $results, $lead->ID, $MSG, $additional_params, array( 'is_sms' => true ) );

				$client->messages->create(
					sanitize_text_field( trim( $lead->phone ) ),
					array(
						'from' => $results->twilio_number,
						'body' => $MSG,
					)
				);

				WebinarIgnition_Logs::add( "TXT Sent to {$lead->name} ({$lead->phone})", $results->id, WebinarIgnition_Logs::LIVE_SMS );
				// echo 'TXT Sent :: ' . $leads->phone;
				// echo "<br>";
			} catch ( Exception $e ) {
				// Error On Phone Number - Do Nothing
				// echo 'Error: ' . $e->getMessage();
				WebinarIgnition_Logs::add( __( 'Error sending TXT to', 'webinar-ignition' ) . " {$lead->name} ({$lead->phone}): " . $e->getMessage(), $results->id, WebinarIgnition_Logs::LIVE_SMS );
			}//end try
		}//end if
	}//end foreach
	if ( ! $txt_sent ) {
		WebinarIgnition_Logs::add( __( 'No leads to send TXT to.', 'webinar-ignition' ), $results->id, WebinarIgnition_Logs::LIVE_SMS );
	}
}
// --------------------------------------------------------------------------------------




// AUTO
// --------------------------------------------------------------------------------------
// Send Out AUTO Emails
function webinarignition_cron_email( $ID, $LEADID, $num, $NAME, $EMAIL, $DATE, $TIMEZONE ) {
	// Setup Info
	$webinar_data   = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
	$is_unsubscribed_to_all = get_option( 'lead_data_restricted_mails'.$EMAIL, false );
	$webinar_restricted_emails = get_option( 'webinar_data_restricted_mails'.$ID, array() );
	if($is_unsubscribed_to_all || in_array($EMAIL, $webinar_restricted_emails)){
		return;
	}
	$is_instant_lead = false;
	$is_watched_lead = false;
	if ( ! empty( $LEADID ) ) {
		$lead = webinarignition_get_lead_info( $LEADID, $webinar_data, false );
		if ( ! empty( $lead ) ) {
			if ( isset( $lead->trk8 ) && 'yes' === $lead->trk8 ) {
				$is_instant_lead = true;
			}

			if ( isset( $lead->lead_status ) && 'watched' === $lead->lead_status ) {
				$is_watched_lead = true;
			}
		}
	}

	if ( $is_instant_lead || $is_watched_lead ) {
		return; // Disable email notifications for instant/watched leads
	}

	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || $webinar_data->time_format == '24hour' ) ) { // old formats
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
	$time_format    = $webinar_data->time_format;
	$date_format    = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format' );

	// check if notification is disabled, and halt sending it
	if ( $webinar_data->{'email_notiff_' . $num} == 'off' ) {
		WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( $num ) . ' disabled - aborting!', $ID, WebinarIgnition_Logs::AUTO_EMAIL );
		// return true so that it can be marked as sent (else the logs table ends up with millions of useless entries)
		return true;
	}
	// Preprocess Email w/ Shortcodes
	$getBody = 'email_notiff_body_' . $num;

	if ( ! empty( $webinar_data->templates_version ) || ( ! empty( $webinar_data->use_new_email_signup_template ) && ( $webinar_data->use_new_email_signup_template == 'yes' ) ) ) {

			// use new templates
			$webinar_data->emailheading     = $webinar_data->{'email_notiff_' . $num . '_heading'};
			$webinar_data->emailpreview     = $webinar_data->{'email_notiff_' . $num . '_preview'};
			$webinar_data->bodyContent      = $webinar_data->{'email_notiff_body_' . $num};
			$webinar_data->footerContent    = ( property_exists( $webinar_data, 'show_or_hide_local_notiff_' . $num . '_footer' ) && $webinar_data->{'show_or_hide_local_notiff_' . $num . '_footer'} == 'show' ) ? $webinar_data->{'local_notiff_' . $num . '_footer'} : '';

			$email                      = new WI_Emails();
			$getBodyEmail               = $email->webinarignition_build_email( $webinar_data );

	} else {

			$emailHead          = WebinarignitionEmailManager::webinarignition_get_email_head();
			$getBodyEmail       = $emailHead;
			$getBodyEmail       .= $webinar_data->$getBody;
			$getBodyEmail       .= '</html>';

	}
	$is_lead_protected = ! empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;

	if ( $is_lead_protected ) {
		$ics_lid = $lead->hash_ID;
	} else {
		$ics_lid = $LEADID;
	}
	$autoDate_info      = explode( ' ', $DATE ?? '' );
	$translated_date    = webinarignition_get_translated_date( $autoDate_info[0], 'Y-m-d', $date_format );

	// Replace
	$timeonly     = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) ) ? false : true;
	$thankyou_URL      = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
	$googleCalendarURL = add_query_arg(
				array(
					'googlecalendarA' => '1', // This line ensures '=' is included
					'lid'             => $ics_lid,
					'webinar'             => '',
				),
			$thankyou_URL
			);

	ob_start();
	?>
	<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php echo esc_url_raw( $googleCalendarURL ); ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
		<i class="icon-google-plus"></i>
		<?php
		webinarignition_display(
			$webinar_data->ty_calendar_google,
			__( 'Add to Google Calendar', 'webinar-ignition' )
		);
		?>
	</a>
	<?php
	$wi_calendar_url = ob_get_clean();
	if($num == 2 || $num == 1){
		$getBodyEmail = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( isset($autoDate_info[1]) ? $autoDate_info[1] : '', $time_format, $TIMEZONE, false, $timeonly ).' '.$wi_calendar_url, $getBodyEmail );
	}else{
		$getBodyEmail = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( isset($autoDate_info[1]) ? $autoDate_info[1] : '', $time_format, $TIMEZONE, false, $timeonly ), $getBodyEmail );
	}
	$getBodyEmail = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz( isset($autoDate_info[1]) ? $autoDate_info[1] : '', $time_format, $TIMEZONE, false, $timeonly ), $getBodyEmail );
	$getBodyEmail = str_replace( '{FIRSTNAME}', $NAME, $getBodyEmail );

	$getSBJ   = 'email_notiff_sbj_' . $num;
	$Subject  = $webinar_data->$getSBJ;
	$Subject  = str_replace( '{TITLE}', $webinar_data->webinar_desc, $Subject );
	$unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
	$unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
	$unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $LEADID, $unsubscribe_next_header_url );
	$unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $LEADID, $unsubscribe_all_header_url );

	$headers = array(
	'Content-Type: text/html; charset=UTF-8',
	'From: ' . get_option('webinarignition_email_templates_from_name', get_option('blogname')) . ' <' . get_option('webinarignition_email_templates_from_email', get_option('admin_email')) . '>',
	'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
	'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>',
	);
	$additional_params = 'event=OI3shBXlqsw';
	$watch_type = 'live';
	if ( $num === 3 || $num === 5 ) {
		$watch_type = 'replay';
	}
	$additional_params .= "&watch_type={$watch_type}";
	$additional_params .= ( ( $webinar_data->paid_status == 'paid' ) ? '&' . md5( $webinar_data->paid_code ) : '' );

	
	$event_data = [
		'id'		  	=> $ics_lid,
		'webdata'       => $webinar_data,
		'leadinfo'		=> array(
			'lead_timezone' 		=>$lead->lead_timezone,
			'date_picked_and_live' 	=> $lead->date_picked_and_live

		)
	];
	$ics_file = generate_icsA_file($event_data);
	if(!$ics_file){
		$ics_file = '';
	}
	// Add Unsubscribe Text
	$unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
	<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
	<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
	</p>';

	$webinarignition_unsubscribe_links					= absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );

	// Check if the setting value is 1
	if (1 === absint($webinarignition_unsubscribe_links)) {
		$getBodyEmail .= $unsubscribe_text;
	}
	
	$getBodyEmail = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $LEADID, $getBodyEmail, $additional_params );
	
	$attachments = [$ics_file];
	if($num == 2 || $num == 1){
		if($getBodyEmail === 'send_mail_wp'){
			$email_sent = wp_mail( $EMAIL, $Subject, $getBodyEmail, $headers, $attachments);
			WebinarIgnition_Logs::add( __( 'Email sent to', 'webinar-ignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
		}
		try {
			if ( ! wp_mail( $EMAIL, $Subject, $getBodyEmail, $headers, $attachments ) ) {
				// echo 'Mailer Error: ' . $mail->ErrorInfo;
				WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinar-ignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
				return false;

			} else {
				// echo 'Email Sent :: ' . $EMAIL;
				// echo "<br>";
				WebinarIgnition_Logs::add( __( 'Mail Sent.', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::AUTO_EMAIL );
				return true;
			}
		} catch ( Exception $e ) {
			WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinar-ignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
			return false;
		}
	}else{
		if($getBodyEmail === 'send_mail_wp'){
			$email_sent = wp_mail( $EMAIL, $Subject, $getBodyEmail, $headers);
			WebinarIgnition_Logs::add( __( 'Email sent to', 'webinar-ignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
		}
		try {
			if ( ! wp_mail( $EMAIL, $Subject, $getBodyEmail, $headers ) ) {
				// echo 'Mailer Error: ' . $mail->ErrorInfo;
				WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinar-ignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
				return false;

			} else {
				// echo 'Email Sent :: ' . $EMAIL;
				// echo "<br>";
				WebinarIgnition_Logs::add( __( 'Mail Sent.', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::AUTO_EMAIL );
				return true;
			}
		} catch ( Exception $e ) {
			WebinarIgnition_Logs::add( __( 'ERROR:: Email could not be sent to', 'webinar-ignition' ) . " {$EMAIL}", $ID, WebinarIgnition_Logs::AUTO_EMAIL );
			return false;
		}
	}
}

// ####################################
//
// Send TXT Notification
//
// ####################################
function webinarignition_send_txt_auto( $ID, $PHONE, $LEADID ) {

	// Get Results
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

	$is_instant_lead = false;
	if ( ! empty( $LEADID ) ) {
		$lead = webinarignition_get_lead_info( $LEADID, $webinar_data, false );
		if ( ! empty( $lead ) && isset( $lead->trk8 ) && $lead->trk8 === 'yes' ) {
			$is_instant_lead = true;
		}
	}

	if ( $is_instant_lead ) {
		return; // Disable SMS notification for instant leads
	}

	if ( ! empty( $webinar_data->twilio_id ) && ! empty( $webinar_data->twilio_token ) ) {

		$AccountSid = $webinar_data->twilio_id;
		$AuthToken  = $webinar_data->twilio_token;
		$client     = new Client( $AccountSid, $AuthToken );

		$MSG = $webinar_data->twilio_msg;

		$additional_params = 'event=OI3shBXlqsw';
		$additional_params .= ( ( $webinar_data->paid_status == 'paid' ) ? '&' . md5( $webinar_data->paid_code ) : '' );

		$MSG = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $LEADID, $MSG, $additional_params, array( 'is_sms' => true ) );

		try {

			$client->messages->create(
				sanitize_text_field( trim( $PHONE ) ),
				array(
					'from' => $webinar_data->twilio_number,
					'body' => $MSG,
				)
			);

			WebinarIgnition_Logs::add( __( 'TXT notification Sent.', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::AUTO_SMS );
		} catch ( Exception $e ) {

			WebinarIgnition_Logs::add( __( 'Error sending TXT to', 'webinar-ignition' ) . " {$PHONE}: " . $e->getMessage(), $ID, WebinarIgnition_Logs::AUTO_SMS );
		}
	} else {

		WebinarIgnition_Logs::add( __( 'Error sending TXT to', 'webinar-ignition' ) . " {$PHONE}: Credentials are required to create a Client.", $ID, WebinarIgnition_Logs::AUTO_SMS );

	}//end if
}
function renotification_url($webinar_id = null, $lead_id = null) {
    $webinar_landing_page = '';
	$webinar_data               = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    
	if (!empty($webinar_id)) {
        $webinar_post_status = get_post_status($webinar_id);
        if (wp_validate_boolean($webinar_post_status)) { // post exists
            $webinar_landing_page = get_the_permalink($webinar_id);
        }
    }

    if (!empty($lead_id)) {
        $webinar_landing_page = add_query_arg('confirmed', '', $webinar_landing_page);
        $webinar_landing_page = add_query_arg('lid', $lead_id, $webinar_landing_page);
    }

    if (WebinarignitionManager::webinarignition_is_paid_webinar($webinar_data)) {
        $webinar_landing_page = add_query_arg($webinar_data->paid_code, '', $webinar_landing_page);
    }

    $webinar_landing_page .= '&webinar';

    return $webinar_landing_page;
}
