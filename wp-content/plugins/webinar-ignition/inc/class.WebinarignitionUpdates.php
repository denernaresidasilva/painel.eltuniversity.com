<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Class WebinarignitionUpdates
 */

class WebinarignitionUpdates {
	public static function webinarignition_check_updates() {
		$is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( $is_ajax ) {
			return;
		}
		if ( empty( get_option( 'webinarignition_installer_version' ) ) ) {
			return;
		}

		$webinarignition_2_2_0_update = get_option( 'webinarignition_2_2_0_update' );
		if ( empty( $webinarignition_2_2_0_update ) ) {
			self::webinarignition_check_2_2_0_update();
		}

		$webinarignition_2_3_0_update = get_option( 'webinarignition_2_3_0_update' );
		if ( empty( $webinarignition_2_3_0_update ) ) {
			self::webinarignition_check_2_3_0_update();
		}

		$webinarignition_2_2_17_update = get_option( 'webinarignition_2_2_17_update' );
		if ( empty( $webinarignition_2_2_17_update ) ) {
			self::webinarignition_check_2_2_17_update();
		}

		$webinarignition_2_4_4_update = get_option( 'webinarignition_2_4_4_update' );
		if ( empty( $webinarignition_2_4_4_update ) ) {
			self::webinarignition_check_2_4_4_update();
		}

		$webinarignition_2_5_0_update = get_option( 'webinarignition_2_5_0_update' );
		if ( empty( $webinarignition_2_5_0_update ) ) {
			self::webinarignition_check_2_5_0_update();
		}

		$webinarignition_2_6_5_update = get_option( 'webinarignition_2_6_5_update' );
		if ( empty( $webinarignition_2_6_5_update ) ) {
			self::webinarignition_check_2_6_5_update();
		}

		$webinarignition_2_6_8_update = get_option( 'webinarignition_2_6_8_update' );
		if ( empty( $webinarignition_2_6_8_update ) ) {
			self::webinarignition_check_2_6_8_update();
		}

		$webinarignition_2_9_0_update = get_option( 'webinarignition_2_9_0_update' );
		if ( empty( $webinarignition_2_9_0_update ) ) {
			self::webinarignition_check_2_9_0_update();
		}

		if ( ! defined( 'WEBINAR_IGNITION_DISABLE_WEBHOOKS' ) || WEBINAR_IGNITION_DISABLE_WEBHOOKS === false ) {
			self::webinarignition_setupWebhooks();
		}

		$webinarignition_2_12_0_update = get_option( 'webinarignition_2_12_0_update' );
		if ( empty( $webinarignition_2_12_0_update ) ) {
			self::webinarignition_check_2_12_0_update();
		}
	}
	private static function webinarignition_check_2_2_0_update() {
	}

	/**
	 * Prepare DB for protected ids
	 */
	private static function webinarignition_check_2_2_17_update() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'webinarignition_leads';
		if ( self::webinarignition_is_db_column_exist( $table_name, 'hash_ID' ) === false ) {
			$column_name = 'hash_ID';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*)
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s AND COLUMN_NAME = %s",
					$table_name,
					$column_name
				)
			);
			
			if (!$column_exists) {
				// Add the column only if it doesn't exist
				$wpdb->query("ALTER TABLE {$table_name} ADD COLUMN `{$column_name}` VARCHAR(40) DEFAULT NULL");
			}
		}
		
		$leads = $wpdb->get_results($wpdb->prepare(
			"SELECT ID, app_id, email FROM $table_name"
		), ARRAY_A);
		if ( ! empty( $leads ) ) {
			foreach ( $leads as $lead ) {
				$lead_hashed_id = sha1( $lead['app_id'] . $lead['email'] );
				$wpdb->query( $wpdb->prepare(
					"UPDATE $table_name SET hash_ID = %s WHERE ID = %d",
					$lead_hashed_id,
					$lead['ID']
				) );
			}
		}

		$table_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
		if ( self::webinarignition_is_db_column_exist( $table_name, 'hash_ID' ) === false ) {
			$column_name = 'hash_ID';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*)
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s AND COLUMN_NAME = %s",
					$table_name,
					$column_name
				)
			);
			
			if (!$column_exists) {
				// Add the column only if it doesn't exist
				$wpdb->query("ALTER TABLE {$table_name} ADD COLUMN `{$column_name}` VARCHAR(40) DEFAULT NULL");
			}
		}

		
		$leads = $wpdb->get_results( $wpdb->prepare( "SELECT ID, app_id, email FROM $table_name" ), ARRAY_A );

		if ( ! empty( $leads ) ) {
			foreach ( $leads as $lead ) {
				$lead_hashed_id = sha1( $lead['app_id'] . $lead['email'] );
				$wpdb->update( $table_name, array( 'hash_ID' => $lead_hashed_id ), array( 'ID' => $lead['ID'] ) );
			}
		}

		
		$webinars = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE %s", 'webinarignition_campaign_%' ), ARRAY_A );
		$map = array();
		$map_rev = array();

		if ( ! empty( $webinars ) ) {
			foreach ( $webinars as $webinar ) {
				$webinar_campaign = $webinar['option_name'];
				$webinar_campaign_array = explode( '_', $webinar_campaign );
				$webinar_id_by_campaign = $webinar_campaign_array[2];
				$webinar_settings_string = $webinar['option_id'] . $webinar['option_value'];
				$webinar_hashed_id = sha1( $webinar_settings_string );
				$map[ $webinar_hashed_id ] = $webinar_id_by_campaign;
				$map_rev[ $webinar_id_by_campaign ] = $webinar_hashed_id;
			}

			update_option( 'webinarignition_map_campaign_hash_to_id', $map );
			update_option( 'webinarignition_map_campaign_id_to_hash', $map_rev );
		}

		update_option( 'webinarignition_2_2_17_update', 1 );
	}

	/**
	 * Update all webinars without IDs
	 */
	private static function webinarignition_check_2_3_0_update() {
		global $wpdb;

		
		$webinars = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE %s", 'webinarignition_campaign_%' ), ARRAY_A );

		if ( ! empty( $webinars ) ) {
			foreach ( $webinars as $webinar ) {
				if ( ! empty( $webinar['option_value'] ) ) {
					$webinar_settings = maybe_unserialize( $webinar['option_value'] );
					$webinar_campaign = $webinar['option_name'];
					$webinar_id = ! empty( $webinar_settings->id ) ? $webinar_settings->id : '';
					$webinar_campaign_array = explode( '_', $webinar_campaign );
					$webinar_id_by_campaign = $webinar_campaign_array[2];

					if ( empty( $webinar_id ) || $webinar_id_by_campaign !== $webinar_id ) {
						$webinar_settings->id = (string) $webinar_id_by_campaign;

						update_option( 'webinarignition_campaign_' . $webinar_id_by_campaign, $webinar_settings );
					}
				}
			}
		}

		update_option( 'webinarignition_2_3_0_update', 1 );
	}

	/**
	 * Create leads metatables
	 */
	private static function webinarignition_check_2_4_4_update() {
		if ( ! class_exists( 'WebinarignitionLeadsManager' ) ) {
			include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionLeadsManager.php';
		}

		WebinarignitionLeadsManager::webinarignition_create_meta_tables();

		update_option( 'webinarignition_2_4_4_update', 1 );
	}

	private static function webinarignition_check_2_5_0_update() {
		global $wpdb;
		$table_name          = $wpdb->prefix . 'webinarignition_questions';
		if ( self::webinarignition_is_db_column_exist( $table_name, 'parent_id' ) === false ) {
			$column_name = 'parent_id';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*)
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s AND COLUMN_NAME = %s",
					$table_name,
					$column_name
				)
			);
			
			if (!$column_exists) {
				// Add the column only if it doesn't exist
				$wpdb->query("ALTER TABLE {$table_name} ADD COLUMN `{$column_name}` BIGINT DEFAULT NULL");
			}
		}

		if ( self::webinarignition_is_db_column_exist( $table_name, 'type' ) === false ) {
			$column_name = 'type';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*)
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s AND COLUMN_NAME = %s",
					$table_name,
					$column_name
				)
			);
			
			if (!$column_exists) {
				// Add the column only if it doesn't exist
				$wpdb->query("ALTER TABLE {$table_name} ADD COLUMN `{$column_name}` VARCHAR(30) NOT NULL DEFAULT ''");
			}
		}

		update_option( 'webinarignition_2_5_0_update', 1 );
	}

	private static function webinarignition_check_2_6_5_update() {
		global $wpdb;
		$table_name          = $wpdb->prefix . 'webinarignition_users_online';

		if ( self::webinarignition_is_db_column_exist( $table_name, 'lead_id' ) === false ) {
			$column_name = 'lead_id';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COLUMN_NAME
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s
					 AND COLUMN_NAME = %s",
					$table_name,
					$column_name
				)
			);
			
			if (!$column_exists) {
				// Add the column if it doesn't exist
				$wpdb->query("
					ALTER TABLE `{$table_name}` 
					ADD COLUMN `{$column_name}` BIGINT NOT NULL;
				");
			}
		}

		update_option( 'webinarignition_2_6_5_update', 1 );
	}

	private static function webinarignition_check_2_6_8_update() {
		global $wpdb;
		$table_name          = $wpdb->prefix . 'webinarignition_questions';

		if ( self::webinarignition_is_db_column_exist( $table_name, 'answer_text' ) === false ) {
			$column_name = 'answer_text';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COLUMN_NAME 
					 FROM INFORMATION_SCHEMA.COLUMNS 
					 WHERE TABLE_NAME = %s 
					 AND COLUMN_NAME = %s 
					 AND TABLE_SCHEMA = DATABASE()", 
					$table_name, 
					$column_name
				)
			);
			
			// Add the column only if it doesn't exist
			if (is_null($column_exists)) {
				$wpdb->query("ALTER TABLE {$table_name} ADD COLUMN {$column_name} MEDIUMTEXT NULL AFTER answer");
			}
		}

		update_option( 'webinarignition_2_6_8_update', 1 );
	}

	private static function webinarignition_is_db_column_exist( $table_name, $column_name ) {
		global $wpdb;
		$row = $wpdb->get_results( $wpdb->prepare( "SHOW COLUMNS FROM `{$table_name}` LIKE %s", $column_name ) );

		return ( ! empty( $row ) );
	}

	/**
	 * Set site language where webinar language is missing
	 */
	private static function webinarignition_check_2_9_0_update() {
		global $wpdb;

		
		$webinars = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE %s", 'webinarignition_campaign_%' ), ARRAY_A );

		if ( ! empty( $webinars ) ) {
			foreach ( $webinars as $webinar ) {
				if ( ! empty( $webinar['option_value'] ) ) {
					$webinar_settings = maybe_unserialize( $webinar['option_value'] );
					$webinar_campaign = $webinar['option_name'];
					$webinar_id = ! empty( $webinar_settings->id ) ? absint( $webinar_settings->id ) : 0;
					$webinar_campaign_array = explode( '_', $webinar_campaign );
					$webinar_campaign_id = absint( $webinar_campaign_array[2] );

					if ( ! empty( $webinar_id ) || $webinar_campaign_id === $webinar_id ) {
						if ( ! isset( $webinar_settings->webinar_lang ) || empty( $webinar_settings->webinar_lang ) ) {
							$webinar_settings->webinar_lang = get_locale();
							add_option( "webinarignition_lang_auto_set_{$webinar_id}", true );
						}

						update_option( 'webinarignition_campaign_' . $webinar_campaign_id, $webinar_settings );
					}
				}
			}
		}

		/**
		 * Re-run following updates to be sure all new columns are there, which might be missing due to invalid queries in previous versions
		 */
		self::webinarignition_check_2_2_17_update();
		self::webinarignition_check_2_5_0_update();
		self::webinarignition_check_2_6_5_update();
		self::webinarignition_check_2_6_8_update();

		update_option( 'webinarignition_2_9_0_update', 1 );
	}

	public static function webinarignition_setupWebhooks() {
		global $wpdb;
		// Create table webinarignition_webhooks
		$table_name = $wpdb->prefix . 'webinarignition_webhooks';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
			$sql = "CREATE TABLE `{$table_name}` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) DEFAULT NULL,
				  `trigger` varchar(255) NOT NULL,
				  `url` text NOT NULL,
				  `request_method` tinyint(1) DEFAULT 0 COMMENT '0=GET,1=POST',
				  `request_format` tinyint(1) DEFAULT 0 COMMENT '0=JSON,1=FORM',
				  `secret` text DEFAULT NULL,
				  `is_active` tinyint(1) DEFAULT 0 COMMENT '0=INACTIVE,1=ACTIVE',
				  `modified` datetime DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) {$charset_collate}";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}
		// Create table webinarignition_webhooks - END

		// Add new column "integration", if not exists
		if ( self::webinarignition_is_db_column_exist( $table_name, 'integration' ) === false ) {
			$column_name = 'integration';

			// Check if the column already exists
			$column_exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*)
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s AND COLUMN_NAME = %s",
					$table_name,
					$column_name
				)
			);
			
			if (!$column_exists) {
				// Add the column only if it doesn't exist
				$wpdb->query("
					ALTER TABLE `{$table_name}` 
					ADD COLUMN `{$column_name}` VARCHAR(255) DEFAULT 'default' NULL 
					COMMENT 'default,fluentcrm,other custom integrations' 
					AFTER `trigger`;
				");
			}
		}
		// Add new column "integration" - END

		if ( self::webinarignition_is_db_column_exist( $table_name, 'conditions' ) === false ) {
			$column_name = 'conditions';
			$after_column = 'is_active'; // The column after which the new column will be added
			
			// Check if the table and columns exist
			$table_columns = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COLUMN_NAME
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s",
					$table_name
				)
			);
			
			$column_exists = false;
			$after_column_exists = false;
			
			if ($table_columns) {
				foreach ($table_columns as $column) {
					if (property_exists($column, 'COLUMN_NAME') && $column->COLUMN_NAME === $column_name) {
						$column_exists = true;
					}
					if (property_exists($column, 'COLUMN_NAME') && $column->COLUMN_NAME === $after_column) {
						$after_column_exists = true;
					}
				}
			}
			
			if (!$column_exists && $after_column_exists) {
				// Add the column only if it doesn't exist and the 'after' column exists
				$wpdb->query("
					ALTER TABLE `{$table_name}` 
					ADD COLUMN `{$column_name}` TEXT NULL 
					COMMENT 'Holds fields conditions and mappings' 
					AFTER `{$after_column}`;
				");
			} 
		}
	}

	private static function webinarignition_check_2_12_0_update() {

		global $wpdb;
		$table_name = $wpdb->prefix . 'webinarignition_leads';

		if ( self::webinarignition_is_db_column_exist( $table_name, 'lead_status' ) === false ) {
			$column_name = 'lead_status';
			$after_column = 'skype';
			
			// Check if the table and columns exist
			$table_columns = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COLUMN_NAME
					 FROM INFORMATION_SCHEMA.COLUMNS
					 WHERE TABLE_NAME = %s",
					$table_name
				)
			);
			
			$column_exists = false;
			$after_column_exists = false;
			
			if ($table_columns) {
				foreach ($table_columns as $column) {
					if (property_exists($column, 'COLUMN_NAME') && $column->COLUMN_NAME === $column_name) {
						$column_exists = true;
					}
					if (property_exists($column, 'COLUMN_NAME') && $column->COLUMN_NAME === $after_column) {
						$after_column_exists = true;
					}
				}
			}
			
			if (!$column_exists && $after_column_exists) {
				// Add the column only if it doesn't exist and the 'after' column exists
				$wpdb->query("
					ALTER TABLE `{$table_name}` 
					ADD COLUMN `{$column_name}` VARCHAR(50) NULL 
					AFTER `{$after_column}`;
				");
			}
		}

		update_option( 'webinarignition_2_12_0_update', 1 );
	}
}
