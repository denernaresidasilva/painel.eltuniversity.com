<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WebinarignitionIntegration {

	public static function webinarignition_set_sac_logged_username( $logged_username, $current_user ) {//phpcs:ignore
		$is_webinarignition = WebinarignitionManager::webinarignition_is_webinarignition();

		if ( ! empty( $is_webinarignition ) ) {
			/**
			 * @var $lead
			 */
			extract( webinarignition_get_global_templates_vars( $is_webinarignition ) );//phpcs:ignore

			if ( ! empty( $lead ) && ! empty( $lead->name ) ) {
				$logged_username = $lead->name;
			}
		}

		return $logged_username;
	}

	public static function webinarignition_hurrytimer_additional_cta_content( $cta_content ) {
		$cta_content = str_replace( 'hurrytimer-campaign ', 'pre-hurrytimer-campaign ', $cta_content );
		return $cta_content;
	}
}

add_filter( 'webinarignition_additional_cta_content', 'WebinarignitionIntegration::webinarignition_hurrytimer_additional_cta_content' );
	// webinarignition_additional_cta_content
