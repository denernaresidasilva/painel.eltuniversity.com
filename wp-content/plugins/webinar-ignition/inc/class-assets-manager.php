<?php
/**
 * Responsible managing plugin assets.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Webinar_Ignition_Assets_Manager' ) ) {

	/**
	 * Plugin assets manager for frontend and backend.
	 *
	 * @since 3.08.1
	 */
	class Webinar_Ignition_Assets_Manager {

		private static $instance;

		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			require_once WEBINARIGNITION_PATH . 'inc/assets/class-common-scripts.php';
			require_once WEBINARIGNITION_PATH . 'inc/assets/class-admin-scripts.php';
			require_once WEBINARIGNITION_PATH . 'inc/assets/class-frontend-scripts.php';

			Webinar_Ignition_Admin_Scripts::init();
			Webinar_Ignition_Frontend_Scripts::init();

			return self::$instance;
		}
	}

	Webinar_Ignition_Assets_Manager::init();
}//end if
