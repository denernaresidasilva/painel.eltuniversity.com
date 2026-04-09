<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinarId
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $instantTest
 * @var $autoDate_format
 * @var $autoTime
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 */

$prefix = 'tyTicketHost-';
$uid    = wp_unique_id( $prefix );
if ( 'custom' === $webinar_data->ty_ticket_host_option ) {
		webinarignition_display( $webinar_data->ty_webinar_option_custom_host, __( 'Your Name Here', 'webinar-ignition' ) );
} else {
	webinarignition_display( $webinar_data->webinar_host, __( 'Host name', 'webinar-ignition' ) );
}
