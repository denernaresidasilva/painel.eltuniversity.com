<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinarId
 * @var $webinar_data
 */
$prefix = 'webinarInfo-';
$uid = wp_unique_id( $prefix );

if ( 'hide' !== trim($webinar_data->webinar_giveaway_toggle) ) {
	?>
	<div id="<?php echo esc_attr( $uid ); ?>" class="webinarQA webinarGivaway webinarGivaway-<?php echo esc_attr( $webinarId ); ?>">
		<div class="webinarTopBar">
			<i class="icon-question-sign"></i> <?php webinarignition_display( $webinar_data->webinar_giveaway_title, __( 'Your Special Gift:', 'webinar-ignition' ) ); ?>
		</div>
		<div class="webinarInner">
			<?php webinarignition_get_webinar_giveaway_compact( $webinar_data, true ); ?>
		</div>
	</div>
	<?php
}
?>
