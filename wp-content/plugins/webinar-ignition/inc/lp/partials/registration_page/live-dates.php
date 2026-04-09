<?php
/**
 * @var $webinar_data
 * @var $uid
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 * @var $autoDate_format
 * @var $autoTime
 * @var $is_compact
 * @var $webinarignition_shortcode_params;
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 global $webinarignition_shortcode_params;
$wi_use_fse_colors = get_option('webinarignition_use_grid_custom_color', 0); 
if ( function_exists( 'wi_get_shortcode_background_color_style' ) ) {
    $wi_shortcode_background_color = wi_get_shortcode_background_color_style( $webinarignition_shortcode_params, $webinar_data->id );
}

if ( function_exists( 'wi_get_shortcode_contrast_color_style' ) ) {
    $wi_shortcode_contrast_color = wi_get_shortcode_contrast_color_style( $webinarignition_shortcode_params, $webinar_data->id );	
    $wi_shortcode_contrast_color_only = wi_get_shortcode_contrast_color_style( $webinarignition_shortcode_params, $webinar_data->id, true );	
}
?>
 
<div class="eventDate <?php echo esc_attr( $uid ); ?> wi-live-dates-wrap <?php if($wi_use_fse_colors == 1) { echo "wi-ignore-fsc";} ?>"

<?php 
		if ( ! empty( $wi_shortcode_background_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_background_color ) . '; border-top: 1px solid ' . esc_attr( $wi_shortcode_contrast_color_only ) . '; border-bottom: 1px solid ' . esc_attr( $wi_shortcode_contrast_color_only ) . ';"';
		}

	?>
	
	>
	<!-- code location 289 -->
	<div class="dateIcon">
		<div class="dateMonth">
			<?php // echo esc_html( $localized_month ); ?>
		</div>
		<div class="dateDay">
			<?php
			// echo esc_html( $webinarDateObject->format( 'd' ) );
			// echo ( 'en' === substr( get_locale(), 0, 2 ) ) ? '' : '.';
			?>
		</div>

			<div class="dateDayWeek">
				<?php // echo esc_html( $localized_week_day ); ?>
			</div>
	</div>

	<?php
	if ( ! $is_compact ) {
		if (preg_match('/UTC([+-]\d+(\.\d+)?)/', $webinar_data->webinar_timezone, $matches)) {
			// Convert offset to valid "+HH:MM" format
			$offset = floatval($matches[1]);
			$hours = floor($offset);
			$minutes = ($offset - $hours) * 60;
			$timezone_offset = sprintf('%+03d:%02d', $hours, abs($minutes));
		} else {
	
			// Assume the input is already a valid timezone identifier
			$timezone_offset = $webinar_data->webinar_timezone;
		}
		if (!in_array($timezone_offset, timezone_identifiers_list())) {
			error_log("Invalid timezone: " . $timezone_offset);
			$timezone_offset = 'UTC'; // Fallback to UTC
		}
		$dateTimeZone = new DateTimeZone($timezone_offset);		$dateTime = new DateTime();
		$dateTime->setTimeZone($dateTimeZone);
		$tz_abbr     = $dateTime->format( 'T' );
		$tz_name     = $dateTime->getTimezone()->getName();
		if ( ! empty( $webinar_data->display_tz ) && ( $webinar_data->display_tz == 'yes' ) ) {
			$tz_abbr     = $tz_name.' ('.$tz_abbr.') ';
		}else{
			$tz_abbr     = '';
		}
		
		?>
		<div class="dateInfo wi_date_time_wrap">
			<div class="dateHeadline"
				<?php if ( !empty( $wi_shortcode_contrast_color ) ) : ?>
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			<?php endif; ?>
			><?php  echo esc_html( $localized_date ); ?></div>
			
			<div class="dateSubHeadline"
				<?php if ( !empty( $wi_shortcode_contrast_color ) ) : ?>
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			<?php endif; ?>
			>
				<?php
				$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date. ' '. $webinar_data->webinar_start_time, new DateTimeZone( $webinar_data->webinar_timezone ) );	
				if($webinarDateObject === false) {
    				$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y '.$webinar_data->time_format, $webinar_data->webinar_date. ' '. $webinar_data->webinar_start_time, new DateTimeZone( $webinar_data->webinar_timezone ) );	

				}
				$webinarTime = $webinarDateObject->format($webinar_data->time_format);

				$webinar_duration =  isset( $webinar_data->webinar_start_duration ) ? sanitize_text_field($webinar_data->webinar_start_duration ) : 60;
				/* translators: %s: Webinar duration in minutes. */	
				echo esc_html($webinarTime) . " | " . esc_html( sprintf( __( 'Duration: %s minutes', 'webinar-ignition' ), $webinar_duration ) );
				?>
			</div>
		</div>
		<?php
	}
	?>
	<br clear="left"/>
</div>
