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
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
$prefix = 'tyHeadlineContainer-';
$uid = wp_unique_id( $prefix );
?>
<div id="<?php echo esc_attr( $uid ); ?>" class="tyHeadlineContainer tyHeadlineContainer-<?php echo esc_attr( $webinarId ); ?>">
	<div class="tyHeadlineCopy">
		<div class="optinHeadline1 wiOptinHeadline1">
			<?php webinarignition_display( $webinar_data->ty_ticket_headline, __( 'Congratulations! You are signed up!', 'webinar-ignition' ) ); ?>
		</div>

		<div class="optinHeadline2 wiOptinHeadline2">
			<?php webinarignition_display( $webinar_data->ty_ticket_subheadline, __( 'Below is all the information you need for the webinar...', 'webinar-ignition' ) ); ?>
		</div>
	</div>
</div>
<?php restore_previous_locale(); ?>
