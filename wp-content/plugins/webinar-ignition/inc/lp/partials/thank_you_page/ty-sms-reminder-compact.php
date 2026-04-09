<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data;
 * @var $leadID;
 * @var $lead;
 */
?>

<?php
if ( ! empty( $webinar_data->txt_area ) && 'on' === $webinar_data->txt_area ) {
	?>
	<div class="phoneReminder ticketSection wiTicketSection">
		<div id="phonePre">
			<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/thank_you_page/partials/sms-form.php'; ?>
		</div>

		<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/thank_you_page/partials/sms-reveal.php'; ?>
	</div>
	<?php
}
?>
