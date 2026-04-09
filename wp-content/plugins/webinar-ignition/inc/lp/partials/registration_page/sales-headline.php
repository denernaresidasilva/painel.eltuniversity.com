<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
?>

<div class="innerHeadline addedArrow" style="background-color: <?php echo esc_attr(isset($webinar_data->lp_sales_headline_color) ? $webinar_data->lp_sales_headline_color : '#0496AC'); ?>;">
	<span>
		<?php webinarignition_display( isset($webinar_data->lp_sales_headline) ? $webinar_data->lp_sales_headline : '', __( 'What You Will Learn On The Webinar...', 'webinar-ignition' ) ); ?>
	</span>
</div>
<?php
restore_previous_locale();
