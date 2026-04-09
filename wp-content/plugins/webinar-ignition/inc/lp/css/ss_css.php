<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<style type="text/css">
	<?php
		$top_area_bg_color = empty( $webinar_data->lp_banner_bg_color ) ? '#FFF' : esc_attr( $webinar_data->lp_banner_bg_color );
	?>

	/*TOP AREA CSS STUFF*/
	.topArea {
		<?php if ( 'hide' === $webinar_data->lp_banner_bg_style ) : ?> 
			display: none;
		<?php endif; ?>
		background-color:<?php echo esc_attr( $top_area_bg_color ); ?>;
		<?php
		if ( empty( $webinar_data->lp_banner_bg_repeater ) ) {
			echo 'border-top: 3px solid rgba(0,0,0,0.20); border-bottom: 3px solid rgba(0,0,0,0.20);';
		} else {
			echo 'background-image: url(' . esc_url( $webinar_data->lp_banner_bg_repeater ) . ');';
		}
		?>
	}

	.headlineArea {
		<?php if ( isset( $webinar_data->lp_background_color ) && ! empty( $webinar_data->lp_background_color ) ) : ?>
		background-color: <?php echo esc_attr( $webinar_data->lp_background_color ); ?> !important;
		border: none !important;
		<?php else : ?>
		background-color: #2F2F2F !important;
		border-top: 3px solid rgba(0,0,0,0.05);
		border-bottom: 3px solid rgba(0,0,0,0.05);	
		<?php endif; ?>

		<?php if ( isset( $webinar_data->lp_background_image ) && ! empty( $webinar_data->lp_background_image ) ) : ?>
		background-image: url("<?php echo esc_url( $webinar_data->lp_background_image ); ?>");
		<?php endif; ?>		
	}

	.ssHeadline {
		max-width: 960px;
		margin-left: auto;
		margin-right: auto;
	}

	.dateInfo {
		padding-left: 5px;
	}

	<?php
	$reg_CTA_BG = '#212121';
	if ( isset( $webinar_data->lp_cta_bg_color ) && ! empty( $webinar_data->lp_cta_bg_color ) ) {
		$reg_CTA_BG = $webinar_data->lp_cta_bg_color;
	}
	?>

	<?php if ( 'transparent' === $reg_CTA_BG ) : ?>
	.ctaArea {
		border:none;
	}
	.ctaArea.video {
		padding:0;
	}
	@media (max-width: 767px) {
		.videoBlock {
			padding:0;
			background-color: <?php echo esc_attr( $reg_CTA_BG ); ?>;
		}
	}
	<?php endif; ?>

	<?php
		$background_color = empty( $webinar_data->lp_sales_headline_color ) ? '#0496AC' : $webinar_data->lp_sales_headline_color;
		$optin_btn_bg_color = empty( $webinar_data->lp_optin_btn_color ) ? '#74BB00' : $webinar_data->lp_optin_btn_color;
	?>

	.ctaArea.video {
		background-color: <?php echo esc_attr( $reg_CTA_BG ); ?>;
	}

	.innerHeadline {
		background-color: <?php echo esc_attr( $background_color ); ?>;
	}
	#optinBTN, #verifyEmailBTN, .wi_arrow_button {
		background-color: <?php echo esc_attr( $optin_btn_bg_color ); ?>;
	}
</style>
