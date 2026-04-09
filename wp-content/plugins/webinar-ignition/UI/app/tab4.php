<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
?>
<div class="tabber" id="tab4" style="display: none;">

<div class="titleBar">
	
	<div class="titleBarText">
		<h2><?php esc_html_e( 'Thank You Page Settings:', 'webinar-ignition' ); ?></h2>

		<p><?php esc_html_e( 'Here you can edit & manage your webinar registration thank you page...', 'webinar-ignition' ); ?></p>

	</div>

	<div class="launchConsole">
		<?php
		if ( $webinar_data->webinar_date == 'AUTO' ) {
			$preview_url = add_query_arg( array(
				'thankyou' => '',
				'lid' => '[lead_id]',
				'preview' => 'true',
			), get_the_permalink( $data->postID ) );
		} else {
			$preview_url = add_query_arg( array(
				'thankyou' => '',
				'lid' => '[lead_id]',
				'preview' => 'true',
			), get_the_permalink( $data->postID ) );
		}
		?>
		<a
				href="<?php echo esc_url( $preview_url ); ?>"
				target="_blank"
				data-default-href="<?php echo esc_url( $preview_url ); ?>"
				class="custom_thankyou_page-webinarPreviewLinkDefaultHolder"
		>
			<i class="icon-external-link-sign"></i>
			<?php esc_html_e( 'Preview Thank You Page', 'webinar-ignition' ); ?>
		</a>
	</div>

	<br clear="all"/>

	<?php
	$input_get = array(
		'id' => isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : null
	); 
	?>

</div>


<?php
	webinarignition_display_edit_toggle(
		'edit-sign',
		esc_html__( 'Thank You Page Actions', 'webinar-ignition' ),
		'we_edit_ty_actions',
		esc_html__( 'Enable/Disable Thank-You/Confirmation page', 'webinar-ignition' )
	);

	?>

	<div id="we_edit_ty_actions" class="we_edit_area">
		<?php

		if ( $webinar_data->webinar_date == 'AUTO' ) {

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->skip_ty_page,
					esc_html__( 'Skip Thank you page for future date webinars', 'webinar-ignition' ),
					'skip_ty_page',
					esc_html__( 'For future-date webinars, users will be sent to the Thank-You/Confirmation page after registration. You can disable this here...', 'webinar-ignition' ),
					esc_html__( 'disabled', 'webinar-ignition' ) . ' [no],' . esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes]'
				);

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->skip_instant_acces_confirm_page,
					esc_html__( 'Skip Thank you page for Instant Access webinars', 'webinar-ignition' ),
					'skip_instant_acces_confirm_page',
					esc_html__( 'By default, attendees will be automatically redirected to the webinar if they choose to watch the webinar instantly. You can disable this here..', 'webinar-ignition' ),
					esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
				);

		} else {

				webinarignition_display_option(
					$input_get['id'],
					$webinar_data->skip_ty_page,
					esc_html__( 'Skip Thank you page', 'webinar-ignition' ),
					'skip_ty_page',
					esc_html__( 'By default, attendees will be sent to the Thank-You/Confirmation page after registration. You can disable this here..', 'webinar-ignition' ),
					esc_html__( 'disabled', 'webinar-ignition' ) . ' [no],' . esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes]'
				);

		}//end if
		?>
	</div>

<?php

webinarignition_display_edit_toggle(
	'edit-sign',
	esc_html__( 'Thank You Headline', 'webinar-ignition' ),
	'we_edit_ty_headline',
	esc_html__( 'This appears above the thank you area...', 'webinar-ignition' )
);

?>

<div id="we_edit_ty_headline" class="we_edit_area">
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_headline', $webinar_data, true, true ); ?>
	<?php
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_ticket_headline,
		esc_html__( 'Main Headline', 'webinar-ignition' ),
		'ty_ticket_headline',
		esc_html__( 'This is the copy that is shown at the top of page...', 'webinar-ignition' ),
		esc_html__( 'e.g. Congrats - You Are Signed Up For The Event!', 'webinar-ignition' )
	);
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_ticket_subheadline,
		esc_html__( 'Ticket Sub Headline', 'webinar-ignition' ),
		'ty_ticket_subheadline',
		esc_html__( 'This is shown under the main headline...', 'webinar-ignition' ),
		esc_html__( 'e.g. Below Is Everything You Need For The Event...', 'webinar-ignition' )
	);
	?>
</div>

<?php

webinarignition_display_edit_toggle(
	'play-sign',
	esc_html__( 'Thank You Message Area - Copy / Video / Image Settings', 'webinar-ignition' ),
	'we_edit_ty_video',
	esc_html__( 'Setup your thank you message / video / image for when they opt in and are on the thank you page...', 'webinar-ignition' )
);

?>

<div id="we_edit_ty_video" class="we_edit_area">
	<?php
	webinarignition_display_color(
		$input_get['id'],
		isset( $webinar_data->ty_cta_bg_color ) ? $webinar_data->ty_cta_bg_color : '',
		esc_html__( 'CTA Area Background Color', 'webinar-ignition' ),
		'ty_cta_bg_color',
		esc_html__( 'This is the color for the CTA area that video or image is displayed, a good contrast color will get a lot of attention for this area...', 'webinar-ignition' ),
		'#000000'
	);
	webinarignition_display_option(
		$input_get['id'],
		$webinar_data->ty_cta_type,
		esc_html__( 'CTA Type:', 'webinar-ignition' ),
		'ty_cta_type',
		esc_html__( 'You can choose to display a video embed code or have an image to be shown here. A video will get higher results...', 'webinar-ignition' ),
		esc_html__( 'Show HTML', 'webinar-ignition' ) . ' [html],' . esc_html__( 'Show Video', 'webinar-ignition' ) . ' [video], ' . esc_html__( 'Show Image', 'webinar-ignition' ) . ' [image]'
	);
	?>
	<div class="ty_cta_type" id="ty_cta_type_video" style="display: none;">
		<?php
		webinarignition_display_field_add_media(
			$input_get['id'],
			isset( $webinar_data->ty_cta_video_url ) ? $webinar_data->ty_cta_video_url : '',
			esc_html__( 'Video URL .MP4 *', 'webinar-ignition' ),
			'ty_cta_video_url',
			esc_html__( 'The MP4 file that you want to play as your CTA... must be in .mp4 format as its uses a html5 video player...', 'webinar-ignition' ),
			esc_html__( 'Ex. http://yoursite.com/webinar-video.mp4', 'webinar-ignition' )
		);

		WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_message_area', $webinar_data, true, true );

		webinarignition_display_info(
			esc_html__( 'Note: Custom Video URL', 'webinar-ignition' ),
			esc_html__( 'The custom video url must be in .mp4 format as the player uses a html5 video player...', 'webinar-ignition' )
		);

		webinarignition_display_textarea(
			$input_get['id'],
			$webinar_data->ty_cta_video_code,
			esc_html__( 'Video Embed Code', 'webinar-ignition' ),
			'ty_cta_video_code',
			__( 'This is your video embed code. Your video will be auto-resized to fit the area which is <b>410px width and 231px height</b> <Br><br>EasyVideoPlayer users must resize their video manually...', 'webinar-ignition' ),
			esc_html__( 'e.g. Youtube embed code, Vimeo embed code, etc', 'webinar-ignition' )
		);

		webinarignition_display_info(
			esc_html__( 'Note: Video Size', 'webinar-ignition' ),
			esc_html__( 'The video will be auto-resized to fit the page at 410x231 - make sure your video is a similiar aspect ratio...', 'webinar-ignition' )
		);
		?>
	</div>

	<div class="ty_cta_type" id="ty_cta_type_image" style="display: none;">
		<?php
		webinarignition_display_field_image_upd(
			$input_get['id'],
			$webinar_data->ty_cta_image,
			esc_html__( 'CTA Image URL', 'webinar-ignition' ),
			'ty_cta_image',
			__( 'This is the image that will be shown in the main cta area, this image will be resized to fit the area: <strong>500px width and 281px height</strong>...', 'webinar-ignition' ),
			esc_html__( 'http://yoursite.com/cta-image.png', 'webinar-ignition' )
		);
		?>
	</div>

	<div class="ty_cta_type" id="ty_cta_type_html">
		<?php
		webinarignition_display_wpeditor(
			$input_get['id'],
			$webinar_data->ty_cta_html,
			esc_html__( 'CTA HTML Copy', 'webinar-ignition' ),
			'ty_cta_html',
			esc_html__( 'This is the copy that is shown on the right of the event ticket area...', 'webinar-ignition' )
		);
		?>
	</div>

</div>

<?php

webinarignition_display_edit_toggle(
	'link',
	esc_html__( 'Grab Webinar URL', 'webinar-ignition' ),
	'we_edit_ty_step1',
	esc_html__( 'This is the area for the webinar URL...', 'webinar-ignition' )
);

?>

<div id="we_edit_ty_step1" class="we_edit_area">
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_webinar_url', $webinar_data, true, true ); ?>
	<?php
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_webinar_headline,
		esc_html__( 'Webinar URL Title Copy', 'webinar-ignition' ),
		'ty_webinar_headline',
		esc_html__( 'This is the the title for the webinar URL which appears above the webinar URL form field...', 'webinar-ignition' ),
		esc_html__( 'e.g. Here is the webinar URL...', 'webinar-ignition' )
	);
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_webinar_subheadline,
		esc_html__( 'Webinar URL Sub Title Copy', 'webinar-ignition' ),
		'ty_webinar_subheadline',
		esc_html__( 'This is the sub title that is shown UNDER the webinar url form field...', 'webinar-ignition' ),
		esc_html__( 'e.g. Save and bookmark this URL so you can get access to the live webinar and webinar replay...', 'webinar-ignition' )
	);
	if ( $webinar_data->webinar_date == 'AUTO' ) {
	} else {
		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->ty_webinar_url,
			esc_html__( 'Webinar URL', 'webinar-ignition' ),
			'ty_webinar_url',
			esc_html__( 'The webinar URL type, you can either display the webinar link for this webinar OR if you want to use your own live webinar page, you can enter in a custom URL...', 'webinar-ignition' ),
			esc_html__( 'WebinarIgnition URL', 'webinar-ignition' ) . ' [we],' . esc_html__( 'Custom Webinar URL', 'webinar-ignition' ) . ' [custom]'
		);
		?>

		<div style="display: none;" class="ty_webinar_url" id="ty_webinar_url_custom">
			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->ty_werbinar_custom_url,
				esc_html__( 'Custom Webinar URL', 'webinar-ignition' ),
				'ty_werbinar_custom_url',
				esc_html__( 'This is the url where your webinar will be viewable... This is only if you want to use your own webinar page with another service...', 'webinar-ignition' ),
				esc_html__( 'e.g. http://yoursite.com/webinar-page.php', 'webinar-ignition' )
			);
			?>
		</div>
	<?php }//end if
	?>

</div>

<?php
// webinarignition_display_edit_toggle(
// 	'twitter-sign',
// 	esc_html__( 'Share & Unlock Gift', 'webinar-ignition' ),
// 	'we_edit_ty_share',
// 	esc_html__( 'This is the headline area for above the share / social unlock area...', 'webinar-ignition' )
// );
?>

<!-- <div id="we_edit_ty_share" class="we_edit_area">
	<?php // WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_share_gift', $webinar_data, true, true ); ?>
	<?php
	// webinarignition_display_option(
	// 	$input_get['id'],
	// 	$webinar_data->ty_share_toggle,
	// 	esc_html__( 'Share Unlock Settings', 'webinar-ignition' ),
	// 	'ty_share_toggle',
	// 	esc_html__( 'Here you can have it where you give a reward for sharing the webinar link...', 'webinar-ignition' ),
	// 	esc_html__( 'Disable Share Unlock', 'webinar-ignition' ) . ' [none],' . esc_html__( 'Enable Share Unlock', 'webinar-ignition' ) . ' [block]'
	// );

	// webinarignition_display_info(
	// 	esc_html__( 'Note: Share Title & Description', 'webinar-ignition' ),
	// 	esc_html__( 'The share title and description are the landing page META info which can be found in the registration page settings area...', 'webinar-ignition' )
	// );

	?>
	<div class="ty_share_toggle" id="ty_share_toggle_block">
		<?php
		// webinarignition_display_field(
		// 	$input_get['id'],
		// 	$webinar_data->ty_step2_headline,
		// 	esc_html__( 'Step #2 Headline Copy', 'webinar-ignition' ),
		// 	'ty_step2_headline',
		// 	esc_html__( 'This is the copy that is shown above the sharing / unlock options...', 'webinar-ignition' ),
		// 	esc_html__( 'e.g. Step #2: Share & Unlock Free Gift...', 'webinar-ignition' )
		// );
		// webinarignition_display_option(
		// 	$input_get['id'],
		// 	$webinar_data->ty_tw_share,
		// 	esc_html__( 'Twitter Share', 'webinar-ignition' ),
		// 	'ty_tw_share',
		// 	esc_html__( 'You can turn on or off the Twiter like area...', 'webinar-ignition' ),
		// 	esc_html__( 'Enable', 'webinar-ignition' ) . ' [on],' . esc_html__( 'Disable', 'webinar-ignition' ) . ' [off]'
		// );

		// webinarignition_display_wpeditor(
		// 	$input_get['id'],
		// 	$webinar_data->ty_share_intro,
		// 	esc_html__( 'Pre-Share Copy', 'webinar-ignition' ),
		// 	'ty_share_intro',
		// 	esc_html__( 'This is the copy that is shown under the share options before they share to unlock the reward...', 'webinar-ignition' )
		// );
		// webinarignition_display_wpeditor(
		// 	$input_get['id'],
		// 	$webinar_data->ty_share_reveal,
		// 	esc_html__( 'Post-Share Reveal Copy', 'webinar-ignition' ),
		// 	'ty_share_reveal',
		// 	esc_html__( 'This is the copy that is shown after they share on one of the social networks, best to have your download link to the free offer here...', 'webinar-ignition' )
		// );
		?>
	</div>

</div> -->

<?php

webinarignition_display_edit_toggle(
	'ticket',
	esc_html__( 'Ticket / Webinar Info Block', 'webinar-ignition' ),
	'we_edit_ty_ticket',
	esc_html__( 'This is a block for the webinar information, quick snap shot...', 'webinar-ignition' )
);

?>

<div id="we_edit_ty_ticket" class="we_edit_area">
	<?php
	webinarignition_display_option(
		$input_get['id'],
		$webinar_data->ty_ticket_webinar_option,
		esc_html__( 'Webinar Event Title', 'webinar-ignition' ),
		'ty_ticket_webinar_option',
		esc_html__( 'This is the webinar event title, you can use the webinar settings, or use custom event title...', 'webinar-ignition' ),
		esc_html__( 'Use Webinar Settings', 'webinar-ignition' ) . ' [webinar],' . esc_html__( 'Custom Webinar Copy', 'webinar-ignition' ) . ' [custom]'
	);
	?>
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_ticket_webinar', $webinar_data, true, true ); ?>
	<div style="display:none;" class="ty_ticket_webinar_option" id="ty_ticket_webinar_option_custom">
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->ty_ticket_webinar,
			esc_html__( 'Webinar', 'webinar-ignition' ),
			'ty_ticket_webinar',
			esc_html__( "This is the text for the Webinar text (for translating), leave blank if you don't need to translate this...", 'webinar-ignition' ),
			esc_html__( 'e.g. Webinar', 'webinar-ignition' )
		);
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->ty_webinar_option_custom_title,
			esc_html__( 'Custom Webinar Title', 'webinar-ignition' ),
			'ty_webinar_option_custom_title',
			esc_html__( 'This is shown next to the webinar copy, this is a custom event title...', 'webinar-ignition' ),
			esc_html__( 'e.g. Super Awesome Webinar...', 'webinar-ignition' )
		);
		?>
	</div>
	<?php
	webinarignition_display_option(
		$input_get['id'],
		$webinar_data->ty_ticket_host_option,
		esc_html__( 'Host Title', 'webinar-ignition' ),
		'ty_ticket_host_option',
		esc_html__( 'This is the host title, you can use the webinar settings, or use custom host title...', 'webinar-ignition' ),
		esc_html__( 'Use Webinar Settings', 'webinar-ignition' ) . ' [webinar],' . esc_html__( 'Custom Host Copy', 'webinar-ignition' ) . ' [custom]'
	);
	?>
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_ticket_host', $webinar_data, true, true ); ?>
	<div class="ty_ticket_host_option" id="ty_ticket_host_option_custom" style="display: none;">
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->ty_ticket_host,
			esc_html__( 'Host', 'webinar-ignition' ),
			'ty_ticket_host',
			esc_html__( "This is the text for the Host text (for translating), leave blank if you don't need to translate this...", 'webinar-ignition' ),
			esc_html__( 'e.g. Host', 'webinar-ignition' )
		);
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->ty_webinar_option_custom_host,
			esc_html__( 'Custom Webinar Host', 'webinar-ignition' ),
			'ty_webinar_option_custom_host',
			esc_html__( 'This is shown next to the host copy, this is a custom host title...', 'webinar-ignition' ),
			esc_html__( 'e.g. Mike Smith', 'webinar-ignition' )
		);
		?>
	</div>

	<?php

	webinarignition_display_option(
		$input_get['id'],
		$webinar_data->ty_ticket_date_option,
		esc_html__( 'Date Title', 'webinar-ignition' ),
		'ty_ticket_date_option',
		esc_html__( 'This is the date, you can use the webinar settings, or use custom date...', 'webinar-ignition' ),
		esc_html__( 'Use Webinar Settings', 'webinar-ignition' ) . ' [webinar],' . esc_html__( 'Custom Date Copy', 'webinar-ignition' ) . ' [custom]'
	);

	?>
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_ticket_date', $webinar_data, true, true ); ?>
	<div class="ty_ticket_date_option" id="ty_ticket_date_option_custom" style="display: none;">
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->ty_ticket_date,
			esc_html__( 'Date', 'webinar-ignition' ),
			'ty_ticket_date',
			esc_html__( "This is the text for the date text (for translating), leave blank if you don't need to translate this...", 'webinar-ignition' ),
			esc_html__( 'e.g. Date', 'webinar-ignition' )
		);
		if ( $webinar_data->webinar_date != 'AUTO' ) {

			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->ty_webinar_option_custom_date,
				esc_html__( 'Custom Webinar Date', 'webinar-ignition' ),
				'ty_webinar_option_custom_date',
				esc_html__( 'This is shown next to the date copy, this is a custom Date...', 'webinar-ignition' ),
				esc_html__( 'e.g. May 4th', 'webinar-ignition' )
			);

		}
		?>
	</div>

	<?php
	webinarignition_display_option(
		$input_get['id'],
		$webinar_data->ty_ticket_time_option,
		esc_html__( 'Time Title', 'webinar-ignition' ),
		'ty_ticket_time_option',
		esc_html__( 'This is the time, you can use the webinar settings, or use custom time...', 'webinar-ignition' ),
		esc_html__( 'Use Webinar Settings', 'webinar-ignition' ) . ' [webinar],' . esc_html__( 'Custom Time Copy', 'webinar-ignition' ) . ' [custom]'
	);
	?>
	<div class="ty_ticket_time_option" id="ty_ticket_time_option_custom" style="display: none;">
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->ty_ticket_time,
			esc_html__( 'Time', 'webinar-ignition' ),
			'ty_ticket_time',
			esc_html__( "This is the text for the time text (for translating), leave blank if you don't need to translate this...", 'webinar-ignition' ),
			esc_html__( 'e.g. Date', 'webinar-ignition' )
		);
		if ( $webinar_data->webinar_date != 'AUTO' ) {

			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->ty_webinar_option_custom_time,
				esc_html__( 'Custom Webinar Time', 'webinar-ignition' ),
				'ty_webinar_option_custom_time',
				esc_html__( 'This is shown next to the time copy, this is a custom time...', 'webinar-ignition' ),
				esc_html__( 'e.g. At 4pm, EST time...', 'webinar-ignition' )
			);

		}
		?>
	</div>
</div>

<?php
webinarignition_display_edit_toggle(
	'time',
	esc_html__( 'Mini Countdown Area', 'webinar-ignition' ),
	'we_edit_ty_cdarea',
	esc_html__( 'This is the mini countdown area that displays in the ticket area...', 'webinar-ignition' )
);
?>

<div id="we_edit_ty_cdarea" class="we_edit_area">
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_countdown', $webinar_data, true, true ); ?>

	<div class="tycdarea" id="tycdarea_show">
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->tycd_countdown,
			esc_html__( 'Counting Down Copy', 'webinar-ignition' ),
			'tycd_countdown',
			esc_html__( 'This is the copy display above the countdown timer...', 'webinar-ignition' ),
			esc_html__( 'e.g. Webinar Starts In:', 'webinar-ignition' )
		);

		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->tycd_progress,
			esc_html__( 'View Webinar Button', 'webinar-ignition' ),
			'tycd_progress',
			esc_html__( 'This is the copy that is shown on the button when the countdown is down to zero, and the button links to the webinar...', 'webinar-ignition' ),
			esc_html__( 'e.g. Webinar In Progress', 'webinar-ignition' )
		);

		// translation of coompact labels for countdown, used in compact mode
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->tycd_years,
			esc_html__( 'Translate::Years', 'webinar-ignition' ),
			'tycd_years',
			esc_html__( 'Label used to describe years in countdown compact mode.', 'webinar-ignition' ),
			esc_html__( 'Default: y', 'webinar-ignition' )
		);
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->tycd_months,
			esc_html__( 'Translate::Months', 'webinar-ignition' ),
			'tycd_months',
			esc_html__( 'Label used to describe months in countdown compact mode.', 'webinar-ignition' ),
			esc_html__( 'Default: m', 'webinar-ignition' )
		);
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->tycd_weeks,
			esc_html__( 'Translate::Weeks', 'webinar-ignition' ),
			'tycd_weeks',
			esc_html__( 'Label used to describe weeks in countdown compact mode.', 'webinar-ignition' ),
			esc_html__( 'Default: w', 'webinar-ignition' )
		);
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->tycd_days,
			esc_html__( 'Translate::Days', 'webinar-ignition' ),
			'tycd_days',
			esc_html__( 'Label used to describe days in countdown compact mode.', 'webinar-ignition' ),
			esc_html__( 'Default: d', 'webinar-ignition' )
		);
		?>
	</div>

</div>

<?php

webinarignition_display_edit_toggle(
	'calendar',
	esc_html__( 'Add To Calendar Block', 'webinar-ignition' ),
	'we_edit_ty_addtocalendar',
	esc_html__( 'This is for the for the buttons to add the webinar to their calendars...', 'webinar-ignition' )
);

?>

<div id="we_edit_ty_addtocalendar" class="we_edit_area">
	<?php WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_calendar_reminder', $webinar_data, true, true ); ?>

	<?php
	webinarignition_display_option(
		$input_get['id'],
		$webinar_data->ty_add_to_calendar_option,
		esc_html__( 'Display Add To Calendar Block', 'webinar-ignition' ),
		'ty_add_to_calendar_option',
		esc_html__( 'Decide whether or not to display the Add To Calendar Option on the Thank You page', 'webinar-ignition' ),
		esc_html__( 'enabled', 'webinar-ignition' ) . ' [enable],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [disable]'
	);

	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_calendar_headline,
		esc_html__( 'Add To Calendar Headline', 'webinar-ignition' ),
		'ty_calendar_headline',
		esc_html__( 'This is the headline for the add to calendar area...', 'webinar-ignition' ),
		esc_html__( 'e.g. Add To Calendar', 'webinar-ignition' )
	);

	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_calendar_google,
		esc_html__( 'Google Calendar Button Copy', 'webinar-ignition' ),
		'ty_calendar_google',
		esc_html__( 'This is the copy for the Google Calendar button...', 'webinar-ignition' ),
		esc_html__( 'e.g. Add To Google Calendar', 'webinar-ignition' )
	);
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->ty_calendar_ical,
		esc_html__( 'iCal / Outlook Button Copy', 'webinar-ignition' ),
		'ty_calendar_ical',
		esc_html__( 'This is the copy for the outlook / ical button, downloads an ICS file...', 'webinar-ignition' ),
		esc_html__( 'e.g. Add To iCal / Outlook', 'webinar-ignition' )
	);

	?>
</div>


<?php
// webinarignition_display_edit_toggle(
// 	'mobile-phone',
// 	esc_html__( 'TXT Reminder Area', 'webinar-ignition' ),
// 	'we_edit_ty_twilio',
// 	esc_html__( 'Edit the copy and settings for the TXT reminder area on the thank you page...', 'webinar-ignition' )
// );

?>

<!-- <div id="we_edit_ty_twilio" class="we_edit_area">
	<?php // WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description( 'ty_sms_reminder', $webinar_data, true, true ); ?>
	<?php
	// webinarignition_display_option(
	// 	$input_get['id'],
	// 	! empty( $webinar_data->txt_area ) ? $webinar_data->txt_area : 'off',
	// 	esc_html__( 'Toggle TXT Notification', 'webinar-ignition' ),
	// 	'txt_area',
	// 	esc_html__( 'This is wether you want to enable the TXT reminder (w/ Twilio)...', 'webinar-ignition' ),
	// 	esc_html__( 'Show TXT Reminder Area', 'webinar-ignition' ) . ' [on],' . esc_html__( 'Hide TXT Reminder Area', 'webinar-ignition' ) . ' [off]'
	// );
	?>
	<div class="txt_area" id="txt_area_on">
		<?php
		// webinarignition_display_field(
		// 	$input_get['id'],
		// 	$webinar_data->txt_headline,
		// 	esc_html__( 'Reminder TXT Headline', 'webinar-ignition' ),
		// 	'txt_headline',
		// 	esc_html__( 'This is the main headline for the TXT reminder area...', 'webinar-ignition' ),
		// 	esc_html__( 'e.g. Get A SMS Reminder', 'webinar-ignition' )
		// );
		// webinarignition_display_field(
		// 	$input_get['id'],
		// 	$webinar_data->txt_placeholder,
		// 	esc_html__( 'Phone Number Input Placeholder', 'webinar-ignition' ),
		// 	'txt_placeholder',
		// 	esc_html__( 'This is the placeholder text for the form they enter in their phone number...', 'webinar-ignition' ),
		// 	esc_html__( 'e.g. Enter In Your Mobile Phone Number...', 'webinar-ignition' )
		// );
		// webinarignition_display_field(
		// 	$input_get['id'],
		// 	$webinar_data->txt_btn,
		// 	esc_html__( 'Remind Button Copy', 'webinar-ignition' ),
		// 	'txt_btn',
		// 	esc_html__( 'This is the copy that is shown on the reminder button...', 'webinar-ignition' ),
		// 	esc_html__( 'e.g. Get Text Message Reminder', 'webinar-ignition' )
		// );
		// webinarignition_display_textarea(
		// 	$input_get['id'],
		// 	$webinar_data->txt_reveal,
		// 	esc_html__( 'Thank You Copy', 'webinar-ignition' ),
		// 	'txt_reveal',
		// 	esc_html__( 'This is the copy that is shown once they submit their phone number...', 'webinar-ignition' ),
		// 	esc_html__( 'e.g. Thanks! You will get the reminder one hour before the webinar...', 'webinar-ignition' )
		// );
		?>
	</div>
</div> -->

<div class="bottomSaveArea">
	<a href="#" class="blue-btn-44 btn saveBTN saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php esc_html_e( 'Save & Update', 'webinar-ignition' ); ?></a>
</div>

</div>
<?php
restore_previous_locale();