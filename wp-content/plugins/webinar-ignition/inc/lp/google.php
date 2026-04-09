<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get DB Info
global $wpdb;
$table_db_name = $wpdb->prefix . 'webinarignition';
// Sanitize input values
$webinar_id = intval($webinar_id); // Ensure $webinar_id is an integer

// Prepare and execute the query
$data = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
        $webinar_id
    ),
    OBJECT
);
// Webinar Info
$webinar_title = $webinar_data->webinar_desc ? $webinar_data->webinar_desc : __( 'Webinar Title', 'webinar-ignition' );
$desc          = $webinar_data->webinar_desc ? $webinar_data->webinar_desc : __( 'Info for what you will learn on the webinar...', 'webinar-ignition' );
$host          = $webinar_data->webinar_host ? $webinar_data->webinar_host : __( 'Webinar Host', 'webinar-ignition' );

$lid = isset( $_GET['lid'] ) ? sanitize_text_field( wp_unslash( $_GET['lid'] ) ) : null;

if ( isset( $webinar_data->ty_webinar_url ) && 'custom' === $webinar_data->ty_webinar_url && ! empty( $webinar_data->ty_werbinar_custom_url ) ) {
	$url = $webinar_data->ty_werbinar_custom_url;
} else {
	$url = get_permalink( $data->postID ) . '?live&lid=' . $lid;
}
$url .='&webinar'; ;
// encode url parameters
$webinar_title = rawurlencode( $webinar_title );
$desc          = rawurlencode( $desc );
$host          = rawurlencode( $host );
$url           = rawurlencode( $url );

$timezone = $webinar_data->webinar_timezone;
if ( ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
	$timezone = '+' . $timezone;
}
$timezone_sign   = $timezone[0];
$timezone_offset = str_pad( str_replace( '0', '', substr( $timezone, 1 ) ), 4, '0', STR_PAD_BOTH );

$webinar_data->webinar_start_time = gmdate( 'H:i', strtotime( $webinar_data->webinar_start_time ) );
$duration = property_exists($webinar_data, 'webinar_start_duration') && isset($webinar_data->webinar_start_duration) ? $webinar_data->webinar_start_duration : 60;

$date = DateTime::createFromFormat( 'm-d-Y H:i:s', $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time . ':00', new DateTimeZone( $timezone_offset ) );
$date->setTimezone( new DateTimeZone( 'UTC' ) );

define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );

// Build Final URL
$build_url = 'http://www.google.com/calendar/event?action=TEMPLATE&text=' . $webinar_title . '&dates=' . $date->format( WEBINARIGNITION_DATE_FORMAT ) . 'Z/' . $date->modify( $duration.' minutes' )->format( WEBINARIGNITION_DATE_FORMAT ) . 'Z&details=' . $desc . '&location=' . $url . '&trp=true&sprop=' . $host . '&sprop=name:' . $url;

header( "Location: $build_url" );
