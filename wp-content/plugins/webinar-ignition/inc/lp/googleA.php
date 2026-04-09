<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $leadID
 * @var $leadId
 * @var $leadinfo
 */
?>
<?php

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
$url .='&webinar'; ;
// encode url parameters
$webinar_title = rawurlencode( $webinar_title );
$desc          = rawurlencode( $desc );
$host          = rawurlencode( $host );
$url           = rawurlencode( $url );

$date = DateTime::createFromFormat( 'Y-m-d H:i', $data->date_picked_and_live, new DateTimeZone( $data->lead_timezone ) );
$date->setTimezone( new DateTimeZone( 'UTC' ) );

define( 'WEBINARIGNITION_DATE_FORMAT', 'Ymd\THis' );

// Build Final URL
$build_url = 'http://www.google.com/calendar/event?action=TEMPLATE&text=' . $webinar_title . '&dates=' . $date->format( WEBINARIGNITION_DATE_FORMAT ) . 'Z/' . $date->modify( '+1 hour' )->format( WEBINARIGNITION_DATE_FORMAT ) . 'Z&details=' . $desc . '&location=' . $url . '&trp=true&sprop=' . $host . '&sprop=name:' . $url;

header( "Location: $build_url" );
