<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registration template 03
 *
 * @var $template_number
 * @var $webinarId
 * @var $webinar_data
 * @var $is_webinar_available
 * @var $assets
 * @var $user_info
 */
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
	webinarignition_get_lp_header( $webinarId, $template_number, $webinar_data );
?>

<!-- TOP AREA -->
<?php webinarignition_get_lp_banner( $webinar_data, true ); ?>


<!-- Main Area -->
<div class="mainWrapper">
	<div class="wiContainer container wi-default-reg-page">
		<!-- HEADLINE AREA -->
		<div class="headlineArea" style="display: <?php echo empty( $webinar_data->lp_main_headline ) ? 'none' : 'block'; ?>;">
			<?php webinarignition_display( $webinar_data->lp_main_headline, '' ); ?>
		</div>

		<!-- Paid webinar Checker  -->
		<?php
		if ( 'paid' === $webinar_data->paid_status ) {
			$paid_check = 'no';
		} else {
			$paid_check = 'yes';
		}
		// check if campaign ID is in the URL, if so, its the thank you url...
		// echo "<pre>";
		// print_r($webinar_data);
		// print_r($input_get);
		// echo "</pre>";
		if ( isset($webinar_data->paid_code) ) {
			$paid_check = 'yes';
		}
		?>

		<div class="cpWrapper">
			<div class="row">
				<div class="col-md-5">
					<div class="cpLeftSide">
						<?php webinarignition_get_lp_optin_headline( $webinar_data, true ); ?>

						<?php webinarignition_get_lp_optin_section( $webinar_data, true ); ?>
					</div>
				</div>

				<div class="col-md-7">
					<div class="cpRightSide">
						<!-- VIDEO / CTA BLOCK AREA HERE -->
						<?php webinarignition_get_video_area( $webinar_data, true ); ?>
						<!-- VIDEO / CTA BLOCK AREA HERE - End -->

						<div class="innerHeadline addedArrow" style="background-color: <?php echo esc_attr( empty( $webinar_data->lp_sales_headline_color ) ? '#0496AC' : $webinar_data->lp_sales_headline_color ); ?>;">
						<span>
								<?php webinarignition_display( $webinar_data->lp_sales_headline, __( 'What You Will Learn On The Webinar...', 'webinar-ignition' ) ); ?>
						</span>
						</div>

						<div class="cpUnderCopy">
							<?php webinarignition_get_lp_host_info( $webinar_data, true ); ?>

							<div class="cpCopyArea">
								<?php
								webinarignition_display(
									$webinar_data->lp_sales_copy,
									'<p>' . __( 'Your Amazing sales copy for your webinar would show up here...', 'webinar-ignition' ) . '</p>'
								);
								restore_previous_locale();
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
						<!--/.cpLeftSide -->
					</div>
					<!--/.cpWrapper .colmd6 -->

					<div class="col-md-6">
						<div class="cpRightSide">
						</div>
						<!--/.cpRightSide-->
					</div>
					<!--/.cpWrapper .colmd6-->

					<br clear="both"/>
				</div>
			</div>
		</div>
	</div>

<?php webinarignition_get_lp_arintegration( $webinar_data, true ); ?>

<?php webinarignition_get_lp_footer( $webinarId, $template_number, $webinar_data, $user_info ); ?>
