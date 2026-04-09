<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $assets
 */
?>
<div class="webinarVideo">
	<div class="ctaArea">
		<div id="vidBox" class="<?php webinarignition_should_use_videojs( $webinar_data ) ? 'vidBoxjs' : ''; ?>">
			<?php if ( 'AUTO' === $webinar_data->webinar_date ) { ?>
				<?php if ( webinarignition_should_use_videojs( $webinar_data ) ) { ?>
					<div id="video-loading-block">
						<div id="video-loading-overlay" type="button" name="button"></div>
						<div id="video-loading-content-container">
							<img id="video-loading-spinner" src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/ajax-loader.gif' ); ?>"/>
							<div id="video-loading-text"><?php esc_html_e( 'Starting replay', 'webinar-ignition' ); ?></div>
						</div>
					</div>

					<div id="no-autoplay-block" style="display: none;">
						<div id="mobile-overlay" type="button" name="button"></div>
						<img id="mobile-play-button" src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/play-button.png' ); ?>" alt="" />
						<span id="mobile-play-button-text" ><?php esc_html_e( 'Watch Replay', 'webinar-ignition' ); ?></span>
					</div>

					<div id="muted-autoplay-block" style="display: none;">
						<div id="muted-overlay" type="button" name="button"></div>
						<div id="unmute-button" >
							<img id="unmute-icon" src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/unmute.png' ); ?>" alt="" />
							<?php esc_html_e( 'Click for sound', 'webinar-ignition' ); ?>
						</div>
					</div>

					<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-video.php'; ?>
					<?php
				} else {

					if ( has_shortcode( $webinar_data->webinar_iframe_source, 'video' ) ) :
						$GLOBALS['content_width'] = 1225;// see /wp-includes/media.php::wp_video_shortcode();
					endif;

					echo do_shortcode( $webinar_data->webinar_iframe_source );

				}//end if
			} else {
				?>
				<?php

				if ( has_shortcode( $webinar_data->replay_video, 'video' ) ) :
					$GLOBALS['content_width'] = 1225;// see /wp-includes/media.php::wp_video_shortcode();
				endif;

				webinarignition_display(
					do_shortcode( $webinar_data->replay_video ),
					'<img src="' . $assets . '/images/videoplaceholder.png" />'
				);
				?>
				<?php
			}//end if
			?>

			<?php
			$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
			if ( ! $is_preview && wp_validate_boolean( $webinar_data->webinar_live_overlay ) && ( ! isset( $webinar_data->webinar_live_video ) || ! strpos( $webinar_data->webinar_live_video, 'zoom' ) ) ) :
				?>
				<!-- disable video controls -->
				<div id="vidOvl" style="display:none;"></div>
			<?php endif ?>

		</div>
	</div>
	<!--/.ctaArea-->
</div>
