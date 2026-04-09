<?php

/**
 * Class WebinarignitionLeadsManager
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WebinarignitionLeadsManager {

	private static function webinarignition_get_meta_table_name( $table ) {
		global $wpdb;

		if ( 'leads' === $table ) {
			return $wpdb->prefix . 'webinarignition_leadmeta';
		} elseif ( 'leads_evergreen' === $table ) {
			return $wpdb->prefix . 'webinarignition_lead_evergreenmeta';
		}
	}

	public static function webinarignition_get_meta_schema( $table ) {
		if ( ! in_array( $table, array( 'leads', 'leads_evergreen' ), true ) ) {
			return false;
		}

		global $wpdb;

		$collate = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';
		$table_name = self::webinarignition_get_meta_table_name( $table );
		$max_index_length = 191;

		$sql = "
		CREATE TABLE {$table_name} (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			lead_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY lead_id (lead_id),
			KEY meta_key (meta_key($max_index_length))
		) {$collate};
		";

		return $sql;
	}

	public static function webinarignition_create_meta_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( self::webinarignition_get_meta_schema( 'leads' ) );
		dbDelta( self::webinarignition_get_meta_schema( 'leads_evergreen' ) );
	}

	public static function webinarignition_create_lead_meta( $lead_id, $meta_key, $meta_value, $webinar_type ) {

		$table = 'live' === $webinar_type ? 'leads' : 'leads_evergreen';
		$table_name = self::webinarignition_get_meta_table_name( $table );

		global $wpdb;


		$wpdb->query($wpdb->prepare(
			"INSERT INTO $table_name (lead_id, meta_key, meta_value) VALUES (%d, %s, %s)",
			$lead_id,
			$meta_key,
			$meta_value
		));// phpcs:ignore WordPress.DB.DirectDatabaseQuery

		return $wpdb->insert_id;
	}

	public static function webinarignition_update_lead_meta( $lead_id, $meta_key, $meta_value, $webinar_type ) {
		$table = 'live' === $webinar_type ? 'leads' : 'leads_evergreen';
		$table_name = self::webinarignition_get_meta_table_name( $table );
		global $wpdb;

		$lead_meta = self::webinarignition_get_lead_meta( $lead_id, $meta_key, $webinar_type );

		if ( empty( $lead_meta['meta_id'] ) ) {

			return self::webinarignition_create_lead_meta( $lead_id, $meta_key, $meta_value, $webinar_type );
		}

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $table_name SET lead_id = %d, meta_key = %s, meta_value = %s WHERE meta_id = %d",
				$lead_id,
				$meta_key,
				$meta_value,
				$lead_meta['meta_id']
			)
		);

		return $wpdb->insert_id;
	}

	public static function webinarignition_get_lead_meta( $lead_id, $meta_key, $webinar_type, $single = true ) {//phpcs:ignore
		$table = 'live' === $webinar_type ? 'leads' : 'leads_evergreen';
		$table_name = self::webinarignition_get_meta_table_name( $table );
		global $wpdb;
		$result     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE lead_id = %d AND meta_key = %s ORDER BY meta_id DESC", array( $lead_id, $meta_key ) ), ARRAY_A );

		if ( ! empty( $result ) ) {
			return $result[0];
		}

		return array();
	}

	/**
	 * Fix optName field from optFName to optName if available
	 *
	 * @param array $lead_meta
	 *
	 * @return array
	 */
	public static function webinarignition_fix_opt_name( array &$lead_meta ) {

		if ( isset( $lead_meta['optName']['value'] ) && '#firstlast#' === $lead_meta['optName']['value'] && isset( $lead_meta['optFName']['value'] ) && isset( $lead_meta['optLName']['value'] ) ) {
			unset( $lead_meta['optName'] );
			$lead_meta = self::webinarignition_replace_key_function( $lead_meta, 'optFName', 'optName' );
		}

		return $lead_meta;
	}

	private static function webinarignition_replace_key_function( $collection, $key1, $key2 ) {
		$keys = array_keys( $collection );
		$index = array_search( $key1, $keys, true );
		$array = array();
		if ( wp_validate_boolean( $index ) ) {
			$keys[ $index ] = $key2;
			$array = array_combine( $keys, $collection );
		}

		return $array;
	}
}
