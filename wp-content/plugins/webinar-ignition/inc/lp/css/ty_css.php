<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<style type="text/css">

	/*TOP AREA CSS STUFF*/
	.topArea{
		<?php if($webinar_data->lp_banner_bg_style == "hide" ){ echo "display: none;";} ?>
		background-color: <?php echo esc_attr( empty( $webinar_data->lp_banner_bg_color ) ? '#FFF' : $webinar_data->lp_banner_bg_color ); ?>;
		<?php
			if($webinar_data->lp_banner_bg_repeater == ""){
				echo "border-top: 3px solid rgba(0,0,0,0.20);
					  border-bottom: 3px solid rgba(0,0,0,0.20);";
			} else{
				echo "background-image: url(" . esc_url($webinar_data->lp_banner_bg_repeater) . ");";
			}
		?>
	}

	.mainWrapper{
		background-color: #f1f1f1;
	}

	<?php
    $ty_CTA_BG = '#212121';
    if( isset($webinar_data->ty_cta_bg_color) && !empty($webinar_data->ty_cta_bg_color) ) {
        $ty_CTA_BG = $webinar_data->ty_cta_bg_color;
    }
    ?>
    .page-thankyou_cp .ctaArea{
        background-color: <?php echo esc_attr($ty_CTA_BG); ?> !important;
    }
    <?php if($ty_CTA_BG == 'transparent'): ?>
    .page-thankyou_cp .ctaArea {
        border:none;
    }
    .page-thankyou_cp .ctaArea.video {
        padding:0;
    }
    <?php endif; ?>


</style>