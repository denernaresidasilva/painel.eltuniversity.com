<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
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
		if (empty($webinar_data->meta_site_title_ty)) {
			webinarignition_display($webinar_data->lp_metashare_title, __('Amazing Webinar', 'webinar-ignition'));
		} else {
			echo esc_html($webinar_data->meta_site_title_ty);
		}
		?>
	</title>
	<meta name="description" content="
	<?php
	if (empty($webinar_data->meta_desc_ty)) {
		webinarignition_display($webinar_data->lp_metashare_desc, __('Join this amazing webinar, and discover industry trade secrets!', 'webinar-ignition'));
	} else {
		echo esc_html($webinar_data->meta_desc_ty);
	}
	?>
	">

	<?php
	if (!empty($webinar_data->ty_share_image)) {
	?>
		<meta property="og:image" content="<?php webinarignition_display($webinar_data->ty_share_image, ''); ?>" /><?php } ?>

	<?php wp_head(); ?>

	<?php require 'css/ty_css.php'; ?>

	<?php
	/**
	 * @var obj $webinar_data The webinar_data.
	 */
	do_action('webinarignition_thankyou_cp_page_header', $webinar_data);
	?>
</head>

<body class="thankyou_cp_preview" id="webinarignition">

	<!-- TOP AREA -->
	<div class="topArea">
		<div class="bannerTop container">
			<?php
			if (!empty($webinar_data->lp_banner_image)) {
				printf('<img src="%s" />', esc_url($webinar_data->lp_banner_image));
			}
			?>
		</div>
	</div>

	<!-- Main Area -->
	<div class="mainWrapper">
		<!-- HEADLINE AREAA -->
		<div class="headlineArea tyheadlineArea">
			<div class="wiContainer container">
				<div class="tyHeadlineIcon">
					<i class="icon-check-sign icon-4x" style="color: #6a9f37;"></i>
				</div>

				<div class="tyHeadlineCopy">
					<div class="optinHeadline1 wiOptinHeadline1">
						<?php
						webinarignition_display(
							$webinar_data->ty_ticket_headline,
							__('Congratulations! You are signed up!', 'webinar-ignition')
						);
						?>
					</div>
					<div class="optinHeadline2 wiOptinHeadline2">
						<?php
						webinarignition_display(
							$webinar_data->ty_ticket_subheadline,
							__('Below is all the information you need for the webinar...', 'webinar-ignition')
						)
						?>
					</div>
				</div>

				<br clear="left" />

			</div>
			<!-- /.headlineArea .container-->
		</div>
		<!-- /.headlineArea -->

		<!-- MAIN AREA -->
		<div class="cpWrapperWrapper">
			<div class="wiContainer container">
				<div class="row">
					<div class="cpWrapper">
						<div class="cpLeftSide col-md-6">
							<div class="ticketWrapper">
								<div class="eventDate ticketSectionNew ts">


									<div class="dateIcon">
										
									</div>

									<div class="dateInfo">
										<div class="dateHeadline"><?php esc_html_e('Date chosen will be here with day name', 'webinar-ignition'); ?></div>
										<div class="dateSubHeadline"><?php esc_html_e('@ Time chosen local time ', 'webinar-ignition'); ?></div>
									</div>

									<br clear="left">
								</div>

								<div class="ticketInfo">

									<div class="ticketInfoNew">

										<div class="ticketSection ticketSectionNew ts">
											<?php
											if ('custom' === $webinar_data->ty_ticket_webinar_option) {
											?>
												<div class="ticketInfoIcon">
													<i class="icon-desktop icon-3x"></i>
												</div>
												<div class="ticketInfoCopy">
													<b><?php webinarignition_display($webinar_data->ty_ticket_webinar, __('Webinar', 'webinar-ignition')); ?></b>

													<div class="ticketInfoNewHeadline">
														<?php
														webinarignition_display(
															$webinar_data->ty_webinar_option_custom_title,
															__('Webinar Event Title', 'webinar-ignition')
														);
														?>
													</div>
												</div>
												<br clear="left" />
											<?php
											} else {
											?>
												<div class="ticketInfoIcon">
													 <img src="<?php echo esc_url($assets . 'images/webinar-icon.png'); ?>" />
												</div>
												<div class="ticketInfoCopy">
													<p><?php esc_html_e('Webinar:', 'webinar-ignition'); ?></p>

													<div class="ticketInfoNewHeadline">
														<?php
														webinarignition_display(
															$webinar_data->webinar_desc,
															__('Webinar Event Title', 'webinar-ignition')
														);
														?>
													</div>
												</div>
												<br clear="left" />
											<?php } //end if 
											?>
										</div>

										<div class="ticketSection ticketSectionNew ts">
											<?php
											if ('custom' === $webinar_data->ty_ticket_host_option) {
											?>
												<div class="ticketInfoIcon2">
													<i class="icon-microphone icon-3x"></i>
												</div>
												<div class="ticketInfoCopy2">
													<b><?php webinarignition_display($webinar_data->ty_ticket_host, 'Host'); ?></b>

													<div class="ticketInfoNewHeadline"><?php webinarignition_display($webinar_data->ty_webinar_option_custom_host, __('Your Name Here', 'webinar-ignition')); ?></div>
												</div>
												<br clear="left" />
											<?php
											} else {
											?>
												<div class="ticketInfoIcon2">
												<img src="<?php echo esc_url($assets . 'images/host-mic.png'); ?>" />

												</div>
												<div class="ticketInfoCopy2">
													<p><?php esc_html_e('Host', 'webinar-ignition'); ?>:</p>

													<div class="ticketInfoNewHeadline"><?php webinarignition_display($webinar_data->webinar_host, __('Host name', 'webinar-ignition')); ?></div>
												</div>
												<br clear="left" />
											<?php } //end if 
											?>
										</div>

										<div class="ticketCDArea ticketSection ticketSectionNew">

											<a href="<?php echo esc_html(webinarignition_fixPerma($data->postID) . 'live'); ?>" class="ticketCDAreaBTN button alert radius disabled addedArrow" id="webinarBTNNN">
												<?php esc_html_e('Example Countdown button', 'webinar-ignition'); ?>
											</a>

										</div>


									</div>


									<div class="webinarURLArea">

										<div class="webinarURLHeadline">
											<i class="icon-bookmark" style="margin-right: 10px; color: #878787;"></i>
											<?php
											webinarignition_display(
												$webinar_data->ty_webinar_headline,
												__('Here Is Your Webinar Event URL...', 'webinar-ignition')
											);
											?>
										</div>

										<div class="webinarURLHeadline2">
											<?php
											webinarignition_display(
												$webinar_data->ty_webinar_subheadline,
												__('Save and bookmark this URL so you can get access to the live webinar and webinar replay...', 'webinar-ignition')
											);
											?>
										</div>
									</div>

								</div>

							</div>


						</div>

						<div class="cpRightSide col-md-6">
							<!-- VIDEO / CTA BLOCK AREA HERE -->
							<div class="ctaArea" <?php
													if ('html' === $webinar_data->ty_cta_type) {
														echo 'style="background-color:#FFF;"';
													}
													?>>



								<?php
								if ('video' === $webinar_data->ty_cta_type) {
									if (isset($webinar_data->ty_cta_video_url) && !empty($webinar_data->ty_cta_video_url)) {

										$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
								?>
										<style>
											#wi_ctaVideo {
												position: relative;
												width: 100%;
											}

											#wi_ctaVideoPlayer {
												height: 100%;
												overflow: hidden;
												border-radius: 10px;
											}

											#wi_ctaVideo>.wi_videoPlayerUnmute {
												position: absolute;
												width: 124px;

												margin-top: -22px;
												right: 10px;

												margin-left: -62px;
												z-index: 9999;
												display: none;
											}






											#wi_ctaVideo>.wi_videoPlayerMute {
												background: no-repeat;
												border: none;
												width: 10%;
												padding: 0 2% 1% 2%;
												position: absolute;
												bottom: 5px;
												right: 0;
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
											<button class="wi_arrow_button button wiButton wiButton-block wiButton-lg addedArrow wi_videoPlayerUnmute"><?php echo esc_html(apply_filters('wi_cta_video_unmute_text', esc_html__('Unmute', 'webinar-ignition'))); ?></button>
											<video id="wi_ctaVideoPlayer" class="video-js vjs-default-skin wi_videoPlayer" disablePictureInPicture oncontextmenu="return false;">
												<source src="<?php echo esc_url($webinar_data->ty_cta_video_url); ?>" type='video/mp4' />
											</video>
											<button class="wi_videoPlayerMute"><img src="<?php echo esc_url($assets . 'images/mute-red.svg'); ?>" /></button>
										</div>

										<div class="preview">
											<p>
												<?php esc_html_e('This is just a preview. The Real Thank You Page Depends On User Submited Dates - Do a Fake Optin For Real The Experience', 'webinar-ignition'); ?>
											</p>
										</div>


								<?php
									} else {
										webinarignition_display(
											do_shortcode($webinar_data->ty_cta_video_code),
											'<img src="' . $assets . 'images/novideo.png" />'
										);
									} //end if
								} elseif ('html' === $webinar_data->ty_cta_type || empty($webinar_data->ty_cta_type)) {
									webinarignition_display(
										$webinar_data->ty_cta_html,
										'<h3>' . __('See you at the webinar!', 'webinar-ignition') . '<br/> ' . '' . '</h3><p>' . __('An email is being sent to you with all the information. If you want more reminders for the event add the event date to your calendar...', 'webinar-ignition') . '</p>'
									);
								} elseif ('image' === $webinar_data->ty_cta_type) {
									echo "<img src='";
									webinarignition_display($webinar_data->ty_cta_image, $assets . 'images/noctaimage.png');
									echo "' height='215' width='287' />";
								} //end if
								?>
							</div>

							<div class="remindersBlock">

								<?php $wi_calendarOption = !empty($webinar_data->ty_add_to_calendar_option) ? $webinar_data->ty_add_to_calendar_option : 'enable'; ?>
								<?php if ('enable' === $wi_calendarOption) : ?>
									<div class="ticketSection ticketCalendarArea">
										<div class="optinHeadline12 wiOptinHeadline2">
											<?php
											webinarignition_display(
												$webinar_data->ty_calendar_headline,
												__('Add To Your Calendar', 'webinar-ignition')
											);
											?>
										</div>

										<!-- AUTO CODE BLOCK AREA -->
										<?php if ('AUTO' === $webinar_data->webinar_date) { ?>
											<!-- AUTO DATE -->
											<div class="wi-btns-wrap">
												<a href="?googlecalendarA" class="small button" target="_blank">
													<i class="icon-google-plus"></i>
													<?php
													webinarignition_display(
														$webinar_data->ty_calendar_google,
														__('Google Calendar', 'webinar-ignition')
													);
													?>
												</a>
												<a href="?icsA" class="small button" target="_blank">
													<i class="icon-calendar"></i> <?php webinarignition_display($webinar_data->ty_calendar_ical, __('iCal / Outlook', 'webinar-ignition')); ?>
												</a>
											</div>
										<?php } else { ?>
											<div class="wi-btns-wrap">
												<a href="?googlecalendar" class="small button" target="_blank">
													<i class="icon-google-plus"></i>
													<?php
													webinarignition_display(
														$webinar_data->ty_calendar_google,
														__('Google Calendar', 'webinar-ignition')
													);
													?>
												</a>
												<a href="?ics" class="small button" target="_blank">
													<i class="icon-calendar"></i> <?php webinarignition_display($webinar_data->ty_calendar_ical, __('iCal / Outlook', 'webinar-ignition')); ?>
												</a>
											</div>
										<?php } //end if 
										?>
										<!-- END OF AUTO CODE BLOCK -->

									</div>
								<?php endif; ?>

							</div>


						</div>

						<br clear="both" />


						<div class="cpUnderHeadline" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? esc_html(webinarignition_display($webinar_data->ty_share_toggle, 'none')) : 'none'; ?>;">
							<?php
							webinarignition_display(
								isset($webinar_data->ty_step2_headline) ? $webinar_data->ty_step2_headline : '',
								__('Step #2: Share & Unlock Reward...', 'webinar-ignition')
							);
							?>
						</div>

						<div class="cpUnderCopy" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? esc_html(webinarignition_display($webinar_data->ty_share_toggle, 'none')) : 'none'; ?>;">

							<div class="cpCopyArea">
								<!-- SHARE BLOCK -->
								<div class="shareBlock wi-block--sharing" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? esc_html(webinarignition_display($webinar_data->ty_share_toggle, 'none')) : 'none'; ?>;">

									<?php
									if (isset($webinar_data->ty_fb_share) && 'off' !== $webinar_data->ty_fb_share) {
									?>
										<div class="socialShare">
											<!-- <div class="fb-like" data-href="<?php // echo esc_url(get_permalink($data->postID)); ?>" data-send="false" data-layout="box_count" data-width="48" data-show-faces="false" data-font="arial"></div> -->
										</div>
										<div class="socialDivider"></div>
									<?php } ?>

									<br clear="left" />

								</div>

								<!-- SHARE REWARD - UNLOCK -->
								<div class="shareReward" style="display:<?php echo isset($webinar_data->ty_share_toggle) ? esc_html(webinarignition_display($webinar_data->ty_share_toggle, 'none')) : 'none'; ?>;">
									<div class="sharePRE">
										<?php
										webinarignition_display(
											isset($webinar_data->ty_share_intro) ? $webinar_data->ty_share_intro : '',
											'<h4>' . __('Share This Webinar & Unlock Free Report', 'webinar-ignition') . '</h4>
							<p>' . __('Simply share the webinar on any of the social networks above, and you will get instant access to this reporcss..', 'webinar-ignition') . '</p>'
										);
										?>
									</div>
									<div class="shareREVEAL" style="display: none;">
										<?php
										webinarignition_display(
											isset($webinar_data->ty_share_reveal) ? $webinar_data->ty_share_reveal : '',
											'<h4>' . __('Congrats! Reward Unlocked', 'webinar-ignition') . '</h4>
							<p>' . __('Here is the text that would be shown when they unlock a reward...', 'webinar-ignition') . '</p>'
										);
										?>
									</div>
								</div>
							</div>

							<div class="cpCopyTY">
								<!-- ADD TO CALENDARS -->
								<div class="addCalendar" style="display:none;">

									<div class="addCalendarHeadline">
										<i class="icon-calendar icon-4x ticketIcon"></i>

										<?php if (!empty($webinar_data->ty_calendar_headline)) : ?>
											<span class="optinHeadline1 wiOptinHeadline1"><?php webinarignition_display($webinar_data->ty_calendar_headline, __('Add To Your Calendar', 'webinar-ignition')); ?></span>
										<?php endif; ?>

										<?php if (!empty($webinar_data->ty_calendar_subheadline)) : ?>
											<span class="optinHeadline2 wiOptinHeadline2"><?php webinarignition_display($webinar_data->ty_calendar_subheadline, __('Remind Yourself Of The Event', 'webinar-ignition')); ?></span>
										<?php endif; 
										restore_previous_locale();

										?>

										<br clear="left" />
									</div>

								</div>


							</div>

							<br clear="all" />

						</div>

					</div>

				</div>


			</div>

			<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>
<script>
	
var playerRoot = document.getElementById( 'wi_ctaVideoPlayer' );

if ( playerRoot ) {
	var wi_ctaVideoPlayer = videojs("wi_ctaVideoPlayer", {
		fluid: true,
		playsinline: true,
		muted: true,
		bigPlayButton: false,
		controls: false,
		controlBar: false
	});

	function wi_videojs_do_autoplay(player, muted, success_cb, error_cb) {

		player.muted(muted);

		var played_promise = player.play();

		if ( played_promise !== undefined ) {

			if( success_cb === null ) {
				success_cb = function() {}
			}

			if( error_cb === null ) {
				error_cb = function() {}
			}

			played_promise.then(success_cb).catch(error_cb);
		}
	}

	//Immediate autoplay stopped working in chrome,
	//Workaround: Play the video programmatically, few seconds after player is ready,
	// and detect if that fails then do autoplay in muted mode.
	wi_ctaVideoPlayer.ready(function() {
		setTimeout(function() {

			wi_ctaVideoPlayer.fluid('true')

			wi_videojs_do_autoplay(wi_ctaVideoPlayer, false, function() {
				jQuery('#wi_ctaVideo > .wi_videoPlayerMute').show();
			}, function(error) {
				console.log(error);
				wi_videojs_do_autoplay(wi_ctaVideoPlayer, true, function() {
					jQuery('#wi_ctaVideo > .wi_videoPlayerUnmute').show();
				}, function(error) {
					console.log(error);
				});
			});

		}, 500);
	});

	jQuery('#wi_ctaVideo > .wi_videoPlayerUnmute').click(function(e) {
		e.preventDefault();
		wi_ctaVideoPlayer.muted(false);
		jQuery(this).hide();
		jQuery('#wi_ctaVideo > .wi_videoPlayerMute').show();
	});

	jQuery('#wi_ctaVideo > .wi_videoPlayerMute').click(function(e) {
		e.preventDefault();
		wi_ctaVideoPlayer.muted(true);
		jQuery(this).hide();
		jQuery('#wi_ctaVideo > .wi_videoPlayerUnmute').show();
	});
}
</script>

</body>

</html>