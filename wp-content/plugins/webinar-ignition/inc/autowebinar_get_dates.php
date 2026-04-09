<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ! TODO: Use if function_not_exists for functions of this file.

function webinarignition_get_full_weekday( $short ) {
	$long_array = array(
		'mon' => 'monday',
		'tue' => 'tuesday',
		'wed' => 'wednesday',
		'thu' => 'thursday',
		'fri' => 'friday',
		'sat' => 'saturday',
		'sun' => 'sunday',
	);
	return $long_array[ $short ];
}

function webinarignition_get_full_weekday_num( $num ) {
	$long_array = array(
		'1' => 'monday',
		'2' => 'tuesday',
		'3' => 'wednesday',
		'4' => 'thursday',
		'5' => 'friday',
		'6' => 'saturday',
		'7' => 'sunday',
	);
	return $long_array[ $num ];
}

function webinarignition_get_wp_locale_date_strings( $dates, $date_format ) {

	if ( ! is_array( $dates ) ) {
		return $dates;
	}

	foreach ( $dates as $date => $label ) {

		if ( 'instant_access' === $date ) {
			continue;
		}
		$dateFromFormat = DateTime::createFromFormat( 'Y-m-d', $date );
		$dates[ $date ] = date_i18n( $date_format, $dateFromFormat->getTimestamp() );
	}

	return $dates;
}

add_action( 'wp_ajax_nopriv_webinarignition_auto_lp_get_dates', 'webinarignition_auto_lp_get_dates_callback' );
add_action( 'wp_ajax_webinarignition_auto_lp_get_dates', 'webinarignition_auto_lp_get_dates_callback' );
add_action( 'wp_ajax_dismiss_old_webinars_notification', 'webinarignition_dismiss_old_webinars_notification_callback' );

function webinarignition_dismiss_old_webinars_notification_callback() {
	/**
	 * Update the value of wi_has_old_webinars
	 * so user will not see the notification again.
	 */
	update_option( 'wi_has_old_webinars', 'msg_dismissed' );
	wp_send_json( array( 'code' => 'success' ) );
	die();
}

function webinarignition_auto_lp_get_dates_callback() {
	check_ajax_referer( 'webinarignition_ajax_nonce', 'security', false );

	// Prototype For Evergreen Webinar
	// Get Variables
	$tz = sanitize_text_field( $_POST['tz'] );
	$ID = sanitize_text_field( $_POST['id'] );

	// Get Results
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );

	if ( ! empty( $webinar_data->webinar_lang ) ) {
		switch_to_locale( $webinar_data->webinar_lang );
		unload_textdomain( 'webinar-ignition' );
		load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
	}

	$date_format = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';

	$day_toggle = array(
		'monday'    => $webinar_data->auto_monday,
		'tuesday'   => $webinar_data->auto_tuesday,
		'wednesday' => $webinar_data->auto_wednesday,
		'thursday'  => $webinar_data->auto_thursday,
		'friday'    => $webinar_data->auto_friday,
		'saturday'  => $webinar_data->auto_saturday,
		'sunday'    => $webinar_data->auto_sunday,
	);

	$blacklisted_dates  = array_map( 'trim', explode( ',', $webinar_data->auto_blacklisted_dates ) );
	$webinar_timezone   = webinarignition_get_webinar_timezone( $webinar_data, $tz );
	$weekday_time_slots = webinarignition_get_time_slots_by_weekdays( $webinar_data );
	$one_day_interval   = DateInterval::createfromdatestring( '+1 day' );
	$datetime_now       = new DateTime( 'now', new DateTimeZone( $webinar_timezone ) );
	$weekday_now        = strtolower( $datetime_now->format( 'l' ) );

	if ( ! empty( $weekday_time_slots[ $weekday_now ] ) ) {
		$today_time_slots_desc_order = $weekday_time_slots[ $weekday_now ];
		webinar_ignition_sort_datetime_desc( $today_time_slots_desc_order ); // Sort the times in DESC order

		// Create a new datetime object with today's date for comparison with max time slot, and assign webinar timezone
		$datetime_compare = new DateTime( $datetime_now->format( 'Y-m-d' ) . ' ' . $today_time_slots_desc_order[0], new DateTimeZone( $webinar_timezone ) );

		// Convert current datetime from webinar timezone to UTC for comparison, and to avoid daylight saving differences
		$datetime_now->setTimezone( new DateTimeZone( 'UTC' ) );

		// Convert compare datetime from webinar timezone to UTC
		$datetime_compare->setTimezone( new DateTimeZone( 'UTC' ) );

		$skip_today = $datetime_now->getTimestamp() > $datetime_compare->getTimestamp();

		if ( $skip_today ) {
			$datetime_now->add( $one_day_interval ); // Skip today
		}

		// Set current datetime back to webinar timezone for later use
		$datetime_now->setTimezone( new DateTimeZone( $webinar_timezone ) );
	}//end if

	$number_of_days       = ! empty( $webinar_data->auto_day_limit ) && is_numeric( $webinar_data->auto_day_limit ) ? $webinar_data->auto_day_limit : 7;
	$number_of_days_delay = ! empty( $webinar_data->auto_day_offset ) && is_numeric( $webinar_data->auto_day_offset ) ? $webinar_data->auto_day_offset : 0;
	if ( ! empty( $number_of_days_delay ) ) {
		$datetime_now->add( DateInterval::createfromdatestring( "+$number_of_days_delay day" ) );
	}

	$datetimeObjects = array();
	$dates           = array();
	$times           = array();

	while ( $number_of_days > count( $datetimeObjects ) ) {//phpcs:ignore

		$_current_date_weekday_full = strtolower( $datetime_now->format( 'l' ) );
		$skip_day                   = in_array( $datetime_now->format( 'Y-m-d' ), $blacklisted_dates, true ) || 'yes' !== trim( $day_toggle[ $_current_date_weekday_full ] );

		if ( $skip_day ) {
			$datetime_now->add( $one_day_interval );
			continue;
		}

		$datetimeObjects[] = clone $datetime_now;
		$datetime_now->add( $one_day_interval );
	}

	// Instant access
	if ( 'yes' === trim( $webinar_data->auto_today ) ) {
		$instant_text = empty( $webinar_data->auto_translate_instant ) ? __( 'Instant Access', 'webinar-ignition' ) : $webinar_data->auto_translate_instant;
		$dates        = array( 'instant_access' => $instant_text );
	}

	if ( ! empty( $datetimeObjects ) ) {
		foreach ( $datetimeObjects as $datetimeObject ) {
			$dates[ $datetimeObject->format( 'Y-m-d' ) ] = $datetimeObject->format( $date_format );
		}
	}

	foreach ( $dates as $mysql_date_string => $date_format_string ) {

		if ( 'instant_access' === $mysql_date_string ) {
			continue;
		}

		$_current_date_weekday_full = strtolower( gmdate( 'l', strtotime( $mysql_date_string ) ) );

		if ( isset( $weekday_time_slots[ $_current_date_weekday_full ] ) && ! empty( $weekday_time_slots[ $_current_date_weekday_full ] ) ) {
			$times[ $mysql_date_string ] = $weekday_time_slots[ $_current_date_weekday_full ];
		} else {
			unset( $dates[ $mysql_date_string ] );
		}
	}

	$times = array_map( 'array_unique', $times );

	$response_data = array(
		'dates'         => webinarignition_get_wp_locale_date_strings( $dates, $date_format ),
		'times_by_date' => $times,
		'tz'            => $webinar_timezone,
	);

	restore_previous_locale();

	wp_send_json( $response_data );
}

function webinarignition_get_time_slots_by_weekdays( $webinar_data ) {
	$time_slots                    = array();
	$is_multiple_auto_time_enabled = WebinarignitionPowerups::webinarignition_is_multiple_auto_time_enabled( $webinar_data );

	if ( $is_multiple_auto_time_enabled ) {
		for ( $number = 1; $number <= 3; $number++ ) {
			$weekday_attribute      = "auto_weekdays_{$number}";
			$time_weekdays          = isset( $webinar_data->{$weekday_attribute} ) && ! empty( $webinar_data->{$weekday_attribute} ) ? $webinar_data->{$weekday_attribute} : array();
			$weekday_time_attribute = "auto_time_{$number}";
			$time_weekday_times     = isset( $webinar_data->{$weekday_time_attribute} ) && ! empty( $webinar_data->{$weekday_time_attribute} ) ? $webinar_data->{$weekday_time_attribute} : array();

			if ( ! empty( $time_weekdays ) && ! empty( $time_weekday_times ) ) {
				foreach ( $time_weekdays as $weekday_short ) {
					$weekday_full = strtolower( gmdate( 'l', strtotime( $weekday_short ) ) );

					if ( 'yes' !== trim( $webinar_data->{"auto_$weekday_full"} ) ) {
						continue;
					}//end if

					$time_slots[ $weekday_full ][] = $time_weekday_times;
				}
			}
		}

		$weekday_attribute           = 'multiple__auto_weekdays';
		$weekday_time_attribute      = 'multiple__auto_time';
		$multiple_time_weekdays      = isset( $webinar_data->{$weekday_attribute} ) && ! empty( $webinar_data->{$weekday_attribute} ) ? $webinar_data->{$weekday_attribute} : array();
		$multiple_time_weekday_times = isset( $webinar_data->{$weekday_time_attribute} ) && ! empty( $webinar_data->{$weekday_time_attribute} ) ? $webinar_data->{$weekday_time_attribute} : array();

		foreach ( $multiple_time_weekdays as $time_weekday_index => $time_weekdays ) {
			$time_weekday_times = isset( $multiple_time_weekday_times[ $time_weekday_index ] ) && ! empty( $multiple_time_weekday_times[ $time_weekday_index ] ) ? $multiple_time_weekday_times[ $time_weekday_index ] : array();
			if ( ! empty( $time_weekdays ) && ! empty( $time_weekday_times ) ) {
				foreach ( $time_weekdays as $weekday_short ) {
					$weekday_full = strtolower( gmdate( 'l', strtotime( $weekday_short ) ) );
					if ( 'yes' !== trim( $webinar_data->{"auto_$weekday_full"} ) ) {
						continue;
					}//end if

					$time_slots[ $weekday_full ][] = $time_weekday_times;
				}
			}
		}
	} else {

		// Loop over each weekday
		for ( $weekday_number = 1; $weekday_number <= 7; $weekday_number++ ) {
			$weekday_full = strtolower( gmdate( 'l', strtotime( "Sunday +{$weekday_number} days" ) ) );

			if ( 'yes' !== $webinar_data->{"auto_$weekday_full"} ) {
				continue;
			}//end if

			for ( $number = 1; $number <= 3; $number++ ) {
				$weekday_time_attribute = "auto_time_{$number}";

				if ( 'no' !== $webinar_data->{$weekday_time_attribute} ) {
					$time_slots[ $weekday_full ][] = $webinar_data->{$weekday_time_attribute};
				}
			}
		}
	}//end if

	return $time_slots;
}

function webinar_ignition_sort_datetime_desc( array &$datetimes ) {
	usort(
		$datetimes,
		function ( $time1, $time2 ) {
			if ( strtotime( $time1 ) < strtotime( $time2 ) ) {
				return 1;
			} elseif ( strtotime( $time1 ) > strtotime( $time2 ) ) {
				return -1;
			} else {
				return 0;
			}
		}
	);
}
