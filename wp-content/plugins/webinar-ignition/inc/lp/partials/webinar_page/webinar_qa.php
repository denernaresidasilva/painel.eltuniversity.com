<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!-- QA AREA -->
<div class="webinarExtraBlock">

	<div id="askQArea">
		<?php
		webinarignition_display(
			$webinar_data->webinar_qa_title,
			'<h4>' . __( 'Got A Question?', 'webinar-ignition' ) . '</h4>
					 <h5 class="subheader">' . __( 'Submit your question, and we can answer it live on air...', 'webinar-ignition' ) . '</h5>'
		);
		?>

		<?php if ( 'custom' === trim($webinar_data->webinar_qa) ) { ?>
			<?php webinarignition_display( $webinar_data->webinar_qa_custom, __( 'CUSTOM Q/A SYSTEM WILL DISPLAY HERE... NO CODE ENTERED...', 'webinar-ignition' ) ); ?>
		<?php } else { ?>

			<div class="form-group">
				<input type="text" id="optName" class="optNamer2 wi_optin_name_c"
						placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_name_placeholder, __( 'Enter your full name', 'webinar-ignition' ) ); ?>">
			</div>

			<div class="form-group">
				<input type="text" id="optEmail" class="optEmailr2"
						placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_email_placeholder, __( 'Enter your email', 'webinar-ignition' ) ); ?>">
			</div>

			<input type="hidden" id="leadID">

			<div class="form-group">
				<textarea class="form-control" id="question"
							placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_desc_placeholder, __( 'Ask your question here', 'webinar-ignition' ) ); ?>"
							style="height: 80px;"></textarea>
			</div>
			<a href="#" id="askQuestion" class="button"
				style="border: 1px solid rgba(0,0,0,0.10); background-color: <?php webinarignition_display( $webinar_data->webinar_qa_button_color, '#3E8FC7' ); ?>;"><?php webinarignition_display( $webinar_data->webinar_qa_button, __( 'Submit Your Question', 'webinar-ignition' ) ); ?></a>
			<?php
		}//end if
		?>
	</div>
	<div id="askQThankyou" style="display:none;">
		<?php webinarignition_display( $webinar_data->webinar_qa_thankyou, '<h4>' . __( 'Thank You For Your Question!', 'webinar-ignition' ) . "</h4><h5 class='subheader' style='margin-top: -15px;'>" . __( 'The question block will refresh in 15 seconds...', 'webinar-ignition' ) . '</h5>' ); ?>
	</div>
</div>
<!--/.webinarExtraBlock-->