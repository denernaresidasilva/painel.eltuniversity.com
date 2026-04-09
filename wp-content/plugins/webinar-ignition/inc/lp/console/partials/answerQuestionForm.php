<?php
/**
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$form_id = '';

if ( empty( $answer_initial ) ) {
	$form_id = 'More';
}
?>

<div id="answer<?php echo esc_attr( $form_id ); ?>FormContainer" style="display:none;">
	<div class="row">
		<div class="col-md-12">
			<form id="answer<?php echo esc_attr( $form_id ); ?>Form">
				<div class="form-group">
					<label for="attendeeName"><h4><?php esc_html_e( 'Your Reply To', 'webinar-ignition' ); ?> <span
								class="attendeeName"></span></h4></label>
					<img data-toggle='tooltip' data-placement='top' title='<?php esc_html_e( 'Close answer form', 'webinar-ignition' ); ?>'
						id="closeAnswer<?php echo esc_attr( $form_id ); ?>Form" style="float:right; cursor:pointer;"
						src="<?php echo esc_url( $assets . 'images/close.png' ); ?>" width="20" height="20">
				</div>

				<div class="form-group">
					<label for="attendee<?php echo esc_attr( $form_id ); ?>Question"><span
							class="attendeeName"></span><?php esc_html_e( "'s Question", 'webinar-ignition' ); ?>
					</label>
					<textarea name="attendeeQuestion" class="form-control" id="attendee<?php echo esc_attr( $form_id ); ?>Question" rows="3" disabled></textarea>
				</div>

				<div class="form-group">
					<label for="answer<?php echo esc_attr( $form_id ); ?>Text"><?php esc_html_e( 'Your Answer', 'webinar-ignition' ); ?></label>
					<!-- <textarea name="answerText" class="form-control" id="answer<?php 
					// echo esc_attr( $form_id ); ?>Text" rows="3"></textarea> -->
					<?php 
						$settings = array(
							'textarea_name' => 'answerText',
							'textarea_rows' => 10,
							'media_buttons' => true,
							'teeny'        => false,
							'quicktags'    => true,
						);
						wp_editor( '', 'answer' . esc_attr( $form_id ) . 'Text', $settings );
					?>
				</div>

				<button type="submit" class="btn btn-primary">
					<?php esc_html_e( 'Submit Answer', 'webinar-ignition' ); ?>
				</button>

				<hr>

				<?php
				$send_answer_via_email = 'off';
				if ( 'chat' === trim($webinar_data->webinar_qa) && ! WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
					$webinar_data->webinar_qa = 'we';
				}

				if ( 'AUTO' === $webinar_data->webinar_date || ! WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
					$send_answer_via_email = 'on';
					$always_email          = true;
				}

				if ( ! empty( $always_email ) || 'chat' !== trim($webinar_data->webinar_qa) ) {
					$send_answer_via_email = 'on';
				}
				?>

				<div<?php echo ! empty( $always_email ) ? ' style="display:none;"' : ''; ?>>
					<div class="airSwitchLeft">
						<span class="airSwitchTitle"><?php esc_html_e( 'Send answer also via email', 'webinar-ignition' ); ?></span>
						<span class="airSwitchInfo"><?php esc_html_e( 'If set to ON, attendees will get your answers vie email.', 'webinar-ignition' ); ?></span>
					</div>

					<div class="airSwitchRight">
						<p class="field switch">
							<input type="hidden" id="EmailQA<?php echo esc_attr( $form_id ); ?>Toggle" name="emailQAEnabled" value="<?php echo esc_html( $send_answer_via_email ); ?>">
							<label for="email-qa-enable" class="email-qa<?php echo esc_attr( $form_id ); ?>-enable<?php echo 'on' === $send_answer_via_email ? ' selected' : ''; ?>">
								<span>On</span>
							</label>
							<label for="email-qa-disable" class="email-qa<?php echo esc_attr( $form_id ); ?>-disable<?php echo 'off' === $send_answer_via_email ? ' selected' : ''; ?>">
								<span>Off</span>
							</label>
						<div style="display: table;content: ' ';clear: both;"></div>
						</p>
					</div>

					<div style="display: table;content: ' ';clear: both;"></div>
				</div>

				<div id="EmailQA<?php echo esc_attr( $form_id ); ?>Container"<?php echo 'off' === $send_answer_via_email ? ' style="display:none;"' : ''; ?>>
					<h3><?php esc_html_e( 'Email Answer', 'webinar-ignition' ); ?></h3>

					<div class="form-group">
						<label for="answer<?php echo esc_attr( $form_id ); ?>Subject"><?php esc_html_e( 'Subject', 'webinar-ignition' ); ?></label>
						<input
							type="text"
							class="form-control"
							name="subject"
							id="answer<?php echo esc_attr( $form_id ); ?>Subject"
							value="<?php echo esc_html( sprintf( __( 'Your questions to the webinar %s', 'webinar-ignition' ), $webinar_data->webinar_desc ) ); //phpcs:ignore ?>"
						>
					</div>

					<div class="form-group">
						<label for="answer<?php echo esc_attr( $form_id ); ?>Content"><?php esc_html_e( 'Answer Body', 'webinar-ignition' ); ?></label>
						<p>
							<small>
								<?php esc_attr_e( 'Do not remove <strong>{ANSWER}</strong> placeholder from template, it will be replaced with <strong>"Your Answer"</strong> field content.', 'webinar-ignition' ); ?>
							</small>

						</p>
						<textarea name="answer" class="form-control" id="answer<?php echo esc_attr( $form_id ); ?>Content" rows="3"></textarea>

					</div>
				</div>

				<input id="attendee<?php echo esc_attr( $form_id ); ?>Email" type="hidden" name="attendeeEmail">
				<input id="question<?php echo esc_attr( $form_id ); ?>Id" type="hidden" name="questionId">
				<input type="hidden" name="supportName" value="<?php echo esc_html( $current_user->display_name ); ?>">
				<input type="hidden" name="supportId" value="<?php echo esc_html( $current_user->ID ); ?>">
				<?php
				if ( empty( $answer_initial ) ) {
					?>
					<input type="hidden" name="isAnswerMore" value="on">
					<?php
				}
				?>
			</form>
		</div>
	</div>
</div>