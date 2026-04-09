<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<style type="text/css">

	/*TOP AREA CSS STUFF*/
	.topArea{
		<?php if ( isset( $webinar_data->lp_banner_bg_style ) && $webinar_data->lp_banner_bg_style == 'hide' ) {
			echo 'display: none;';} ?>
		background-color: 
		<?php
		if ( isset( $webinar_data->lp_banner_bg_color ) && $webinar_data->lp_banner_bg_color == '' ) {
			echo '#FFF';
		} else {
			echo isset( $webinar_data->lp_banner_bg_color ) ? esc_attr( $webinar_data->lp_banner_bg_color ) : ''; }
		?>
		;
		<?php
		if ( !isset( $webinar_data->lp_banner_bg_repeater ) || $webinar_data->lp_banner_bg_repeater == '' ) {
			echo 'border-top: 3px solid rgba(0,0,0,0.20);
					  border-bottom: 3px solid rgba(0,0,0,0.20);';
		} else {
			echo 'background-image: url(' . esc_url( $webinar_data->lp_banner_bg_repeater ) . ');';
		}
		?>
	}

	.mainWrapper{
		background-color: 
		<?php
		if ( isset( $webinar_data->lp_background_color ) && $webinar_data->lp_background_color == '' ) {
			echo '#f1f1f1;';
		} else {
			echo isset( $webinar_data->lp_background_color ) ? esc_attr( $webinar_data->lp_background_color ) : ''; }
		?>
		;
		<?php
		if ( !isset( $webinar_data->lp_background_image ) || $webinar_data->lp_background_image == '' ) {
			echo 'border-top: 3px solid rgba(0,0,0,0.05);
					  border-bottom: 3px solid rgba(0,0,0,0.05);';
		} else {
			echo 'background-image: url(' . esc_url( $webinar_data->lp_background_image ) . ');';
		}
		?>
	}

	<?php
	$reg_CTA_BG = '#212121';
	if ( isset( $webinar_data->lp_cta_bg_color ) && ! empty( $webinar_data->lp_cta_bg_color ) ) {
		$reg_CTA_BG = $webinar_data->lp_cta_bg_color;
	}
	?>

	<?php if ( $reg_CTA_BG == 'transparent' ) : ?>
	.ctaArea {
		border:none;
	}
	.ctaArea.video {
		padding:0;
	}
	<?php endif; ?>

	.ctaArea.video {
		background-color: <?php echo esc_attr( $reg_CTA_BG ); ?>;
	}

	.innerHeadline{
		background-color: 
		<?php
		if ( isset( $webinar_data->lp_sales_headline_color ) && $webinar_data->lp_sales_headline_color == '' ) {
			echo '#0496AC;';
		} else {
			echo isset( $webinar_data->lp_sales_headline_color ) ? esc_attr( $webinar_data->lp_sales_headline_color ) : ''; }
		?>
		;
	}

	<?php
	$btn_color = isset($webinar_data->lp_optin_btn_color) && $webinar_data->lp_optin_btn_color !== '' ? $webinar_data->lp_optin_btn_color : '#74BB00';
	$color_array = webinarignition_btn_color($btn_color);
	$hover_color = $color_array['hover_color'];
	$text_color = $color_array['text_color'];
	?>

	#optinBTN, #verifyEmailBTN, .wi_arrow_button {
		background-color: <?php echo esc_attr( $hover_color ); ?> !important;
		color: <?php echo esc_attr( $text_color ); ?>;
	}
	.wi_registration #optinBTN{
		background-color: <?php echo esc_attr( $hover_color ); ?> !important;
	}

	.wi_registration .wi_arrow_button{
		background-color: <?php echo esc_attr( $hover_color ); ?> !important;
	}

	#optinBTN:hover, #verifyEmailBTN:hover, .wi_arrow_button:hover {
		background-color: <?php echo esc_attr( $btn_color ); ?> !important;
		color: <?php echo esc_attr( $text_color ); ?> !important;
	}

	#optinBTN.optinBTNimg, #verifyEmailBTN.optinBTNimg, .wi_arrow_button.optinBTNimg {
		display: inline-block;
		width: auto;
		max-width: 100%;
		border: none;
		background-color: transparent;
	}

</style>
