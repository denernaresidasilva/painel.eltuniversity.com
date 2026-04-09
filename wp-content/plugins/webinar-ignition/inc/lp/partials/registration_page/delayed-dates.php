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
<div class="eventDate fixed-type delayed-dates <?php echo esc_attr( $uid ); ?> <?php if($wi_use_fse_colors == 1) { echo "wi-ignore-fsc";} ?>"

<?php 
		if ( ! empty( $wi_shortcode_background_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_background_color ) . '; border-top: 1px solid ' . esc_attr( $wi_shortcode_contrast_color_only ) . '; border-bottom: 1px solid ' . esc_attr( $wi_shortcode_contrast_color_only ) . ';"';
		}
	?>
	
	>
	<?php
	$dateTime     = webinarignition_make_delayed_date( $webinar_data );
	$tz_abbr      = $dateTime->format( 'T' );
	$date_format  = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y';
	$delayed_date = webinarignition_get_translated_date( $dateTime->format($date_format), 'm-d-Y', $date_format );
	?>


<div class="wi-datetime-icon-wrap">
	<div class="wi-icon-wrap">
		<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'inc/lp/images/datebgnew.png' ); ?>"/>
	</div>
	<div class="wi-datetime">
		
		<span class="wi-date"
		<?php if ( !empty( $wi_shortcode_contrast_color ) ) : ?>
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			<?php endif; ?>
			>
			<?php 
				if ( ! empty( $delayed_date ) ) {
					echo esc_html( $delayed_date );
				} else {
					// Example date format (not real date, just sample)
					echo esc_html__( 'No date available', 'webinar-ignition' );
				}
			?>								
		</span>
		<span class="wi-time"
		<?php if ( !empty( $wi_shortcode_contrast_color ) ) : ?>
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			<?php endif; ?>
			>
		<?php
		echo esc_html( $webinar_data->auto_time_delayed ) . ' ';
		if ( 'user_specific' !== $webinar_data->delayed_timezone_type ) {
			echo esc_html( $tz_abbr );
		} else {
			?>
			<div class="user_specific_timezone_name">
				<?php
				if ( $webinar_data->auto_timezone_user_specific_name ) {
					echo esc_html(wp_strip_all_tags($webinar_data->auto_timezone_user_specific_name) );
				} else {
					?>
					<?php esc_attr_e( 'YOUR<br/>TIMEZONE', 'webinar-ignition' ); ?>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</span>
	</div>
</div>


	
	
	




	

	<input type="hidden" id="webinar_start_date" value="<?php echo esc_html( $dateTime->format( 'Y-m-d' ) ); ?>"/>
	<input type="hidden" id="webinar_start_time" value="<?php echo esc_html( $webinar_data->auto_time_delayed ); ?>"/>
	<input type="hidden" id="timezone_user"
			value="
			<?php
			if ( 'user_specific' !== $webinar_data->delayed_timezone_type ) {
				echo esc_html( $webinar_data->auto_timezone_delayed );
			}
			?>
					">
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
	<br clear="left"/>
</div>
