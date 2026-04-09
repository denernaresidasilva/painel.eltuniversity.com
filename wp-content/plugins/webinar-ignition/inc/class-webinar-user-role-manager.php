<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Webinar_User_Role_Manager' ) ) {
	class Webinar_User_Role_Manager {
		private $user;
		private $user_email;
		private $role;
		private $capabilities;
		private $webinar_data;

		public function __construct( $webinar_data ) {
			$this->webinar_data = $webinar_data;

			if ( is_user_logged_in() ) {
				$this->user         = wp_get_current_user();
				$this->user_email   = $this->user->user_email;
				$this->role         = $this->webinarignition_get_current_user_role();
				$this->capabilities = $this->webinarignition_check_user_capabilities( $this->role );
				$this->webinarignition_validate_roles();
			}
		}

		private function webinarignition_get_current_user_role() {
			$roles = (array) $this->user->roles;

			if ( empty( $roles ) ) {
				return null;
			}

			if ( count( $roles ) === 1 ) {
				return $roles[0];
			}

			$role = 'subscriber';
			foreach ( $roles as $role_temp ) {
				if ( 'webinarignition_support' === $role_temp && ! in_array( $role, array( 'webinarignition_host', 'webinarignition_admin', 'administrator' ), true ) ) {
					$role = $role_temp;
				} elseif ( 'webinarignition_host' === $role_temp && ! in_array( $role, array( 'webinarignition_admin', 'administrator' ), true ) ) {
					$role = $role_temp;
				}
			}

			return $role;
		}

		private function webinarignition_check_user_capabilities( $role ) {
			$capabilities = array(
				'is_support' => false,
				'is_host'    => false,
				'is_admin'   => false,
			);

			if ( 'webinarignition_support' === $role ) {
				$capabilities['is_support'] = true;
			} elseif ( 'webinarignition_host' === $role ) {
				$capabilities['is_host'] = true;
			} elseif ( current_user_can( 'manage_options' ) ) {
				$capabilities['is_admin'] = true;
			}

			return $capabilities;
		}

		private function webinarignition_validate_roles() {
			if ( $this->capabilities['is_support'] ) {
				$this->capabilities['is_support'] = $this->webinarignition_validate_support_role();
			}

			if ( $this->capabilities['is_host'] ) {
				$this->capabilities['is_host'] = $this->webinarignition_validate_host_role();
			}
		}

		private function webinarignition_validate_support_role() {
			if ( ! WebinarignitionManager::webinarignition_is_support_enabled( $this->webinar_data ) ) {
				return false;
			}

			for ( $x = 1; $x <= $this->webinar_data->support_staff_count; $x++ ) {
				$member_email_str = 'member_email_' . $x;
				if ( ! empty( $this->webinar_data->{$member_email_str} ) && $this->user_email === $this->webinar_data->{$member_email_str} ) {
					return true;
				}
			}

			return false;
		}

		private function webinarignition_validate_host_role() {
			if ( ! WebinarignitionManager::webinarignition_is_support_enabled( $this->webinar_data, 'host' ) ) {
				return false;
			}

			for ( $x = 1; $x <= $this->webinar_data->host_member_count; $x++ ) {
				$host_member_email_str = 'host_member_email_' . $x;
				if ( ! empty( $this->webinar_data->{$host_member_email_str} ) && $this->user_email === $this->webinar_data->{$host_member_email_str} ) {
					return true;
				}
			}

			return false;
		}

		public function webinarignition_is_support() {
			return $this->capabilities['is_support'];
		}

		public function webinarignition_is_host() {
			return $this->capabilities['is_host'];
		}

		public function webinarignition_is_admin() {
			return $this->capabilities['is_admin'];
		}
	}
}//end if
