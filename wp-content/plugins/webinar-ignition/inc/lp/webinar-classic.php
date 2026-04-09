<?php

/**
 * @var $webinar_data
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
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
add_action( 'admin_bar_menu', 'webinarignition_admin_bar_links', 999 );
// Add custom links to admin bar
if ( !function_exists( 'webinarignition_admin_bar_links' ) ) {
    function webinarignition_admin_bar_links() {
        global $wpdb;
        // Check if HTTPS is set and non-empty
        $protocol = ( isset( $_SERVER['HTTPS'] ) && !empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] ? 'https://' : 'http://' );
        // Get the current URL with the determined protocol
        $host = ( isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '' );
        $request_uri = ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' );
        $url = esc_url_raw( $protocol . $host . $request_uri );
        $pattern = '/\\/([^\\/?]+)\\/?\\?/';
        preg_match( $pattern, $url, $matches );
        if ( isset( $matches[1] ) ) {
            $webinar_name = $matches[1];
            $website_url = home_url();
            if ( isset( $_GET['page_id'] ) && is_numeric( $_GET['page_id'] ) && $_GET['page_id'] > 0 ) {
                $page_id = absint( wp_unslash( $_GET['page_id'] ) );
                //phpcs:ignore
                $console_link = "{$website_url}?page_id=" . $page_id . '&console#/dashboard';
            } else {
                $console_link = "{$website_url}/{$webinar_name}/?console#/dashboard";
            }
            if ( is_super_admin() ) {
                global $wp_admin_bar;
                // Add parent dropdown menu
                $wp_admin_bar->add_menu( array(
                    'id'     => 'webinarignition_menu',
                    'title'  => __( 'WebinarIgnition', 'webinar-ignition' ),
                    'href'   => '#',
                    'parent' => 'top-secondary',
                    'meta'   => array(
                        'class' => 'custom-menu',
                    ),
                ) );
                // Add child links
                $wp_admin_bar->add_menu( array(
                    'id'     => 'webinar_console',
                    'title'  => __( 'Live Console', 'webinar-ignition' ),
                    'href'   => $console_link,
                    'parent' => 'webinarignition_menu',
                    'meta'   => array(
                        'class'  => 'custom-link',
                        'target' => '_blank',
                    ),
                ) );
            }
            //end if
        }
        //end if
        $show_setting_link = false;
        $first_post_id = 0;
        $second_post_id = 0;
        if ( isset( $_GET['page_id'] ) && is_numeric( $_GET['page_id'] ) && $_GET['page_id'] > 0 ) {
            $first_post_id = absint( wp_unslash( $_GET['page_id'] ) );
            $show_setting_link = true;
        } else {
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s ORDER BY ID ASC", $webinar_name ) );
            // Check if post_id is found
            if ( $results ) {
                /**
                 * The previous system was generating two posts on a single webinar
                 * so that's why it was requirement to get two post id's and match them with the webinar table
                 */
                $first_post_id = ( isset( $results[0]->ID ) ? $results[0]->ID : 0 );
                $second_post_id = ( isset( $results[1]->ID ) ? $results[1]->ID : 0 );
                $show_setting_link = true;
            }
        }
        //end if
        if ( $show_setting_link ) {
            $tbl_webinarignition = $wpdb->prefix . 'webinarignition';
            // Execute the query and get id
            $webinar_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$tbl_webinarignition} WHERE postID = %d OR postID = %d LIMIT 1", $first_post_id, $second_post_id ) );
            if ( !is_null( $webinar_id ) && 0 < $webinar_id ) {
                $webinar_setting_link = admin_url() . 'admin.php?page=webinarignition-dashboard&id=' . $webinar_id;
                $wp_admin_bar->add_menu( array(
                    'id'     => 'webinar_settings',
                    'title'  => __( 'Settings Dashboard', 'webinar-ignition' ),
                    'href'   => $webinar_setting_link,
                    'parent' => 'webinarignition_menu',
                    'meta'   => array(
                        'class'  => 'custom-link',
                        'target' => '_blank',
                    ),
                ) );
            }
        }
        //end if
    }

}
//end if
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>
		<?php 
if ( empty( $webinar_data->meta_site_title_webinar ) ) {
    webinarignition_display( $webinar_data->lp_metashare_title, __( 'Amazing Webinar', 'webinar-ignition' ) );
} else {
    echo esc_html( $webinar_data->meta_site_title_webinar );
}
?>
	</title>
	<meta name="description" content="
	<?php 
if ( empty( $webinar_data->meta_desc_webinar ) ) {
    webinarignition_display( $webinar_data->lp_metashare_desc, __( 'Join this amazing webinar, and discover industry trade secrets!', 'webinar-ignition' ) );
} else {
    echo esc_html( $webinar_data->meta_desc_webinar );
}
?>
	">

	<?php 
if ( !empty( $webinar_data->ty_share_image ) ) {
    ?>
		<meta property="og:image" content="<?php 
    webinarignition_display( $webinar_data->ty_share_image, '' );
    ?>"/>
	<?php 
}
?>

	<?php 
wp_head();
?>
</head>

<body class="webinar_page" id="webinarignition">
<!-- TOP AREA -->
<div class="topArea">
	<div class="bannerTop container">
		<?php 
if ( !empty( $webinar_data->webinar_banner_image ) ) {
    ?>
			<img src='<?php 
    echo esc_url( $webinar_data->webinar_banner_image );
    ?>'/>
		<?php 
}
?>
	</div>
</div>

<!-- Main Area -->
<div class="mainWrapper">
	<!-- WEBINAR WRAPPER -->
	<div class="webinarWrapper container">
		<!-- WEBINAR MAIN BLOCK LEFT -->
		<div class="webinarBlock">
			<!-- WEBINAR TOP AREA -->
			<div class="webinarTopArea">
				<div class="webinarSound" style="color: <?php 
webinarignition_display( $webinar_data->webinar_speaker_color, '#222' );
?>;">
					<i class="icon-volume-up"></i> <?php 
webinarignition_display( $webinar_data->webinar_speaker, __( 'Turn Up Your Speakers', 'webinar-ignition' ) );
?>
				</div>

				<?php 
if ( isset( $webinar_data->social_share_links ) && 'disabled' !== $webinar_data->social_share_links ) {
    require_once WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/social_share_links.php';
}
?>

				<br clear="all"/>
			</div>
			<!-- WEBINAR VIDEO -->
			<div class="webinarVideo wi_position_relative">
				<?php 
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

					<div id="vidBox"
						class="<?php 
echo ( webinarignition_should_use_videojs( $webinar_data ) ? 'vidBoxjs' : '' );
?>"
						style="display:inline-block; position:absolute">
						<?php 
$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
if ( !$is_preview && wp_validate_boolean( $webinar_data->webinar_live_overlay ) && (!isset( $webinar_data->webinar_live_video ) || !strpos( $webinar_data->webinar_live_video, 'zoom' )) ) {
    ?>
							<div id="vidOvl" style="display:none;"></div>
						<?php 
}
?>

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
										<div id="video-loading-text"><?php 
        esc_html_e( 'Joining Webinar', 'webinar-ignition' );
        ?></div>
									</div>
								</div>

								<div id="no-autoplay-block" style="display: none;">
									<div id="mobile-overlay" type="button" name="button"></div>
									<img
										id="mobile-play-button"
										src="<?php 
        echo esc_url( WEBINARIGNITION_URL . 'images/play-button.png' );
        ?>"
										alt=""
									/>
									<span id="mobile-play-button-text"><?php 
        esc_html_e( 'Join Webinar', 'webinar-ignition' );
        ?></span>
								</div>

								<div id="muted-autoplay-block" style="display: none;">
									<div id="muted-overlay" type="button" name="button"></div>
									<div id="unmute-button">
										<img
											id="unmute-icon"
											src="<?php 
        echo esc_url( WEBINARIGNITION_URL . 'images/unmute.png' );
        ?>"
											alt=""
										/>
										<?php 
        esc_html_e( 'Click for sound', 'webinar-ignition' );
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
        ?>
								<?php 
        echo do_shortcode( $webinar_data->webinar_iframe_source );
        ?>
							<?php 
    }
    ?>

						<?php 
} else {
    ?>
							<?php 
    require WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/partials/webinar-live-video-content.php';
    ?>
							<?php 
}
//end if
?>
					</div>
					<div id="vidOvlSpc" style="width:100%; height: 100%;"></div>

					<?php 
/** TODO: Need clean up **/
$is_cta_aside = false;
$is_cta_overlay = false;
/** TODO: Need clean up - END */
$has_overlay_ctas = false;
if ( 'AUTO' === $webinar_data->webinar_date ) {
    $webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );
    $has_overlay_ctas = !empty( $webinar_cta_by_position['overlay'] );
    // Webinar has overlay CTAs
    /** TODO: Need clean up */
    $is_cta_aside = !empty( $webinar_cta_by_position['outer'] );
    $is_cta_overlay = $has_overlay_ctas;
    /** TODO: Need clean up - END */
} else {
    $has_overlay_ctas = WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && isset( $webinar_data->cta_position ) && 'overlay' === $webinar_data->cta_position;
}
?>

					<?php 
if ( $has_overlay_ctas ) {
    // $cta_transperancy = absint( $webinar_data->cta_transparancy );
    // if ( $cta_transperancy > 100 ) {
    // 	$cta_transperancy = 100;
    // }
    // $cta_transperancy = 100 - $cta_transperancy;
    // $cta_transperancy = $cta_transperancy / 100;
    ?>
						<style>
							.ctaArea {
								position: relative;
							}

							/* .timedUnderArea {
								border: 1px solid rgba(33, 33, 33, <?php 
    // echo ( $cta_transperancy < 1 ) ? absint( $cta_transperancy ) : 1;
    ?>);
							} */
							.timedUnderArea:after {
								display: none;
							}

							.timedUnderArea.timedUnderAreaOverlay, .additional_autoaction_item {
								position: absolute;
								height: auto;
								max-height: 98%;
								left: 100vw;
								bottom: 0;
								right: 0;
								overflow: auto;
								<?php 
    // echo ( $cta_transperancy < 1 ) ? 'background-color: rgba(255, 255, 255, ' . esc_html( $cta_transperancy ) . ');' : '';
    ?>
							}

							@media only screen and (max-width : 768px) {
								.timedUnderArea.timedUnderAreaOverlay, .additional_autoaction_item {
									height: auto;
									max-height: none!important;
								}
							}
						</style>
						<?php 
}
//end if
?>


					<?php 
if ( 'AUTO' === $webinar_data->webinar_date ) {
    include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-overlay-cta-area.php';
} else {
    ?>
						<div class="timedUnderArea test-8" id="orderBTN" style="display: none; position:initial;">
							<div id="orderBTNCopy"></div>
							<div id="orderBTNArea"></div>
						</div>
					<?php 
}
?>
				</div>
					<?php 
if ( $is_cta_aside ) {
    ?>
						<div class="ctaAreaOuter" style="padding-bottom: 3px;">
							<?php 
    include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-cta-area.php';
    ?>
						</div>
						<?php 
}
?>
				<!--/.ctaArea-->
			</div>
			<!--/.webinarVideo-->

			<!-- WEBINAR BOTTOM AREA -->
			<div class="webinarBottomArea" style="display:none;">
				<?php 
if ( 'hide' !== $webinar_data->webinar_callin ) {
    ?>
					<div class="webinarSound"
						style="color: <?php 
    webinarignition_display( $webinar_data->webinar_callin_color, '#222' );
    ?>;">
						<i class="icon-phone"></i> <?php 
    webinarignition_display( $webinar_data->webinar_callin_copy, __( 'To Join Call:', 'webinar-ignition' ) );
    ?>
						<a style="color: <?php 
    webinarignition_display( $webinar_data->webinar_callin_color2, '#3E8FC7' );
    ?>;"><?php 
    webinarignition_display( $webinar_data->webinar_callin_number, '1-555-555-5555' );
    ?></a>
					</div>
				<?php 
}
?>

				<div class="webinarShare">
					<div class="webinarShareCopy">
						<div class="webinarLive"
							style="color: <?php 
webinarignition_display( $webinar_data->webinar_live_color, '#498A00' );
?>;">
							<?php 
webinarignition_display( $webinar_data->webinar_live, __( 'Webinar Is Live', 'webinar-ignition' ) );
?> <i class="icon-circle"></i>
						</div>
					</div>
				</div>
				<br clear="all"/>
			</div>

			<!-- WEBINAR UNDER EXTRA CTA AREA -->
			<div class="webinarUnderArea" style="margin-top: 30px;">
				<div class="row">
					<div class="col-md-4">
						<!-- WEBINAR BLOCK RIGHT -->
						<div class="webinarBlockRight">

							<!-- WEBINAR INFO BLOCK -->
							<?php 
webinarignition_get_webinar_info( $webinar_data, true );
?>

							<!-- GIVE AWAY BLOCK -->
							<?php 
webinarignition_get_webinar_giveaway( $webinar_data, true );
?>

						</div>
						<!--/.webinarBlockRight-->
					</div>


					<?php 
if ( 'hide' !== trim( $webinar_data->webinar_qa ) ) {
    ?>
						<div class="col-md-8">
							<?php 
    webinarignition_get_webinar_qa( $webinar_data, true );
    ?>
						</div>
						<?php 
}
?>
				</div>
			</div><!--/.webinarUnderArea -->

		</div>
		<br clear="left"/>

	</div>

</div>

<?php 
require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php';
?>

<div id="fb-root"></div>

<?php 
wp_footer();
?>

<?php 
echo ( isset( $webinar_data->footer_code ) ? do_shortcode( $webinar_data->footer_code ) : '' );
?>

<?php 
restore_previous_locale();
?>
</body>
</html>
