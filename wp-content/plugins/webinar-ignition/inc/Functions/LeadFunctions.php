<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function webinarignition_live_get_lead_by_email( $webinarId, $email, $is_protected = false ) {
	global $wpdb;
	$table = $wpdb->prefix . 'webinarignition_leads';

	if ( $is_protected ) {
		return $wpdb->get_row($wpdb->prepare( "SELECT hash_ID AS ID FROM {$table} WHERE email = %s AND app_id = %d", $email, $webinarId ), OBJECT );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	} else {
		return $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$table} WHERE email = %s AND app_id = %d", $email, $webinarId ), OBJECT );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}
}

function webinarignition_get_lead( $webinarId, $leadId, $isAuto, $is_protected = false ) {

	global $wpdb;
	$leadTable = $wpdb->prefix . ( $isAuto ? 'webinarignition_leads_evergreen' : 'webinarignition_leads' );

	if ( $is_protected ) {
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$leadTable} WHERE hash_ID = %s AND app_id = %d", $leadId, $webinarId ), OBJECT );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	} else {
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$leadTable} WHERE id = %s AND app_id = %d", $leadId, $webinarId ), OBJECT );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

}

function webinarignition_get_lead_info( $leadId, $webinar_data, $protected = true ) {
    if($webinar_data){
        $webinarId = absint( $webinar_data->id );
        $is_lead_protected = ! empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id && $protected;

        global $wpdb;
        $leadTable = WebinarignitionManager::webinarignition_is_auto_webinar( $webinar_data ) ? 'webinarignition_leads_evergreen' : 'webinarignition_leads';
        $leadTable = $wpdb->prefix . $leadTable; // Ensure this contains the correct table name
        $sql_query_params = array( $webinarId, $leadId );

        if ( $is_lead_protected ) {
            if ( WebinarignitionManager::webinarignition_is_auto_webinar( $webinar_data ) && ! $is_lead_protected ) {
                return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$leadTable}` L WHERE L.`app_id` = %d AND hash_ID = %s ORDER BY L.`date_picked_and_live` DESC LIMIT 1;", $sql_query_params ), OBJECT );
            }else{
                return $wpdb->get_row( $wpdb->prepare("SELECT * FROM `{$leadTable}` L WHERE L.`app_id` = %d AND hash_ID = %s", $sql_query_params ), OBJECT );
            }
        } else {
            $sql_query_params[] = $leadId;
            if ( WebinarignitionManager::webinarignition_is_auto_webinar( $webinar_data ) && ! $is_lead_protected ) {
                return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$leadTable}` L WHERE L.`app_id` = %d AND (L.`hash_ID` = %s OR L.`ID` = %d) ORDER BY L.`date_picked_and_live` DESC LIMIT 1;", $sql_query_params ), OBJECT );
            }else{
                return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$leadTable}` L WHERE L.`app_id` = %d AND (L.`hash_ID` = %s OR L.`ID` = %d)", $sql_query_params ), OBJECT );
            }
        }
    }
}
