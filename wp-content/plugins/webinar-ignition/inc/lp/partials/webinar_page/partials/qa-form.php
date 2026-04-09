<?php
/**
 * @var $webinar_data
 * @var $name
 * @var $email
 * @var $allow_qa_edit_name_email
 * @var $webinar_id
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );

if ( 'custom' === trim($webinar_data->webinar_qa) ) {
	webinarignition_display( $webinar_data->webinar_qa_custom, __( 'CUSTOM Q/A SYSTEM WILL DISPLAY HERE... NO CODE ENTERED...', 'webinar-ignition' ) );
} else {
	if ( 'chat' === trim($webinar_data->webinar_qa) && ! WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
		$webinar_data->webinar_qa = 'we';
	}

	if ( 'chat' === trim($webinar_data->webinar_qa) ) {
		?>
		<input type="hidden" id="optName" class="wi_optin_name_d" value="<?php echo esc_html( $name ); ?>">
		<input type="hidden" id="optEmail" value="<?php echo esc_html( $email ); ?>">
		<?php
	} else {
		?>
		<div class="form-group wiFormGroup wiFormGroup-lg">
			<input
				value="<?php echo esc_html( $name ); ?>"
				type="<?php echo $allow_qa_edit_name_email ? 'text' : 'hidden'; ?>"
				id="optName"
				class="optNamer2 wiRegForm wiFormControl wi_optin_name_e"
				placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_name_placeholder, __( 'Enter your full name', 'webinar-ignition' ) ); ?>"
			>
		</div>

		<div class="form-group wiFormGroup wiFormGroup-lg">
			<input
				value="<?php echo esc_html( $email ); ?>"
				type="<?php echo $allow_qa_edit_name_email ? 'text' : 'hidden'; ?>"
				id="optEmail"
				class="optEmailr2 wiRegForm wiFormControl"
				placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_email_placeholder, __( 'Enter your email', 'webinar-ignition' ) ); ?>"
			>
		</div>
		<?php
	}//end if
	?>

	<input type="hidden" id="leadID" value="<?php echo !empty($_GET['lid']) ? esc_attr(sanitize_text_field($_GET['lid'])) : ''; ?>">

	<?php
	if ( 'chat' === trim($webinar_data->webinar_qa) ) {
		?>
		<div id="chatQASubmit">
			<div class="form-group wiFormGroup wiFormGroup-lg">
				<textarea id="question"
					class="form-control wiRegForm wiFormControl"
					placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_desc_placeholder, __( 'Ask Your Question Here...', 'webinar-ignition' ) ); ?>"
					style="height: 80px;"
				></textarea>
			</div>

			<button id="chatQuestion"
				class="button wiButton wiButton-lg wiButton-block addedArrow"
				style="color:#fff;border:1px solid rgba(0,0,0,0.10);background-color:<?php webinarignition_display( $webinar_data->webinar_qa_button_color, '#3E8FC7' ); ?>;"
				data-app_id="<?php echo esc_html( $webinar_id ); ?>"
				data-video_live_time="<?php echo ( 'AUTO' === $webinar_data->webinar_date ) ? esc_attr( $lead->date_picked_and_live ) : esc_attr( $webinar_data->webinar_date ) . ' ' . esc_attr( $webinar_data->webinar_start_time ); ?>"
			>
				<?php webinarignition_display( $webinar_data->webinar_qa_button, __( 'Submit Your Question', 'webinar-ignition' ) ); ?>
			</button>
		</div>
		<?php
	} else {
		?>
		<div class="form-group wiFormGroup wiFormGroup-lg">
			<textarea class="form-control wiRegForm wiFormControl" id="question"
						placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_desc_placeholder, __( 'Ask Your Question Here...', 'webinar-ignition' ) ); ?>"
						style="height: 80px;"
			></textarea>
		</div>
		<a href="#"
			id="askQuestion"
			class="button wiButton wiButton-lg wiButton-block addedArrow"
			style="color:#fff;border:1px solid rgba(0,0,0,0.10);background-color:<?php webinarignition_display( $webinar_data->webinar_qa_button_color, '#3E8FC7' ); ?>;">
			<?php webinarignition_display( $webinar_data->webinar_qa_button, __( 'Submit Your Question', 'webinar-ignition' ) ); ?>
		</a>
		<?php
	}//end if
	?>

<?php }//end if
?>