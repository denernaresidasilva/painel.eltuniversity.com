<?php

/**
 * @var $webinar_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );



if (
	empty( $webinar_cta_by_position )
	|| empty( $webinar_cta_by_position['is_time'] )
	|| empty( $webinar_cta_by_position['overlay'] )
) {
	$additional_autoactions = array();
} else {
	$additional_autoactions = $webinar_cta_by_position['overlay'];
}

$cta_border_desktop = '';
$cta_border_mobile  = '';

if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) ) {
	if ( isset( $webinar_data->cta_border_desktop ) && 'no' === $webinar_data->cta_border_desktop ) {
		$cta_border_desktop = ' cta_border_hide_desktop';
	}

	if ( isset( $webinar_data->cta_border_mobile ) && 'no' === $webinar_data->cta_border_mobile ) {
		$cta_border_mobile = ' cta_border_hide_mobile';
	}
}


foreach ( $additional_autoactions as $index => $additional_autoaction ) {

	$auto_action_title = __( 'Click here', 'webinar-ignition' );

	if ( ! empty( $additional_autoaction['auto_action_title'] ) ) {
		$auto_action_title = $additional_autoaction['auto_action_title'];
	} elseif ( isset( $additional_autoaction['auto_action_btn_copy'] ) ) {
		$auto_action_title = $additional_autoaction['auto_action_btn_copy'];
	}

	$max_width = isset( $additional_autoaction['auto_action_max_width'] ) ? $additional_autoaction['auto_action_max_width'] : 0;
	if ( ! empty( $max_width ) ) {
		if ( preg_match( '/^\d+$/', $max_width ) ) {
			// Append 'px' to $max_width if it's a pure number
			$max_width = "{$max_width}px; margin: 0 auto;";
		}
		$max_width = "width:{$max_width}; margin: 0 auto;";
	} else {
		$max_width = 'width:60%; margin: 0 auto;';
	}

	$align_class = '';
	$align_style = '';

	if ( ! empty( $additional_autoaction['cta_alignment'] ) ) {
		if ( 'Left' === $additional_autoaction['cta_alignment'] ) {
			$align_style = 'margin:0px !important;';
		} elseif ( 'Center' === $additional_autoaction['cta_alignment'] ) {
			$align_style = 'margin:0px auto;';
		} elseif ( 'Right' === $additional_autoaction['cta_alignment'] ) {
			$align_style = '';
			$align_class = 'right_cta_class';
		} else {
			$align_style = '';
			$align_class = '';
		}
	}

	$cta_transparency_single = absint(isset( $additional_autoaction['auto_action_transparency'] ) ? $additional_autoaction['auto_action_transparency'] : 0);
	if ( $cta_transparency_single > 100 ) {
		$cta_transparency_single = 100;
	}
	$cta_shadow_transparency = 100 - $cta_transparency_single;
	$cta_transparency_single = $cta_shadow_transparency / 100;
	$background_style =  ( $cta_transparency_single < 1 ) ? 'background-color: rgba(255, 255, 255, ' . $cta_transparency_single . ') !important;': '';
	?>
	<style>
		.timedUnderAreaOverlay {
			padding: 15px !important;
		}
		.timedUnderArea.timedUnderAreaOverlay, .additional_autoaction_item{
		}
		#wi-cta-<?php echo absint( $index ); ?>-tab{
			<?php
				echo ( $cta_transparency_single < 1 ) ? 'background-color: rgba(255, 255, 255, ' . floatval( $cta_transparency_single ) . ') !important;' : '';	
			?>
		}
	</style>
	<div id="wi-cta-<?php echo absint( $index ); ?>-tab" class="Test_Class_HOLA additional_autoaction_item timedUnderArea <?php echo esc_attr( $cta_border_desktop )?> <?php echo esc_attr( $cta_border_mobile ); ?> timedUnderAreaOverlay wi-cta-tab <?php echo esc_attr( $align_class ); ?>" style="z-index:-1; <?php echo esc_attr( $max_width );?> <?php echo esc_attr( $align_style ); ?>">
		<div class="wig_overlayOrderBTNCopy" id="overlayOrderBTNCopy_<?php echo absint( $index ); ?>">
			<?php
			include WEBINARIGNITION_PATH . 'inc/lp/partials/print_cta.php';
			?>
		</div>
 
		<div id="overlayOrderBTNArea_<?php echo absint( $index ); ?>">
			<?php
			if ( ! empty( $additional_autoaction['auto_action_url'] ) ) :
				$btn_id     = wp_unique_id( 'orderBTN_' );
				$bg_color   = empty( $additional_autoaction['replay_order_color'] ) ? '#6BBA40' : $additional_autoaction['replay_order_color'];
				$text_color = webinarignition_get_text_color_from_bg_color( $bg_color );

				$hover_color      = webinarignition_get_hover_color_from_bg_color( $bg_color );
				$text_hover_color = webinarignition_get_text_color_from_bg_color( $hover_color );
				?>
				<style>
					#<?php echo esc_html( $btn_id ); ?> {
						background-color: <?php echo esc_html( $bg_color ); ?>;
						color: <?php echo esc_html( $text_color ); ?>;
						white-space: normal;
					}

					#<?php echo esc_html( $btn_id ); ?>:hover {
						background-color: <?php echo esc_html( $hover_color ); ?>;
						color: <?php echo esc_html( $text_hover_color ); ?>;
					}
				</style>
				<a href="<?php webinarignition_display( $additional_autoaction['auto_action_url'], '#' ); ?>" id="<?php echo esc_attr( $btn_id ); ?>" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-evergreen-btn" style="border: 1px solid rgba(0,0,0,0.20);">
					<?php webinarignition_display( $additional_autoaction['auto_action_btn_copy'], __( 'Click Here To Grab Your Copy Now', 'webinar-ignition' ) ); ?>
				</a>
			<?php endif ?>
		</div>
	</div>
	<?php
}//end foreach
