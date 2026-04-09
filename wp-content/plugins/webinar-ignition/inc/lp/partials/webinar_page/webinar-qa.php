<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $leadinfo
 * @var $webinar_data
 * @var $webinar_id
 */

$is_compact = ! empty( $is_compact );

ob_start();
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
?>
<h4><?php echo esc_html__( 'Got A Question?', 'webinar-ignition' ); ?></h4>
<h5 class="subheader"><?php echo esc_html__( 'Submit your question, and we can answer it live on air...', 'webinar-ignition' ); ?></h5>
<?php
$default_webinar_qa_title = ob_get_clean();


ob_start();
?>
<h4><?php echo esc_html__( 'Thank You For Your Question!', 'webinar-ignition' ); ?></h4>
<h5 class="subheader"><?php echo esc_html__( 'The question block will refresh in 15 seconds...', 'webinar-ignition' ); ?></h5>
<?php
$default_webinar_qa_thankyou = ob_get_clean();
restore_previous_locale();

$name                     = ! empty( $leadinfo->name ) ? $leadinfo->name : '';
$email                    = ! empty( $leadinfo->email ) ? $leadinfo->email : '';
$allow_qa_edit_name_email = ! empty( $webinar_data->webinar_qa_edit_name_email ) && 'allow' === $webinar_data->webinar_qa_edit_name_email;
$has_qa_tab = false;

if ( ! empty( $webinar_data->webinar_tabs ) ) {
	foreach ( $webinar_data->webinar_tabs as $tab ) {
		if ( isset( $tab['type'] ) && $tab['type'] === 'qa_tab' ) {
			$has_qa_tab = true;
			break;
		}
	}
}

if ( 'hide' !== trim($webinar_data->webinar_qa) || $has_qa_tab ) {
	?>
	<?php
	if ( ! $is_compact ) {
		?>
		<div class="webinarExtraBlock">
		<?php
	}
	?>
	<?php
	if ( 'chat' === trim($webinar_data->webinar_qa) && ! WebinarignitionPowerups::webinarignition_is_two_way_qa_enabled( $webinar_data ) ) {
		$webinar_data->webinar_qa = 'we';
	}


	if ( 'chat' === trim($webinar_data->webinar_qa) ) {
		$webinar_modern_background_color = ! empty( $webinar_data->webinar_modern_background_color ) ? $webinar_data->webinar_modern_background_color : '#ced4da';

		$webinar_qa_chat_question_color      = ! empty( $webinar_data->webinar_qa_chat_question_color ) ? $webinar_data->webinar_qa_chat_question_color : $webinar_modern_background_color;
		$webinar_qa_chat_question_text_color = webinarignition_get_text_color_from_bg_color( $webinar_qa_chat_question_color );

		$webinar_qa_chat_answer_color      = ! empty( $webinar_data->webinar_qa_chat_answer_color ) ? $webinar_data->webinar_qa_chat_answer_color : '#eee';
		$webinar_qa_chat_answer_text_color = webinarignition_get_text_color_from_bg_color( $webinar_qa_chat_answer_color );

		$chat_type    = 'private';
		$chat_refresh = 2;

		if ( ! empty( (int) $webinar_data->webinar_qa_chat_refresh ) ) {
			$chat_refresh = (int) $webinar_data->webinar_qa_chat_refresh;
		}
		?>
		<div id="chatQArea" data-app_id="<?php echo absint( $webinar_id ); ?>" data-email="<?php echo esc_attr( $email ); ?>" data-refresh="<?php echo esc_attr( $chat_refresh ); ?>">
			<div id="chatQA">
				<div id="chatQAMessages" data-wimsg-bg="<?php echo esc_attr( $webinar_qa_chat_answer_color ); ?>" data-wimsg-color="<?php echo esc_attr( $webinar_qa_chat_answer_text_color ); ?>" >

				</div>
			</div>
			<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/partials/qa-form.php'; ?>
		</div>
		<?php
	} elseif ( 'we' === $webinar_data->webinar_qa || $has_qa_tab ) {
		?>
		<div id="askQArea">
			<?php
			if ( ! $is_compact ) {
				webinarignition_display( $webinar_data->webinar_qa_title, $default_webinar_qa_title );}
			?>
			<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/partials/qa-form.php'; ?>
		</div>
		<div id="askQThankyou" style="display:none;">
			<?php webinarignition_display( $webinar_data->webinar_qa_thankyou, $default_webinar_qa_thankyou ); ?>
		</div>
		<?php
	}//end if
	?>
	<?php
	if ( ! $is_compact ) {
	?>
		</div>
	<?php
	}
	?>
	<?php
}//end if
?>
