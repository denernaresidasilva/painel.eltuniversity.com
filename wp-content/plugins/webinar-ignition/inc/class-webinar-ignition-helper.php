<?php
/**
 * Helper function class to be used globally
 *
 * @link       https://wp-centric.com/
 * @since      2.9.1
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Webinar_Ignition_Helper' ) ) {

	class Webinar_Ignition_Helper {

		/**
		 * Debug Log
		 *
		 * @param object $var
		 * @param bool   $print
		 * @param bool   $show_execute_at
		 */
		public static function webinarignition_debug_log( $var, $print = true, $show_execute_at = false ) {
			ob_start();

			if ( $show_execute_at ) {
				$bt = debug_backtrace();
				$caller = array_shift( $bt );
				$execute_at = $caller['file'] . ':' . $caller['line'] . "\n";
				echo esc_attr( $execute_at );
			}

			if ( $print ) {
				if ( is_object( $var ) || is_array( $var ) ) {
					echo esc_attr( print_r( $var, true ) );
				} else {
					echo esc_attr( $var, );
				}
			} else {
				var_dump( $var );
			}

			error_log( ob_get_clean() );
		}

		public static function webinarignition_doing_cron() {

			// Bail if not doing WordPress cron (>4.8.0)
			if ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) {
				return true;

				// Bail if not doing WordPress cron (<4.8.0)
			} elseif ( defined( 'DOING_CRON' ) && ( true === DOING_CRON ) ) {
				return true;
			}

			// Default to false
			return false;
		}

		public static function webinarignition_str_replace_first( $search, $replace, $subject ) {
			$search = '/' . preg_quote( $search, '/' ) . '/';
			return preg_replace( $search, $replace, $subject, 1 );
		}

		public static function webinarignition_ar_field_to_opt( $ar_field_name ) {
			$field_name = self::webinarignition_str_replace_first( 'ar_', '', $ar_field_name );
			$field_name = ucwords( $field_name, '_' );
			if ( strpos( $field_name, 'Custom', '0' ) === 0 ) {
				$field_name = 'opt' . $field_name;
			} else {
				$field_name = 'opt' . str_replace( '_', '', $field_name );
			}

			if ( 'optLname' === $field_name ) {
				$field_name = 'optLName';
			} elseif ( 'optPrivacyPolicy' === $field_name ) {
				$field_name = 'optGDPR_PP';
			} elseif ( 'optTermsAndConditions' === $field_name ) {
				$field_name = 'optGDPR_TC';
			} elseif ( 'optMailingList' === $field_name ) {
				$field_name = 'optGDPR_ML';
			}

			return $field_name;
		}

		/**
		 * Validate if given timezone string is valid
		 *
		 * @param DataTimeZone $timezoneId
		 *
		 * @return DateTimeZone|false
		 */
		public static function webinarignition_getValidTimezoneId( $timezoneId ) {
			try {
				new DateTimeZone( $timezoneId );
			} catch ( Exception $e ) {
				$timezoneId = wp_timezone_string();
			}

			return $timezoneId;
		}

		public static function webinarignition_is_user_registered_on_this_webinar( $webinar_id, $lead_id ) {
			global $wpdb;

			// Escape the table name
			$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
			$table_name = webinarignition_is_auto( $webinar_data ) ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads';

			// Sanitize input values
			$webinar_id = absint($webinar_id); // Sanitize as integer
			$lead_id = sanitize_text_field($lead_id); // Sanitize as text

			// Prepare and execute the query
			$query = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM `{$table_name}` WHERE `app_id` = %d AND (`hash_ID` = %s OR `ID` = %s);",
					$webinar_id,
					$lead_id,
					$lead_id
				),
				OBJECT
			);
			return ! empty( $query );
		}
	}
}//end if
