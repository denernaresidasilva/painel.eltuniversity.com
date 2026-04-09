<?php
/**
 * @var $webinar_data
 * @var $questionDone
 * @var $is_support
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$answers = array();

if (!function_exists('webinarignition_extract_answer')) {
	function webinarignition_extract_answer($questionDone) {
		$answer = !empty($questionDone->answer) ? $questionDone->answer : '';
		if (empty($answer)) {
			$answer = !empty($questionDone->answer_text) ? $questionDone->answer_text : '';
		}

		$answer_author = !empty($questionDone->attr3) ? $questionDone->attr3 : '';

		return array(
			'answer' => $answer,
			'author' => $answer_author,
		);
	}
}

if ( ! WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
	$answers[] = webinarignition_extract_answer($questionDone);
} else {
	$q_answers = WebinarignitionQA::webinarignition_get_question_answers( $questionDone->ID );

	if ( empty( $q_answers ) ) {
		$answers[] = webinarignition_extract_answer($questionDone);
	} else {
		foreach ( $q_answers as $answer_array ) {
			$answers[] = webinarignition_extract_answer($answer_array);
		}
	}
}

?>

<div
	class="questionBlockWrapper questionBlockWrapperDone"
	qa_lead="<?php echo esc_attr( $questionDone->ID ); ?>"
	id="QA-BLOCK-<?php echo esc_attr( $questionDone->ID ); ?>"
>

	<div class="questionBlockContainer">
		<div class="questionBlockByTitle">
			<h3><?php esc_html_e( 'Question', 'webinar-ignition' ); ?></h3>

			<p>
				<?php esc_html_e( 'by', 'webinar-ignition' ); ?>
				<strong><?php echo esc_html( $questionDone->name ); ?></strong>
				<?php esc_html_e( 'at', 'webinar-ignition' ); ?>
				<strong><?php echo esc_html( $questionDone->created ); ?></strong> <br>

				<small>
					<strong><?php echo esc_html( $questionDone->email ); ?></strong>
					<?php echo ! empty( $questionDone->webinarTime ) && false ? ' <br>(' . esc_html( $questionDone->webinarTime ) . esc_html__( ' minutes into the webinar)', 'webinar-ignition' ) : ''; ?>
				</small>
			</p>
		</div>

		<div class="questionBlockQuestionText"><?php echo esc_html( $questionDone->question ); ?></div>


		<div class="questionBlockAnswers">
			<div class="questionBlockByTitle">
				<h3>
					<?php esc_html_e( 'Answers', 'webinar-ignition' ); ?>
				</h3>
			</div>
			<?php
			if ( ! empty( $answers ) ) {
				foreach ( $answers as $answer ) {
					?>
					<div class="questionBlockAnswer">
						<?php
						if ( ! empty( $answer['author'] ) ) {
							?>
							<div class="questionBlockByTitle">
								<p>
									<?php esc_html_e( 'by', 'webinar-ignition' ); ?>
									<strong><?php echo esc_html( $answer['author'] ); ?></strong>
								</p>
							</div>
							<?php
						}
						?>
						<div class="questionBlockQuestionText">
							<?php echo esc_html( $answer['answer'] ); ?>
						</div>
					</div>
					<?php
				}//end foreach
			}//end if
			?>
		</div>
	</div>

	<div class="questionActions">
		<?php if ( ! $is_support ) : ?>
			<div class="questionBlockIcons qbi-removeDone"
				qaID="<?php echo esc_attr( $questionDone->ID ); ?>">
				<i data-toggle="tooltip" data-placement="top" title="<?php esc_attr_e( 'Delete question', 'webinar-ignition' ); ?>"
					class="icon-remove icon-large"></i>
			</div>
		<?php endif; ?>

		<?php
		if ( 'AUTO' !== $webinar_data->webinar_date && 'chat' === trim($webinar_data->webinar_qa) && WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
			?>

			<div class="questionBlockIcons qbi-reply">
				<a class="answerMoreAttendee" data-toggle="tooltip" data-placement="top"
					title="<?php esc_html_e( 'Respond to attendee question', 'webinar-ignition' ); ?>"
					data-questionid="<?php echo esc_attr( $questionDone->ID ); ?>"
					data-attendee-name="<?php echo esc_attr( $questionDone->name ); ?>"
					data-attendee-email="<?php echo esc_attr( $questionDone->email ); ?>"><i
							class="icon-comments icon-large"></i></a>
			</div>
			<?php
		}
		?>
	</div>
</div>
<!-- END OF QUESTION BLOCK -->