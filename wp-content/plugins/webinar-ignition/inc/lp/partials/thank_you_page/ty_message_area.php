<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
/**
 * @var $webinar_data
 * @var $assets
 */
?>
<?php
$ty_CTA_BG = '#212121';
if ( isset( $webinar_data->ty_cta_bg_color ) && ! empty( $webinar_data->ty_cta_bg_color ) ) {
	$ty_CTA_BG = $webinar_data->ty_cta_bg_color;
}

global $webinarignition_shortcode_params;
$webinarignition_shortcode_params = (array) $webinarignition_shortcode_params;


$shortcode_params =  isset( $webinarignition_shortcode_params[ absint($webinar_data->id) ] ) ? $webinarignition_shortcode_params[ absint($webinar_data->id) ] : null;
$video_source     = ! empty( $shortcode_params['custom_video_url'] ) ? ( $shortcode_params['custom_video_url'] ) : esc_url( $webinar_data->ty_cta_video_url );
?>
<style>
	.ctaArea.video {
		background-color: <?php echo esc_attr( $ty_CTA_BG ); ?>;
	}
	<?php if ( 'transparent' === $ty_CTA_BG ) : ?>
		.ctaArea {
			border:none;
		}
		.ctaArea.video {
			padding:0;
		}
	<?php endif; ?>
</style>
<div class="ctaArea video" <?php echo 'html' === $webinar_data->ty_cta_type ? ' style="background-color:#FFF;"' : ''; ?>>
	<?php
	if ( has_shortcode( $webinar_data->lp_cta_video_code, 'video' ) && empty( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 1225;// see /wp-includes/media.php::wp_video_shortcode();
	}
	if ( 'video' === $webinar_data->ty_cta_type || ! empty( $shortcode_params['custom_video_url'] ) ) {
		if ( ! empty( $video_source ) ) {
			$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
			wp_enqueue_style( 'webinarignition_video_css' );
			wp_enqueue_script( 'webinarignition_video_js' );
			?>
			<style>
				#wi_ctaVideo {
					position:relative;
					width: 100%;
				}

				#wi_ctaVideoPlayer {
					width:100%;
					height:100%;
				}

				#wi_ctaVideo > .wi_videoPlayerUnmute {
					position: absolute;
					width: 124px;
					top: 50%;
					margin-top: -22px;
					left: 50%;
					margin-left: -62px;
					z-index: 9999;
					display:none;
				}

				#wi_ctaVideo > .wi_videoPlayerMute {
					background: no-repeat;
					border: none;
					width: 10%;
					padding:0 2% 1% 2%;
					position: absolute;
					bottom: 0;
					display: none;
					-webkit-box-shadow: none;
					box-shadow: none;
					-webkit-transition: none;
					-moz-transition: none;
					transition: none;
					z-index: 9999;
					cursor: pointer;
				}
			</style>

			<div id="wi_ctaVideo">
				<button class="button addedArrow wiButton wiButton-primary wiButton-block wiButton-lg wi_videoPlayerUnmute"><?php echo esc_html( apply_filters( 'wi_cta_video_unmute_text', __( 'Unmute', 'webinar-ignition' ) ) ); ?></button>
				<button class="wi_videoPlayerMute"><img src="<?php echo esc_url( $assets . 'images/mute.svg' ); ?>" /></button>
				<video id="wi_ctaVideoPlayer" class="video-js vjs-default-skin wi_videoPlayer" disablePictureInPicture oncontextmenu="return false;">
					<source src="<?php echo esc_url( $video_source ); ?>" type='video/mp4'/>
				</video>
			</div>
			<?php
		} else {
			webinarignition_display(
				do_shortcode( $webinar_data->ty_cta_video_code ),
				'<img src="' . $assets . 'images/novideo.png" />'
			);
		}//end if
	} elseif ( 'html' === $webinar_data->ty_cta_type || $webinar_data->ty_cta_type == '' ) {
		webinarignition_display(
			$webinar_data->ty_cta_html,
			__( '<h3>Looking Forward To Seeing You</h3><p>An email is being sent to you with all the information. If you want more reminders for the event add the event date to your calendar...</p>', 'webinar-ignition' )
		);
	} elseif ( 'image' === $webinar_data->ty_cta_type ) {
		echo "<img src='";
		webinarignition_display( $webinar_data->ty_cta_image, $assets . 'images/noctaimage.png' );
		echo "' height='215' width='287' />";
	}//end if
	?>
</div>
