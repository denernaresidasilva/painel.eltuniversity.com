<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
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
?>

<div id="<?php echo esc_attr( $uid ); ?>" class="tyTicketHost tyTicketHost-<?php echo esc_attr( $webinarId ); ?> ticketSection ticketSectionNew ts newticketTest">
	<?php
	if ( 'custom' === $webinar_data->ty_ticket_host_option ) {
		?>
		<div class="ticketInfoIcon2">
			<img src="<?php echo esc_url($assets . 'images/host-mic.png'); ?>" />
		</div>
		<div class="tyTicketInfoCopy">
			<b><?php webinarignition_display( $webinar_data->ty_ticket_host, __( 'Host', 'webinar-ignition' ) ); ?></b>
			<div class="tyTicketInfoNewHeadline">
				<?php webinarignition_display( $webinar_data->ty_webinar_option_custom_host, __( 'Your Name Here', 'webinar-ignition' ) ); ?>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="ticketInfoIcon2">
			<img src="<?php echo esc_url($assets . 'images/host-mic.png'); ?>" />
		</div>
		<div class="tyTicketInfoCopy">
			<b><?php esc_html_e( 'Host', 'webinar-ignition' ); ?>:</b>
			<div class="tyTicketInfoNewHeadline">
				<?php webinarignition_display( $webinar_data->webinar_host, __( 'Host name', 'webinar-ignition' ) ); ?>
			</div>
		</div>
		<?php
	}//end if
	?>
</div>
