<?php
/**
 * @var $webinar_data
 * @var $uid
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

<div 
	class="eventDate fixed-type <?php echo esc_attr( $uid ); ?> <?php echo ( $wi_use_fse_colors == 1 ) ? 'wi-ignore-fsc' : ''; ?>"
	<?php 
		if ( ! empty( $wi_shortcode_background_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_background_color ) . '; border-top: 1px solid ' . esc_attr( $wi_shortcode_contrast_color_only ) . '; border-bottom: 1px solid ' . esc_attr( $wi_shortcode_contrast_color_only ) . ';"';
		}
	?>
>

	<?php

	if (preg_match('/UTC([+-]\d+(\.\d+)?)/', $webinar_data->auto_timezone_fixed, $matches)) {
        // Convert offset to valid "+HH:MM" format
        $offset = floatval($matches[1]);
        $hours = floor($offset);
        $minutes = ($offset - $hours) * 60;
        $timezone_offset = sprintf('%+03d:%02d', $hours, abs($minutes));
    } else {

        // Assume the input is already a valid timezone identifier
        $timezone_offset = $webinar_data->auto_timezone_fixed;
    }

    // Create a DateTimeZone object with the validated or transformed timezone
    $dateTimeZone = new DateTimeZone($timezone_offset);
    $dateTime = new DateTime();
    $dateTime->setTimeZone($dateTimeZone);
	$tz_abbr     = $dateTime->format( 'T' );
	$tz_name     = $dateTime->getTimezone()->getName();
	$tz_abbr     = $tz_name.' ('.$tz_abbr.') ';

	$split_date = !empty($webinar_data->auto_date_fixed) ? explode('-', $webinar_data->auto_date_fixed) : [];
	$date_format = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';
	$auto_date   = webinarignition_get_translated_date( $webinar_data->auto_date_fixed, 'Y-m-d', $date_format );
	?>
	
	<div class="wi_fix_wrap">

		<div class="wi_img_wrap">

			<img width="50px" src="<?php echo esc_url( WEBINARIGNITION_URL . 'inc/lp/images/datebgnew.png' ); ?>"/>

		</div>

		<div class="wi_date_and_time_wrap">

			<span 
				class="fixed-web-date"
				<?php 
					if ( ! empty( $wi_shortcode_contrast_color ) ) {
						echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
					}
				?>
			>
				<?php echo esc_html( $auto_date ); ?>
			</span>


		<span 
			class="fixed-web-time" 
			<?php if ( !empty( $wi_shortcode_contrast_color ) ) : ?>
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			<?php endif; ?>
		>
			<?php echo esc_html( $webinar_data->auto_time_fixed ); ?> 
			<div class="wi_fixed_timezone"><?php echo esc_html( $tz_abbr ); ?></div>
		</span>


		</div>

	</div>

	
	<input type="hidden" id="webinar_start_date"
			value="<?php echo isset($split_date[2]) ? esc_html($split_date[2]) : ''; ?>-<?php echo isset($split_date[0]) ? esc_html($split_date[0]) : ''; ?>-<?php echo isset($split_date[1]) ? esc_html($split_date[1]) : ''; ?>"/>
	<input type="hidden" id="webinar_start_time" value="<?php echo esc_html( $webinar_data->auto_time_fixed ); ?>"/>
	<input type="hidden" id="timezone_user" value="<?php echo esc_html( $webinar_data->auto_timezone_fixed ); ?>">
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
	<br clear="left"/>
</div>
