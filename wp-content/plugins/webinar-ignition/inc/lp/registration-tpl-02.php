<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Registration template 02
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

<!-- main wrapper -->
<div class="mainWrapper">
	<!-- HEADLINE AREA -->
	<div class="headlineArea" style="display: <?php echo empty( $webinar_data->lp_main_headline ) ? 'none' : 'block'; ?>;">
		<div class="wiContainer container">
			<div class="ssHeadline">
				<?php webinarignition_display( $webinar_data->lp_main_headline, '' ); ?>
			</div>
		</div>
	</div>


	<div class="wiContainer container wi-default-reg-page">

		<!-- MAIN AREA -->
		<div class="cpWrapper">
			<div class="row">
				<div class="col-md-7">
					<div class="cpLeftSide">
						<!-- VIDEO / CTA AREA -->
						<div class="videoBlock">
							<!-- VIDEO / CTA BLOCK AREA HERE -->
							<?php webinarignition_get_video_area( $webinar_data, true ); ?>
							<!-- VIDEO / CTA BLOCK AREA HERE - End -->
						</div>
						<!--/.videoBlock -->


						<!-- BAR AREA -->
						<div class="innerHeadline addedArrow" style="background-color: <?php echo esc_attr( empty( $webinar_data->lp_sales_headline_color ) ? '#0496AC' : $webinar_data->lp_sales_headline_color ); ?>;">
							<span>
								<?php webinarignition_display( $webinar_data->lp_sales_headline, __( 'What You Will Learn On The Webinar...', 'webinar-ignition' ) ); ?>
							</span>
						</div>
						<!--/.innerHeadline-->

						<div class="wi-host-info-wrap">
							<?php webinarignition_get_lp_host_info( $webinar_data, true ); ?>
						</div>

						<div class="ssSalesArea">

							<?php
							webinarignition_display(
								$webinar_data->lp_sales_copy,
								'<p>' . __( 'Your Amazing sales copy for your webinar would show up here...', 'webinar-ignition' ) . '</p>'
							);
							restore_previous_locale();
							?>

						</div>
					</div>
					<!--/.cpLeftSide -->
				</div>
				<!--/.col-md-7-->
				

				<div class="col-md-5">
					<div class="cpRightSide">
						<div class="ssRight">
							<!-- OPT HEADLINE -->
							<?php webinarignition_get_lp_optin_headline( $webinar_data, true ); ?>

							
							<!-- Paid webinar Checker  -->
							<?php webinarignition_get_lp_optin_section( $webinar_data, true ); ?>
							
						</div>
					</div>
					<!--/.cpRightSide -->
				</div>
				<!--/.cpWrapper .com-md-5-->
			</div>
			
			<!--/.cpWrapper .row-->
		</div>
		<!--/.cpWrapper -->
	</div>
	<!--/.container -->
</div>
<!--/.mainwrapper-->

<!-- AR OPTIN INTEGRATION -->
<?php webinarignition_get_lp_arintegration( $webinar_data, true ); ?>

<?php webinarignition_get_lp_footer( $webinarId, $template_number, $webinar_data, $user_info ); ?>
