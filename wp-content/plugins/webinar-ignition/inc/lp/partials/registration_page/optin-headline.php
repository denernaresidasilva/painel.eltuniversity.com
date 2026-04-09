<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
$prefix = 'optinHeadline-';
$uid = wp_unique_id( $prefix );

// Only get the required input values
$input_get = [
    'payment' => isset( $_GET['payment'] ) ? sanitize_text_field( wp_unslash( $_GET['payment'] ) ) : null,
];
?>
<div class="optinHeadline wiOptinHeadline <?php echo esc_attr( $uid ); ?>">
		<?php
		switch_to_locale( $webinar_data->webinar_lang );
		unload_textdomain( 'webinar-ignition' );
		load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
		ob_start();

		if ( ( ! empty( $webinar_data->latecomer ) ) && ( ! empty( $webinar_data->latecomer_registration_copy ) ) ) { ?>
		<div id="latecomer_copy"><?php echo wp_kses_post( $webinar_data->latecomer_registration_copy ); ?></div>
		<?php } else { 
			
			?>
		<div class="optinHeadline1 wiOptinHeadline1"><?php echo esc_html__( 'RESERVE YOUR SPOT!', 'webinar-ignition' ); ?></div>
		<div class="optinHeadline2 wiOptinHeadline2"><?php echo esc_html__( 'WEBINAR REGISTRATION', 'webinar-ignition' ); ?></div>
		<?php }

		if ( isset( $input_get['payment'] ) && 'success' === $input_get['payment'] ) {
			?>
			<div class="optinHeadline2 wiOptinHeadline2"><?php echo esc_html__( 'Payment Success!', 'webinar-ignition' ); ?></div>
			<p>
				<?php echo esc_html__( 'Please finalize your registration by filling out the form below:', 'webinar-ignition' ); ?>
			</p>
			<?php
		}

		$displayReserveSpot = ob_get_clean();
		restore_previous_locale();

		webinarignition_display(
			isset($webinar_data->lp_optin_headline) ? $webinar_data->lp_optin_headline : '',
			$displayReserveSpot
		);
		?>
	</div>