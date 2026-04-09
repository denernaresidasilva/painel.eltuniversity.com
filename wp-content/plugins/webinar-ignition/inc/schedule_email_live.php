<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $campaignID ) ) {
	require_once 'schedule_notifications.php';
} else {
	// Get Results
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $campaignID );
	if ( ! empty( $webinar_data->time_format ) && ( $webinar_data->time_format == '12hour' || $webinar_data->time_format == '24hour' ) ) { // old formats
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
	$time_format = isset($webinar_data->time_format) ? $webinar_data->time_format : get_option('time_format', 'H:i');

	// SETUP :: Core Time Settings
	$TZID = isset($webinar_data->webinar_timezone) ? webinarignition_convert_utc_to_tzid($webinar_data->webinar_timezone) : null;


	// Create a new DateTime object with the current time in GMT
	$date = new DateTime('now', new DateTimeZone('GMT'));

	// Convert the time to the specified timezone or handle UTC offset
	if (!empty($TZID)) {
		try {
			if (preg_match('/^UTC([+-]\d{1,2})$/', $TZID, $matches)) {
				// Handle UTC offset like UTC+7 or UTC-5
				$hoursOffset = (int) $matches[1];
				$offsetInSeconds = $hoursOffset * 3600;
				$date->modify("$offsetInSeconds seconds");
			} else {
				// Handle standard timezone strings like Asia/Bangkok
				$date->setTimezone(new DateTimeZone($TZID));
			}
		} catch (Exception $e) {
			// Handle invalid timezone strings gracefully
			error_log('Invalid timezone string: ' . $TZID);
		}
	}

	// Format the date and time according to your needs
	$date_and_time = $date->format('Y-m-d H:i');
	$date_only     = $date->format('Y-m-d');
	$time_only     = $date->format('H:i');
	$time_only_e   = explode( ':', $time_only );


	// If you need the time as a timestamp
	$time = strtotime($date_and_time);




	// #####################################
	//
	// ### Schedule Checks - Match Time/Date
	//
	// #####################################
	//
	// NOTIFICATION EMAIL #1
	//
	// #####################################
	//
	$timeonly    = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) ) ? false : true;
	$webinar_timezone = isset($webinar_data->webinar_timezone) ? $webinar_data->webinar_timezone : 'UTC';
	$webinar_utc = trim( webinarignition_get_time_tz( $time, $time_format, $webinar_timezone, true, $timeonly ) );

	for ( $num = 5; $num > 1; $num-- ) {
		if($num == 5 || $num == 4){
			$live_video_data = isset($webinar_data->webinar_live_video) ? $webinar_data->webinar_live_video : '';
			if($live_video_data == ''){
				continue;
			}
		}
		$webinar_timestamp = NULL;
		if ( isset( $webinar_data->{"email_notiff_date_{$num}"} ) && isset( $webinar_data->{"email_notiff_time_{$num}"} ) ) {
			$notification_date = webinarignition_build_time( $webinar_data->{"email_notiff_date_{$num}"}, $webinar_data->{"email_notiff_time_{$num}"} );
			$webinar_timestamp = strtotime($notification_date);
		
		} else {
			$notification_date = '';
		}
		if (
			isset( $webinar_data->{'email_notiff_' . $num} )
			&& $webinar_data->{'email_notiff_' . $num} != 'off'
			&& isset( $webinar_data->{"email_notiff_status_{$num}"} )
			&& $webinar_data->{"email_notiff_status_{$num}"} != 'sent'
			&& (
				( $num == 3 && $time >= strtotime( $notification_date ) - 300 ) // 5 min before or after
				|| ( $num != 3 && $time - strtotime( $notification_date ) >= 0 && ( ( strtotime( $notification_date ) >= $time - 2700 ) )  ) // all others
			)
		) {
			$dateInWebinarTz = new DateTime( 'now', new DateTimeZone( $webinar_data->webinar_timezone ) );
			$dateInWebinarTz->setTime( 0, 0 ); // Set the time to the start of the day (midnight)
			$formattedDate = $dateInWebinarTz->format( 'm-d-Y' );

			// if this is the day-before notification "WEBINAR REMINDER :: Goes Live Tomorrow" and the webinar is today, do not send.
			if ( ( 1 == $num ) && ( $formattedDate == $webinar_data->webinar_date ) ) {
				$webinar_data->{"email_notiff_status_{$num}"} = 'sent';
				update_option( 'webinarignition_campaign_' . $campaignID, $webinar_data );
				continue;
			}

			if ( isset( $webinar_data->webinar_date ) ) {
				WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( $num ) . " ($notification_date) " . __( 'triggered for webinar starting on', 'webinar-ignition' ) . " {$webinar_data->webinar_date} @ {$webinar_data->webinar_start_time} ($webinar_utc)", $campaignID, WebinarIgnition_Logs::LIVE_EMAIL );
			}

			$webinar_data->{"email_notiff_status_{$num}"} = 'sent';
			update_option( 'webinarignition_campaign_' . $campaignID, $webinar_data );
			webinarignition_send_email( $campaignID, $num, $webinar_data );

		}//end if
	}//end for

	//
	// #####################################
	//
	// NOTIFICATION TXT
	//
	// #####################################
	//

	if ( isset( $webinar_data->email_twilio ) && $webinar_data->email_twilio != 'off' ) {

		$notification_date = webinarignition_build_time( $webinar_data->email_twilio_date, $webinar_data->email_twilio_time );
		if ( isset( $webinar_data->email_twilio_status ) && $webinar_data->email_twilio_status != 'sent' && ( $time - strtotime( $notification_date ) ) >= 0 ) {

			WebinarIgnition_Logs::add( "TXT notification ($notification_date) " . __( 'triggered for webinar starting on', 'webinar-ignition' ) . " {$webinar_data->webinar_date} @ {$webinar_data->webinar_start_time} ($webinar_utc)", $campaignID, WebinarIgnition_Logs::LIVE_SMS );
			if ( ! empty( $webinar_data->twilio_id ) && ! empty( $webinar_data->twilio_token ) ) {
				webinarignition_send_txt( $webinar_data );
				$webinar_data->email_twilio_status = 'sent';
			}

			update_option( 'webinarignition_campaign_' . $campaignID, $webinar_data );
		}
	}
}//end if
