<?php
/**
 * @var $webinar_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cta_position_default = 'outer';
$cta_position_allowed = 'outer';

if ( ! empty( $webinar_data->cta_position ) ) {
	$cta_position_default = $webinar_data->cta_position;
}

$cta_border_desktop = '';
$cta_border_mobile  = '';

$show_main_cta = false;

if ( 'time' !== $webinar_data->auto_action && $cta_position_default === $cta_position_allowed ) {
	$show_main_cta = true;
}

if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
	if ( isset( $webinar_data->cta_border_desktop ) && 'no' === $webinar_data->cta_border_desktop ) {
		$cta_border_desktop = ' cta_border_hide_desktop';
	}

	if ( isset( $webinar_data->cta_border_mobile ) && 'no' === $webinar_data->cta_border_mobile ) {
		$cta_border_mobile = ' cta_border_hide_mobile';
	}
}

if ( 'time' !== $webinar_data->auto_action && ! empty( $webinar_data->auto_action_max_width ) ) {
	$auto_action_max_width = $webinar_data->auto_action_max_width;
	?>
	<style>
		#orderBTN {
			max-width: <?php echo absint( $auto_action_max_width ); ?>px !important;
			margin: auto;
		}
	</style>
	<?php
}

require WEBINARIGNITION_PATH . 'inc/lp/partials/additional-cta.php';
?>