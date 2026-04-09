<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class WI_Install {

	public static function install() {
		update_option( 'webinarignition_plugin_activation_date', gmdate( 'Y-m-d' ), false );

		$page_number = get_option( 'wi_data_conversion_page', 0 );

		if ( ! empty( $page_number ) ) {
			return;
		}

		update_option( 'wi_data_conversion_status', 'start' );
	}
}
