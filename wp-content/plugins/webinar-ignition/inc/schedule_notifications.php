<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $wpdb;
// def :: define local vars
// -----------------------------------------------------------------------------------
	$rpl = array( 'new' => 'live' );                                  // replace string values
	$table_name = $wpdb->prefix . 'webinarignition';
	$lst = $wpdb->get_results("SELECT id, camtype FROM `{$table_name}`");                        // job list
	$cmp = null;                                             // campaign

	require_once 'wi-admin-functions.php';

if ( ! empty( $lst ) ) {

	require 'schedule_email_live_fn.php';

	foreach ( $lst as $cmp ) {
		if ( is_numeric( $cmp->camtype ) || $cmp->camtype == 'import' ) {
			$sil = WebinarignitionManager::webinarignition_get_webinar_data( $cmp->id );
			$cmp->camtype       = 'live';
			if ( $sil->webinar_date == 'AUTO' ) {
				$cmp->camtype    = 'auto';
			}
		}

		$cmp->camtype      = ( isset( $rpl[ $cmp->camtype ] ) ? $rpl[ $cmp->camtype ] : $cmp->camtype );
		$fnBaseName        = "schedule_email_{$cmp->camtype}.php";
		$campaignID        = $cmp->id;
		include $fnBaseName;
	}
}
