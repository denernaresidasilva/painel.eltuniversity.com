<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
?>

<div class="topArea">
	<div class="optinHeadline1 wiOptinHeadline1">
		<?php 
		if ( ! empty( $webinar_data->replay_cd_headline ) ) {
			echo wp_kses_post($webinar_data->replay_cd_headline);
		}
		?>
	</div>
	<div class="bannerTop">
		<?php
		if ( ! empty( $webinar_data->webinar_banner_image ) ) {
			printf( '<image src="%s" alt="" />', esc_url( $webinar_data->webinar_banner_image ) );
		}
		?>
	</div>
</div>
