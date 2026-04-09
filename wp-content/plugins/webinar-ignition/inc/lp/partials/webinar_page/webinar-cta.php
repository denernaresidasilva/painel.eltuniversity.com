<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 */

if ( 'AUTO' === $webinar_data->webinar_date ) {
	?>
	<div class="webinarVideoCTA<?php echo 'time' === $webinar_data->auto_action ? '' : ' webinarVideoCTAActive'; ?>">
		<div class="ctaArea">
			<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-cta-area.php'; ?>
		</div>
	</div>
	<?php
} else {
	// Get CTA Width
	$styles = '';
	if ( isset( $webinar_data->live_webinar_ctas_alignment_radios ) && trim( $webinar_data->live_webinar_ctas_alignment_radios ) === 'flex-end' ) {
		$styles = 'right:10px;left:auto';
	}
	if ( isset( $webinar_data->live_webinar_ctas_alignment_radios ) && trim( $webinar_data->live_webinar_ctas_alignment_radios ) === 'flex-start' ) {
		$styles = 'left:10px;margin:0';
	}

	if ( isset( $webinar_data->live_webinar_ctas_alignment_radios ) && trim( $webinar_data->live_webinar_ctas_alignment_radios ) === 'center' ) {
		$styles = 'left: 0; right: 0;';
	}
	
	?>
	<div class="webinarVideoCTA">
		<div class="ctaArea">
			
		</div>
	</div>
	<?php
}//end if
?>
