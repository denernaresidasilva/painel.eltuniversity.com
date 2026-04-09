<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get Results
$results = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

// Get DB Info
global $wpdb;
$table_db_name = $wpdb->prefix . $pluginName;
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
$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinar-ignition' );
$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinar-ignition' );
$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinar-ignition' );

$lid = isset( $_GET['lid'] ) ? sanitize_text_field( wp_unslash( $_GET['lid'] ) ) : null;
$lead         = webinarignition_get_lead_info( $lid, $results, false );

if ( 'custom' === $results->ty_webinar_url ) {
	$url = $results->ty_werbinar_custom_url;
} else {
	$url = get_permalink( $data->postID ) . '?live&lid=' . $lid;
}


$results->webinar_start_time = gmdate( 'H:i', strtotime( $results->webinar_start_time ) );
$duration = property_exists($results, 'webinar_start_duration') && isset($results->webinar_start_duration) ? $results->webinar_start_duration : 60;

$timezone = $results->webinar_timezone;
if ( ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
	$timezone = '+' . $timezone;
}
$timezone_sign   = $timezone[0];
$timezone_offset = str_pad( str_replace( '0', '', substr( $timezone, 1 ) ), 4, '0', STR_PAD_BOTH );
// error_log('date: ' . print_r($timezone_offset, true));

$date            = DateTime::createFromFormat( 'm-d-Y H:i:s', $results->webinar_date . ' ' . $results->webinar_start_time . ':00', new DateTimeZone( $timezone_offset ) );

// Convert both start and end times to UTC
$date->setTimezone( new DateTimeZone( $lead->lead_browser_and_os ) );
define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );

header( 'Content-type: application/text' );
header( 'Content-Disposition: attachment; filename=webinar-date.ics' );
header( 'Pragma: no-cache' );
header( 'Expires: 0' );
$tzid = trim( $lead->lead_browser_and_os ); // e.g. "Asia/Karachi"

// Validate timezone, fallback to UTC if invalid
try {
    $timezone = new DateTimeZone( $tzid );
} catch (Exception $e) {
    $timezone = new DateTimeZone( 'UTC' );
    $tzid = 'UTC';
}

// Get offset for the event start time
$offsetSeconds = $timezone->getOffset( $date ); // $date is your already-set DateTime
$sign = ($offsetSeconds < 0) ? '-' : '+';
$offsetSeconds = abs($offsetSeconds);
$hours = floor($offsetSeconds / 3600);
$minutes = floor(($offsetSeconds % 3600) / 60);
$offsetFormatted = sprintf('%s%02d%02d', $sign, $hours, $minutes);

// Build .ics content
echo 'BEGIN:VCALENDAR' . "\r\n" .
    'VERSION:2.0' . "\r\n" .
    'PRODID:-//project/author//NONSGML v1.0//EN' . "\r\n" .
    'CALSCALE:GREGORIAN' . "\r\n" .
    'METHOD:PUBLISH' . "\r\n" .
    'BEGIN:VTIMEZONE' . "\r\n" .
    'TZID:' . esc_html($tzid) . "\r\n" .
    'BEGIN:STANDARD' . "\r\n" .
    'DTSTART:20000101T000000' . "\r\n" .
    'TZOFFSETTO:' . esc_html($offsetFormatted) . "\r\n" .
    'TZOFFSETFROM:' . esc_html($offsetFormatted) . "\r\n" .
    'END:STANDARD' . "\r\n" .
    'END:VTIMEZONE' . "\r\n" .
    'BEGIN:VEVENT' . "\r\n" .
    'DTSTART;TZID=' . esc_html($tzid) . ':' . esc_html( $date->format( 'Ymd\THis' ) ) . "\r\n" .
    'DTEND;TZID=' . esc_html($tzid) . ':' . esc_html( (clone $date)->modify( $duration . ' minutes' )->format( 'Ymd\THis' ) ) . "\r\n" .
    'UID:' . esc_html( $date->getTimestamp() ) . '@' . esc_html( $webinar_id ) . "\r\n" .
    'DTSTAMP:' . esc_html( gmdate( 'Ymd\THis' ) ) . 'Z' . "\r\n" .
    'SUMMARY:' . esc_html( $webinar_title ) . "\r\n" .
    'DESCRIPTION:' . esc_html( $desc ) . '. Visit ' . wp_kses_post( $url )  . "\r\n" .
    'URL;VALUE=URI:' . esc_url_raw( $url ) . "\r\n" .
    'END:VEVENT' . "\r\n" .
    'END:VCALENDAR';

