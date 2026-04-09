<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( empty( get_option( 'webinarignition_installer_version' ) ) ) {
	return;
}

global $wpdb;

// CHECK DB VERSION AND RUN MIGRATIONS
$webinarignition_db_version = get_option( 'webinarignition_db_version' );
$webinarignition_db_version = ! empty( $webinarignition_db_version ) ? $webinarignition_db_version : 0;


if ( $webinarignition_db_version < 9 ) {
	// add os/browser columns (live)
	$table_name = $wpdb->prefix . 'webinarignition_leads';
	$row = $wpdb->get_results( $wpdb->prepare(
		"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = %s AND column_name = %s",
		$table_name,
		'gdpr_data'
	) );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	if ( empty( $row ) ) {
		$wpdb->query( "ALTER TABLE {$table_name} ADD COLUMN gdpr_data VARCHAR(256) DEFAULT NULL, ADD COLUMN event varchar(50), ADD COLUMN replay varchar(50),  ADD COLUMN trk1 varchar(50),ADD COLUMN trk2 varchar(50),ADD COLUMN trk3 varchar(50),ADD COLUMN trk4 varchar(50),ADD COLUMN trk5 varchar(50),ADD COLUMN trk6 varchar(50),ADD COLUMN trk7 varchar(50),ADD COLUMN trk8 varchar(50),ADD COLUMN trk9 varchar(50),ADD COLUMN lead_browser_and_os varchar(256)" );
	}


	$table_name = $wpdb->prefix . 'webinarignition_wi';
	$table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );

	if ( empty( $table_exists ) ) {
		$sql = 'CREATE TABLE ' . $table_name . ' (
		ID INTEGER(100) UNSIGNED AUTO_INCREMENT,
		keyused varchar(150),
		switch varchar(150),
		UNIQUE KEY id (id)
		)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}


	$table_name = $wpdb->prefix . 'webinarignition_leads_evergreen';

	$table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );

	if ( empty( $table_exists ) ) {
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
              UNIQUE KEY id (id)
              )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );
	} else {
		// add os/browser columns (evergreen)
		$row = $wpdb->get_results( $wpdb->prepare(
			"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = %s AND column_name = %s",
			$table_name,
			'gdpr_data'
		) );
		if ( empty( $row ) ) {
			$wpdb->query( "ALTER TABLE {$table_name} ADD COLUMN gdpr_data VARCHAR(256) DEFAULT NULL, ADD COLUMN event varchar(50), ADD COLUMN replay varchar(50),  ADD COLUMN trk1 varchar(50),ADD COLUMN trk2 varchar(50),ADD COLUMN trk3 varchar(50),ADD COLUMN trk4 varchar(50),ADD COLUMN trk5 varchar(50),ADD COLUMN trk6 varchar(50),ADD COLUMN trk7 varchar(50),ADD COLUMN trk8 varchar(50),ADD COLUMN trk9 varchar(50),ADD COLUMN lead_browser_and_os varchar(256)" );
		}
	}//end if



	// if this is an upgrade from old, legacy, version then add these columns. DB version in legacy == 5
	$table_name = $wpdb->prefix . 'webinarignition_questions';
	$row = $wpdb->get_results( $wpdb->prepare(
		"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = %s AND column_name = %s",
		$table_name,
		'name'
	) );
	if ( empty( $row ) ) {
		$wpdb->query( "ALTER TABLE {$table_name} ADD COLUMN name VARCHAR(100) DEFAULT NULL, ADD COLUMN email varchar(100), ADD COLUMN attr1 varchar(50),ADD COLUMN attr2 varchar(50),ADD COLUMN attr3 varchar(50),ADD COLUMN attr4 varchar(50),ADD COLUMN attr5 varchar(50),ADD COLUMN answer TEXT NULL,ADD COLUMN webinarTime " );
	}



	// update old webinars with correct url of wi logo
	$table_name = $wpdb->prefix . 'webinarignition';
	$lst = $wpdb->get_results( $wpdb->prepare( "SELECT id, camtype FROM $table_name" ) );// phpcs:ignore WordPress.DB.DirectDatabaseQuery

	if ( ! empty( $lst ) ) {

		foreach ( $lst as $cmp ) {
			$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $cmp->id );
			if($webinar_data){
				$webinar_data->email_notiff_body_1 = str_replace( '//images/wi-logo.png', '/images/wi-logo.png', $webinar_data->email_notiff_body_1 );
				$webinar_data->email_notiff_body_2 = str_replace( '//images/wi-logo.png', '/images/wi-logo.png', $webinar_data->email_notiff_body_2 );
				$webinar_data->email_notiff_body_3 = str_replace( '//images/wi-logo.png', '/images/wi-logo.png', $webinar_data->email_notiff_body_3 );
				$webinar_data->email_notiff_body_4 = str_replace( '//images/wi-logo.png', '/images/wi-logo.png', $webinar_data->email_notiff_body_4 );
				$webinar_data->email_notiff_body_5 = str_replace( '//images/wi-logo.png', '/images/wi-logo.png', $webinar_data->email_notiff_body_5 );
				$webinar_data->email_signup_body   = str_replace( '//images/wi-logo.png', '/images/wi-logo.png', $webinar_data->email_signup_body );
	
	
				$webinar_data->email_notiff_body_1 = str_replace( 'webinarignition/images/wi-logo.png', 'webinar-ignition/images/wi-logo.png', $webinar_data->email_notiff_body_1 );
				$webinar_data->email_notiff_body_2 = str_replace( 'webinarignition/images/wi-logo.png', 'webinar-ignition/images/wi-logo.png', $webinar_data->email_notiff_body_2 );
				$webinar_data->email_notiff_body_3 = str_replace( 'webinarignition/images/wi-logo.png', 'webinar-ignition/images/wi-logo.png', $webinar_data->email_notiff_body_3 );
				$webinar_data->email_notiff_body_4 = str_replace( 'webinarignition/images/wi-logo.png', 'webinar-ignition/images/wi-logo.png', $webinar_data->email_notiff_body_4 );
				$webinar_data->email_notiff_body_5 = str_replace( 'webinarignition/images/wi-logo.png', 'webinar-ignition/images/wi-logo.png', $webinar_data->email_notiff_body_5 );
				$webinar_data->email_signup_body   = str_replace( 'webinarignition/images/wi-logo.png', 'webinar-ignition/images/wi-logo.png', $webinar_data->email_signup_body );
	
				update_option( 'webinarignition_campaign_' . $cmp->id, $webinar_data );
			}

		}//end foreach
	}//end if

	update_option( 'webinarignition_db_version', WEBINARIGNITION_DB_VERSION );

}//end if

if ( ! $webinarignition_db_version || $webinarignition_db_version < 12 ) {

	// $table_name = $wpdb->prefix . 'webinarignition_questions';
	// $wpdb->query( "ALTER TABLE {$table_name} MODIFY attr6 TEXT NULL" );
	// $wpdb->query( "ALTER TABLE {$table_name} CHANGE COLUMN attr6 answer TEXT" );

	// $wpdb->query( "ALTER TABLE {$table_name} MODIFY attr7 varchar NULL" );
	// $wpdb->query( "ALTER TABLE {$table_name} CHANGE COLUMN attr7 webinarTime varchar(50)" );

	update_option( 'webinarignition_db_version', WEBINARIGNITION_DB_VERSION );

}

if ( ! $webinarignition_db_version || $webinarignition_db_version < 16 ) {
	$table_name = $wpdb->prefix . 'webinarignition_verification';

	if ( $wpdb->get_var( "show tables like '$table_name'" ) !== $table_name ) {
		$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		email varchar(150)  DEFAULT NULL,
		code INTEGER(100) DEFAULT NULL,  
		verified tinyint(4) DEFAULT NULL,  
		token varchar(150) DEFAULT NULL,
		PRIMARY KEY  (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );
	}

	update_option( 'webinarignition_db_version', WEBINARIGNITION_DB_VERSION );
}

add_action( 'init', 'webinarignition_smtp_migration' );

function webinarignition_smtp_migration() {
	$option_smtp_host       = get_option( 'webinarignition_smtp_host' );
	$option_smtp_migrated   = get_option( 'webinarignition_migrated_smtp' );

	if ( empty( $option_smtp_migrated ) && empty( $option_smtp_host ) && current_user_can( 'manage_options' ) ) {
		global $wpdb;

		$lst = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'webinarignition WHERE id = %d', 0 ) );
		if ( ! empty( $lst ) ) {
			foreach ( $lst as $cmp ) {
				$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $cmp->id );
				$option_smtp_host                = get_option( 'webinarignition_smtp_host' );
				$option_smtp_port                = get_option( 'webinarignition_smtp_port' );
				$option_smtp_protocol            = get_option( 'webinarignition_smtp_protocol' );
				$option_smtp_user                = get_option( 'webinarignition_smtp_user' );
				$option_smtp_pass                = get_option( 'webinarignition_smtp_pass' );
				$option_smtp_name                = get_option( 'webinarignition_smtp_name' );
				$option_smtp_email               = get_option( 'webinarignition_smtp_email' );
				$option_reply_to_email           = get_option( 'webinarignition_reply_to_email' );

				if ( empty( $option_smtp_host ) && empty( $option_smtp_user ) && empty( $option_smtp_pass ) && ! empty( $webinar_data->smtp_user ) && ! empty( $webinar_data->smtp_pass ) && ! empty( $webinar_data->smtp_port ) && ! empty( $webinar_data->smtp_host ) && ! empty( $webinar_data->transfer_protocol ) && ! empty( $webinar_data->smtp_name ) && ! empty( $webinar_data->smtp_email ) ) {
					$smtp_test_results_array = webinarignition_test_smtp_phpmailer( $webinar_data->smtp_host, $webinar_data->smtp_port, $webinar_data->smtp_user, $webinar_data->smtp_pass );
					if ( is_array( $smtp_test_results_array ) && isset( $smtp_test_results_array['status'] ) && ( 1 === (int) $smtp_test_results_array['status'] ) ) {
						update_option( 'webinarignition_smtp_host', $webinar_data->smtp_host );
						update_option( 'webinarignition_smtp_port', $webinar_data->smtp_port );
						update_option( 'webinarignition_smtp_protocol', $webinar_data->transfer_protocol );
						update_option( 'webinarignition_smtp_user', $webinar_data->smtp_user );
						update_option( 'webinarignition_smtp_pass', $webinar_data->smtp_pass );
						update_option( 'webinarignition_smtp_email', $webinar_data->smtp_email );
						update_option( 'webinarignition_smtp_name', $webinar_data->smtp_name );
						update_option( 'webinarignition_smtp_email', $webinar_data->smtp_email );
						update_option( 'webinarignition_reply_to_email', $webinar_data->smtp_email );
						update_option( 'webinarignition_smtp_connect', 1 );
						update_option( 'webinarignition_migrated_smtp', 1 );
						update_option( 'webinarignition_upgraded_smtp', 1 );
						break;
					}
				}
			}//end foreach
		}//end if

		update_option( 'webinarignition_db_version', WEBINARIGNITION_DB_VERSION );
	}//end if
}



add_action( 'admin_notices', 'webinarignition_smtp_migration_admin_notice' );

function webinarignition_smtp_migration_admin_notice() {

	$webinarignition_upgraded_smtp                = get_option( 'webinarignition_upgraded_smtp' );

	if ( 1 === (int) $webinarignition_upgraded_smtp ) { ?>
		<div id="webinarignition-smtp-notice" class="notice notice-success is-dismissible">
			<p>
				<?php esc_attr_e( 'Your WebinarIgnition SMTP settings have been migrated. You can find the new settings ', 'webinar-ignition' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition_settings' ) ); ?>">
					<?php esc_html_e( 'here', 'webinar-ignition' ); ?>
				</a>
			</p>
		</div>
		<?php
	}//end if
}

