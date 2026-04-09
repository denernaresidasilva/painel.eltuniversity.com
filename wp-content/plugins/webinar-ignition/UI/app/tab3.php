<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
$settings_language = ( isset( $webinar_data->settings_language ) ? $webinar_data->settings_language : '' );
if ( !empty( $settings_language ) ) {
    switch_to_locale( $settings_language );
    unload_textdomain( 'webinar-ignition' );
    load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $settings_language . '.mo' );
}
?>
<div class="tabber wi-tab-register" id="tab3" style="display: none;">

	<div class="titleBar">

		<div class="titleBarText">
			<h2><?php 
esc_html_e( 'Landing Page Settings:', 'webinar-ignition' );
?></h2>
			<p><?php 
esc_html_e( 'Here you can edit & manage your webinar registration page...', 'webinar-ignition' );
?></p>
		</div>

		<?php 
$registration_preview_url = add_query_arg( array(
    'preview' => 'true',
), esc_url( get_the_permalink( $data->postID ) ) );
?>

		<div class="launchConsole">
			<a
					href="<?php 
echo esc_url( $registration_preview_url );
?>"
					target="_blank"
					data-default-href="<?php 
echo esc_url( $registration_preview_url );
?>"
					class="custom_registration_page-webinarPreviewLinkDefaultHolder-1"
			>
				<i class="icon-external-link-sign"></i>
				<?php 
esc_html_e( 'Preview Registration Page', 'webinar-ignition' );
?>
			</a>
		</div>

		<br clear="all"/>

		<?php 
$input_get = array(
    'id' => ( isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : null ),
);
?>

	</div>

	<?php 
// Evergreen Check
if ( 'AUTO' === $webinar_data->webinar_date ) {
    // Evergreen
    webinarignition_display_edit_toggle(
        'calendar',
        esc_html__( 'Auto Webinar Dates & Times', 'webinar-ignition' ),
        'we_edit_lp_auto_dates',
        esc_html__( 'Select the dates & times for the auto webinar...', 'webinar-ignition' )
    );
    ?>
		<div id="we_edit_lp_auto_dates" class="we_edit_area">
			<?php 
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->lp_schedule_type,
        esc_html__( 'Webinar Schedule Type', 'webinar-ignition' ),
        'lp_schedule_type',
        esc_html__( 'Choose if you want to customize the dates and times when your webinar will be available, or choose a fixed date and time.', 'webinar-ignition' ),
        esc_html__( 'Customized', 'webinar-ignition' ) . ' [customized],' . esc_html__( 'Fixed', 'webinar-ignition' ) . ' [fixed], ' . esc_html__( 'Delayed', 'webinar-ignition' ) . ' [delayed]'
    );
    ?>
			<div class="lp_schedule_type" id="lp_schedule_type_customized">
				<?php 
    // dates
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_today,
        esc_html__( 'Today - Instant Access', 'webinar-ignition' ),
        'auto_today',
        esc_html__( 'You can allow people to watch the replay right away...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_day_offset,
        esc_html__( 'Delay available registration date by', 'webinar-ignition' ),
        'auto_day_offset',
        esc_html__( 'Specify the number of days to postpone the first available registration date. During this period, the Registration page will remain accessible, but the options in the date dropdown will adjust according to your specified delay.', 'webinar-ignition' ),
        esc_html__( 'Example: 3 (Defaults to 0)', 'webinar-ignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_day_limit,
        esc_html__( 'Limit number of available dates', 'webinar-ignition' ),
        'auto_day_limit',
        esc_html__( 'Specify how many days with time slots should be available. The default value is 7, the maximum value is also 7.', 'webinar-ignition' ),
        esc_html__( 'Example: 5 (Defaults to 7)', 'webinar-ignition' )
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_monday,
        esc_html__( 'Monday', 'webinar-ignition' ),
        'auto_monday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_tuesday,
        esc_html__( 'Tuesday', 'webinar-ignition' ),
        'auto_tuesday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_wednesday,
        esc_html__( 'Wednesday', 'webinar-ignition' ),
        'auto_wednesday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_thursday,
        esc_html__( 'Thursday', 'webinar-ignition' ),
        'auto_thursday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_friday,
        esc_html__( 'Friday', 'webinar-ignition' ),
        'auto_friday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_saturday,
        esc_html__( 'Saturday', 'webinar-ignition' ),
        'auto_saturday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_sunday,
        esc_html__( 'Sunday', 'webinar-ignition' ),
        'auto_sunday',
        esc_html__( 'You can choose to show this day as a possible day for the webinar, it will select the next possible occurrence within the week...', 'webinar-ignition' ),
        esc_html__( 'enabled', 'webinar-ignition' ) . ' [yes],' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [no]'
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_blacklisted_dates,
        esc_html__( 'Blacklist Dates', 'webinar-ignition' ),
        'auto_blacklisted_dates',
        __( 'Here you can hide certain dates or holidays...<br><br><b>The format must be Y-M-D and seperated by a comma and space.<br><br>IE: 2013-12-25, 2013-01-01</b>', 'webinar-ignition' ),
        ''
    );
    $is_multiple_auto_time_enabled = WebinarignitionPowerups::webinarignition_is_multiple_auto_time_enabled( $webinar_data );
    // times below
    webinarignition_display_time_auto(
        $input_get['id'],
        ( isset( $webinar_data->auto_time_1 ) ? $webinar_data->auto_time_1 : '' ),
        ( !isset( $webinar_data->auto_weekdays_1 ) ? false : $webinar_data->auto_weekdays_1 ),
        esc_html__( 'Webinar Time #1', 'webinar-ignition' ),
        'auto_time_1',
        'auto_weekdays_1',
        esc_html__( 'Select Webinar available time', 'webinar-ignition' ),
        false,
        $webinar_data
    );
    webinarignition_display_time_auto(
        $input_get['id'],
        ( isset( $webinar_data->auto_time_2 ) ? $webinar_data->auto_time_2 : '' ),
        ( !isset( $webinar_data->auto_weekdays_2 ) ? false : $webinar_data->auto_weekdays_2 ),
        esc_html__( 'Webinar Time #2', 'webinar-ignition' ),
        'auto_time_2',
        'auto_weekdays_2',
        esc_html__( 'Select Webinar available time', 'webinar-ignition' ),
        false,
        $webinar_data
    );
    webinarignition_display_time_auto(
        $input_get['id'],
        ( isset( $webinar_data->auto_time_3 ) ? $webinar_data->auto_time_3 : '' ),
        ( !isset( $webinar_data->auto_weekdays_3 ) ? false : $webinar_data->auto_weekdays_3 ),
        esc_html__( 'Webinar Time #3', 'webinar-ignition' ),
        'auto_time_3',
        'auto_weekdays_3',
        esc_html__( 'Select Webinar available time', 'webinar-ignition' ),
        false,
        $webinar_data
    );
    if ( $is_multiple_auto_time_enabled ) {
        ?>
					<div id="additional_auto_time_template" style="display: none;">
						<div class="additional_auto_time_item">
							<?php 
        webinarignition_display_time_auto(
            $input_get['id'],
            '',
            false,
            /* translators: %s: Placeholder for the webinar time number */
            sprintf( esc_html__( 'Webinar Time #%s', 'webinar-ignition' ), '<span class="index_holder"></span>' ),
            'multiple__auto_time',
            'multiple__auto_weekdays',
            esc_html__( 'Select Webinar available time', 'webinar-ignition' ),
            true
        );
        ?>

							<button type="button" class="blue-btn btn deleteAutoTime" style="color:#FFF;float:none;">
								<i class="icon-remove"></i>
							</button>
						</div>
					</div>
					<?php 
    }
    //end if
    ?>
				<div id="additional_auto_time_container"<?php 
    echo ( $is_multiple_auto_time_enabled ? '' : ' style="display:none;"' );
    ?>>
					<?php 
    if ( !empty( $webinar_data->multiple__auto_time ) ) {
        $multiple__auto_weekdays = ( !empty( $webinar_data->multiple__auto_weekdays ) ? $webinar_data->multiple__auto_weekdays : false );
        foreach ( $webinar_data->multiple__auto_time as $index => $item ) {
            $num = $index + 4;
            $num_id = $index + 1;
            $weekdays_selected = $multiple__auto_weekdays;
            if ( false !== $multiple__auto_weekdays ) {
                $weekdays_selected = ( !empty( $multiple__auto_weekdays[$index] ) ? $multiple__auto_weekdays[$index] : array() );
            }
            ?>
							<div class="additional_auto_time_item">
								<?php 
            webinarignition_display_time_auto(
                $input_get['id'],
                $item,
                $weekdays_selected,
                /* translators: %s: Placeholder for the webinar time number */
                sprintf( esc_html__( 'Webinar Time #%s', 'webinar-ignition' ), '<span class="index_holder">' . $num . '</span>' ),
                'multiple__auto_time__' . $num_id,
                'multiple__auto_weekdays__' . $num_id,
                esc_html__( 'Select Webinar available time', 'webinar-ignition' )
            );
            ?>

								<button type="button" class="blue-btn btn deleteAutoTime" style="color:#FFF;float:none;">
									<i class="icon-remove"></i>
								</button>
							</div>
							<?php 
        }
        //end foreach
    }
    //end if
    ?>
				</div>
				<?php 
    if ( $is_multiple_auto_time_enabled ) {
        ?>
					<div class="additional_auto_time_control editSection">
						<button type="button" id="createAutoTime" class="blue-btn-44 btn" style="color:#FFF;float:none;">
							<i class="icon-plus"></i> <?php 
        esc_html_e( 'Add New Webinar Time', 'webinar-ignition' );
        ?>
						</button>
					</div>
					<?php 
    }
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->auto_timezone_type ?? '',
        // Fallback to an empty string if the property is undefined
        esc_html__( 'Choose timezone type', 'webinar-ignition' ),
        'auto_timezone_type',
        esc_html__( 'Choose whether you want to specify a fixed timezone, or let the user sign up for a time in their timezone.', 'webinar-ignition' ),
        esc_html__( 'User Specific', 'webinar-ignition' ) . ' [user_specific],' . esc_html__( 'Fixed', 'webinar-ignition' ) . ' [fixed]'
    );
    ?>
				<div class="auto_timezone_type" id="auto_timezone_type_fixed">
					<?php 
    webinarignition_display_timezone_identifiers(
        $input_get['id'],
        $webinar_data->auto_timezone_custom,
        esc_html__( 'Fixed Webinar Timezone', 'webinar-ignition' ),
        'auto_timezone_custom',
        esc_html__( 'Choose a timezone for your webinar.', 'webinar-ignition' ),
        esc_html__( 'Select webinar timezone', 'webinar-ignition' )
    );
    ?>
				</div>

			</div>
			<div class="lp_schedule_type" id="lp_schedule_type_fixed">
				<?php 
    webinarignition_display_date_picker(
        $input_get['id'],
        $webinar_data->auto_date_fixed,
        'Y-m-d',
        esc_html__( 'Fixed Webinar Date', 'webinar-ignition' ),
        'auto_date_fixed',
        esc_html__( 'Choose a fixed date for your evergreen webinar.', 'webinar-ignition' ),
        esc_html__( 'Choose date', 'webinar-ignition' ),
        $webinar_date_format
    );
    webinarignition_display_time_picker(
        $input_get['id'],
        $webinar_data->auto_time_fixed,
        esc_html__( 'Fixed Webinar Time', 'webinar-ignition' ),
        'auto_time_fixed',
        esc_html__( 'Choose a fixed time for your evergreen webinar.', 'webinar-ignition' ),
        '',
        $webinar_data
    );
    webinarignition_display_timezone_identifiers(
        $input_get['id'],
        $webinar_data->auto_timezone_fixed,
        esc_html__( 'Fixed Webinar Timezone', 'webinar-ignition' ),
        'auto_timezone_fixed',
        esc_html__( 'Choose a timezone for your webinar.', 'webinar-ignition' ),
        esc_html__( 'Select webinar timezone', 'webinar-ignition' )
    );
    ?>
			</div>
			<div class="lp_schedule_type" id="lp_schedule_type_delayed">
				<?php 
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->delayed_day_offset,
        esc_html__( 'Delay available registration date by', 'webinar-ignition' ),
        'delayed_day_offset',
        esc_html__( 'Specify by how many days to delay the available registration date, based when the user visited the registration page.', 'webinar-ignition' ),
        esc_html__( 'Example: 3', 'webinar-ignition' )
    );
    webinarignition_display_time_picker(
        $input_get['id'],
        $webinar_data->auto_time_delayed,
        esc_html__( 'Fixed Webinar Time', 'webinar-ignition' ),
        'auto_time_delayed',
        esc_html__( 'Choose a fixed time for your evergreen webinar.', 'webinar-ignition' ),
        '',
        $webinar_data
    );
    webinarignition_display_option(
        $input_get['id'],
        $webinar_data->delayed_timezone_type,
        esc_html__( 'Choose timezone type', 'webinar-ignition' ),
        'delayed_timezone_type',
        esc_html__( 'Choose whether you want to specify a fixed timezone, or let the user sign up for a time in their timezone.', 'webinar-ignition' ),
        esc_html__( 'Fixed', 'webinar-ignition' ) . ' [fixed],' . esc_html__( 'User Specific', 'webinar-ignition' ) . ' [user_specific]'
    );
    ?>
				<div class="delayed_timezone_type" id="delayed_timezone_type_user_specific">
					<?php 
    webinarignition_display_wpeditor(
        $input_get['id'],
        $webinar_data->auto_timezone_user_specific_name,
        esc_html__( 'Your Timezone translation', 'webinar-ignition' ),
        'auto_timezone_user_specific_name',
        esc_html__( 'Translate "Your Timezone" text into your language.', 'webinar-ignition' )
    );
    ?>
				</div>
				<div class="delayed_timezone_type" id="delayed_timezone_type_fixed">
					<?php 
    webinarignition_display_timezone_identifiers(
        $input_get['id'],
        $webinar_data->auto_timezone_delayed,
        esc_html__( 'Fixed Webinar Timezone', 'webinar-ignition' ),
        'auto_timezone_delayed',
        esc_html__( 'Choose a timezone for your webinar.', 'webinar-ignition' ),
        esc_html__( 'Select webinar timezone', 'webinar-ignition' )
    );
    ?>
				</div>

				<?php 
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->delayed_blacklisted_dates,
        esc_html__( 'Blacklist Dates', 'webinar-ignition' ),
        'delayed_blacklisted_dates',
        __( 'Here you can hide certain dates or holidays...<br><br><b>The format must be Y-M-D and seperated by a comma and space.<br><br>IE: 2013-12-25, 2013-01-01</b>', 'webinar-ignition' ),
        ''
    );
    ?>
			</div>
		</div>

		<?php 
}
//end if
?>

	<?php 
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
if ( 'AUTO' === $webinar_data->webinar_date ) {
    webinarignition_display_edit_toggle(
        'edit-sign',
        esc_html__( 'Translation For Months / Days / Copy', 'webinar-ignition' ),
        'we_edit_lp_auto_times_translate',
        esc_html__( 'Translation options for date. times & copy...', 'webinar-ignition' )
    );
}
?>
	<div id="we_edit_lp_auto_times_translate" class="we_edit_area">
		<?php 
if ( 'AUTO' === $webinar_data->webinar_date ) {
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_translate_instant,
        esc_html__( 'Translate :: Instant Access/Today', 'webinar-ignition' ),
        'auto_translate_instant',
        esc_html__( 'This is the text that is shown if they want to watch the replay...', 'webinar-ignition' ),
        esc_html__( 'e.g. Watch Replay', 'webinar-ignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_translate_headline1,
        esc_html__( 'Choose Date Headline', 'webinar-ignition' ),
        'auto_translate_headline1',
        esc_html__( 'This is the headline text for choosing a date for the webinar...', 'webinar-ignition' ),
        esc_html__( 'e.g. Choose a Date To Attend...', 'webinar-ignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_translate_subheadline1,
        esc_html__( 'Choose Date Sub-Headline', 'webinar-ignition' ),
        'auto_translate_subheadline1',
        esc_html__( 'This is shown under the headline above...', 'webinar-ignition' ),
        esc_html__( 'e.g. Select a date that best suits your schedule', 'webinar-ignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_translate_headline2,
        esc_html__( 'Choose Time Headline', 'webinar-ignition' ),
        'auto_translate_headline2',
        esc_html__( 'This is the headline text for choosing a time for the webinar...', 'webinar-ignition' ),
        esc_html__( 'e.g. What Time Is Best For You', 'webinar-ignition' )
    );
    webinarignition_display_field(
        $input_get['id'],
        $webinar_data->auto_translate_subheadline2,
        esc_html__( 'Choose Time Sub-Headline', 'webinar-ignition' ),
        'auto_translate_subheadline2',
        esc_html__( 'This is shown under the headline above and shows the users local time...', 'webinar-ignition' ),
        esc_html__( 'e.g. Your Local Time is:', 'webinar-ignition' )
    );
}
//end if
?>
	</div>

	<?php 
webinarignition_display_edit_toggle(
    'picture',
    esc_html__( 'Banner Settings', 'webinar-ignition' ),
    'we_edit_lp_header_image',
    esc_html__( 'Your main banner image for the landing page...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_header_image" class="we_edit_area">
		<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'reg_banner',
    $webinar_data,
    true,
    true
);
?>
		<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->lp_banner_bg_style,
    esc_html__( 'Banner Background Style', 'webinar-ignition' ),
    'lp_banner_bg_style',
    esc_html__( 'You can choose between a simple background color, or to have a background image (repeating horiztonally)', 'webinar-ignition' ),
    esc_html__( 'Show Banner Area', 'webinar-ignition' ) . ' [show],' . esc_html__( 'Hide Banner Area', 'webinar-ignition' ) . ' [hide]'
);
?>
		<div class="lp_banner_bg_style" id="lp_banner_bg_style_show">
			<?php 
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->lp_banner_bg_color,
    esc_html__( 'Banner Background Color', 'webinar-ignition' ),
    'lp_banner_bg_color',
    esc_html__( 'Choose a color for the top banner area, this will fill the entire top banner area...', 'webinar-ignition' ),
    '#FFFFFF'
);
?>
			<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->lp_banner_bg_repeater,
    esc_html__( 'Banner Repeating BG Image', 'webinar-ignition' ),
    'lp_banner_bg_repeater',
    __( 'This is the image that is repeated horiztonally in the background of the banner area... If you leave this blank, it will just show the banner BG color... <br><br><b>best results:</b> 89px high..', 'webinar-ignition' ),
    esc_html__( 'http://yoursite.com/banner-bg.png', 'webinar-ignition' )
);
?>
			<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->lp_banner_image,
    esc_html__( 'Banner Image URL:', 'webinar-ignition' ),
    'lp_banner_image',
    __( 'This is the URL for the banner image you want to be shown. By defualt it is placed in the middle, perfect for a logo... <br><br><b>best results:</b> 89px high and 960px wide...', 'webinar-ignition' ),
    esc_html__( 'http://yoursite.com/banner-image.png', 'webinar-ignition' )
);
?>
			<div class="wi-banner-wrap">
					<?php 
webinarignition_display_info( esc_html__( 'Note: Banner Sizing', 'webinar-ignition' ), esc_html__( 'Your banner image size can be any height, but its best at 89px high. Also, your banner repeating graphic should be the same height...', 'webinar-ignition' ) );
?>
			</div>
		</div>

	</div>

	<?php 
webinarignition_display_edit_toggle(
    'magic',
    esc_html__( 'Background Style Settings', 'webinar-ignition' ),
    'we_edit_lp_bg',
    esc_html__( 'Select the style of your background...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_bg" class="we_edit_area">
		<?php 
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->lp_background_color,
    esc_html__( 'Background Color', 'webinar-ignition' ),
    'lp_background_color',
    esc_html__( 'This is the color for the main section, this fills the entire landing page area...', 'webinar-ignition' ),
    '#DDDDDD'
);
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->lp_background_image,
    esc_html__( 'Repeating Background Image URL', 'webinar-ignition' ),
    'lp_background_image',
    esc_html__( 'You can have a repeating image to be shown as the background to add some flare to your landing page...', 'webinar-ignition' ),
    esc_html__( 'http://yoursite.com/background-image.png', 'webinar-ignition' )
);
?>
			<div class="wi-banner-wrap">
		<?php 
webinarignition_display_info( esc_html__( 'Note: Background Image', 'webinar-ignition' ), esc_html__( 'If you leave the background image blank, no bg image will be shown...', 'webinar-ignition' ) );
?>
		</div>
	</div>

	<?php 
webinarignition_display_edit_toggle(
    'cogs',
    esc_html__( 'Meta Information (Social Share Settings)', 'webinar-ignition' ),
    'we_edit_lp_metashare',
    esc_html__( 'Edit your meta information used for the social sharing features...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_metashare" class="we_edit_area">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->lp_metashare_title,
    esc_html__( 'Meta Site Title', 'webinar-ignition' ),
    'lp_metashare_title',
    esc_html__( 'This is your site title - this will be used as the main headline for social shares...', 'webinar-ignition' ),
    esc_html__( 'e.g. Amazing Webinar Training!', 'webinar-ignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->lp_metashare_desc,
    esc_html__( 'Meta Description', 'webinar-ignition' ),
    'lp_metashare_desc',
    esc_html__( 'This is your site description - this will be used as the main copy for social shares...', 'webinar-ignition' ),
    esc_html__( 'e.g. Check out this awesome training, this is a one time webinar!', 'webinar-ignition' )
);
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->ty_share_image,
    esc_html__( 'Social Share Image URL', 'webinar-ignition' ),
    'ty_share_image',
    esc_html__( 'This is the image that is used with the social shares, for best results, keep it: 120px by 120px..', 'webinar-ignition' ),
    esc_html__( 'e.g. http://yoursite.com/share-image.png', 'webinar-ignition' )
);
?>
	</div>

	<?php 
webinarignition_display_edit_toggle(
    'edit-sign',
    esc_html__( 'Main Headline', 'webinar-ignition' ),
    'we_edit_lp_headline',
    esc_html__( 'Copy for the main headline on the landing page...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_headline" class="we_edit_area">
		<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'reg_main_headline',
    $webinar_data,
    true,
    true
);
?>
		<?php 
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->lp_main_headline,
    esc_html__( 'Main Headline', 'webinar-ignition' ),
    'lp_main_headline',
    esc_html__( 'This appears above the main optin area. This should really get people excited for your event, so they really want to be there...', 'webinar-ignition' )
);
?>
	</div>

	<?php 
$cta_area_string = ( class_exists( 'WI_GRID' ) ? esc_html__( 'CTA Area - Video / Image / Grid Settings', 'webinar-ignition' ) : esc_html__( 'CTA Area - Video / Image Settings', 'webinar-ignition' ) );
webinarignition_display_edit_toggle(
    'film',
    $cta_area_string,
    'we_edit_lp_cta_area',
    esc_html__( 'The core CTA area, which can be a video or an image...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_cta_area" class="we_edit_area">
		<?php 
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->lp_cta_bg_color,
    esc_html__( 'CTA Area Background Color', 'webinar-ignition' ),
    'lp_cta_bg_color',
    esc_html__( 'This is the color for the CTA area that video or image is displayed, a good contrast color will get a lot of attention for this area...', 'webinar-ignition' ),
    '#000000'
);
?>
			<div class="wi-banner-wrap">
		<?php 
webinarignition_display_info( esc_html__( 'Note: CTA BG Color', 'webinar-ignition' ), esc_html__( 'This is also used for the thank you page for the CTA area there...', 'webinar-ignition' ) );
?>
			</div>
		<?php 
if ( class_exists( 'WI_GRID' ) ) {
    ?>
		<div class="lp_grid_image_url" id="lp_grid_image_url">
			<?php 
    webinarignition_display_field_image_upd(
        $input_get['id'],
        ( isset( $webinar_data->lp_grid_image_url ) ? $webinar_data->lp_grid_image_url : '' ),
        // 275x200
        esc_html__( 'Grid Image', 'webinar-ignition' ),
        'lp_grid_image_url',
        esc_html__( 'This is the image will be shown on Grid View.', 'webinar-ignition' ),
        esc_html__( 'http://yoursite.com/grid-image.png', 'webinar-ignition' )
    );
    ?>
<div class="wi-banner-wrap">
			<?php 
    webinarignition_display_info( esc_html__( 'Note: Grid Image Size', 'webinar-ignition' ), esc_html__( 'For the best results, make sure your Grid image size is 275(w) X 200(h) pixels', 'webinar-ignition' ) );
    ?>
			</div>
		</div>
			<?php 
}
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->lp_cta_type,
    esc_html__( 'CTA Type:', 'webinar-ignition' ),
    'lp_cta_type',
    esc_html__( 'You can choose to display a video embed code or have an image to be shown here. A video will get higher results...', 'webinar-ignition' ),
    esc_html__( 'Show Video', 'webinar-ignition' ) . ' [video],' . esc_html__( 'Show Image', 'webinar-ignition' ) . ' [image]'
);
?>
		<div class="lp_cta_type" id="lp_cta_type_video">
			<?php 
webinarignition_display_field_add_media(
    $input_get['id'],
    ( isset( $webinar_data->lp_cta_video_url ) ? $webinar_data->lp_cta_video_url : '' ),
    esc_html__( 'Webinar Video URL .MP4 *', 'webinar-ignition' ),
    'lp_cta_video_url',
    esc_html__( 'The MP4 file that you want to play as your CTA... must be in .mp4 format as its uses a html5 video player...', 'webinar-ignition' ),
    esc_html__( 'Ex. http://yoursite.com/webinar-video.mp4', 'webinar-ignition' )
);
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'reg_video_area',
    $webinar_data,
    true,
    true
);
?>

<div class="wi-banner-wrap">

<?php 
webinarignition_display_info( esc_html__( 'Note: Custom Video URL', 'webinar-ignition' ), esc_html__( 'The custom video url must be in .mp4 format as the player uses a html5 video player...', 'webinar-ignition' ) );
?>
			</div>
			<?php 
webinarignition_display_textarea(
    $input_get['id'],
    $webinar_data->lp_cta_video_code,
    esc_html__( 'Video Embed Code', 'webinar-ignition' ),
    'lp_cta_video_code',
    __( 'This is your video embed code. Your video will be auto-resized to fit the area which is <strong>500px width and 281px height</strong> <br><br>EasyVideoPlayer users must resize their video manually...', 'webinar-ignition' ),
    esc_html__( 'e.g. Youtube embed code, Vimeo embed code, etc', 'webinar-ignition' )
);
?>
<div class="wi-banner-wrap">
			<?php 
webinarignition_display_info( esc_html__( 'Note: Video Size', 'webinar-ignition' ), esc_html__( 'The video will auto-resized, but its best you have a video with the same aspect ratio of 500x281...', 'webinar-ignition' ) );
?>
			</div>
		</div>

		<div class="lp_cta_type" id="lp_cta_type_image" style="display: none;">
			<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->lp_cta_image,
    esc_html__( 'CTA Image URL', 'webinar-ignition' ),
    'lp_cta_image',
    __( 'This is the image that will be shown in the main cta area, this image will be resized to fit the area: <strong>500px width and 281px height</strong>...', 'webinar-ignition' ),
    esc_html__( 'http://yoursite.com/cta-image.png', 'webinar-ignition' )
);
?>
<div class="wi-banner-wrap">
			<?php 
webinarignition_display_info( esc_html__( 'Note: CTA Image', 'webinar-ignition' ), esc_html__( 'For the best results, make sure your CTA image is 500 wide...', 'webinar-ignition' ) );
?>
			</div>
		</div>

	</div>

	<?php 
webinarignition_display_edit_toggle(
    'edit-sign',
    esc_html__( 'Sales Copy', 'webinar-ignition' ),
    'we_edit_lp_sales_copy',
    esc_html__( 'The main landing page copy that appears under the CTA video / image area...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_sales_copy" class="we_edit_area">
		<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'reg_sales_headline',
    $webinar_data,
    true,
    true
);
?>
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->lp_sales_headline,
    esc_html__( 'Sales Copy Headline', 'webinar-ignition' ),
    'lp_sales_headline',
    esc_html__( 'This is the copy that is shown above the sales copy for the landing page, it has a background color to make it pop on the page...', 'webinar-ignition' ),
    esc_html__( 'e.g. What You Will Learn On The Webinar...', 'webinar-ignition' )
);
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->lp_sales_headline_color,
    esc_html__( 'Sales Copy Headline BG Color', 'webinar-ignition' ),
    'lp_sales_headline_color',
    esc_html__( 'This is the background color for the headline area... Make it a color that stands out on the page. The sales copy headline will always be white, so make sure this color works well with white text...', 'webinar-ignition' ),
    '#0496AC'
);
?>
<div class="wi-banner-wrap">
		<?php 
webinarignition_display_info( esc_html__( 'Note: Headline BG Color', 'webinar-ignition' ), esc_html__( 'This color will also be used in the thank you page for the step headlines...', 'webinar-ignition' ) );
?>
</div>
		<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'reg_sales_copy',
    $webinar_data,
    true,
    true
);
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->lp_sales_copy,
    esc_html__( 'Main Sales Copy', 'webinar-ignition' ),
    'lp_sales_copy',
    esc_html__( 'This is the main sales copy that is shown under the CTA area and sales headline. This is where you can explain all the finer details about the webinar...', 'webinar-ignition' )
);
?>
<div class="wi-banner-wrap">
		<?php 
webinarignition_display_info( esc_html__( 'Note: Sales Copy', 'webinar-ignition' ), esc_html__( 'This is shown below the video area, you can have the main bits of what they will learn on the webinar here...', 'webinar-ignition' ) );
?>
		</div>
	</div>

	<?php 
webinarignition_display_edit_toggle(
    'edit-sign',
    esc_html__( 'Optin Headline', 'webinar-ignition' ),
    'we_edit_lp_optin_headline',
    esc_html__( 'The headline that appears over the optin area...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_optin_headline" class="we_edit_area">
		<?php 
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->lp_optin_headline,
    esc_html__( 'Optin Headline', 'webinar-ignition' ),
    'lp_optin_headline',
    esc_html__( 'This is shown on the right hand side of the page above the webinar date...', 'webinar-ignition' )
);
?>
	</div>

	<?php 
if ( 'AUTO' !== $webinar_data->webinar_date ) {
    webinarignition_display_edit_toggle(
        'calendar',
        esc_html__( 'Optin Webinar Date', 'webinar-ignition' ),
        'we_edit_lp_optin_date',
        esc_html__( 'Dates / Copy for the landing page...', 'webinar-ignition' )
    );
}
?>

	<div id="we_edit_lp_optin_date" class="we_edit_area">
		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->lp_webinar_subheadline,
    esc_html__( 'Webinar Date Sub Headline', 'webinar-ignition' ),
    'lp_webinar_subheadline',
    esc_html__( 'This is shown under the headline above, ideal for stating the time of the webinar...', 'webinar-ignition' ),
    esc_html__( 'at 5pm Eastern, 2pm Pacific', 'webinar-ignition' )
);
?>
<div class="wi-banner-wrap">
		<?php 
webinarignition_display_info( esc_html__( 'Note: Webinar Date', 'webinar-ignition' ), esc_html__( "The date format depends on the format you have chosen in WordPress's General Settings page.", 'webinar-ignition' ) );
?>
		</div>
	</div>

	<?php 
webinarignition_display_edit_toggle(
    'user',
    esc_html__( 'Webinar Host Info', 'webinar-ignition' ),
    'we_edit_lp_host',
    esc_html__( 'Information about the webinar host, Photo & Text...', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_host" class="we_edit_area" style="display: none;">
		<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->lp_webinar_host_block,
    esc_html__( 'Banner Background Style', 'webinar-ignition' ),
    'lp_webinar_host_block',
    esc_html__( 'You can choose to show or hide the webinar host info block...', 'webinar-ignition' ),
    esc_html__( 'Show Host Info Area', 'webinar-ignition' ) . ' [show],' . esc_html__( 'Hide Host Info Area', 'webinar-ignition' ) . ' [hide]'
);
?>
		<div class="lp_webinar_host_block" id="lp_webinar_host_block_show">

			<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->lp_host_image,
    esc_html__( 'Webinar Host Photo URL', 'webinar-ignition' ),
    'lp_host_image',
    __( 'This is the image for the person hosting the webinar, this is shown under the optin area... <b>best results: 100px wide and  100px high</b>', 'webinar-ignition' ),
    esc_html__( 'http://yoursite.com/webinar-host.png', 'webinar-ignition' )
);
webinarignition_display_textarea(
    $input_get['id'],
    $webinar_data->lp_host_info,
    esc_html__( 'Webinar Host Info', 'webinar-ignition' ),
    'lp_host_info',
    __( 'This is the text that is show on the right side of the webinars host photo. This should tell the visitor who the host is and why they should listen them...(html allowed ie. <b>bold tags</b>)', 'webinar-ignition' ),
    ''
);
?>

		</div>

	</div>

	<?php 
webinarignition_display_edit_toggle(
    'money',
    esc_html__( 'Paid Webinar', 'webinar-ignition' ),
    'we_edit_lp_paid',
    esc_html__( 'Require payment to sign up & view webinar..', 'webinar-ignition' )
);
?>
	<div id="we_edit_lp_paid" class="we_edit_area">
		<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->paid_status,
    esc_html__( 'Paid Status', 'webinar-ignition' ),
    'paid_status',
    esc_html__( 'Choose to make it a free webinar, or a paid webinar...', 'webinar-ignition' ) . '<br><br> <a href="https://webinarignition.tawk.help/article/creating-paid-webinars" target="_blank">' . esc_html__( 'KB article: Paid webinars', 'webinar-ignition' ) . '<a/>',
    esc_html__( 'Free Webinar', 'webinar-ignition' ) . ' [free],' . esc_html__( 'Paid Webinar', 'webinar-ignition' ) . ' [paid]'
);
?>
		<div class="paid_status" id="paid_status_paid" style="display: none;">
			<?php 
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?>
		</div>
	</div>

	<?php 
webinarignition_display_edit_toggle(
    'cog',
    esc_html__( 'Optin Form Creator / AR Integration', 'webinar-ignition' ),
    'we_edit_lp_ar',
    esc_html__( 'Setup your integration with your Auto-Responder', 'webinar-ignition' )
);
?>

	<div id="we_edit_lp_ar" class="we_edit_area">
		<?php 
WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description(
    'reg_optin_form',
    $webinar_data,
    true,
    true
);
?>
		<?php 
// if ( 'AUTO' !== $webinar_data->webinar_date ) {
// 	webinarignition_display_option(
// 		$input_get['id'],
// 		$webinar_data->lp_fb_button,
// 		esc_html__( 'Facebook Connect Button', 'webinar-ignition' ),
// 		'lp_fb_button',
// 		esc_html__( 'You can choose to use the Facebook connect button, by default its not shown, and if you do enable it, you must setup the FB connect settings in order for it to work...', 'webinar-ignition' ),
// 		esc_html__( 'Disable - FB Connect', 'webinar-ignition' ) . ' [hide],' . esc_html__( 'Enable - FB Connect', 'webinar-ignition' ) . ' [show]'
// 	);
// }
?>

		<!-- <div class="lp_fb_button" id="lp_fb_button_show" style="display: none;"> -->
			<?php 
// webinarignition_display_field(
// 	$input_get['id'],
// 	$webinar_data->fb_id,
// 	esc_html__( 'Facebook App ID', 'webinar-ignition' ),
// 	'fb_id',
// 	esc_html__( 'This is your FB App ID', 'webinar-ignition' ),
// 	esc_html__( 'Get From Facebook App...', 'webinar-ignition' )
// );
// webinarignition_display_field(
// 	$input_get['id'],
// 	$webinar_data->fb_secret,
// 	esc_html__( 'Facebook App Secret', 'webinar-ignition' ),
// 	'fb_secret',
// 	esc_html__( 'This is your FB App Secret', 'webinar-ignition' ),
// 	esc_html__( 'Get From Facebook App...', 'webinar-ignition' )
// );
// webinarignition_display_field(
// 	$input_get['id'],
// 	$webinar_data->lp_fb_copy,
// 	esc_html__( 'Facebook Connect Button Copy', 'webinar-ignition' ),
// 	'lp_fb_copy',
// 	esc_html__( 'This is the text that is shown on the Facebook Connect sign up button...', 'webinar-ignition' ),
// 	esc_html__( 'e.g. Register With Facebook', 'webinar-ignition' )
// );
// webinarignition_display_field(
// 	$input_get['id'],
// 	$webinar_data->lp_fb_or,
// 	esc_html__( "Custom Copy 'OR'", 'webinarignition' ),
// 	'lp_fb_or',
// 	esc_html__( 'You can edit the copy displayed under the FB connect button...', 'webinar-ignition' ),
// 	esc_html__( 'e.g. OR', 'webinar-ignition' )
// );
?>
<!-- <div class="wi-banner-wrap"> -->
			<?php 
// webinarignition_display_info(
// 	esc_html__( 'Note: FB Button', 'webinar-ignition' ),
// 	esc_html__( 'You will need to make sure you setup the FB Connect info, it is editable at the bottom of this page...', 'webinar-ignition' )
// );
?>
			<!-- </div> -->

		<!-- </div> -->

		<?php 
?>
			<div class="editSection">
				<div class="inputTitle">
					<div class="inputTitleCopy"><?php 
esc_html_e( 'Easiest (Raw) HTML only Opt-in Code:', 'webinar-ignition' );
?></div>
					<div class="inputTitleHelp"><?php 
esc_html_e( 'This should be the easiest (RAW) html version of the optin code your AR service provides you...', 'webinar-ignition' );
?><br><br><a href="https://webinarignition.tawk.help/article/aweber-integration"
							target="_blank"><b><?php 
esc_html_e( 'Aweber Integration Tutorial', 'webinar-ignition' );
?></b></a><br><br><a
							href="https://webinarignition.tawk.help/article/mailchimp-integration" target="_blank"><b><?php 
esc_html_e( 'MailChimp Integration Tutorial', 'webinar-ignition' );
?></b></a></div>
				</div>

				<div class="inputSection">
					<?php 
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?> 
				</div>
				<br clear="left">
			</div>	
			<?php 
?>

		<div class="editSection section--ar_fields">
			<div id="ar_templates" class="hidden">
				<div class="available-fields">
					<li class="wi-form-field wi-form-field--available">
						<span class="wi-field-add" data-hidden="false" data-name=""><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
						{field_name}
					</li>
					<li class="wi-form-field wi-form-field--hidden">
						<span class="wi-field-add" data-hidden="true" data-names=""><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
						{field_names}
					</li>
				</div>
				<div class="labels">
					<input type="hidden" class="ar_name" value="<?php 
esc_html_e( 'First Name', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter first name', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_lname" value="<?php 
esc_html_e( 'Last Name', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter last name', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_salutation" value="<?php 
esc_html_e( 'Title/Salutation', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter title', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_reason" value="<?php 
esc_html_e( 'Participation Reason', 'webinar-ignition' );
?>"  data-placeholder="<?php 
esc_html_e( 'Enter participation reason', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_email" value="<?php 
esc_html_e( 'Email', 'webinar-ignition' );
?>"  data-placeholder="<?php 
esc_html_e( 'Enter email', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_phone" value="<?php 
esc_html_e( 'Phone', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter phone number', 'webinar-ignition' );
?>"/>

					<input type="hidden" class="ar_custom_1" value="<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter custom field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_2" value="<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter custom field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_3" value="<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter custom field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_4" value="<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter custom field', 'webinar-ignition' );
?>"/>

					<input type="hidden" class="ar_custom_5" value="<?php 
esc_html_e( 'Custom Checkbox Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_6" value="<?php 
esc_html_e( 'Custom Checkbox Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_7" value="<?php 
esc_html_e( 'Custom Textarea Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Enter text', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_8" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_9" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_10" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_11" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_12" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_13" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_14" value="<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>"/>

					<input type="hidden" class="ar_custom_15" value="<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Select an option', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_16" value="<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Select an option', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_17" value="<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Select an option', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_custom_18" value="<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>" data-placeholder="<?php 
esc_html_e( 'Select an option', 'webinar-ignition' );
?>"/>

					<input type="hidden" class="ar_privacy_policy" value="<?php 
esc_html_e( 'Privacy Policy', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_terms_and_conditions" value="<?php 
esc_html_e( 'Terms and Conditions', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_mailing_list" value="<?php 
esc_html_e( 'Mailing List', 'webinar-ignition' );
?>"/>
					<input type="hidden" class="ar_webinar_title" value="<?php 
esc_html_e( 'Webinar Title', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_host" value="<?php 
esc_html_e( 'Webinar Host', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_url" value="<?php 
esc_html_e( 'Webinar URL', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_date" value="<?php 
esc_html_e( 'Webinar Date', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_time" value="<?php 
esc_html_e( 'Webinar Time', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_timezone" value="<?php 
esc_html_e( 'Webinar Time Zone', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_registration_date" value="<?php 
esc_html_e( 'Webinar Registration Date', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_registration_time" value="<?php 
esc_html_e( 'Webinar Registration Time', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_utm_source" value="<?php 
esc_html_e( 'UTM Source', 'webinar-ignition' );
?>">
					<input type="hidden" class="ar_webinar_date_time" value="<?php 
esc_html_e( 'Webinar Date & Time', 'webinar-ignition' );
?>">
				</div>

				<div class="label_names">
					<input type="hidden" class="ar_name" value="lp_optin_name"/>
					<input type="hidden" class="ar_lname" value="lp_optin_lname"/>
					<input type="hidden" class="ar_salutation" value="lp_optin_salutation"/>					
					<input type="hidden" class="ar_reason" value="lp_optin_reason"/>					
					<input type="hidden" class="ar_email" value="lp_optin_email"/>
					<input type="hidden" class="ar_phone" value="lp_optin_phone"/>
					<input type="hidden" class="ar_custom_1" value="lp_optin_custom_1"/>
					<input type="hidden" class="ar_custom_2" value="lp_optin_custom_2"/>
					<input type="hidden" class="ar_custom_3" value="lp_optin_custom_3"/>
					<input type="hidden" class="ar_custom_4" value="lp_optin_custom_4"/>
					<input type="hidden" class="ar_custom_5" value="lp_optin_custom_5"/>
					<input type="hidden" class="ar_custom_6" value="lp_optin_custom_6"/>
					<input type="hidden" class="ar_custom_7" value="lp_optin_custom_7"/>
					<input type="hidden" class="ar_custom_8" value="lp_optin_custom_8"/>
					<input type="hidden" class="ar_custom_9" value="lp_optin_custom_9"/>
					<input type="hidden" class="ar_custom_10" value="lp_optin_custom_10"/>
					<input type="hidden" class="ar_custom_11" value="lp_optin_custom_11"/>
					<input type="hidden" class="ar_custom_12" value="lp_optin_custom_12"/>
					<input type="hidden" class="ar_custom_13" value="lp_optin_custom_13"/>
					<input type="hidden" class="ar_custom_14" value="lp_optin_custom_14"/>

					<input type="hidden" class="ar_custom_15" value="lp_optin_custom_15"/>
					<input type="hidden" class="ar_custom_16" value="lp_optin_custom_16"/>
					<input type="hidden" class="ar_custom_17" value="lp_optin_custom_17"/>
					<input type="hidden" class="ar_custom_18" value="lp_optin_custom_18"/>

					<input type="hidden" class="ar_utm_source" value="UTM Source">
					<input type="hidden" class="ar_privacy_policy" value="lp_optin_privacy_policy"/>
					<input type="hidden" class="ar_terms_and_conditions" value="lp_optin_terms_and_conditions"/>
					<input type="hidden" class="ar_mailing_list" value="lp_optin_mailing_list"/>

				</div>
				<div class="form-builder">
					<li class="wi-form-fieldblock wi-form-fieldblock-visible">
						<div class="field-block--table">
							<div class="field-block field-block--cell">
								<small class="sublabel" style="background: #0074A2; color: white; border: none;"><?php 
esc_html_e( 'Field Type (Visible):', 'webinar-ignition' );
?></small>
								<input type="text" class="fieldblock field__ar-label" value="" disabled="disabled"/>
							</div>
							<div class="field-block field-block--cell">
								<small class="sublabel"><?php 
esc_html_e( 'Map to AR Form Field', 'webinar-ignition' );
?> (<span></span>):</small>
								<select class="fieldblock field__ar-mapping">
									<option value=""><?php 
esc_html_e( '* Not mapped', 'webinar-ignition' );
?></option>
								</select>
							</div>
						</div>
						<div class="field-block">
							<small id="placeHolderText" class="sublabel"><?php 
esc_html_e( 'Field label / placeholder:', 'webinar-ignition' );
?></small>
							<input class="fieldblock field__label field__ar-placeholder" type="text"/>
						</div>
						<div class="field__actions">
							<input type="checkbox" class="required_ar" style="width: 18px !important; height: 18px !important;"> <span><?php 
esc_html_e( 'Required?', 'webinar-ignition' );
?></span>
							<!-- multi col code -->
							<!-- <input type="checkbox" class="new_row_ar" style="width: 18px !important; height: 18px !important; margin-left:15px;"> <span><?php 
// esc_html_e( 'Show in new row', 'webinar-ignition' );
?></span> -->
							<a href="#" class="field__action js__fieldblock-remove field__action--remove"><?php 
esc_html_e( 'Remove', 'webinar-ignition' );
?></a>
						</div>
						<div class="hidden">
							<input type="hidden" class="field__label-name"/>
							<input type="hidden" class="field__ar-name"/>
						</div>
					</li>


					<li class="wi-form-fieldblock wi-form-fieldblock-custom">
						<div class="field-block--table">
							<div class="field-block field-block--cell">
								<small class="sublabel" style="background: #0074A2; color: white; border: none;"><?php 
esc_html_e( 'Field Type (Visible):', 'webinar-ignition' );
?></small>
								<input type="text" class="fieldblock field__ar-label" value="" disabled="disabled"/>
							</div>
							<div class="field-block field-block--cell">
								<small class="sublabel"><?php 
esc_html_e( 'Map to AR Form Field', 'webinar-ignition' );
?> (<span></span>):</small>
								<select class="fieldblock field__ar-mapping">
									<option value=""><?php 
esc_html_e( '* Not mapped', 'webinar-ignition' );
?></option>
								</select>
							</div>
						</div>
						<div class="field-block">
							<small id="placeHolderText" class="sublabel"><?php 
esc_html_e( 'Hidden Field Value:', 'webinar-ignition' );
?></small>
							<input class="fieldblock field__label" type="text"/>
						</div>
						<div class="field__actions">
							<!--					<a href="#" class="field__action js_fieldblock-move field__action--move">Order</a>-->
							<a href="#" class="field__action js__fieldblock-remove field__action--remove"><?php 
esc_html_e( 'Remove', 'webinar-ignition' );
?></a>
						</div>
						<div class="hidden">
							<input type="hidden" class="field__label-name"/>
							<input type="hidden" class="field__ar-name"/>
						</div>
					</li>

					<li class="wi-form-fieldblock wi-form-fieldblock-select">
						<div class="field-block--table">
							<div class="field-block field-block--cell">
								<small class="sublabel" style="background: #0074A2; color: white; border: none;"><?php 
esc_html_e( 'Field Type (Visible):', 'webinar-ignition' );
?></small>
								<input type="text" class="fieldblock field__ar-label" value="" disabled="disabled"/>
							</div>
							<div class="field-block field-block--cell">
								<small class="sublabel"><?php 
esc_html_e( 'Map to AR Form Field', 'webinar-ignition' );
?> (<span></span>):</small>
								<select class="fieldblock field__ar-mapping">
									<option value=""><?php 
esc_html_e( '* Not mapped', 'webinar-ignition' );
?></option>
								</select>
							</div>
						</div>
						<div class="field-block">
							<small id="placeHolderText" class="sublabel"><?php 
esc_html_e( 'Field label:', 'webinar-ignition' );
?></small>
							<input class="fieldblock field__label" type="text"/>
						</div>
						<div class="field-block">
							<small id="placeHolderText" class="sublabel"><?php 
esc_html_e( 'Field options:', 'webinar-ignition' );
?></small>
							<textarea class="fieldblock field__options"></textarea>
							<p>
								<?php 
esc_html_e( 'Enter each dropdown option on a new line.', 'webinar-ignition' );
?><br>

								<code><?php 
esc_html_e( 'Green', 'webinar-ignition' );
?></code><br>
								<code><?php 
esc_html_e( 'Blue', 'webinar-ignition' );
?></code><br>

								<?php 
esc_html_e( 'For more control, you may specify both a value (save to database) and label (visible in dropdown) like this:', 'webinar-ignition' );
?><br>

								<code>AU :: <?php 
esc_html_e( 'Australia', 'webinar-ignition' );
?></code><br>
								<code>US :: <?php 
esc_html_e( 'USA', 'webinar-ignition' );
?></code><br>

								<?php 
esc_html_e( 'If you want allow empty value put like this:', 'webinar-ignition' );
?><br>

								<code> :: -- <?php 
esc_html_e( 'select one', 'webinar-ignition' );
?> -- </code><br>
								<code>green :: <?php 
esc_html_e( 'Green', 'webinar-ignition' );
?></code><br>
								<code>blue :: <?php 
esc_html_e( 'Blue', 'webinar-ignition' );
?></code><br>

								<strong>
									<a href="https://webinarignition.tawk.help/article/dropdown-fields-in-webinar-registration" target="_blank">
										<?php 
esc_html_e( 'Help?', 'webinar-ignition' );
?>
									</a>
								</strong>
							</p>
						</div>
						<div class="field__actions">
							<input type="checkbox" class="required_ar" style="width: 20px !important; height: 20px !important; margin-right:15px;"> <span><?php 
esc_html_e( 'Required?', 'webinar-ignition' );
?></span>
							<a href="#" class="field__action js__fieldblock-remove field__action--remove"><?php 
esc_html_e( 'Remove', 'webinar-ignition' );
?></a>
						</div>
						<div class="hidden">
							<input type="hidden" class="field__label-name"/>
							<input type="hidden" class="field__ar-name"/>
						</div>
					</li>

					<li class="wi-form-fieldblock wi-form-fieldblock-invisible">
						<div class="field-block--table">
							<div class="field-block field-block--cell">
								<small class="sublabel" style="background: #EEEEEE; color: #777777; border: none;" ><?php 
esc_html_e( 'Field Type (Hidden):', 'webinar-ignition' );
?></small>
								<input type="text" class="fieldblock field__ar-label" value="" disabled="disabled"/>
							</div>
							<div class="field-block field-block--cell">
								<small class="sublabel"><?php 
esc_html_e( 'Map to AR Form Field', 'webinar-ignition' );
?> (<span></span>):</small>
								<select class="fieldblock field__ar-mapping">
									<option value=""><?php 
esc_html_e( '* Not mapped', 'webinar-ignition' );
?></option>
								</select>
							</div>
						</div>

						<div class="field__actions">

							<a href="#" class="field__action js__fieldblock-remove field__action--remove"><?php 
esc_html_e( 'Remove', 'webinar-ignition' );
?></a>
						</div>
						<div class="hidden">
							<input type="hidden" class="field__label-name"/>
							<input type="hidden" class="field__ar-name"/>
						</div>
					</li>
				</div>
				<div class="form-builder-hidden-field">
					<div class="field-block--table field-group">
						<div class="field-block field-block--cell">
							<small class="sublabel"><?php 
esc_html_e( 'Field name:', 'webinar-ignition' );
?></small>
							<input type="text" class="fieldblock fieldblock__name" value=""/>
						</div>
						<div class="field-block field-block--cell">
							<small class="sublabel"><?php 
esc_html_e( 'Field value:', 'webinar-ignition' );
?></small>
							<input type="text" class="fieldblock fieldblock__value" value=""/>
						</div>
					</div>
				</div>
			</div>
			<section class="wi wi__ar_section extracted-form_fields">

				<h2><?php 
esc_html_e( 'Available Fields:', 'webinar-ignition' );
?> </h2>
				<h3><?php 
esc_html_e( 'Visible Fields', 'webinar-ignition' );
?></h3>
				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_name"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'First Name field', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_lname"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Last Name field', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_salutation"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Title/Salutation field', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_reason"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Participation Reason field', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_email"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Email field', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_phone"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Phone field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_privacy_policy"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Privacy Policy Checkbox', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_terms_and_conditions"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Terms and Conditions Checkbox', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_mailing_list"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Mailing List Checkbox', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_1"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_2"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_3"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_4"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_5"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Checkbox Field', 'webinar-ignition' );
?>
				</div>


				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_6"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Checkbox Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_7"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Textarea Field', 'webinar-ignition' );
?>
				</div>


				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_15"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_16"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_17"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_18"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Dropdown Select Field', 'webinar-ignition' );
?>
				</div>


				<h3 style="margin-top: 20px;"><?php 
esc_html_e( 'Hidden Fields', 'webinar-ignition' );
?></h3>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_8"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>


				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_9"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_10"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_11"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_12"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_13"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>

				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_custom_14"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Custom Hidden Field', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_title"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Title', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_host"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Host', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_url"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar URL', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_date"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Date', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_time"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Time', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_timezone"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Time Zone', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_registration_date"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Registration Date', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_registration_time"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Registration Time', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_utm_source"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'UTM Source', 'webinar-ignition' );
?>
				</div>
				<div class="wi-form-field wi-form-field-invisible">
					<span class="wi-field-add" data-hidden="false" data-name="ar_webinar_date_time"><?php 
esc_html_e( 'add', 'webinar-ignition' );
?></span>
					<?php 
esc_html_e( 'Webinar Date & Time', 'webinar-ignition' );
?>
				</div>
				
				<ul id="wi-available-fields" class="content"></ul>
				<div id="ar_available_mappings" class="hidden" data-mappings=""></div>
				<div class="clear"></div>
			</section>
			<section class="wi wi__ar_section form_builder">
				<h2><?php 
esc_html_e( 'Form Builder', 'webinar-ignition' );
?></h2>
				<?php 
?>
				<div class="field-block field-block--cell field-block--form-action">
					<label for="ar_url"><?php 
esc_html_e( 'Form Action URL:', 'webinar-ignition' );
?></label>
					<?php 
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?> 
				</div>
				<?php 
?>
				<div class="field-block">
					<label>Form Fields: </label>
					<ul id="wi-form-builder" class="wi-form-builder">
						<li id="form-builder-gdpr-heading" style="display: none; ">
							<div class="editSection" style="padding: 0;">

								<div class="inputTitle" style="border: none; width: 100%;">
									<div class="inputTitleCopy"><h4><?php 
esc_html_e( 'GDPR Heading', 'webinar-ignition' );
?></h4></div>
									<div class="inputTitleHelp"><?php 
esc_html_e( 'This is the heading that is shown above the GDPR fields.', 'webinar-ignition' );
?></div>
								</div>

								<div class="inputSection" style="width: 100%;">
									<input class="inputField elem" placeholder="<?php 
esc_html_e( 'Ex. Please confirm that you', 'webinar-ignition' );
?>" type="text" name="gdpr_heading" id="gdpr_heading" value="<?php 
echo ( !empty( $webinar_data->gdpr_heading ) ? esc_html( $webinar_data->gdpr_heading ) : esc_html__( 'Please confirm that you', 'webinar-ignition' ) );
?>">
								</div>
								<br clear="left">

							</div>
						</li>
					</ul>

					<div class="wi-form-fields--hidden hidden">
						<div id="wi-form-hidden-fields" class="fieldblock__content"></div>
						<div class="field__actions">
							<a href="#" class="field__action js__fieldblock-remove field__action--remove"> <?php 
esc_html_e( 'Remove Hidden Fields', 'webinar-ignition' );
?></a>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</section>
			<div id="ar-settings" class="hidden ar-integration-settings">
				<?php 
$_props = array(
    'ar_url',
    'ar_method',
    'ar_name',
    'ar_lname',
    'ar_salutation',
    'ar_reason',
    'ar_phone',
    'ar_email',
    'ar_privacy_policy',
    'ar_terms_and_conditions',
    'ar_mailing_list',
    'ar_webinar_title',
    'ar_webinar_url',
    'ar_webinar_date',
    'ar_webinar_time',
    'ar_webinar_timezone',
    'ar_webinar_registration_date',
    'ar_webinar_registration_time',
    'ar_utm_source',
    'ar_webinar_date_time',
    'ar_webinar_host',
    'ar_hidden',
    'lp_optin_name',
    'lp_optin_lname',
    'lp_optin_salutation',
    'lp_optin_reason',
    'lp_optin_email',
    'lp_webinar_host',
    'lp_optin_phone',
    'lp_optin_privacy_policy',
    'lp_optin_terms_and_conditions',
    'lp_optin_mailing_list',
    'ar_fields_order',
    'ar_required_fields',
    'ar_new_row_fields',
    'ar_custom_1',
    'ar_custom_2',
    'ar_custom_3',
    'ar_custom_4',
    'ar_custom_5',
    'ar_custom_6',
    'ar_custom_7',
    'ar_custom_8',
    'ar_custom_9',
    'ar_custom_10',
    'ar_custom_11',
    'ar_custom_12',
    'ar_custom_13',
    'ar_custom_14',
    'ar_custom_15',
    'ar_custom_16',
    'ar_custom_17',
    'ar_custom_18',
    'lp_optin_custom_1',
    'lp_optin_custom_2',
    'lp_optin_custom_3',
    'lp_optin_custom_4',
    'lp_optin_custom_5',
    'lp_optin_custom_6',
    'lp_optin_custom_7',
    'lp_optin_custom_8',
    'lp_optin_custom_9',
    'lp_optin_custom_10',
    'lp_optin_custom_11',
    'lp_optin_custom_12',
    'lp_optin_custom_13',
    'lp_optin_custom_14',
    'lp_optin_custom_15',
    'lp_optin_custom_16',
    'lp_optin_custom_17',
    'lp_optin_custom_18',
    'lp_optin_custom_select_15',
    'lp_optin_custom_select_16',
    'lp_optin_custom_select_17',
    'lp_optin_custom_select_18'
);
foreach ( $_props as $_prop ) {
    if ( property_exists( $webinar_data, $_prop ) ) {
        if ( !is_array( $webinar_data->{$_prop} ) ) {
            if ( false !== strpos( $_prop, 'lp_optin_custom_select' ) ) {
                ?>
								<textarea name="<?php 
                echo esc_attr( $_prop );
                ?>"><?php 
                echo wp_kses_post( htmlentities( $webinar_data->{$_prop}, ENT_QUOTES, 'UTF-8' ) );
                ?></textarea>
								<?php 
            } else {
                ?>
								<input type="hidden" name="<?php 
                echo esc_attr( $_prop );
                ?>"
										value="<?php 
                echo wp_kses_post( htmlentities( $webinar_data->{$_prop}, ENT_QUOTES, 'UTF-8' ) );
                ?>"/>
								<?php 
            }
            ?>
							<?php 
        } else {
            foreach ( $webinar_data->{$_prop} as $_key => $_val ) {
                ?>
								<input type="hidden" name="<?php 
                echo esc_attr( $_prop );
                ?>[<?php 
                echo esc_attr( $_key );
                ?>]"
										value="<?php 
                echo wp_kses_post( htmlentities( $_val, ENT_QUOTES, 'UTF-8' ) );
                ?>"/>
								<?php 
            }
        }
        //end if
    }
    //end if
}
//end foreach
?>
			</div>
		</div>

		<?php 
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->ar_custom_date_format,
    esc_html__( 'Custom Date Format', 'webinar-ignition' ),
    'ar_custom_date_format',
    esc_html__( 'By default the AR form will submit date values in MM-DD-YYYY format and in most cases you can leave this at the default setting. But if your AR service requires you to use a different format, you can change it here.', 'webinar-ignition' ),
    'MM-DD-YYYY [MM-DD-YYYY], DD-MM-YYYY [DD-MM-YYYY], YYYY-MM-DD [YYYY-MM-DD]'
);
// fix :: ar integration test
// --------------------------------------------------------------------------------------
$ar_save_button = sprintf( '<div style="margin-top:6px; display: inline-block; padding: 10px 20px; background-color: #e64f1d; color: white; text-align: center; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: normal; border: none; width: fit-content;" id="wi_test_ar" data-test-url="%s">%s</div>', add_query_arg( 'artest', 1, WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'registration' ) ), esc_html__( 'Save & Test AR Integration', 'webinar-ignition' ) );
?>
<div class="wi-banner-wrap">
		<?php 
webinarignition_display_info( 
    esc_html__( 'AR Integration Help', 'webinar-ignition' ),
    /* translators: %s: HTML button for testing AR integration */
    sprintf( esc_html__( 'Use the button below to test your AR Integration setup. %s', 'webinar-ignition' ), $ar_save_button )
 );
?>
		</div>

		<?php 
// --------------------------------------------------------------------------------------
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->lp_optin_button,
    esc_html__( 'Optin Button Style', 'webinar-ignition' ),
    'lp_optin_button',
    esc_html__( 'You can choose between our optin button or your own custom image optin button...', 'webinar-ignition' ),
    esc_html__( 'CSS Button', 'webinar-ignition' ) . ' [color],' . esc_html__( 'Custom Image Button', 'webinar-ignition' ) . ' [image]'
);
?>

		<div class="lp_optin_button" id="lp_optin_button_color">
			<?php 
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->lp_optin_btn_color,
    esc_html__( 'Opt-in Button Background Color', 'webinar-ignition' ),
    'lp_optin_btn_color',
    esc_html__( 'This is the color you want the opt-in button to be. By default, it will be green.', 'webinar-ignition' ),
    '#74BB00'
);
?>
		</div>

		<div class="lp_optin_button" id="lp_optin_button_image" style="display:none;">
			<?php 
webinarignition_display_field_image_upd(
    $input_get['id'],
    $webinar_data->lp_optin_btn_image,
    esc_html__( 'Custom Button Image URL', 'webinar-ignition' ),
    'lp_optin_btn_image',
    esc_html__( 'This is the url for your custom optin button, for best results, it should be 327px wide...', 'webinar-ignition' ),
    esc_html__( 'http://yoursite.com/custom-optin-image.png', 'webinar-ignition' )
);
?>
		</div>

		<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->lp_optin_btn,
    esc_html__( 'Optin Button Copy', 'webinar-ignition' ),
    'lp_optin_btn',
    esc_html__( 'This is the text that is shown on the optin button...', 'webinar-ignition' ),
    esc_html__( 'e.g. Register For The Webinar', 'webinar-ignition' )
);
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->lp_optin_spam,
    esc_html__( 'Optin Spam Notice', 'webinar-ignition' ),
    'lp_optin_spam',
    esc_html__( 'This is the spam notice that is shown under the optin area... Helps a lot for conversion rates...', 'webinar-ignition' ),
    esc_html__( 'e.g. * Your data is safe with us *', 'webinar-ignition' )
);
webinarignition_display_wpeditor(
    $input_get['id'],
    $webinar_data->lp_optin_closed,
    esc_html__( 'Optin Closed Message', 'webinar-ignition' ),
    'lp_optin_closed',
    esc_html__( 'This is message displayed when the webinar registration is closed.', 'webinar-ignition' )
);
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->custom_ty_url_state,
    esc_html__( 'Thank You URL', 'webinar-ignition' ),
    'custom_ty_url_state',
    esc_html__( 'You can choose to keep default WebinarIgnition confirmation page, or redirect users to a custom URL.', 'webinar-ignition' ),
    esc_html__( 'Keep Default', 'webinar-ignition' ) . ' [hide],' . esc_html__( 'Custom URL', 'webinar-ignition' ) . ' [show]'
);
?>
		<div class="custom_ty_url_state" id="custom_ty_url_state_show" style="display: none;">
			<?php 
webinarignition_display_field(
    $input_get['id'],
    $webinar_data->custom_ty_url,
    esc_html__( 'Custom Thank You URL', 'webinar-ignition' ),
    'custom_ty_url',
    esc_html__( 'Instead of redirecting the user to the WebinarIgnition confirmation page, the user will be redirected to a custom thank you page that you define here.', 'webinar-ignition' ),
    'http://google.com'
);
?>
		</div>

		<?php 
webinarignition_display_option(
    $input_get['id'],
    ( isset( $webinar_data->get_registration_notices_state ) ? $webinar_data->get_registration_notices_state : '' ),
    esc_html__( 'Get Registration Notices', 'webinar-ignition' ),
    'get_registration_notices_state',
    esc_html__( 'You can choose to receive an email notification whenever someone registers.', 'webinar-ignition' ),
    esc_html__( 'disabled', 'webinar-ignition' ) . ' [hide],' . esc_html__( 'enabled', 'webinar-ignition' ) . ' [show]'
);
?>
		<div class="get_registration_notices_state" id="get_registration_notices_state_show" style="display: none;">
			<?php 
?>
				<div class="editSection">
					<div class="inputSection">
						<?php 
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?> 
					</div>
					<br clear="left">
				</div>
				<?php 
?>	
		</div>
	</div>

	<div class="bottomSaveArea">
		<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php 
esc_html_e( 'Save & Update', 'webinar-ignition' );
?></a>
	</div>

</div>
