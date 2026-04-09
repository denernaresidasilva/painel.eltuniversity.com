<?php
/**
 * @var $webinar_data
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- META INFO -->
	<title>
		<?php
		if ( empty( $webinar_data->meta_site_title_replay ) ) {
			webinarignition_display( $webinar_data->lp_metashare_title, __( 'Amazing Webinar', 'webinar-ignition' ) );
		} else {
			echo esc_html( $webinar_data->meta_site_title_replay );
		}
		?>
	</title>

	<meta name="description" content="
	<?php
	if ( empty( $webinar_data->meta_desc_replay ) ) {
		webinarignition_display( $webinar_data->lp_metashare_desc, __( 'Join this amazing webinar!', 'webinar-ignition' ) );
	} else {
		echo esc_html( $webinar_data->meta_desc_replay );
	}
	?>
	">

	<?php if ( ! empty( $webinar_data->ty_share_image ) ) : ?>
		<meta property="og:image" content="<?php webinarignition_display( $webinar_data->ty_share_image, '' ); ?>"/>
	<?php endif ?>

	<?php wp_head(); ?>
</head>
<body class="tpl-inc-lp-replay" id="webinarignition">

<!-- TOP AREA -->
<?php webinarignition_get_replay_main_headline( $webinar_data, true ); ?>

<!-- Main Area -->
<div class="mainWrapper">

		<!-- WEBINAR WRAPPER -->
		<div class="webinarWrapper container">

		<!-- CLOSED WEBINAR -->
		<div id="closed" class="webinarExtraBlock2" style="<?php echo ( isset( $webinar_data->auto_replay ) && ! wp_validate_boolean( $webinar_data->auto_replay ) ) ? '' : 'display:none;'; ?>" >
			<?php webinarignition_display( $webinar_data->replay_closed, '<h1>' . __( 'Webinar Is Over', 'webinar-ignition' ) . '</h1>' ); ?>
		</div>

		<!-- WEBINAR MAIN BLOCK LEFT -->
		<?php if ( ! webinarignition_is_auto( $webinar_data ) || ( isset( $webinar_data->auto_replay ) && wp_validate_boolean( $webinar_data->auto_replay ) ) ) { ?>
				<div class="webinarBlock">

					<!-- WEBINAR TOP AREA -->
					<div class="webinarTopArea">

					<div class="webinarSound" style="color: <?php webinarignition_display( $webinar_data->webinar_speaker_color, '#222' ); ?>;">
						<i class="icon-volume-up"></i> <?php webinarignition_display( $webinar_data->webinar_speaker, __( 'Turn Up Your Speakers', 'webinar-ignition' ) ); ?>
					</div>

					<div class="webinarShare">
						<?php
						if ( 'disabled' !== $webinar_data->social_share_links ) {
							?>
							<div class="webinarShareCopy" style="color: <?php webinarignition_display( $webinar_data->webinar_invite_color, '#222' ); ?>;">
								<i class="fas fa-user"></i> 
								<?php
								webinarignition_display(
									$webinar_data->webinar_invite,
									__( 'Invite Your Friends To The Webinar:', 'webinar-ignition' )
								);
								?>
							</div>
							<div class="webinarShareIcons wi-block--sharing">
								<!-- Facebook Share	Button -->
								<?php if ( 'off' !== $webinar_data->webinar_fb_share ) : ?>
									<div style="position: relative; float: left; /*background: #ff8285;*/ min-height: 20px; width: 60px; margin-right: 15px;">
										<div class="fb-like"
											data-href="<?php echo esc_url( $webinar_data->webinar_permalink ); ?>"
											style="position: absolute; top: -2px; left: 0;"
											data-layout="button_count"
											data-width="60"
											data-show-faces="false"
											>
										</div>
									</div>
								<?php endif ?>

								<!-- Twitter Share Button -->
								<?php if ( 'off' !== $webinar_data->webinar_tw_share ) : ?>
									<div style="position: relative; float: left; min-height: 20px; width: 60px; margin-right: 15px;">
										<a
											href="https://twitter.com/share"
											data-url="<?php echo esc_url( $webinar_data->webinar_permalink ); ?>"
											class="twitter-share-button"
											>Tweet
										</a>
									</div>
								<?php endif ?>

								<!-- Linkedin Share Button -->
								<?php if ( 'off' !== $webinar_data->webinar_ld_share ) : ?>
									<script type="IN/Share" data-url="<?php echo esc_url( $webinar_data->webinar_permalink ); ?>" data-counter="right"></script>
								<?php endif ?>
							</div>
							<?php
						}//end if
						?>
					</div>

					<br clear="all"/>

				</div>

					<!-- WEBINAR VIDEO -->
					<div class="webinarVideo">
						<!-- REPLAY Top -->
						<?php if ( ( ! empty( $webinar_data->replay_optional ) && 'hide' !== $webinar_data->replay_optional ) || ( isset( $webinar_data->auto_replay ) && wp_validate_boolean( $webinar_data->auto_replay ) ) ) : ?>
								<div class="webinarExtireTop">
									<div class="webinarReplayExpireCopy">
										<span><?php webinarignition_display( $webinar_data->replay_cd_headline, __( 'This Replay Is Going Down Very Soon!', 'webinar-ignition' ) ); ?></span>
									</div>
									<div class="webinarReplayExpireCD" id="cdExpire"></div>
									<br clear="left">
								</div>
						<?php endif; ?>

						<div class="ctaArea">
							<div id="vidBox" class="<?php echo webinarignition_should_use_videojs( $webinar_data ) ? 'vidBoxjs' : ''; ?>" style="display:inline-block; position:absolute">
								<?php if ( 'AUTO' === $webinar_data->webinar_date ) { ?>
									<?php if ( webinarignition_should_use_videojs( $webinar_data ) ) { ?>
										<div id="video-loading-block">
											<div id="video-loading-overlay" type="button" name="button"></div>
											<div id="video-loading-content-container">
												<img
													id="video-loading-spinner"
													src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/ajax-loader.gif' ); ?>"
												/>
												<div id="video-loading-text">
													<?php esc_html_e( 'Starting replay', 'webinar-ignition' ); ?>
												</div>
											</div>
										</div>

										<div id="no-autoplay-block" style="display: none;">
											<div id="mobile-overlay" type="button" name="button"></div>
											<img
												id="mobile-play-button"
												src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/play-button.png' ); ?>"
												alt=""
											/>
											<span id="mobile-play-button-text" >
												<?php esc_html_e( 'Watch Replay', 'webinar-ignition' ); ?>
											</span>
										</div>

										<div id="muted-autoplay-block" style="display: none;">
											<div id="muted-overlay" type="button" name="button"></div>
											<div id="unmute-button" >
												<img
													id="unmute-icon"
													src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/unmute.png' ); ?>"
													alt=""
												/>
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
									if ( has_shortcode( $webinar_data->replay_video, 'video' ) ) :
										$GLOBALS['content_width'] = 1225;// see /wp-includes/media.php::wp_video_shortcode();
										endif;

										webinarignition_display(
											do_shortcode( $webinar_data->replay_video ),
											'<img src="' . $assets . '/images/videoplaceholder.png" />'
										);
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

							<?php
							$is_cta_aside            = false;
							$is_cta_overlay          = false;
							$webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );
							if ( ! empty( $webinar_cta_by_position['outer'] ) ) {
								$is_cta_aside = true;
							}
							if ( ! empty( $webinar_cta_by_position['overlay'] ) ) {
								$is_cta_overlay = true;
							}
							?>

						<?php
						if ( 'AUTO' === $webinar_data->webinar_date ) {
							?>
							<?php
							if ( $is_cta_overlay ) {
								?>

								<style>
									.ctaArea {
										position: relative;
									}

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
										// if ( ! empty( $webinar_data->cta_transparancy ) ) {
										// 	$cta_transparancy = (int) $webinar_data->cta_transparancy;

										// 	if ( 0 < $cta_transparancy ) {
										// 		if ( 100 < $cta_transparancy ) {
										// 			$cta_transparancy = 100;
										// 		}

										// 		$cta_transparancy = 100 - $cta_transparancy;
										// 		?>
										 		/* background-color: rgba(255, 255, 255, <?php 
												// echo absint( $cta_transparancy ) / 100; ?>);
										 		<?php
										// 	}
										// }
										?>
									}

									.timedUnderArea.timedUnderAreaOverlay, .additional_autoaction_item.active {
										position: relative;
										left:0;
									}

									@media only screen and (max-width : 992px) {
										.timedUnderArea.timedUnderAreaOverlay, .additional_autoaction_item {
											height: auto;
											max-height: none !important;
										}
									}
								</style>
								<?php
								include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-overlay-cta-area.php';
							}//end if
							?>
						<?php } else { ?>

							<?php

							if (
								WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data )
								&& isset( $webinar_data->cta_position )
								&& 'overlay' === $webinar_data->cta_position
							) {
								?>
								<style>
									.ctaArea {
										position: relative;
									}

									.timedUnderArea:after {
										display: none;
									}

									.timedUnderArea, .additional_autoaction_item {
										position: absolute;
										height: auto;
										max-height: 98%;
										left: 0;
										bottom: 0;
										right: 0;
										overflow: auto;
									<?php
									// if ( ! empty( $webinar_data->cta_transparancy ) ) {
									// 	$cta_transparancy = (int) $webinar_data->cta_transparancy;

									// 	if ( 0 < $cta_transparancy ) {
									// 		if ( 100 < $cta_transparancy ) {
									// 			$cta_transparancy = 100;
									// 		}

									// 		$cta_transparancy = 100 - $cta_transparancy;
									// 		?>
									// 	background-color: rgba(255, 255, 255, <?php 
									// echo absint( $cta_transparancy ) / 100; ?>);
									// 		<?php
									// 	}
									// }
									?>
									}
									@media only screen and (max-width : 768px) {
										.timedUnderArea, .additional_autoaction_item {
											position: static;
											height: auto;
											max-height: none!important;}
									}
								</style>
								<?php
							}//end if
							if ( empty( $webinar_data->replay_timed_style ) || 'button' === $webinar_data->replay_timed_style ) {
								?>
								<a href="<?php webinarignition_display( $webinar_data->replay_order_url, '#' ); ?>" target="_blank" id="orderBTN"
									class="large radius button success addedArrow replayOrder"
									style="background-color: 
									<?php
									webinarignition_display(
										! empty( $webinar_data->replay_order_color ) ? $webinar_data->replay_order_color : '',
										'#6BBA40'
									);
									?>
									; border: 1px solid rgba(0,0,0,0.20); display:none;">
									<?php
									webinarignition_display(
										! empty( $webinar_data->replay_order_copy ) ? $webinar_data->replay_order_copy : '',
										__( 'Order Your Copy Now!', 'webinar-ignition' )
									);
									?>
									</a>
								<?php
							} else {
								?>
								<div class="timedUnderArea" id="orderBTN" style="display: none;">
									<?php webinarignition_display( $webinar_data->replay_order_html, __( 'Custom HTML Here...', 'webinar-ignition' ) ); ?>
								</div>
								<?php
							}//end if
							?>

							<?php
						}//end if
						?>



						</div>
						<?php
						if ( $is_cta_aside ) {
							?>
							<div class="ctaAreaOuter" style="padding-bottom: 3px;">
								<?php include WEBINARIGNITION_PATH . 'inc/lp/partials/auto-cta-area.php'; ?>
							</div>
							<?php
						}
						?>
					</div>

					<!-- WEBINAR UNDER EXTRA CTA AREA -->
					<div class="webinarUnderArea">
						<!-- WEBINAR BLOCK RIGHT -->
						<div class="webinarBlockRight" style="padding-top: 35px;">
							<!-- WEBINAR INFO BLOCK -->
							<?php webinarignition_get_replay_info( $webinar_data, true ); ?>

							<!-- GIVE AWAY BLOCK -->
							<?php webinarignition_get_replay_giveaway( $webinar_data, true ); ?>

						</div>

						<div class="webinarExtraBlock" style="margin-top: 30px; display:none;">
							<div id="askQArea" style="display:none;">
								<?php
								webinarignition_display(
									$webinar_data->webinar_qa_title,
									'<h4 style="margin-top: -5px;">' . __( 'Got A Question?', 'webinar-ignition' ) . '</h4>
                                     <h5 class="subheader" style="margin-top: -15px;">' . __( 'Submit your question, and we can answer it live on air...', 'webinar-ignition' ) . '</h5>'
								);
								?>

								<?php
								if ( 'custom' === trim($webinar_data->webinar_qa) ) {
									webinarignition_display( $webinar_data->webinar_qa_custom, __( 'CUSTOM Q/A SYSTEM WILL DISPLAY HERE... NO CODE ENTERED...', 'webinar-ignition' ) );
									?>
							</div>
									<?php
								} elseif ( 'hide' === trim($webinar_data->webinar_qa) ) {
									echo '</div>';
								} else {
									?>

							<textarea id="question"
										placeholder="<?php webinarignition_display( $webinar_data->webinar_qa_desc_placeholder, __( 'Ask Your Question Here...', 'webinar-ignition' ) ); ?>"
										style="height: 80px;"></textarea>
							<a href="#" id="askQuestion" class="button"
								style="border: 1px solid rgba(0,0,0,0.10); background-color: 
									<?php
									webinarignition_display(
										$webinar_data->webinar_qa_button_color,
										'#3E8FC7'
									);
									?>
								;"><?php webinarignition_display( $webinar_data->webinar_qa_button, __( 'Submit Your Question', 'webinar-ignition' ) ); ?></a>
						</div>

						<div id="askQThankyou" style="display:none;">
									<?php
									webinarignition_display(
										$webinar_data->webinar_qa_thankyou,
										'<h4>' . __( 'Thank You For Your Question!', 'webinar-ignition' ) . "</h4><h5 class='subheader' style='margin-top: -15px;'>" . __( 'The question block will refresh in 15 seconds...', 'webinar-ignition' ) . '</h5>'
									);
									?>
						</div>
									<?php
								}//end if
								?>

					</div>

				</div>
			<?php
		}//end if
		?>
		</div>

		<br clear="left"/>

</div>

</div>

<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>


<div id="fb-root"></div>

<?php wp_footer(); ?>

<?php webinarignition_footer($webinar_data); ?>
<?php restore_previous_locale(); ?>
</body>
</html>
