<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $leadID
 * @var $leadId
 * @var $leadinfo
 */

// Get Results
$results = $webinar_data;
$data    = $leadinfo;

// Webinar Info
$webinar_title = $results->webinar_desc ? $results->webinar_desc : __( 'Webinar Title', 'webinar-ignition' );
$desc          = $results->webinar_desc ? $results->webinar_desc : __( 'Info on what you will learn on the webinar...', 'webinar-ignition' );
$host          = $results->webinar_host ? $results->webinar_host : __( 'Webinar Host', 'webinar-ignition' );

if ( isset( $results->ty_webinar_url ) && 'custom' === $results->ty_webinar_url ) {
	$url = $results->ty_werbinar_custom_url;
} else {
	$url = isset($results->webinar_permalink) ? ($results->webinar_permalink) . '?live&webinar&lid=' . $leadId : '#';
}

$timezone = isset($data->lead_timezone) ? $data->lead_timezone : '+00'; // Default to UTC if not set
if ( isset($timezone) && ! in_array( $timezone[0], array( '-', '+' ), true ) ) {
	$timezone = '+' . $timezone;
}
if(property_exists($webinar_data, 'lp_schedule_type') && $webinar_data->lp_schedule_type == 'fixed'){
	$date = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->auto_date_fixed_submit. ' ' . $webinar_data->auto_time_fixed_submit, new DateTimeZone( $webinar_data->auto_timezone_fixed  ) );
	$data->lead_timezone = $webinar_data->auto_timezone_fixed;
	
}else{

	$date = DateTime::createFromFormat( 'Y-m-d H:i', $data->date_picked_and_live, new DateTimeZone( $data->lead_timezone ) );
}

define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );

header( 'Content-type: application/text' );
header( 'Content-Disposition: attachment; filename=webinar-date.ics' );
header( 'Pragma: no-cache' );
header( 'Expires: 0' );
$tzid = trim( $data->lead_timezone ); // e.g. "Asia/Karachi"

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
    'DTEND;TZID=' . esc_html($tzid) . ':' . esc_html( (clone $date)->modify('60 minutes' )->format( 'Ymd\THis' ) ) . "\r\n" .
    'UID:' . esc_html( $date->getTimestamp() ) . '@' . esc_html( $webinar_id ) . "\r\n" .
    'DTSTAMP:' . esc_html( gmdate( 'Ymd\THis' ) ) . 'Z' . "\r\n" .
    'SUMMARY:' . esc_html( $webinar_title ) . "\r\n" .
    'DESCRIPTION:' . esc_html( $desc ) . '. Visit ' . wp_kses_post($url) . "\r\n" .
    'URL;VALUE=URI:' .  esc_url_raw($url) . "\r\n" .
    'END:VEVENT' . "\r\n" .
    'END:VCALENDAR';

