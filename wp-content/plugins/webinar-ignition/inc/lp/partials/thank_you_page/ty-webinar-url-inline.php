<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $webinarId
 */

$prefix = 'tyCountdown-';
$uid = wp_unique_id( $prefix );
$is_public = WebinarignitionManager::webinarignition_is_webinar_public( $webinar_data );

if ( ! $is_public ) {
	$webinarIdUrl = $webinar_data->hash_id;
} else {
	$webinarIdUrl = $webinarId;
}

$watch_type = 'live';

if ( isset( $webinar_data->ty_webinar_url ) && 'custom' === $webinar_data->ty_webinar_url && ! empty( $webinar_data->ty_werbinar_custom_url ) ) {
	echo esc_url( $webinar_data->ty_werbinar_custom_url );
} else {
	$liveWebinarUrl = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'webinar' );
	$liveWebinarUrl = add_query_arg( 'live', '', $liveWebinarUrl );
	$liveWebinarUrl = add_query_arg( 'webinar', '', $liveWebinarUrl );

	if ( empty( $leadId ) && isset( $getLiveIDByEmail->id ) && ! empty( $getLiveIDByEmail->id ) ) {
		$leadId = $getLiveIDByEmail->id;
	}

	$liveWebinarUrl = add_query_arg( 'lid', $leadId, $liveWebinarUrl );
	if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
		$liveWebinarUrl = add_query_arg( md5( $webinar_data->paid_code ), '', $liveWebinarUrl );
	}

	$liveWebinarUrl = add_query_arg( 'watch_type', $watch_type, $liveWebinarUrl );

	echo esc_url( $liveWebinarUrl );
}
