<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * @var $webinar_data
 * @var $assets
 */
$webinarignition_modern_page = get_query_var( 'webinarignition_modern_page' );
$is_replay = false;
if ( $webinarignition_modern_page && 'replay_page' === $webinarignition_modern_page ) {
    $is_replay = true;
}
$webinar_type = 'live';
$is_cta_aside = false;
$is_cta_overlay = false;
$is_cta_timed = false;
$webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );
if ( !empty( $webinar_cta_by_position ) ) {
    $webinar_type = 'evergreen';
    if ( !empty( $webinar_cta_by_position['is_time'] ) ) {
        $is_cta_timed = true;
    }
    if ( !empty( $webinar_cta_by_position['outer'] ) ) {
        $is_cta_aside = true;
    }
    if ( !empty( $webinar_cta_by_position['overlay'] ) ) {
        $is_cta_overlay = true;
    }
}
?>
<div class="webinarVideo">
	<?php 
$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
if ( !$is_preview && wp_validate_boolean( $webinar_data->webinar_live_overlay ) && (!isset( $webinar_data->webinar_live_video ) || !strpos( $webinar_data->webinar_live_video, 'zoom' )) ) {
    ?>
		<!-- disable video controls -->
		<div id="vidOvl" style="display:none;"></div>
	<?php 
}
?>
	<div class="ctaArea">
		<?php 
if ( !empty( $webinar_data->webinar_iframe_source ) ) {
    if ( has_shortcode( $webinar_data->webinar_iframe_source, 'video' ) ) {
        $GLOBALS['content_width'] = 1225;
    }
}
?>

		<?php 
if ( !empty( $webinar_data->webinar_live_video ) ) {
    if ( has_shortcode( $webinar_data->webinar_live_video, 'video' ) ) {
        $GLOBALS['content_width'] = 1225;
    }
}
?>

		<div id="vidBox" class="<?php 
( webinarignition_should_use_videojs( $webinar_data ) ? 'vidBoxjs' : '' );
?>">
			<?php 
if ( 'AUTO' === $webinar_data->webinar_date ) {
    ?>
				<?php 
    if ( webinarignition_should_use_videojs( $webinar_data ) ) {
        ?>
					<div id="video-loading-block">
						<div id="video-loading-overlay" type="button" name="button"></div>
						<div id="video-loading-content-container">
							<img id="video-loading-spinner"
								src="<?php 
        echo esc_url( WEBINARIGNITION_URL . 'images/ajax-loader.gif' );
        ?>"/>
							<div id="video-loading-text">
								<?php 
        echo ( $is_replay ? esc_html__( 'Starting replay', 'webinar-ignition' ) : esc_html__( 'Joining Webinar', 'webinar-ignition' ) );
        ?>
							</div>
						</div>
					</div>

					<div id="no-autoplay-block" style="display: none;">
						<div id="mobile-overlay" type="button" name="button"></div>
						<img id="mobile-play-button" src="<?php 
        echo esc_url( WEBINARIGNITION_URL . 'images/play-button.png' );
        ?>"
							alt=""/>
						<span id="mobile-play-button-text">
							<?php 
        echo ( $is_replay ? esc_html__( 'Watch Replay', 'webinar-ignition' ) : esc_html__( 'Join Webinar', 'webinar-ignition' ) );
        ?>
						</span>
					</div>

					<div id="muted-autoplay-block" style="display: none;">
						<div id="muted-overlay" type="button" name="button"></div>
						<div id="unmute-button">
							<img id="unmute-icon" src="<?php 
        echo esc_url( WEBINARIGNITION_URL . 'images/unmute.png' );
        ?>"
								alt=""/>
							<?php 
        echo esc_html__( 'Click for sound', 'webinar-ignition' );
        ?>

						</div>
					</div>
					<div class="autoWebinarLoading"
						style="z-index: 888888; background-color: rgba(0, 0, 0, 0.8); width: 100%; position:absolute; display: none">

						<div class="autoWebinarLoadingCopy">
							<i class="icon-spinner icon-spin icon-large autoWebinarLoader"></i>
							<br/>
							<p>
								<b><?php 
        webinarignition_display( $webinar_data->auto_video_load, __( 'Please Wait - The Webinar Is Loading...', 'webinar-ignition' ) );
        ?></b>
							</p>
						</div>
					</div>

					<?php 
        include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-video.php';
        ?>
					<?php 
    } else {
        $timeover = false;
        if ( isset( $_GET['lid'] ) ) {
            $lead_id = sanitize_text_field( $_GET['lid'] );
            $watch_time = get_option( 'wi_lead_watch_time_' . $lead_id, true );
            $site_url = get_site_url();
            $statusCheck = new stdClass();
            $statusCheck->switch = 'free';
            $statusCheck->slug = 'free';
            $statusCheck->licensor = '';
            $statusCheck->is_free = 1;
            $statusCheck->is_dev = '';
            $statusCheck->is_registered = '';
            $statusCheck->title = 'Free';
            $statusCheck->member_area = '';
            $statusCheck->is_pending_activation = 1;
            $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
            $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
            $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
            $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
            $statusCheck->name = '';
        }
        //end if
        if ( !$timeover ) {
            echo do_shortcode( $webinar_data->webinar_iframe_source );
        } else {
            $site_url = get_site_url();
            $statusCheck = new stdClass();
            $statusCheck->switch = 'free';
            $statusCheck->slug = 'free';
            $statusCheck->licensor = '';
            $statusCheck->is_free = 1;
            $statusCheck->is_dev = '';
            $statusCheck->is_registered = '';
            $statusCheck->title = 'Free';
            $statusCheck->member_area = '';
            $statusCheck->is_pending_activation = 1;
            $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
            $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
            $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
            $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
            $statusCheck->name = '';
        }
    }
} else {
    if ( isset( $_GET['lid'] ) ) {
        $lead_id = sanitize_text_field( $_GET['lid'] );
    }
    if ( $is_replay ) {
        webinarignition_display( do_shortcode( $webinar_data->replay_video ), '<img src="' . $assets . '/images/videoplaceholder.png" />' );
    } else {
        require WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/partials/webinar-live-video-content.php';
    }
}
//end if
?>
		</div>
		<div id="vidOvlSpc" style="width:100%; height: 100%;"></div>
	</div>
	<!--/.ctaArea-->
</div>
