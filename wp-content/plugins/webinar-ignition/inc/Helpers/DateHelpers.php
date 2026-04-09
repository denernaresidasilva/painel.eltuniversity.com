<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WiDateHelpers {

	/**
	 * @param string $name e.g. 'Africa/Johannesburg'.
	 * @return string e.g. 'SAST' or 'UTC +8' if there is no abbreviation.
	 */
	public static function webinarignition_get_tz_abbr_from_name( $name ) {
		// Create a DateTime object for the current date and time in the specified timezone
		$dt = new DateTime( 'now', new DateTimeZone( $name ) );

		// Get the timezone abbreviation
		$abbr = $dt->format( 'T' );

		// Check if the abbreviation is numeric
		if ( is_numeric( $abbr ) ) {
			// Get UTC offset
			if ( strlen( $abbr ) > 2 && $abbr[1] == 0 ) {
				$abbr = $abbr[0] . $abbr[2];
			}
			return 'UTC ' . $abbr;
		}

		return $abbr;
	}

	/**
	 * NOT IN USE ANYMORE, SHOULD BE REMOVED
	 *
	 * Get next days for custom schedule
	 *
	 * @param integer $offset e.g. skip the next 2 days.
	 * @param int     $max e.g. max number of days to return max 7.
	 * @param array   $toggle_days
	 * @param string  $blacklist e.g. '2019-01-27, 2019-01-28'.
	 * @return array
	 */
	public static function webinarignition_get_next_days( $offset, $max, $toggle_days, $blacklist, $date_format = 'l, F j, Y' ) {
		// $max cannot be higher than 7.
		if ( $max > 7 ) {
			$max = 7;
		}
		if ( $offset < 1 ) {
			$offset = 1;
		}

		$excludedWeekdays  = array_keys(
			array_filter(
				$toggle_days,
				function ( $weekday ) {
					return 'no' === $weekday;
				}
			)
		);
		$blacklisted_dates = array_map( 'trim', explode( ',', $blacklist ) );

		$arr  = array();
		$date = new DateTime( 'now' );
		$date->modify( "+$offset day" );

		for ( $i = $offset; count( $arr ) < $max; $i++ ) {
			$date->modify( '+1 day' );

			$day = strtolower( $date->format( 'l' ) );
			if ( in_array( $day, $excludedWeekdays ) ) {
				continue;
			}

			$Ymd = $date->format( 'Y-m-d' );
			if ( in_array( $Ymd, $blacklisted_dates ) ) {
				continue;
			}

			$arr[ $Ymd ] = $date->format( $date_format );
		}

		return $arr;
	}

	/**
	 * Retrieve list of translated months
	 */
	public static function webinarignition_get_locale_months() {

				global $wp_locale;
				
				$translated_months = array();
		for ( $month_index = 1; $month_index <= 12; $month_index++ ) :
				$translated_months[] = $wp_locale->get_month( $month_index );
				endfor;

				return $translated_months;
	}


	/**
	 * Retrieve list of translated days
	 */
	public static function webinarignition_get_locale_days() {

				global $wp_locale;

				$translate_days = array();
		for ( $day_index = 0; $day_index <= 6; $day_index++ ) :
				$translate_days[] = $wp_locale->get_weekday( $day_index );
				endfor;

				return $translate_days;
	}


	/**
	 * Retrieve list of translated days
	 */
	public static function webinarignition_get_locale_weekday_abbrev() {

				global $wp_locale;

				$translate_days = array();
		for ( $day_index = 0; $day_index <= 6; $day_index++ ) :
					$weekday_name     = $wp_locale->get_weekday( $day_index );
					$translate_days[] = $wp_locale->get_weekday_abbrev( $weekday_name );
				endfor;

				return $translate_days;
	}
}
