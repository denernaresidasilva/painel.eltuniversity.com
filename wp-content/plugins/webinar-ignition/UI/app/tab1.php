<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
$webinar_duration = ( property_exists( $webinar_data, 'webinar_start_duration' ) && isset( $webinar_data->webinar_start_duration ) ? $webinar_data->webinar_start_duration : 60 );
?>

<div class="tabber wi-tab-one" id="tab1">

	<div class="titleBar">
		<div class="titleBarText">
			<h2><?php 
esc_html_e( 'Dashboard - Your Webinar Settings', 'webinar-ignition' );
?></h2>

			<p><?php 
esc_html_e( 'In the console, you will find your leads, questions, call-to-actions (live only) ...', 'webinar-ignition' );
?></p>
		</div>

		<div class="launchConsole">
			<a href="<?php 
$console_link = webinarignition_fixPerma( $data->postID );
if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
    $console_link = str_replace( 'http://', 'https://', $console_link );
}
echo esc_url( $console_link );
?>console#/dashboard" target="_blank"><i
					class="icon-external-link-sign"></i> <?php 
esc_html_e( 'Show Live Console', 'webinar-ignition' );
?></a>
		</div>

		<br clear="all" />
	</div>

	<!-- NEW AREA TOP -->

	<div class="weDashLeft wi-webinar-detail-left">

		<?php 
// Evergreen Check
if ( $webinar_data->webinar_date == 'AUTO' ) {
    // Evergreen
} else {
    ?>

			<div class="weDashWebinarTitle wi-dash-webinar-title">

				<div class="dashWebinarTitleIcon"><i class="icon-play-sign icon-3x"></i></div>

				<div class="dashWebinarTitleCopy">
					<h2 style="color:#FFF !important;"><?php 
    esc_html_e( 'Webinar Master Switch:', 'webinar-ignition' );
    ?></h2>

					<p><?php 
    esc_html_e( 'Toggle the event / webinar status (instantly without saving)', 'webinar-ignition' );
    ?></p>
				</div>

				<br clear="left" />

			</div>

			<div class="weDashWebinarInner">
				<div class="webinarURLArea">
					<div class="webinarURLAreaStatus">
						<ul class="webinarStatusGroup">
							<li><a href="#" class="webinarStatus webinarStatusFirst 
							<?php 
    if ( $webinar_data->webinar_switch == 'countdown' || $webinar_data->webinar_switch == '' ) {
        echo 'webinarStatusSelected';
    }
    ?>" data="countdown"><i class="icon-time"></i> <?php 
    esc_html_e( 'Countdown', 'webinar-ignition' );
    ?></a></li>
							<li><a href="#" class="webinarStatus 
							<?php 
    if ( $webinar_data->webinar_switch == 'live' ) {
        echo 'webinarStatusSelected';
    }
    ?>" data="live"><i class="icon-microphone"></i> <?php 
    esc_html_e( 'Live', 'webinar-ignition' );
    ?></a></li>
							<li><a href="#" class="webinarStatus 
							<?php 
    if ( $webinar_data->webinar_switch == 'replay' ) {
        echo 'webinarStatusSelected';
    }
    ?>" data="replay"><i class="icon-refresh"></i> <?php 
    esc_html_e( 'Replay', 'webinar-ignition' );
    ?></a></li>
							<li><a href="#" class="webinarStatus webinarStatusEnd 
							<?php 
    if ( $webinar_data->webinar_switch == 'closed' ) {
        echo 'webinarStatusSelected';
    }
    ?>" data="closed"><i class="icon-lock"></i> <?php 
    esc_html_e( 'Closed', 'webinar-ignition' );
    ?></a></li>
							<input type="hidden" name="webinar_switch" id="webinar_switch"
								value="<?php 
    echo esc_html( $webinar_data->webinar_switch );
    ?>">
							<br clear="left" />
						</ul>
					</div>
				</div>
			</div>
		<?php 
}
//end if
?>

		<div class="weDashWebinarTitle">
			<div class="dashWebinarTitleIcon"><i class="icon-share-sign icon-3x"></i></div>
			<div class="dashWebinarTitleCopy">
				<h2 style="color:#FFF !important;"><?php 
esc_html_e( 'Your Webinar URL', 'webinar-ignition' );
?></h2>

				<p>
					<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    esc_html_e( 'This is the URL for your live webinar you can share with your audience.', 'webinar-ignition' );
} else {
    esc_html_e( 'This is the URL for your evergreen webinar you can share with your audience.', 'webinar-ignition' );
}
?>
				</p>
			</div>

			<br clear="left" />
		</div>

		<div class="weDashWebinarInner wi-dash-webinar-inner">
			<div class="wi-input-wrap">
				<p class="wi-title"><?php 
esc_html_e( 'Registration Page URL:', 'webinar-ignition' );
?></p>
				<input
					id="custom_registration_page-shareUrl-1"

					onclick="this.select()"
					type="text"
					class="inputField inputFieldDash wi-input"
					data-default-value="<?php 
echo esc_url( get_permalink( $data->postID ) );
?>"
					value="<?php 
esc_html_e( 'Loading URL...', 'webinar-ignition' );
?>"
					readonly>
			</div>
			<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    ?>
				<div class="wi-input-wrap">
					<p class="wi-title"><?php 
    esc_html_e( 'Autofill Registration URL:', 'webinar-ignition' );
    ?></p>
					<p class="wi-desc"><?php 
    esc_html_e( 'Use the link in emails. Replace NAME/EMAIL with placeholder e.g. %Subscriber:CustomFieldFirstName% %Subscriber:EmailAddress%', 'webinar-ignition' );
    ?></p>
					<input onclick="this.select()" type="text" id="auto_registration_page-shareUrl-1" class="inputField inputFieldDash wi-input"
						value="<?php 
    echo esc_url( webinarignition_fixPerma( $data->postID ) . 'register-now&n=NAME&e=EMAIL&readonly=true' );
    ?>">
				</div>
			<?php 
} else {
    ?>
				<div class="wi-input-wrap">
					<p class="wi-title"><?php 
    esc_html_e( 'One Click Registration URL:', 'webinar-ignition' );
    ?></p>
					<p class="wi-desc"><?php 
    esc_html_e( 'Use the link in emails. Replace NAME/EMAIL with placeholder e.g. %Subscriber:CustomFieldFirstName% %Subscriber:EmailAddress%', 'webinar-ignition' );
    ?></p>
					<input onclick="this.select()" type="text" id="auto_registration_page-shareUrl-2" class="inputField inputFieldDash wi-input"
						value="<?php 
    echo esc_url( webinarignition_fixPerma( $data->postID ) );
    ?>register-now&n=NAME&e=EMAIL&readonly=true&login=true">
				</div>
			<?php 
}
?>

			<?php 
$host_presenters_url = WebinarignitionManager::webinarignition_get_host_presenters_url( $ID );
?>
			<?php 
$support_stuff_url = WebinarignitionManager::webinarignition_get_support_stuff_url( $ID );
?>
			<input type="hidden" name="host_presenters_url" value="<?php 
echo esc_url( $host_presenters_url );
?>">
			<input type="hidden" name="support_stuff_url" value="<?php 
echo esc_url( $support_stuff_url );
?>">
		</div>

		<div class="statsLabelx" style="text-align:right; padding-top:15px;">
			<?php 
esc_html_e( 'Total Views', 'webinar-ignition' );
?> / <b><?php 
esc_html_e( 'Unique Views', 'webinar-ignition' );
?></b>
		</div>
		<?php 
// Get Total & Uniques
$getTotal_lp = $data->total_lp;
$getTotal_lp = explode( '%%', $getTotal_lp );
$registration_preview_url = add_query_arg( array(
    'preview' => 'true',
), get_the_permalink( $data->postID ) );
?>

		<a
			href="<?php 
echo esc_url( $registration_preview_url );
?>"
			target="_blank"
			data-default-href="<?php 
echo esc_url( $registration_preview_url );
?>"
			class="custom_registration_page-webinarPreviewLinkDefaultHolder-1 wi-color-dark">
				<div class="webinarPreviewItem webinarPreviewItemTop " style="<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    echo 'margin-top:0px;';
}
?>">

				<div class="webinarPreviewIcon"><i class="icon-calendar icon-2x"></i></div>
				<div class="webinarPreviewTitle">

					<i class="icon-external-link"></i>
					<?php 
esc_html_e( 'View Registration Page', 'webinar-ignition' );
?>

				</div>
				<!-- <div class="webinarPreviewStat"><span class="dashViews" >Total: </span> <?php 
if ( $getTotal_lp[1] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_lp[1] );
}
?> <span class="dashViews" >Uniques:</span> <?php 
if ( $getTotal_lp[0] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_lp[0] );
}
?> </div> -->
				<div class="webinarPreviewStat"><span style="font-weight: normal;"><?php 
if ( $getTotal_lp[1] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_lp[1] );
}
?> / </span> <?php 
if ( $getTotal_lp[0] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_lp[0] );
}
?> </div>
				<br clear="both" />
			</div>
		</a>



		<?php 
// Get Total & Uniques
$getTotal_ty = $data->total_ty;
$getTotal_ty = explode( '%%', $getTotal_ty );
$thank_you_preview_url = add_query_arg( array(
    'thankyou' => '',
    'lid'      => '[lead_id]',
    'preview'  => 'true',
), get_the_permalink( $data->postID ) );
?>

		<a href="<?php 
echo esc_url( $thank_you_preview_url );
?>" target="_blank" data-default-href="<?php 
echo esc_url( $thank_you_preview_url );
?>" class="custom_thankyou_page-webinarPreviewLinkDefaultHolder wi-color-dark">
			<div class="webinarPreviewItem">

				<div class="webinarPreviewIcon"><i class="icon-copy icon-2x"></i></div>
				<div class="webinarPreviewTitle">

					<i class="icon-external-link"></i>
					<?php 
esc_html_e( 'View Thank You Page', 'webinar-ignition' );
?>

				</div>
				<!-- <div class="webinarPreviewStat"><span class="dashViews" >Total: </span> <?php 
if ( $getTotal_ty[1] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_ty[1] );
}
?> <span class="dashViews" >Uniques:</span> <?php 
if ( $getTotal_ty[0] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_ty[0] );
}
?> </div> -->
				<div class="webinarPreviewStat"><span style="font-weight: normal;"><?php 
if ( $getTotal_ty[1] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_ty[1] );
}
?> / </span> <?php 
if ( $getTotal_ty[0] == '' ) {
    echo '0';
} else {
    echo esc_html( $getTotal_ty[0] );
}
?> </div>
				<br clear="both" />
			</div>
		</a>



		<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    // Evergreen
} else {
    $countdown_preview_url = add_query_arg( array(
        'countdown' => '',
        'lid'       => '[lead_id]',
        'preview'   => 'true',
    ), get_the_permalink( $data->postID ) );
    ?>
			<a
				href="<?php 
    echo esc_url( $countdown_preview_url );
    ?>"
				target="_blank"
				data-default-href="<?php 
    echo esc_url( $countdown_preview_url );
    ?>"
				class="custom_countdown_page-webinarPreviewLinkDefaultHolder">

				<div class="webinarPreviewItem">
					<div class="webinarPreviewIcon"><i class="icon-time icon-2x"></i></div>
					<div class="webinarPreviewTitle">

						<i class="icon-external-link"></i>
						<?php 
    esc_html_e( 'Preview Countdown Page', 'webinar-ignition' );
    ?>

					</div>

					<br clear="both" />
				</div>
			</a>

		<?php 
}
//end if
?>





		<?php 
// Get Total & Uniques
$getTotal_live = $data->total_live;
$getTotal_live = explode( '%%', $getTotal_live );
$webinar_preview_url = add_query_arg( array(
    'webinar' => '',
    'lid'     => '[lead_id]',
    'preview' => 'true',
), get_the_permalink( $data->postID ) );
?>

		<a
			href="<?php 
echo esc_url( $webinar_preview_url );
?>"
			target="_blank"
			data-default-href="<?php 
echo esc_url( $webinar_preview_url );
?>"
			class="custom_webinar_page-webinarPreviewLinkDefaultHolder">
			<div class="webinarPreviewItem wi-color-dark">

				<div class="webinarPreviewIcon"><i class="icon-microphone icon-2x"></i></div>
				<div class="webinarPreviewTitle">

					<i class="icon-external-link"></i>
					<?php 
esc_html_e( 'Preview Webinar Page', 'webinar-ignition' );
?>

				</div>
				<br clear="both" />
			</div>

		</a>



		<?php 
// Get Total & Uniques
$getTotal_replay = $data->total_replay;
$getTotal_replay = explode( '%%', $getTotal_replay );
$replay_preview_url = add_query_arg( array(
    'replay'  => '',
    'lid'     => '[lead_id]',
    'preview' => 'true',
), get_the_permalink( $data->postID ) );
?>
		<a
			href="<?php 
echo esc_url( $replay_preview_url );
?>"
			target="_blank"
			data-default-href="<?php 
echo esc_url( $replay_preview_url );
?>"
			class="custom_replay_page-webinarPreviewLinkDefaultHolder wi-color-dark">
			<div class="webinarPreviewItem webinarPreviewItemBottom">

				<div class="webinarPreviewIcon"><i class="icon-film icon-2x"></i></div>
				<div class="webinarPreviewTitle">

					<i class="icon-external-link"></i>
					<?php 
esc_html_e( 'Preview Replay Page', 'webinar-ignition' );
?>
				</div>
				<br clear="both" />
			</div>
		</a>


		<div class="timezoneRef" style="<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    echo 'display:none;';
}
?>">
			<div class="timezoneRefTitle"><b><?php 
esc_html_e( 'REFERENCE', 'webinar-ignition' );
?></b> :: <?php 
esc_html_e( 'Current Time:', 'webinar-ignition' );
?> <span class="timezoneRefZ"></span></div>
		</div>

		<?php 
if ( $webinar_data->webinar_date == 'AUTO' ) {
    ?>

			<div class="timezoneRef">
				<b><?php 
    esc_html_e( 'Notice:', 'webinar-ignition' );
    ?></b> <?php 
    esc_html_e( 'The previews above are for the Thank You Page, Webinar & Replay are just previews. They change depending  on the time & date chosen by the lead...', 'webinar-ignition' );
    ?>
			</div>

		<?php 
}
?>

	</div>

	<div class="weDashRight wi-dash-right">

		<div class="weDashDateTitle">
			<!-- <i class="icon-ticket"></i> Webinar Event Info: -->
			<div class="dashWebinarTitleIcon"><i class="icon-ticket icon-3x"></i></div>

			<div class="dashWebinarTitleCopy">
				<h2 style="margin:0px; margin-top: 3px;"><?php 
esc_html_e( 'Webinar Event Info', 'webinar-ignition' );
?></h2>

				<p style="margin:0px; margin-top: 3px;"><?php 
esc_html_e( 'The core settings for your webinar event...', 'webinar-ignition' );
?></p>
			</div>

			<br clear="left" />
		</div>

		<div class="weDashDateInner">

			<div class="weDashSection">
				<span class="weDashSectionTitle"><?php 
esc_html_e( 'Webinar Title', 'webinar-ignition' );
?>
					<span class="weDashSectionIcon"><i class="icon-desktop"></i></span>
				</span>

				<br clear="right" />
				<input type="text" class="inputField inputFieldDash elem" name="webinar_desc" id="webinar_desc" value="<?php 
echo esc_attr( $webinar_data->webinar_desc );
?>" />
			</div>

			<?php 
if ( !empty( $webinar_data->webinar_lang ) ) {
    require_once ABSPATH . 'wp-admin/includes/translation-install.php';
    $languages = wp_get_available_translations();
    $webinar_lang = ( $webinar_data->webinar_lang === 'en_US' ? 'English' : $languages[$webinar_data->webinar_lang]['native_name'] );
    $webinar_lang_auto_set = false;
    if ( isset( $webinar_data->id ) && !empty( $webinar_data->id ) ) {
        $webinar_lang_auto_set = get_option( "webinarignition_lang_auto_set_{$webinar_data->id}", false );
    }
    ?>
				<div class="weDashSection">
					<span class="weDashSectionTitle"><?php 
    esc_html_e( 'Webinar Language', 'webinar-ignition' );
    ?>
						<span class="weDashSectionIcon"><i class="icon-desktop"></i></span>
					</span>

					<br clear="right" />
					<div class="inputField inputFieldDash" style="display: block;width: 100%;background-color: #f0f0f1;padding: 0 8px;min-height: 30px;border-radius: 4px;"><?php 
    echo esc_html( $webinar_lang );
    ?> <?php 
    echo ( $webinar_lang_auto_set ? '<span>(<a href="https://webinarignition.tawk.help/article/auto-set-webinar-language-for-webinars-created-before-version-290" target="_blank">' . esc_html__( 'auto set', 'webinar-ignition' ) . '</a>)</span>' : '' );
    ?></div>
					<input type="hidden" class="inputField inputFieldDash elem" readonly name="webinar_lang" id="webinar_lang" value="<?php 
    echo esc_attr( $webinar_data->webinar_lang );
    ?>" />
				</div>
			<?php 
}
//end if
?>

			<div class="weDashSection">
				<span class="weDashSectionTitle"><?php 
esc_html_e( 'Webinar Host(s)', 'webinar-ignition' );
?>
					<span class="weDashSectionIcon"><i class="icon-user"></i></span>
				</span>
				<br clear="right" />
				<input type="text" class="inputField inputFieldDash elem" name="webinar_host" id="webinar_host"
					value="<?php 
echo esc_html( $webinar_data->webinar_host );
?>" />
			</div>

			<?php 
// Evergreen Check
if ( $webinar_data->webinar_date == 'AUTO' ) {
    // Evergreen
    ?>
				<input type="hidden" class="inputField inputFieldDash elem" name="webinar_date" id="webinar_date"
					value="<?php 
    echo esc_html( $webinar_data->webinar_date );
    ?>" />
			<?php 
} else {
    ?>

				<div class="weDashSection">
					<span class="weDashSectionTitle"><?php 
    esc_html_e( 'Event Date', 'webinar-ignition' );
    ?>
						<span class="weDashSectionIcon"><i class="icon-calendar"></i></span>
					</span>
					<br clear="right" />
					<input type="text" class="inputField inputFieldDash elem dp-date" name="webinar_date" id="webinar_date" value="<?php 
    echo esc_html( webinarignition_get_live_date( $webinar_data ) );
    ?>" />
					<!-- Hidden input field -->
					<input type="hidden" name="notify_current_user" id="notify_current_user" value="<?php 
    echo esc_html( ( isset( $webinar_data->notify_current_user ) ? $webinar_data->notify_current_user : 'no' ) );
    ?>">
					<!-- Hidden input field for previous_date -->
					<input type="hidden" name="wi_live_previous_date" id="wi_live_previous_date" value="<?php 
    echo esc_html( ( isset( $webinar_data->wi_live_previous_date ) ? $webinar_data->wi_live_previous_date : "" ) );
    ?>">
					<!-- Hidden input field for id -->
					<input type="hidden" name="wi_webinar_id_resend_mail" id="wi_webinar_id_resend_mail" value="<?php 
    echo esc_html( ( isset( $webinar_data->id ) ? $webinar_data->id : "" ) );
    ?>">
					<!-- Hidden input field for timestamp -->
					<input type="hidden" name="wi_compare_timestamp" id="wi_compare_timestamp" value="">

				</div>
				<div class="weDashSection">
					<span class="weDashSectionTitle"><?php 
    esc_html_e( 'Event Time', 'webinar-ignition' );
    ?>
						<span class="weDashSectionIcon"><i class="icon-time"></i></span>
					</span>
					<br clear="right" />
					<input type="text" class="timepicker inputField inputFieldDash elem" 
					name="webinar_start_time" 
					id="webinar_start_time" 
					value="<?php 
    echo esc_html( webinarignition_get_localized_time( $webinar_data->webinar_start_time, $webinar_data ) );
    ?>"
					data-time-format="<?php 
    echo ( $webinar_data->time_format === 'H:i' ? 'H' : 'false' );
    ?>" />
				</div>
				<div class="weDashSection">
					<span class="weDashSectionTitle"><?php 
    esc_html_e( 'Event Duration', 'webinar-ignition' );
    ?>
						<span class="weDashSectionIcon"><i class="icon-time"></i></span>
					</span>
					<br clear="right" />
					<input type="number" class=" inputField inputFieldDash elem" 
					name="webinar_start_duration" 
					id="webinar_start_duration" 
					value="<?php 
    echo esc_html( $webinar_duration );
    ?>"
					data-time-format="<?php 
    echo ( $webinar_data->time_format === 'H:i' ? 'H' : 'false' );
    ?>" />
				</div>

				<div class="weDashSection">
					<span class="weDashSectionTitle"><?php 
    esc_html_e( 'Event Timezone', 'webinar-ignition' );
    ?>
						<span class="weDashSectionIcon"><i class="icon-globe"></i></span>
					</span>
					<br clear="right" />
					<?php 
    $webinarTZ = ( isset( $webinar_data->webinar_timezone ) ? webinarignition_convert_utc_to_tzid( $webinar_data->webinar_timezone ) : '' );
    ?>

					<select name="webinar_timezone" id="webinar_timezone" class="wi_webinar_timezone inputField inputFieldDash elem webinar_timezone_b">
						<?php 
    // Generate the timezone list
    $timezone_options = webinarignition_create_tz_select_list( $webinarTZ, get_user_locale() );
    // Sanitize the output
    echo wp_kses( $timezone_options, array(
        'optgroup' => array(
            'label' => array(),
        ),
        'option'   => array(
            'value'    => array(),
            'selected' => array(),
        ),
    ) );
    ?>
					</select>

				</div>


			<?php 
}
//end if
?>
			<?php 
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
$shortcode_string_old = "[wi_webinar id=\"{$ID}\"]";
$shortcode_string_link_old = sprintf( ' %s <a href="https://webinarignition.tawk.help/article/shortcode-sign-up-widget-wi_webinar-id105-outdated" target="_blank">%s</a>', esc_html__( 'Outdated', 'webinar-ignition' ), esc_html__( 'Read more', 'webinar-ignition' ) );
$shortcode_string = $shortcode_string_new = '[wi_webinar_block id="' . $ID . '" block="reg_optin_section" background_color="example: transparent, #000" contrast_color="example: #fff"]';
$shortcode_string_link = '';
$is_eg_webinar = strtolower( $webinar_data->webinar_date ) === 'auto';
$shortcode_string_link = sprintf( ' <a href="https://webinarignition.tawk.help/article/create-your-own-designed-webinar-landing-pages_webinar-registration-pages" target="_blank">%s</a>', esc_html__( 'Read more', 'webinar-ignition' ) );
?>

			<!-- NEW SHORTCODE -->
			<div class="weDashSection">
				<span class="weDashSectionTitle"><?php 
echo esc_html_e( 'Registration Shortcode', 'webinar-ignition' );
?>

					<span class="weDashSectionIcon"><i class="icon-code"></i></span>
				</span>

				<br clear="right">
				<p class="code-example">
					<span class="code-example-value"><?php 
echo esc_attr( $shortcode_string );
?></span>
					<span class="code-example-copy"><?php 
echo esc_html__( 'copy', 'webinar-ignition' );
?></span>
					<span class="code-example-copied"><?php 
echo esc_html__( 'Copied. Input into your content!', 'webinar-ignition' );
?></span>
				</p>
				<span style="float:right;"><?php 
echo wp_kses_post( $shortcode_string_link );
?>&nbsp;</span>

				<div style="clear:both; margin-top: 15px">
					<?php 
$pages = get_posts( array(
    'numberposts' => -1,
    'orderby'     => 'post_title',
    'order'       => 'ASC',
    'post_type'   => 'page',
) );
$param_webinar_id = ( WebinarignitionManager::webinarignition_is_webinar_public( $webinar_data ) ? $webinar_data->id : $webinar_data->hash_id );
$default_registration_page_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_data->id );
if ( !empty( $pages ) ) {
    $selected_page_id = ( isset( $webinar_data->custom_registration_page ) && get_post_type( $webinar_data->default_registration_page ) == 'page' ? (array) $webinar_data->custom_registration_page : array($default_registration_page_id) );
    $selected_page_id = ( is_array( $selected_page_id ) ? array_unique( array_filter( $selected_page_id ) ) : $selected_page_id );
    $selected_page_links = array();
    $saved_registration_page = ( empty( $webinar_data->default_registration_page ) || get_post_type( $webinar_data->default_registration_page ) != 'page' ? absint( $default_registration_page_id ) : absint( $webinar_data->default_registration_page ) );
    $selected = '';
    $i_class = '';
    if ( empty( $selected_page_id ) ) {
        $selected_page_id[] = $default_registration_page_id;
    }
    if ( !in_array( $saved_registration_page, $selected_page_id ) ) {
        $saved_registration_page = reset( $selected_page_id );
    }
    foreach ( (array) $selected_page_id as $index => $page_id ) {
        if ( $saved_registration_page == $page_id ) {
            $selected = 'checked';
            $i_class = 'icon-circle';
        } else {
            $selected = '';
            $i_class = 'icon-circle-blank';
        }
        $selected_page_links[] = sprintf(
            '<div class="wi_webinar_preview_box wi_webinar_preview_box_%d %s"><input data-page_url="%s" name="default_registration_page" class="default_registration_page" value="%d" type="radio" %s><i class="icon %s"></i>%s<a href="%s" target="_blank" class="wi_page_link"><i class="icon-external-link"></i> %s</a></div>',
            $page_id,
            $selected,
            get_permalink( $page_id ),
            $page_id,
            $selected,
            $i_class,
            get_the_title( $page_id ),
            get_permalink( $page_id ),
            esc_html__( 'Preview', 'webinar-ignition' )
        );
    }
    if ( !empty( $selected_page_links ) ) {
        ?>
							<div class="wi_selected_pages_links_container">
								<p>
									<?php 
        echo esc_html__( 'If you are using multiple registration pages, select the default registration page below.', 'webinar-ignition' );
        ?>
								</p>
								<div class="wi_selected_pages_links">
									<?php 
        foreach ( $selected_page_links as $link ) {
            echo wp_kses( $link, array(
                'div'   => array(
                    'class' => array(),
                ),
                'input' => array(
                    'data-page_url' => array(),
                    'name'          => array(),
                    'class'         => array(),
                    'value'         => array(),
                    'type'          => array(),
                    'checked'       => true,
                ),
                'i'     => array(
                    'class' => array(),
                ),
                'a'     => array(
                    'href'   => array(),
                    'target' => array(),
                    'class'  => array(),
                ),
            ) );
        }
        ?>
								</div>
							</div>
						<?php 
    }
    ?>
						<?php 
    usort( $pages, function ( $a, $b ) {
        return (int) $b->ID - (int) $a->ID;
    } );
    ?>
						<p><?php 
    esc_html_e( 'If you are using the shortcode, select the page where you are using it. The default registration page will be replaced!', 'webinar-ignition' );
    ?></p>
						<select id="custom_registration_page_1" name="custom_registration_page[]" class="inputField multiSelectField elem" multiple>
							<option value=""><?php 
    esc_html_e( '-- Select Registration Page(s) --', 'webinar-ignition' );
    ?></option>
							<?php 
    $paid_code = ( isset( $webinar_data->paid_code ) ? $webinar_data->paid_code : '' );
    foreach ( $pages as $page ) {
        $page_url = add_query_arg( 'webinar', $param_webinar_id, get_the_permalink( $page->ID ) );
        $page_thank_you_url = add_query_arg( $paid_code, '', get_the_permalink( $page->ID ) );
        $data_params = array();
        foreach ( array('url', 'public-url', 'protected-url') as $data_type ) {
            $data_params[] = 'data-' . $data_type . '="' . $page_url . '"';
        }
        $data_params[] = 'data-paid-thank-you-url="' . $page_thank_you_url . '"';
        ?>
								<?php 
        if ( in_array( $page->ID, $selected_page_id ) ) {
            ?>
									<option
										value="<?php 
            echo esc_attr( $page->ID );
            ?>"
										<?php 
            echo esc_attr( implode( ' ', $data_params ) );
            ?>
										<?php 
            selected( in_array( $page->ID, $selected_page_id ), true );
            ?>
									>
										<?php 
            echo esc_attr( $page->ID );
            ?> - <?php 
            echo esc_attr( $page->post_title );
            ?>
									</option>
								<?php 
        } else {
            ?>
									<option value="<?php 
            echo esc_attr( $page->ID );
            ?>" <?php 
            echo esc_attr( implode( ' ', $data_params ) );
            ?>><?php 
            echo esc_attr( $page->ID );
            ?> - <?php 
            echo esc_attr( $page->post_title );
            ?></option>
								<?php 
        }
        ?>
							<?php 
    }
    ?>
						</select>
					<?php 
}
//end if
?>

				</div>
			</div>

		</div>


	</div>

	<br clear="left" />

	<!-- NEW AREA END -->


	<div style="" class="wi-bottom-btns-wrap">
		<!--
								<div class="statsDashbord" style="display:none;" >

												<div class="statsDashBlock">
																<div class="statsDashBlockNumber"><?php 
if ( $data->total_lp == '' ) {
    echo '0';
} else {
    echo esc_html( $data->total_lp );
}
?></div>
																<div class="statsDashBlockTag">landing page</div>
												</div>

												<div class="statsDashBlock">
																<div class="statsDashBlockNumber"><?php 
if ( $data->total_ty == '' ) {
    echo '0';
} else {
    echo esc_html( $data->total_ty );
}
?></div>
																<div class="statsDashBlockTag">thank you page</div>
												</div>

												<div class="statsDashBlock">
																<div class="statsDashBlockNumber"><?php 
if ( $data->total_live == '' ) {
    echo '0';
} else {
    echo esc_html( $data->total_live );
}
?></div>
																<div class="statsDashBlockTag">live webinar</div>
												</div>

												<div class="statsDashBlock">
																<div class="statsDashBlockNumber"><?php 
if ( $data->total_replay == '' ) {
    echo '0';
} else {
    echo esc_html( $data->total_replay );
}
?></div>
																<div class="statsDashBlockTag">webinar replay</div>
												</div>

												<br clear="left" />

								</div>

								<br clear="left" /> -->

		<div class="editableSectionHeading2" style="display:none;">

			<?php 
// Display Leads For This App
$getVersion = 'webinarignition_leads';
$table_db_name = $wpdb->prefix . $getVersion;
$ID = ( isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : null );
// Sanitize input values
$ID = intval( $ID );
// Ensure $ID is an integer
// Prepare and execute the queries
$leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d", $ID ), OBJECT );
$leads2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d", $ID ), ARRAY_A );
$totalLeads = count( $leads2 );
?>

			<div class="editableSectionTitle">
				<i class="icon-user"></i>
				<?php 
esc_html_e( 'Manage Your Leads', 'webinar-ignition' );
?> ( <?php 
esc_html_e( 'Total Leads:', 'webinar-ignition' );
?> <?php 
echo esc_html( $totalLeads );
?> )
			</div>

			<div class="editableSectionToggle">
				<!-- <i class="toggleIcon  icon-chevron-down "></i> -->
			</div>

			<br clear="all" />

		</div>

		<div class="leads" style="clear: both; display:none;">
			<table id="leads" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th><i class="icon-user" style="margin-right: 5px;"></i><?php 
esc_html_e( 'Full Name', 'webinar-ignition' );
?></th>
						<th><i class="icon-envelope-alt" style="margin-right: 5px;"></i><?php 
esc_html_e( 'Email Address', 'webinar-ignition' );
?></th>
						<th><i class="icon-mobile-phone" style="margin-right: 5px;"></i><?php 
esc_html_e( 'Phone', 'webinar-ignition' );
?></th>
						<th><i class="icon-calendar" style="margin-right: 5px;"></i><?php 
esc_html_e( 'Sign Up Date', 'webinar-ignition' );
?></th>
						<th width="70"><i class="icon-trash" style="margin-right: 5px;"></i> <?php 
esc_html_e( 'delete', 'webinar-ignition' );
?></th>
					</tr>
				</thead>
				<tbody>

					<?php 
foreach ( $leads as $leads ) {
    ?>
						<tr id="table_lead_<?php 
    echo esc_attr( $leads->ID );
    ?>">
							<td><?php 
    echo esc_html( $leads->name );
    ?></td>
							<td><?php 
    echo esc_html( $leads->email );
    ?></td>
							<td><?php 
    echo esc_html( $leads->phone );
    ?></td>
							<td><?php 
    echo esc_html( $leads->created );
    ?></td>
							<td>
								<center><i class="icon-remove delete_lead" lead_id="<?php 
    echo esc_attr( $leads->ID );
    ?>"></i></center>
							</td>
						</tr>
					<?php 
}
?>

				</tbody>
			</table>
		</div>

	</div>

	<br clear="all" />

	<div class="wi-bottom-btns-wrap" style="border-top: 1px dotted #e2e2e2; padding-top: 15px; margin-top: 25px; ">
		<span style="float: right;" id="deleteCampaign"
			data-nonce="<?php 
echo esc_attr( wp_create_nonce( 'wi_delete_campaign_' . sanitize_text_field( wp_unslash( $_GET['id'] ) ) ) );
?>" class="grey-btn"><i
				class="icon-trash" style="margin-right: 5px;"></i> <?php 
esc_html_e( 'Delete This Campaign', 'webinar-ignition' );
?></span>
		
		
				<a
				href="#TB_inline?width=637&height=550&inlineId=export-campaign" class="thickbox wi-color-dark">

				<span style="float: left;" id="exportCampaign" class="grey-btn"><i class="icon-magic"
					style="margin-right: 5px;"></i>
				<?php 
esc_html_e( 'Export Campaign', 'webinar-ignition' );
?></span></a>



		<a
			href="#" id="resetStats" class="wi-color-dark">

			<span style="float: right; " id="resetStats2" class="grey-btn wi-mr-15"><i class="icon-bar-chart"
					style="margin-right: 5px;"></i> <?php 
esc_html_e( 'Reset View Stats', 'webinar-ignition' );
?></span></a>
		<br clear="right" />
	</div>

	<!-- Export Modal -->
	<?php 
add_thickbox();
?>
	<div id="export-campaign" style="display:none;">
		<p style="font-weight: bold; font-size: 18px;"><?php 
esc_html_e( 'Export Campaign Code:', 'webinar-ignition' );
?></p>

		<p style="margin-top:-25px;"><?php 
esc_html_e( 'Copy & paste this code to the target website: Open the WebinarIgniton Dashboard, click the "Create a new webinar" button, select "Import campaign" from the drop-down menu, paste the code and click "Create new webinar".', 'webinar-ignition' );
?></p>
		<textarea onclick="this.select()"
			style="width:100%; height:250px;"><?php 
echo esc_attr( base64_encode( wp_json_encode( $webinar_data ) ) );
?></textarea>
	</div>


</div>