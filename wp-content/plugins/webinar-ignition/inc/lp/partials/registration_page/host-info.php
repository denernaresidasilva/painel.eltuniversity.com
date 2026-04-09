<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );

	$lp_webinar_host_block = isset($webinar_data->lp_webinar_host_block) ? $webinar_data->lp_webinar_host_block : '';
	$prefix                = 'hostInfoBlock-';
	$uid                   = wp_unique_id( $prefix );

if ( 'hide' !== $lp_webinar_host_block ) {
	?>
	<div class="hostInfoBlock <?php echo esc_attr( $uid ); ?>">
		<div class="hostInfoPhoto">
			<img src="
			<?php
			webinarignition_display(
				isset($webinar_data->lp_host_image) ? $webinar_data->lp_host_image : '',
				WEBINARIGNITION_URL . 'images/generic-headshot-male.jpg'
			);
			?>
			"/>
		</div>

		<div class="hostInfoCopy">
			<?php
			webinarignition_display(
				isset($webinar_data->lp_host_info) ? $webinar_data->lp_host_info : '',
				__( 'It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software...', 'webinar-ignition' ) . '<br><b>' . __( 'Your Name Here', 'webinar-ignition' ) . '</b>'
			);
			?>
		</div>
	</div>
	<?php
	restore_previous_locale();

}//end if