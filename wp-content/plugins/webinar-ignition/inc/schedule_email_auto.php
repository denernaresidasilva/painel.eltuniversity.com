<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $campaignID ) ) {
	require_once 'schedule_notifications.php';
} else {
	// Get ALL Leads
	global $wpdb;
	$table_db_name          = $wpdb->prefix . 'webinarignition_leads_evergreen';
	$results                = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_db_name WHERE app_id = %s", $campaignID ), OBJECT );
	$timezone_string_option = get_option( 'timezone_string' );

	if ( ! empty( $results ) ) {
		foreach ( $results as $result ) {
			
			
			// LOOP START ##################
			// GET DATE -------------------
			// Get Date
			// Set Timezone:

			$tzstring = empty($result->lead_timezone) ? $timezone_string_option : $result->lead_timezone;

			// Create a new DateTime object with the current time in GMT
			$date = new DateTime('now', new DateTimeZone('GMT'));

			// Convert the time to the specified timezone or handle UTC offset
			if (!empty($tzstring)) {
				try {
					if (preg_match('/^UTC([+-]\d{1,2})$/', $tzstring, $matches)) {
						// Handle UTC offset like UTC+7 or UTC-5
						$hoursOffset = (int) $matches[1];
						$offsetInSeconds = $hoursOffset * 3600;
						$date->modify("$offsetInSeconds seconds");
					} else {
						// Handle standard timezone strings like Asia/Bangkok
						$date->setTimezone(new DateTimeZone($tzstring));
					}
				} catch (Exception $e) {
					// Handle invalid timezone strings gracefully
					error_log('Invalid timezone string: ' . $tzstring);
				}
			}

			// Format the date and time according to your needs
			$date_and_time = $date->format('Y-m-d H:i');
			$date_only     = $date->format('Y-m-d');
			$time_only     = $date->format('H:i');
			$time_only_e   = explode( ':', $time_only );


			// If you need the time as a timestamp
			$time = strtotime($date_and_time);
			
			$startTime = gmdate( 'H:i', strtotime( '-30 minutes', $time ) );
			$endTime   = gmdate( 'H:i', strtotime( '+30 minutes', $time ) );

			$time_buffer                   = $time_only_e[1] - 10;
			$time_buffer2                  = $time_only_e[1] + 10;
			$date_and_time_buffer_negative = $date_only . ' ' . $startTime;
			$date_and_time_buffer_plus     = $date_only . ' ' . $endTime;

			// Check If Lead is Complete - Ignore
			if (in_array($result->lead_status, array('complete', 'attending', 'watched'), true)) { //phpcs:ignore
				// IGNORE - done sequence
			} else {
				// ####################################
				//
				// Check 1 Day After
				//
				// ####################################
				if ( 'sent' !== $result->date_1_day_after_check && ( $time - strtotime( $result->date_1_day_after ?? '' ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: 1 DAY AFTER :: ". $result->email ."</b>";
					if( get_option('webinarignition_lead_confirmed_'.$result->ID)){
						WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 5 ) . " ({$result->date_1_day_after}) " . __( 'triggered for', 'webinar-ignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinar-ignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
						webinarignition_cron_email( $campaignID, $result->ID, 5, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
						// Update In DB
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table_db_name SET 
								date_1_day_after_check = %s,
								date_after_live_check = %s,
								date_picked_and_live_check = %s,
								date_1_day_before_check = %s,
								date_1_hour_before_check = %s,
								lead_status = %s
								WHERE id = %d",
								'sent',
								$result->date_after_live_check,
								$result->date_picked_and_live_check,
								$result->date_1_day_before_check,
								$result->date_1_hour_before_check,
								'pending',
								$result->ID
							)
						);
					}
					continue;
				}

				// ####################################
				//
				// Check After Live Is Over
				//
				// ####################################
				if ( 'sent' !== $result->date_after_live_check && ( $time - strtotime( $result->date_after_live ?? '' ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: 1 HOUR AFTER :: ". $result->email ."</b>";
					if( get_option('webinarignition_lead_confirmed_'.$result->ID)){
						WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 4 ) . " ({$result->date_after_live}) " . __( 'triggered for', 'webinar-ignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinar-ignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
						webinarignition_cron_email( $campaignID, $result->ID, 4, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
						// Update In DB
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table_db_name SET 
								date_after_live_check = %s,
								date_picked_and_live_check = %s,
								date_1_day_before_check = %s,
								date_1_hour_before_check = %s
								WHERE id = %d",
								'sent',
								$result->date_picked_and_live_check,
								$result->date_1_day_before_check,
								$result->date_1_hour_before_check,
								$result->ID
							)
						);
					}
					
					continue;
				}

				// ####################################
				//
				// Check LIVE Webinar
				//
				// ####################################
				if ( 'sent' !== $result->date_picked_and_live_check && $time >= strtotime( $result->date_picked_and_live ) - 300  ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: EVENT LIVE :: ". $result->email ."</b>";
					if( get_option('webinarignition_lead_confirmed_'.$result->ID)){
						WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 3 ) . " ({$result->date_picked_and_live}) " . __( 'triggered for', 'webinar-ignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinar-ignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
						webinarignition_cron_email( $campaignID, $result->ID, 3, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
						// Update In DB
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table_db_name SET 
								date_picked_and_live_check = %s,
								date_1_day_before_check = %s,
								date_1_hour_before_check = %s
								WHERE id = %d",
								'sent',
								$result->date_1_day_before_check,
								$result->date_1_hour_before_check,
								$result->ID
							)
						);
					}
				
					continue;
				}

				// ####################################
				//
				// Check 1 Hour Before
				//
				// ####################################

				if ( 'sent' !== $result->date_1_hour_before_check && ( $time - strtotime( $result->date_1_hour_before ?? '' ) >= 0 ) ) {
					// Send Out Email
					// echo "<br><br><b>EMAIL :: 1 HOUR BEFORE :: ". $result->email ."</b>";
					if( get_option('webinarignition_lead_confirmed_'.$result->ID)){
						WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 2 ) . " ({$result->date_1_hour_before}) " . __( 'triggered for', 'webinar-ignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinar-ignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );

						webinarignition_cron_email( $campaignID, $result->ID, 2, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );

						if ( ! empty( $result->phone ) ) {
							WebinarIgnition_Logs::add( "TXT notification ({$result->date_1_hour_before}) " . __( 'triggered for', 'webinar-ignition' ) . " {$result->name} ({$result->phone}) - " . __( 'chosen starting date:', 'webinar-ignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_SMS );
							webinarignition_send_txt_auto( $campaignID, $result->phone, $result->ID );
						}

						// Update In DB
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE $table_db_name SET 
								date_1_hour_before_check = %s,
								date_1_day_before_check = %s
								WHERE id = %d",
								'sent',
								$result->date_1_day_before_check,
								$result->ID
							)
						);

					}
					
					continue;
				}

				// start if loop
				// ####################################
				//
				// Check 1 Day Before
				//
				// ####################################

				if ( 'sent' !== $result->date_1_day_before_check && ( $time - strtotime( $result->date_1_day_before ?? '' ) >= 0 ) && isset($result->date_picked_and_live) ) {

					// Input date string
					$input_date = trim($result->date_picked_and_live); // Trim any extra spaces or newlines

					// Create a DateTime object from the input
					$date_picked = DateTime::createFromFormat('Y-m-d H:i', $input_date);

					// Check if the DateTime object was created successfully
					if ($date_picked === false) {
						error_log('Failed to create DateTime object. Invalid format: ' . $input_date);
						return; // Handle the error or return early
					}

					// Format the date as 'm-d-Y'
					$date_picked_formatted = $date_picked->format('m-d-Y');

					// Create a DateTime object for 'today' in the specified timezone
					$timezone_string = !empty($result->lead_timezone) ? $result->lead_timezone : get_option('timezone_string');

					// Fallback in case even the site timezone is not set
					if (empty($timezone_string)) {
						$timezone_string = 'UTC';
					}
					
					$today = new DateTime('now', new DateTimeZone($timezone_string));
					// Set the time to the start of the day (midnight)
					$today->setTime( 0, 0 );

					// Format today's date as 'm-d-Y'
					$today_formatted = $today->format( 'm-d-Y' );

					if ( $today_formatted !== $date_picked_formatted ) { // don't send tomorrow-reminders on the day.
						if( get_option('webinarignition_lead_confirmed_'.$result->ID)){
							WebinarIgnition_Logs::add( webinarignition_prettifyNotificationTitle( 1 ) . " ({$result->date_1_day_before }) " . __( 'triggered for', 'webinar-ignition' ) . " {$result->name} ({$result->email}) - " . __( 'chosen starting date:', 'webinar-ignition' ) . " {$result->date_picked_and_live}", $campaignID, WebinarIgnition_Logs::AUTO_EMAIL );
							webinarignition_cron_email( $campaignID, $result->ID, 1, $result->name, $result->email, $result->date_picked_and_live, $result->lead_timezone );
							// Update In DB
							$wpdb->query(
								$wpdb->prepare(
									"UPDATE $table_db_name SET date_1_day_before_check = %s WHERE id = %d",
									'sent',
									$result->ID
								)
							);
						}
						
					}

					continue;
				}
				// end if loop
			} //end if
		} //end foreach
	} //end if
}//end if
