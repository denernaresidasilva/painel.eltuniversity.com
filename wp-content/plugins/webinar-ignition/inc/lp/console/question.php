<!-- ON AIR AREA -->
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="questionTab" <?php echo ( ! $is_support ) ? 'style="display:none;"' : ''; ?> class="consoleTabs">
	<div class="statsDashbord">
		<div class="statsTitle">
			<div class="statsTitleIcon">
				<i class="icon-question-sign icon-2x"></i>
			</div>

			<div class="statsTitleCopy">
				<h2><?php esc_html_e( 'Manage Live Questions', 'webinar-ignition' ); ?></h2>
				<p><?php esc_html_e( 'All questions - answered & unanswered...', 'webinar-ignition' ); ?></p>
			</div>

			<br clear="all"/>
		</div>
	</div>

	<div class="innerOuterContainer">
		<div class="innerContainer">

			<div class="statsQuestionsTab" style="margin-top: -108px;">

				<div class="questionTabIt questionTabSelected" id="qa-active">
					<i class="icon-question"></i> <?php esc_html_e( 'Active Questions', 'webinar-ignition' ); ?> <span
							class="labelQA" id="totalQAActive"
							style='display: none;'><?php echo esc_html( $totalQuestionsActive ); ?></span>
				</div>

				<div class="questionTabIt" id="qa-done">
					<i class="icon-check-sign"></i> <?php esc_html_e( 'Answered Questions', 'webinar-ignition' ); ?> <span
							class="labelQA" id="totalQADone"
							style='display: none;'><?php echo esc_html( $totalQuestionsDone ); ?></span>
				</div>

				<br clear="left"/>

			</div>

			<br clear="all"/>

			<div class="questionMainTabe" id="QAActive">

				<div class="airSwitch" style="padding-top: 0px;">

					<?php if ( ( 'AUTO' !== $webinar_data->webinar_date ) && ( ! $is_support ) ) : ?>

						<div style="padding-bottom:45px;">

							<div class="airSwitchLeft">
								<span class="airSwitchTitle"><?php esc_html_e( 'Enable/Disable Live Questions', 'webinar-ignition' ); ?></span>
								<span class="airSwitchInfo"><?php esc_html_e( 'If set to ON, attendees will be able to send questions.', 'webinar-ignition' ); ?></span>
							</div>

							<div class="airSwitchRight">
								<p class="field switch">
									<input type="hidden" id="QAToggle"
											value="
											<?php
											if ( 'hide' === trim($webinar_data->webinar_qa) ) {
												echo 'hide';
											} else {
												echo 'show';
											}
											?>
													">
									<label for="radio1"
											class="qa-enable 
											<?php
											if ( 'hide' !== trim($webinar_data->webinar_qa) ) {
												echo 'selected';
											}
											?>
											"><span><?php esc_html_e( 'ON', 'webinar-ignition' ); ?></span></label>
									<label for="radio2"
											class="qa-disable 
											<?php
											if ( 'hide' === trim($webinar_data->webinar_qa) ) {
												echo 'selected';
											}
											?>
									"><span><?php esc_html_e( 'OFF', 'webinar-ignition' ); ?></span></label>
								</p>
							</div>

						</div>

						<br clear="all"/>

					<?php endif; ?>

					<div class="airSwitchLeft">
						<span class="airSwitchTitle"><?php esc_html_e( 'Active / Unanswered Questions', 'webinar-ignition' ); ?></span>
						<span class="airSwitchInfo"><?php esc_html_e( 'Below are the questions that have come in that are yet to be answered...', 'webinar-ignition' ); ?></span>
					</div>

					<div class="airSwitchRight">

						<a href="#" class="small disabled button secondary" style="margin-right: 0px;"><i
									class="icon-refresh"></i> <?php esc_html_e( 'Questions Will Auto-Update', 'webinar-ignition' ); ?>
						</a>
						<?php if ( ( ! $is_support ) ) : ?>
							<a target="_blank"
								href="<?php echo esc_url( $webinar_data->webinar_permalink ) . '?csv_key=' . esc_html( $webinar_data->csv_key ); ?>"
								class="small button secondary" style="margin-right: 0px;"><i class="icon-file-text"></i>
								CSV</a>
						<?php endif; ?>

					</div>

					<br clear="all"/>

				</div>

				<?php require 'partials/answerQuestionFormInitial.php'; ?>

				<div id="active_questions" class="questionsBlock">

					<?php foreach ( $questionsActive as $questionsActive ) { ?>

						<!-- QUESTION BLOCK -->
						<div class="questionBlockWrapper questionBlockWrapperActive"
							qa_lead="<?php echo esc_attr( $questionsActive->ID ); ?>"
							id="QA-BLOCK-<?php echo esc_attr( $questionsActive->ID ); ?>">

							<div class="questionBlockQuestion">
							<span class="questionTimestamp"> <?php echo esc_html( $questionsActive->created ); ?> <?php if ( ! empty( $questionsActive->webinarTime ) ) { echo esc_html( '(' . $questionsActive->webinarTime . ' ' . esc_html__( 'minutes into the webinar)', 'webinar-ignition' ) ); } ?> </span>
								<p style='padding: 10px; background-color: #eee; width: 100%;border-radius: 7px;'>
									<span class="questionBlockText"><?php echo esc_html( $questionsActive->question ); ?></span>
									<br>
									<span class='questionBlockAuthor' >
										<?php echo esc_html( $questionsActive->name ); ?> -
										<span
												data-toggle='tooltip'
												data-placement='top'
												title='Search leads table'
												class='radius secondary label qa-lead-search'
										>
											<?php echo esc_html( $questionsActive->email ); ?>
										</span>
									</span>
								</p>

								<?php if ( ( 'hold' === $questionsActive->attr4 && $questionsActive->attr2 === $current_user->ID ) ) : ?>
									<span class="questionOnHold green bold"> <?php esc_html_e( "You're answering this question...", 'webinar-ignition' ); ?></span>
								<?php endif; ?>

								<?php if ( ( 'hold' === $questionsActive->attr4 && $questionsActive->attr2 !== $current_user->ID && ! empty( $questionsActive->attr5 ) ) ) : ?>
									<span class="questionOnHold green bold"> <?php echo esc_html( $questionsActive->attr5 ); ?><?php esc_html_e( 'is answering this question...', 'webinar-ignition' ); ?></span>
								<?php endif; ?>
							</div>

							<div class="questionActions">

								<?php if ( ! $is_support ) : ?>

									<div class="questionBlockIcons qbi-remove"
										qaID="<?php echo esc_attr( $questionsActive->ID ); ?>">
										<i data-toggle="tooltip" data-placement="top" title="<?php esc_html_e( 'Delete question', 'webinar-ignition' ); ?>"
											class="icon-remove icon-large"></i>
									</div>

								<?php endif; ?>

								<?php if ( ( 'hold' !== $questionsActive->attr4 ) || ( 'hold' === $questionsActive->attr4 && $questionsActive->attr2 === $current_user->ID ) ) : ?>

									<div class="questionBlockIcons qbi-reply">
										<a class="answerAttendee" data-toggle="tooltip" data-placement="top"
											title="<?php esc_html_e( 'Respond to attendee question', 'webinar-ignition' ); ?>"
											data-questionid="<?php echo esc_attr( $questionsActive->ID ); ?>"
											data-attendee-name="<?php echo esc_attr( $questionsActive->name ); ?>"
											data-attendee-email="<?php echo esc_attr( $questionsActive->email ); ?>"><i
													class="icon-comments icon-large"></i></a>
									</div>

								<?php endif; ?>

								<br clear="left"/>

							</div>

							<br clear="all"/>

						</div>
						<!-- END OF QUESTION BLOCK -->

						<?php
					}//end foreach
					?>

				</div>

			</div>

			<div class="questionMainTabe" id="QADone" style="display:none;">
				<div class="airSwitch" style="padding-top: 0px;">
					<div class="airSwitchLeft">
						<span class="airSwitchTitle"><?php esc_html_e( 'Answered Questions', 'webinar-ignition' ); ?></span>
						<span class="airSwitchInfo"><?php esc_html_e( 'Below are all the answered questions...', 'webinar-ignition' ); ?></span>
					</div>

					<br clear="all"/>
				</div>

				<?php require 'partials/answerQuestionFormMore.php'; ?>

				<div id="answered_questions" class="questionsBlock">

					<?php
					foreach ( $questionsDone as $questionDone ) {
						?>

						<!-- QUESTION BLOCK -->
						<?php include 'partials/answeredQuestion.php'; ?>
						<!-- END OF QUESTION BLOCK -->

					<?php } ?>

				</div>

			</div>

		</div>
	</div>
</div>

<script type="text/html" id="qstn_answer_email_body">
	<?php echo wp_kses_post( $webinar_data->qstn_answer_email_body ); ?>
</script>
