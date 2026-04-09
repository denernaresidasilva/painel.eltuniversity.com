<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registration template 01
 *
 * @var $template_number
 * @var $webinarId
 * @var $webinar_data
 * @var $is_webinar_available
 * @var $assets
 * @var $user_info
 */
webinarignition_get_lp_header( $webinarId, $template_number, $webinar_data );

webinarignition_get_lp_banner( $webinar_data, true );
?>
<!-- Main Area -->
<div class="mainWrapper">
	<div class="wiContainer container wi-default-reg-page">
		<!-- HEADLINE AREA -->
		<div class="headlineArea" style="display: <?php echo empty( $webinar_data->lp_main_headline ) ? 'none' : 'block'; ?>;">
			<?php webinarignition_display( isset($webinar_data->lp_main_headline) ? $webinar_data->lp_main_headline : '', '' ); ?>
		</div>

		<!-- SALES AREA -->
		<div class="salesWrapper row row-no-gutters">
			<div class="col-md-7">
				<!-- LEFT SIDE -->
				<div class="ctaLeft">
					<!-- VIDEO / CTA BLOCK AREA HERE -->
					<?php webinarignition_get_video_area( $webinar_data, true, 1225 ); ?>
					<!-- VIDEO / CTA BLOCK AREA HERE - End -->

					<?php webinarignition_get_lp_sales_headline( $webinar_data, true ); ?>

					<div class="innerCopy">
						<?php webinarignition_get_lp_host_info( $webinar_data, true ); ?>

						<?php webinarignition_get_lp_sales_copy( $webinar_data, true ); ?>
					</div>

				</div>
				<!-- /LEFT SIDE -->
			</div>

			<div class="col-md-5">
				<div class="ctaRight">
					<!-- OPT HEADLINE -->
					<?php webinarignition_get_lp_optin_headline( $webinar_data, true ); ?>

					<?php webinarignition_get_lp_optin_section( $webinar_data, true ); ?>
				</div>
				<!-- /RIGHT SIDE -->
			</div>
		</div>
	</div>
</div>
<!-- /.main Area -->

<!-- AR OPTIN INTEGRATION -->
<?php webinarignition_get_lp_arintegration( $webinar_data, true ); ?>
<?php webinarignition_get_lp_footer( $webinarId, $template_number, $webinar_data, $user_info ); ?>
