<?php
/**
 * @var $webinar_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
switch_to_locale( $webinar_data->webinar_lang );
 unload_textdomain( 'webinar-ignition' );
 load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
$default_footerArea_content = '<p>' . __( 'Live Console For WebinarIgnition - All Rights Reserved', 'webinar-ignition' ) . ' @ ' . gmdate( 'Y' ) . '</p>';
$footerArea_content         = isset( $webinar_data->live_console_footer_area_content ) ? $webinar_data->live_console_footer_area_content : $default_footerArea_content;

if ( empty( $footerArea_content ) ) {
	return '';
}

if ( false !== strpos( $footerArea_content, '{{currentYear}}' ) ) {
	$footerArea_content = str_replace( '{{currentYear}}', gmdate( 'Y' ), $footerArea_content );
}
?>

<div class="footerArea">
	<?php echo wp_kses_post( $footerArea_content );
		restore_previous_locale();

	?>
</div>
