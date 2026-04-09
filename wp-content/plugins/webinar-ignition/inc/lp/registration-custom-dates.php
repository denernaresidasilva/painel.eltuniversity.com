<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
?>
<div class="eventDate" style="border:none; margin:0px; padding: 0 0 10px 0;">
	<span class="autoTitle wi_auto_title1">
		<?php
		webinarignition_display(
			$webinar_data->auto_translate_headline1,
			__( 'Choose A Date To Attend', 'webinar-ignition' )
		);
		?>
	</span>
	<span class="autoSubTitle">
		<?php
		webinarignition_display(
			$webinar_data->auto_translate_subheadline1,
			__( 'Select a date that best suits your schedule', 'webinar-ignition' )
		);
		?>
	</span>

	<select id="webinar_start_date">
		<option value="none"><?php esc_html_e( 'Loading Available Dates', 'webinar-ignition' ); ?></option>
	</select>

	<div class="autoSep autoSep-c" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> ></div>
	<div id="webinarTime" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> >
		<span class="autoTitle wi_auto_title_2"><?php webinarignition_display( $webinar_data->auto_translate_headline2, __( 'What Time Is Best For You', 'webinar-ignition' ) ); ?></span>
		<select id="webinar_start_time">
			<?php
			if ( 'no' !== $webinar_data->auto_time_1 ) {
				printf(
					'<option value="%s">%s</option>',
					esc_html( $webinar_data->auto_time_1 ),
					esc_html( webinarignition_auto_custom_time( $webinar_data, $webinar_data->auto_time_1 ) )
				);
			}

			if ( 'no' !== $webinar_data->auto_time_2 ) {
				printf(
					'<option value="%s">%s</option>',
					esc_html( $webinar_data->auto_time_2 ),
					esc_html( webinarignition_auto_custom_time( $webinar_data, $webinar_data->auto_time_2 ) )
				);
			}

			if ( 'no' !== $webinar_data->auto_time_3 ) {
				printf(
					'<option value="%s">%s</option>',
					esc_html( $webinar_data->auto_time_3 ),
					esc_html( webinarignition_auto_custom_time( $webinar_data, $webinar_data->auto_time_3 ) )
				);
			}
			?>
		</select>
	</div>
	<input type="hidden" id="timezone_user" value="<?php echo 'fixed' === $webinar_data->auto_timezone_type ? esc_html( $webinar_data->auto_timezone_custom ) : ''; ?>">
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
</div>
<?php
	restore_previous_locale();

