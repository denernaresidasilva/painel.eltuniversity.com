<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WebinarignitionEmailManager {

	public static function webinarignition_replace_email_body_placeholders( $webinar_data, $lead_id, $email_body, $additional_params = '', $args = array() ) {
		WebinarignitionManager::webinarignition_set_locale( $webinar_data );
		$lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
		$lead_id_url = ! empty( $lead ) ? $lead->hash_ID : '';
		$lead_meta = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead_id, 'wiRegForm', 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' );

			if ( ! empty( $lead_meta['meta_value'] ) ) {
				$lead_meta_data = maybe_unserialize( $lead_meta['meta_value'] );
				$lead_meta_data = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta_data );
			}else{
				$lead_meta_data = array();
			}
			
		if ( ! empty( $webinar_data->footer_branding ) && 'show' === $webinar_data->footer_branding ) {
			$email_body = str_replace( '{AFFILIATE}', $webinar_data->footer_branding_url, $email_body );
		}

		$name = ! empty( $lead->name ) ? $lead->name : '';
			if(in_array('full_name', array_keys($lead_meta_data))) {
				$email_body = str_replace( '{FULLNAME}', $lead_meta_data['full_name']['value'], $email_body );
			} else {
				$email_body = str_replace( '{FULLNAME}', $name, $email_body );
			}
		if(in_array('optReason', array_keys($lead_meta_data))) {
			$email_body = str_replace( '{REASON}', $lead_meta_data['optReason']['value'], $email_body );
		}
		if(in_array('optSalutation', array_keys($lead_meta_data))) {
			$email_body = str_replace( '{SALUTATION}', $lead_meta_data['optSalutation']['value'], $email_body );
		}
		if(in_array('optPhone', array_keys($lead_meta_data))) {
			$email_body = str_replace( '{PHONENUM}', $lead_meta_data['optPhone']['value'], $email_body );
		}
		if(in_array('optName', array_keys($lead_meta_data))) {
			$email_body = str_replace( '{FIRSTNAME}', $lead_meta_data['optName']['value'], $email_body );
		}
		if(in_array('optLName', array_keys($lead_meta_data))) {
			$email_body = str_replace( '{LASTNAME}', $lead_meta_data['optLName']['value'], $email_body );
		}
		// Handle optCustom_1 to optCustom_18
		for ($i = 1; $i <= 18; $i++) {
			$meta_key = 'optCustom_' . $i;
			$placeholder = '{CUSTOM' . $i . '}';

			if (isset($lead_meta_data[$meta_key]['value'])) {
				$email_body = str_replace($placeholder, $lead_meta_data[$meta_key]['value'], $email_body);
			}
		}
		$email_body = str_replace( '{LEAD_NAME}', $name, $email_body );
		$email_body = str_replace( '{NAME}', $name, $email_body );
		$email_body = str_replace( '{TITLE}', $webinar_data->webinar_desc, $email_body );
		$email_body = str_replace( '{HOST}', $webinar_data->webinar_host, $email_body );
		$email_body = str_replace( '{YEAR}', gmdate( 'Y' ), $email_body );
		$email_body = str_replace( '{EMAIL}', ! empty( $lead->email ) ? $lead->email : '', $email_body );

		$default_webinar_link  = $webinar_data->webinar_permalink . ( strstr( $webinar_data->webinar_permalink, '?' ) ? '&' : '?' );
		$default_webinar_link .= "live=1&webinar&lid={$lead_id_url}";
		$webinar_landing_page = $default_webinar_link; // Setting default link from object, no sure if correct always

		$webinar_post_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_data->id );

		if ( isset( $webinar_data->custom_webinar_page ) && ! empty( $webinar_data->custom_webinar_page ) ) { // Check if custom webinar page is set
			$webinar_post_id = absint( $webinar_data->custom_webinar_page );
		}

		if ( ! empty( $webinar_post_id ) ) {
			$webinar_post_status = get_post_status( $webinar_post_id );
			if ( wp_validate_boolean( $webinar_post_status ) ) { // post exists
				$webinar_landing_page = get_the_permalink( $webinar_post_id );
			}
		}

		if ( ! empty( $lead_id_url ) ) {
			$webinar_landing_page = add_query_arg( 'confirmed', '', $webinar_landing_page );
			$webinar_landing_page = add_query_arg( 'lid', $lead_id_url, $webinar_landing_page );
		}

		if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
			$webinar_landing_page = add_query_arg( $webinar_data->paid_code, '', $webinar_landing_page );
		}

		if ( ! empty( $additional_params ) ) {
			$webinar_landing_page = add_query_arg( $additional_params, '', $webinar_landing_page );
		}
		$webinar_landing_page = $webinar_landing_page.'&webinar';
		$webinar_landing_link = $webinar_landing_page.'&webinar';

		if ( empty( $args['is_sms'] ) ) {
			$webinar_landing_page = sprintf( '<a target="_blank" href="%s">%s</a>', $webinar_landing_page, __( 'Join the webinar!', 'webinar-ignition' ) );
		}

		$email_body = str_replace( '{LINK}', $webinar_landing_page, $email_body );
		$email_body = str_replace( '{SUBLINK}', $webinar_landing_link, $email_body );

		WebinarignitionManager::webinarignition_restore_locale( $webinar_data );

		return $email_body;
	}

	public static function webinarignition_get_email_head() {
		ob_start();
		?><!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="x-apple-disable-message-reformatting">
			<style>

				html,
				body {
					margin: 0 auto !important;
					padding: 0 !important;
					height: 100% !important;
					width: 100% !important;
					background: #f1f1f1;
				}

				* {
					-ms-text-size-adjust: 100%;
					-webkit-text-size-adjust: 100%;
				}

				/* What it does: Centers email on Android 4.4 */
				div[style*="margin: 16px 0"] {
					margin: 0 !important;
				}

				table,
				td {
					mso-table-lspace: 0pt !important;
					mso-table-rspace: 0pt !important;
				}


				table {
					border-spacing: 0 !important;
					border-collapse: collapse !important;
					table-layout: fixed !important;
					margin: 0 auto !important;
				}


				img {
					-ms-interpolation-mode:bicubic;
				}


				a {
					text-decoration: none;
				}

				*[x-apple-data-detectors],  /* iOS */
				.unstyle-auto-detected-links *,
				.aBn {
					border-bottom: 0 !important;
					cursor: default !important;
					color: inherit !important;
					text-decoration: none !important;
					font-size: inherit !important;
					font-family: inherit !important;
					font-weight: inherit !important;
					line-height: inherit !important;
				}

				.a6S {
					display: none !important;
					opacity: 0.01 !important;
				}

				.im {
					color: inherit !important;
				}

				img.g-img + div {
					display: none !important;
				}

				/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
				@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
					u ~ div .email-container {
						min-width: 320px !important;
					}
				}
				/* iPhone 6, 6S, 7, 8, and X */
				@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
					u ~ div .email-container {
						min-width: 375px !important;
					}
				}
				/* iPhone 6+, 7+, and 8+ */
				@media only screen and (min-device-width: 414px) {
					u ~ div .email-container {
						min-width: 414px !important;
					}
				}

				.bg_white{
					background: #ffffff;
				}
				.bg_light{
					background: #fafafa;
				}
				.bg_black{
					background: #000000;
				}
				.bg_dark{
					background: rgba(0,0,0,.8);
				}
				.email-section{
					padding:2.5em;
				}

				h1,h2,h3,h4,h5,h6{
					font-family: "Nunito Sans", sans-serif;
					color: #000000;
					margin-top: 0;
				}
				a{
					color: #f5564e;
				}
			</style>
		</head>
		<?php
		return ob_get_clean();
	}
}

function generate_webinar_url($webinar_id = null, $lead_id = null, $additional_params = array()) {
    $webinar_landing_page = '';
	$webinar_data               = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    
	if ( ! empty( $webinar_data->preview_url ) && ( $webinar_data->preview_url || $webinar_data->preview_url) ) {
		$webinar_data->preview_url = get_option( 'preview_url' );
	}

    if (!empty($lead_id)) {
        $webinar_landing_page = add_query_arg('confirmed', '', $webinar_landing_page);
        $webinar_landing_page = add_query_arg('lid', $lead_id, $webinar_landing_page);
    }

    // Assuming $webinar_data is accessible and contains the necessary paid_code
    if (WebinarignitionManager::webinarignition_is_paid_webinar($webinar_data)) {
        $webinar_landing_page = add_query_arg($webinar_data->paid_code, '', $webinar_landing_page);
    }

    

    $webinar_landing_page .= '&webinar';

    return $webinar_landing_page;
}
function generate_ics_file($event_data) {
	$lead_id		 = $event_data['id'];
	$webinar_data	 = $event_data['webdata'];
	// Get Results
	$results = $webinar_data;

	// Get DB Info
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';
	// Sanitize input values
	$webinar_id = intval($results->wi_webinar_id_resend_mail); // Ensure $webinar_id is an integer
	// Prepare and execute the query
	$data = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
			$webinar_id
		),
		OBJECT
	);
	



	// Webinar Info
	$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinar-ignition' );
	$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinar-ignition' );
	$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinar-ignition' );


	if ( 'custom' === $results->ty_webinar_url ) {
		$url = $results->ty_werbinar_custom_url;
	} else {
		$url = get_permalink( $data->postID ) . '?live&lid=' . $lead_id;
	}
	$results->webinar_start_time = gmdate( 'H:i', strtotime( $results->webinar_start_time ) );
	$duration = property_exists($results, 'webinar_start_duration') && isset($results->webinar_start_duration) ? $results->webinar_start_duration : 60;


	$timezone = $results->webinar_timezone;
	if ( ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
		$timezone = '+' . $timezone;
	}
	$timezone_sign   = $timezone[0];
	$timezone_offset = str_pad( str_replace( '0', '', substr( $timezone, 1 ) ), 4, '0', STR_PAD_BOTH );
	$date            = DateTime::createFromFormat( 'm-d-Y H:i:s', $results->webinar_date . ' ' . $results->webinar_start_time . ':00', new DateTimeZone( $timezone_offset ) );
	

	if ( ! defined( 'WEBINARIGNITION_DATE_FORMAT' ) ) {
		define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );
	}

	$ics_content  = 'BEGIN:VCALENDAR' . "\r\n";
	$ics_content .= 'VERSION:2.0' . "\r\n";
	$ics_content .= 'PRODID:-//project/author//NONSGML v1.0//EN' . "\r\n";
	$ics_content .= 'CALSCALE:GREGORIAN' . "\r\n";
	$ics_content .= 'METHOD:PUBLISH' . "\r\n";
	$ics_content .= 'BEGIN:VTIMEZONE' . "\r\n";
	$ics_content .= 'TZID:' . esc_html( $timezone_offset ) . "\r\n";
	$ics_content .= 'BEGIN:STANDARD' . "\r\n";
	$ics_content .= 'DTSTART:20071028T010000' . "\r\n";
	$ics_content .= 'TZOFFSETTO:+0000' . "\r\n";
	$ics_content .= 'TZOFFSETFROM:+0000' . "\r\n";
	$ics_content .= 'END:STANDARD' . "\r\n";
	$ics_content .= 'END:VTIMEZONE' . "\r\n";
	$ics_content .= 'BEGIN:VEVENT' . "\r\n";
	$ics_content .= 'DTSTART;TZID='. esc_html( $timezone_offset ) .':' . $date->format( WEBINARIGNITION_DATE_FORMAT ) . "\r\n";
	$ics_content .= 'DTEND;TZID='. esc_html( $timezone_offset ) .':' . $date->modify( $duration.' minutes' )->format( WEBINARIGNITION_DATE_FORMAT ) . "\r\n";
	$ics_content .= 'UID:' . $date->getTimestamp() . '@' . $webinar_id . "\r\n";
	$ics_content .= 'DTSTAMP:' . gmdate( WEBINARIGNITION_DATE_FORMAT ) . 'Z' . "\r\n";
	$ics_content .= 'SUMMARY:' . $webinar_title . "\r\n";
	$ics_content .= 'DESCRIPTION:' . $desc . '. Visit ' . $url . "\r\n";
	$ics_content .= 'URL;VALUE=URI:' . $url . "\r\n";
	$ics_content .= 'END:VEVENT' . "\r\n";
	$ics_content .= 'END:VCALENDAR';

	$upload_dir = wp_upload_dir();
	$file_path = $upload_dir['path'] . '/event.ics';
	
	file_put_contents($file_path, $ics_content);

	return $file_path;
}
function generate_renotification_ics_file($event_data) {
	$lead_id		 = $event_data['id'];
	$webinar_data	 = $event_data['webdata'];
	// Get Results
	$results = $webinar_data;

	// Get DB Info
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition';
	// Sanitize input values
	$webinar_id = intval($results->wi_webinar_id_resend_mail); // Ensure $webinar_id is an integer
	// Prepare and execute the query
	$data = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
			$webinar_id
		),
		OBJECT
	);
	

	

	// Webinar Info
	$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinar-ignition' );
	$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinar-ignition' );
	$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinar-ignition' );


	if ( 'custom' === $results->ty_webinar_url ) {
		$url = $results->ty_werbinar_custom_url;
	} else {
		$url = get_permalink( $data->postID ) . '?live&lid=' . $lead_id;
	}
	$duration = property_exists($results, 'webinar_start_duration') && isset($results->webinar_start_duration) ? $results->webinar_start_duration : 60;

	$timezone = $results->webinar_timezone;
	if ( ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
		$timezone = '+' . $timezone;
	}
	$timezone_sign   = $timezone[0];
	$timezone_offset = str_pad( str_replace( '0', '', substr( $timezone, 1 ) ), 4, '0', STR_PAD_BOTH );	
	$date      		 = DateTime::createFromFormat( 'm-d-Y h:i A', $event_data['webdate']. ' '. $event_data['webtime'], new DateTimeZone( $timezone_offset ) );	
	if ( ! defined( 'WEBINARIGNITION_DATE_FORMAT' ) ) {
		define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );
	}

	$ics_content  = 'BEGIN:VCALENDAR' . "\r\n";
	$ics_content .= 'VERSION:2.0' . "\r\n";
	$ics_content .= 'PRODID:-//project/author//NONSGML v1.0//EN' . "\r\n";
	$ics_content .= 'CALSCALE:GREGORIAN' . "\r\n";
	$ics_content .= 'METHOD:PUBLISH' . "\r\n";
	$ics_content .= 'BEGIN:VTIMEZONE' . "\r\n";
	$ics_content .= 'TZID:' . esc_html( $timezone_offset ) . "\r\n";
	$ics_content .= 'BEGIN:STANDARD' . "\r\n";
	$ics_content .= 'DTSTART:20071028T010000' . "\r\n";
	$ics_content .= 'TZOFFSETTO:+0000' . "\r\n";
	$ics_content .= 'TZOFFSETFROM:+0000' . "\r\n";
	$ics_content .= 'END:STANDARD' . "\r\n";
	$ics_content .= 'END:VTIMEZONE' . "\r\n";
	$ics_content .= 'BEGIN:VEVENT' . "\r\n";
	$ics_content .= 'DTSTART;TZID='. esc_html( $timezone_offset ) .':' . $date->format( WEBINARIGNITION_DATE_FORMAT ) . "\r\n";
	$ics_content .= 'DTEND;TZID='. esc_html( $timezone_offset ) .':' . $date->modify( $duration.' minutes' )->format( WEBINARIGNITION_DATE_FORMAT ) . "\r\n";
	$ics_content .= 'UID:' . $date->getTimestamp() . '@' . $webinar_id . "\r\n";
	$ics_content .= 'DTSTAMP:' . gmdate( WEBINARIGNITION_DATE_FORMAT ) . 'Z' . "\r\n";
	$ics_content .= 'SUMMARY:' . $webinar_title . "\r\n";
	$ics_content .= 'DESCRIPTION:' . $desc . '. Visit ' . $url . "\r\n";
	$ics_content .= 'URL;VALUE=URI:' . $url . "\r\n";
	$ics_content .= 'END:VEVENT' . "\r\n";
	$ics_content .= 'END:VCALENDAR';

	$upload_dir = wp_upload_dir();
	$file_path = $upload_dir['path'] . '/event.ics';
	
	file_put_contents($file_path, $ics_content);

	return $file_path;
}

function generate_icsA_file($event_data) {
	$lead_id		 = $event_data['id'];
	$webinar_data	 = $event_data['webdata'];
	$leadinfo	 = $event_data['leadinfo'];

	// Get Results
	$results = $webinar_data;
	$data    = $leadinfo;
	if(!$data['date_picked_and_live']) {
		return false;
	}
	// Webinar Info
	$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinar-ignition' );
	$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinar-ignition' );
	$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinar-ignition' );

	if ( isset( $results->ty_webinar_url ) && 'custom' === $results->ty_webinar_url ) {
		$url = $results->ty_werbinar_custom_url;
	} else {
		$url = isset($results->webinar_permalink) ? ($results->webinar_permalink) . '?live&webinar&lid=' . $lead_id : '#';
	}

	$timezone = isset($data['lead_timezone']) ? $data['lead_timezone'] : '+00'; // Default to UTC if not set
	if ( isset($timezone) && ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
		$timezone = '+' . $timezone;
	}
	try {
		$timezone_fn = new DateTimeZone($timezone);
	} catch (Exception $e) {
		$timezone_fn = new DateTimeZone('UTC');
	}
	$date = DateTime::createFromFormat( 'Y-m-d H:i', $data['date_picked_and_live'],$timezone_fn );

	

	if ( ! defined( 'WEBINARIGNITION_DATE_FORMAT' ) ) {
		define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );
	}

	$ics_content  = 'BEGIN:VCALENDAR' . "\r\n";
	$ics_content .= 'VERSION:2.0' . "\r\n";
	$ics_content .= 'PRODID:-//project/author//NONSGML v1.0//EN' . "\r\n";
	$ics_content .= 'CALSCALE:GREGORIAN' . "\r\n";
	$ics_content .= 'METHOD:PUBLISH' . "\r\n";
	$ics_content .= 'BEGIN:VTIMEZONE' . "\r\n";
	$ics_content .= 'TZID:'. esc_html( $data['lead_timezone'] ) . "\r\n";
	$ics_content .= 'BEGIN:STANDARD' . "\r\n";
	$ics_content .= 'DTSTART:20071028T010000' . "\r\n";
	$ics_content .= 'TZOFFSETTO:+0000' . "\r\n";
	$ics_content .= 'TZOFFSETFROM:+0000' . "\r\n";
	$ics_content .= 'END:STANDARD' . "\r\n";
	$ics_content .= 'END:VTIMEZONE' . "\r\n";
	$ics_content .= 'BEGIN:VEVENT' . "\r\n";
	$ics_content .= 'DTSTART;TZID='. esc_html( $data['lead_timezone'] ) . ':' . $date->format( WEBINARIGNITION_DATE_FORMAT ) . "\r\n";
	$ics_content .= 'DTEND;TZID='. esc_html( $data['lead_timezone'] ) . ':' . $date->modify( '+1 hour' )->format( WEBINARIGNITION_DATE_FORMAT ) . "\r\n";
	$ics_content .= 'UID:' . $date->getTimestamp() . '@' . $lead_id . "\r\n";
	$ics_content .= 'DTSTAMP:' . gmdate( WEBINARIGNITION_DATE_FORMAT ) . 'Z' . "\r\n";
	$ics_content .= 'SUMMARY:' . $webinar_title . "\r\n";
	$ics_content .= 'DESCRIPTION:' . $desc . '. Visit ' . $url . "\r\n";
	$ics_content .= 'URL;VALUE=URI:' . $url . "\r\n";
	$ics_content .= 'END:VEVENT' . "\r\n";
	$ics_content .= 'END:VCALENDAR';

	$upload_dir = wp_upload_dir();
	$file_path = $upload_dir['path'] . '/event.ics';
	
	file_put_contents($file_path, $ics_content);

	return $file_path;
}
