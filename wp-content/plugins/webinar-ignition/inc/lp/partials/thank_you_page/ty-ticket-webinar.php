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

$prefix = 'tyTicketWebinar-';
$uid = wp_unique_id( $prefix );
?>

<div id="<?php echo esc_attr( $uid ); ?>" class="tyTicketWebinar tyTicketWebinar-<?php echo esc_attr( $webinarId ); ?> ticketSection ticketSectionNew ts newticketTest">
	<?php
	if ( 'custom' === $webinar_data->ty_ticket_webinar_option ) {
		?>
		<div class="ticketInfoIcon">
				<img src="<?php echo esc_url($assets . 'images/webinar-icon.png'); ?>" />
		</div>
			<div class="tyTicketInfoCopy">
				<b><?php webinarignition_display( $webinar_data->ty_ticket_webinar, __( 'Webinar', 'webinar-ignition' ) ); ?></b>
				<div class="tyTicketInfoNewHeadline">
					<?php webinarignition_display( $webinar_data->ty_webinar_option_custom_title, __( 'Webinar Event Title', 'webinar-ignition' ) ); ?>
				</div>
			</div>
		<?php
	} else {
		?>
		<div class="ticketInfoIcon">
			<img src="<?php echo esc_url($assets . 'images/webinar-icon.png'); ?>" />
		</div>
		<div class="tyTicketInfoCopy">
			<b><?php esc_html_e( 'Webinar:', 'webinar-ignition' ); ?></b>
			<div class="tyTicketInfoNewHeadline">
				<?php webinarignition_display( $webinar_data->webinar_desc, __( 'Webinar Event Title', 'webinar-ignition' ) ); ?>
			</div>
		</div>

		<?php
	}//end if
	?>
</div>
