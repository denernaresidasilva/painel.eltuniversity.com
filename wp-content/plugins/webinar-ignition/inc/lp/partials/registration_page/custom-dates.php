<?php
/**
 * @var $webinar_data
 * @var $uid
 * @var $is_compact
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $webinarignition_shortcode_params;

// Default inline style

// Check if a background color is set for this webinar ID and is not "default"
$inline_style = 'border:none; margin:0px;';

/*
 * This check is important; otherwise, due to the plugin structure, 
 * sometimes when we visit wp-admin it tries to check this function,
 * and if it’s not found, it throws an error.
 */

$wi_shortcode_contrast_color = ''; // Define empty variable to prevent undefined variable errors

if ( function_exists( 'wi_get_shortcode_contrast_color_style' ) ) {
    $wi_shortcode_contrast_color = wi_get_shortcode_contrast_color_style( $webinarignition_shortcode_params, $webinar_data->id );
}

if ( function_exists( 'wi_get_shortcode_background_color_style' ) ) {
    $inline_style .= wi_get_shortcode_background_color_style( $webinarignition_shortcode_params, $webinar_data->id );
}
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );

// WebinarignitionManager::webinarignition_set_locale( $webinar_data );
$wi_use_fse_colors = get_option('webinarignition_use_grid_custom_color', 0);
?>
<div class="eventDate <?php echo esc_attr( $uid ); ?> evergreen-Registration <?php if($wi_use_fse_colors == 1) { echo "wi-ignore-fsc";} ?>" style="<?php echo esc_attr( $inline_style ); ?>">

	<div class="wiFormGroup wiFormGroup-lg">
		<?php
		if ( ! $is_compact ) {
			?>
			<label 
				for="webinar_start_date" 
				class="wi-label" 
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			>
				
					<?php
					webinarignition_display(
						$webinar_data->auto_translate_headline1,
						__( 'Choose A Date To Attend', 'webinar-ignition' )
					);
					?>
				
					<h5 
					class="autoSubTitle"
					style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
					>
					<?php
					webinarignition_display(
						$webinar_data->auto_translate_subheadline1,
						__( 'Select a date that best suits your schedule', 'webinar-ignition' )
					);
					?>
				</h5>
			</label>
			<?php
		}//end if
		?>
		<select id="webinar_start_date" class="wiFormControl">
			<option value="none"><?php esc_html_e( 'Loading Available Dates', 'webinar-ignition' ); ?></option>
		</select>
	</div>

	<div class="autoSep autoSep-d" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> ></div>
	<div id="webinarTime" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> >
		<div class="wiFormGroup wiFormGroup-lg">
			<?php
			if ( ! $is_compact ) {
				?>
				
				<label 
    for="webinar_start_time" 
    class="wi-label" 
    style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
>
    <?php webinarignition_display( 
        $webinar_data->auto_translate_headline2, 
        __( 'What Time Is Best For You', 'webinar-ignition' ) 
    ); ?>
</label>

				<?php
			}
			?>

			<select id="webinar_start_time" class="wiFormControl">
				<?php

				$webinar_times = array();

				if ( isset( $webinar_data->auto_time_1 ) && 'no' !== $webinar_data->auto_time_1 ) {
					$webinar_times[] = $webinar_data->auto_time_1;
				}

				if ( isset( $webinar_data->auto_time_2 ) && 'no' !== $webinar_data->auto_time_2 ) {
					$webinar_times[] = $webinar_data->auto_time_2;
				}

				if ( isset( $webinar_data->auto_time_3 ) && 'no' !== $webinar_data->auto_time_3 ) {
					$webinar_times[] = $webinar_data->auto_time_3;
				}

				// if ( isset( $webinar_data->auto_time_4 ) && 'no' !== $webinar_data->auto_times_4 ) {
				// 	$webinar_time[] = $webinar_data->auto_time_4;
				// }

				$is_multiple_auto_time_enabled = WebinarignitionPowerups::webinarignition_is_multiple_auto_time_enabled( $webinar_data );

				if ( $is_multiple_auto_time_enabled && ! empty( $webinar_data->multiple__auto_time ) ) {
					foreach ( $webinar_data->multiple__auto_time as $index => $item ) {
						if ( 'no' !== $item ) {
							$webinar_times[] = $item;
						}
					}
				}

				$webinar_times = array_unique( $webinar_times );

				usort(
					$webinar_times,
					function ( $time1, $time2 ) {
						return ( strtotime( $time1 ) < strtotime( $time2 ) ) ? -1 : 1;
					}
				);

				foreach ( $webinar_times as $index => $item ) {
					printf(
						'<option value="%s">%s</option>',
						esc_html( $item ),
						esc_html( webinarignition_auto_custom_time( $webinar_data, $item ) )
					);
				}
				?>
			</select>
		</div>
	</div>
	<input
		type="hidden"
		id="timezone_user"
		value="<?php echo 'fixed' === $webinar_data->auto_timezone_type ? esc_html( $webinar_data->auto_timezone_custom ) : ''; ?>"
	>
	<input
		type="hidden"
		id="webinar_timezone"
		value="<?php echo 'fixed' === $webinar_data->auto_timezone_type ? esc_html( $webinar_data->auto_timezone_custom ) : ''; ?>"
	>
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
</div>

<?php
WebinarignitionManager::webinarignition_restore_locale( $webinar_data );

$order_id = WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) && WebinarignitionManager::webinarignition_get_paid_webinar_type( $webinar_data ) === 'woocommerce' && WebinarignitionManager::webinarignition_url_has_valid_wc_order_id();
global $wpdb;

if ( $order_id ) {
	$user = WebinarignitionManager::webinarignition_get_user_from_wc_order_id();
} else {
	$user = wp_get_current_user();
}

$selected_date     = null;
$selected_time     = null;
$selected_datetime = null;
$user_id           = 0;
if ( ! empty( $user ) && isset( $user->user_email ) && ! empty( $user->user_email ) ) {
	$user_id = $user->ID;
}
restore_previous_locale();
