<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wiFormGroup wiFormGroup-lg">
	<input
		type="text"
		class="wi_phone_number radius fieldRadius wiRegForm wiFormControl"
		id="optPhone"
		value="<?php echo ! empty( $lead->phone ) ? esc_html( $lead->phone ) : ''; ?>"
		placeholder="<?php webinarignition_display( $webinar_data->txt_placeholder, __( 'Enter your phone number', 'webinar-ignition' ) ); ?>"
	/>
</div>
<a href="#" id="storePhone" class="button addedArrow large wiButton wiButton-primary wiButton-block wiButton-lg">
	<?php webinarignition_display( $webinar_data->txt_btn, __( 'Get Text Message Reminder', 'webinar-ignition' ) ); ?>
</a>
<input type="hidden" value="<?php echo ! empty( $leadID ) ? esc_html( $leadID ) : ''; ?>" id="leadID">