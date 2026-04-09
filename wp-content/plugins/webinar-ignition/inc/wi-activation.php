<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function webinarignition_installer() {

			global $wpdb;

			$table_name = $wpdb->prefix . 'webinarignition';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

		$sql = 'CREATE TABLE ' . $table_name . ' (

                    ID INTEGER(100) UNSIGNED AUTO_INCREMENT,

                    created varchar(30),

                    appname varchar(200),

                    camtype varchar(20),

                    total_lp varchar(200),

                    total_ty varchar(200),

                    total_live varchar(200),

                    total_replay varchar(200),

                    postID varchar(10),

                   UNIQUE KEY id (id)

                 )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );

	}//end if

			$table_name = $wpdb->prefix . 'webinarignition_leads';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = 'CREATE TABLE ' . $table_name . ' (

                  ID INTEGER(100) UNSIGNED AUTO_INCREMENT,

                  app_id varchar(20),

                  name varchar(200),

                  email varchar(200),

                  phone varchar(200),

                  skype varchar(200),

                  event varchar(50),

                  replay varchar(50),

                  trk1 varchar(50),
                  trk2 varchar(50),
                  trk3 varchar(50),
                  trk4 varchar(50),
                  trk5 varchar(50),
                  trk6 varchar(50),
                  trk7 varchar(50),
                  trk8 varchar(50),
                  trk9 varchar(50),
                  lead_browser_and_os varchar(256),
                  gdpr_data VARCHAR(256) DEFAULT NULL,
                  `created` varchar(200) DEFAULT NULL,
				  `hash_ID` varchar(40) DEFAULT NULL,
                  UNIQUE KEY id (id)

                  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );

	}//end if

			$table_name = $wpdb->prefix . 'webinarignition_leads_evergreen';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = 'CREATE TABLE ' . $table_name . ' (

                  ID INTEGER(100) UNSIGNED AUTO_INCREMENT,

                  app_id varchar(20),

                  name varchar(200),
                  email varchar(200),

                  phone varchar(200),
                  skype varchar(200),

                  created varchar(200),

                  date_picked_and_live varchar(50),
                  date_picked_and_live_check varchar(50),

                  date_1_day_before varchar(50),
                  date_1_day_before_check varchar(50),

                  date_1_hour_before varchar(50),
                  date_1_hour_before_check varchar(50),

                  date_after_live varchar(50),
                  date_after_live_check varchar(50),

                  date_1_day_after varchar(50),
                  date_1_day_after_check varchar(50),

                  lead_timezone varchar(50),

                  lead_status varchar(50),

                  event varchar(50),

                  replay varchar(50),

                  trk1 varchar(50),
                  trk2 varchar(50),
                  trk3 varchar(50),
                  trk4 varchar(50),
                  trk5 varchar(50),
                  trk6 varchar(50),
                  trk7 varchar(50),
                  trk8 varchar(50),
                  trk9 varchar(50),
                  lead_browser_and_os varchar(256),
                  gdpr_data VARCHAR(256) DEFAULT NULL,
				  `hash_ID` varchar(40) DEFAULT NULL,
                  UNIQUE KEY id (id)

                  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );

	}//end if

			$table_name = $wpdb->prefix . 'webinarignition_questions';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = 'CREATE TABLE ' . $table_name . ' (

                  ID INTEGER(100) UNSIGNED AUTO_INCREMENT,

                  app_id varchar(20),

                  name varchar(100),

                  email varchar(100),

                  question LONGTEXT,

                  status varchar(200),

                  attr1 varchar(50),
                  attr2 varchar(50),
                  attr3 varchar(50),
                  attr4 varchar(50),
                  attr5 varchar(50),
                  answer TEXT,
                  answer_text MEDIUMTEXT,
                  webinarTime TEXT,

                  created varchar(200),

                  UNIQUE KEY id (id)

                  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );

	}//end if

			$table_name = $wpdb->prefix . 'webinarignition_wi';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = 'CREATE TABLE ' . $table_name . ' (

                  ID INTEGER(100) UNSIGNED AUTO_INCREMENT,

                  keyused varchar(150),

                  switch varchar(150),

                  UNIQUE KEY id (id)

                  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );

	}

			$table_name = $wpdb->prefix . 'webinarignition_users_online';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = 'CREATE TABLE ' . $table_name . ' (

                  ID INTEGER(100) UNSIGNED AUTO_INCREMENT,

                  app_id varchar(150),

                  dt timestamp NOT NULL default CURRENT_TIMESTAMP,

                  IP varchar(150),

                  UNIQUE KEY id (id)

                  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );

	}//end if

			$table_name = $wpdb->prefix . 'wi_logs';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = "CREATE TABLE `$table_name` (
                  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                  `campaign_id` bigint(20) unsigned DEFAULT NULL,
                  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `type` tinyint(4) DEFAULT NULL,
                  `message` text NOT NULL,
                  PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );
	}

			$table_name = $wpdb->prefix . 'webinarignition_verification';

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

			$sql = "CREATE TABLE `$table_name` (
                  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                  `email` varchar(150)  DEFAULT NULL,
                  `code` INTEGER(100) DEFAULT NULL,  
                  `verified` tinyint(4) DEFAULT NULL,  
                  `token` varchar(150) DEFAULT NULL,
                  PRIMARY KEY  (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );
	}

			// update_option('webinarignition_db_version', WEBINARIGNITION_DB_VERSION);

			update_option( 'webinarignition_installer_version', WEBINARIGNITION_DB_VERSION );

			// // Set default value for third party server option
			// if ( get_option( 'webinarignition_enable_third_party_server' ) === false ) {
			// 	update_option( 'webinarignition_enable_third_party_server', 1 );
			// }
}
