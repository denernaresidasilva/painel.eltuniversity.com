<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
/**
 * @var $webinar_data
 */

$cta_position_default = 'outer';
$cta_position_allowed = 'overlay';

$cta_alignment_default = 'Center';

if (!empty($webinar_data->cta_alignment) ) {
    $cta_alignment_default = $webinar_data->cta_alignment;
}

if (!empty($webinar_data->cta_position) ) {
    $cta_position_default = $webinar_data->cta_position;
}

$cta_border_desktop = '';
$cta_border_mobile = '';

$show_main_cta = false;

if ($webinar_data->auto_action !== "time" && $cta_position_default === $cta_position_allowed) {
    $show_main_cta = true;
}

if (WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled($webinar_data)) {
    if (isset($webinar_data->cta_border_desktop) && 'no' === $webinar_data->cta_border_desktop) {
        $cta_border_desktop = ' cta_border_hide_desktop';
    }

    if (isset($webinar_data->cta_border_mobile) && 'no' === $webinar_data->cta_border_mobile) {
        $cta_border_mobile = ' cta_border_hide_mobile';
    }
}

if ($webinar_data->auto_action !== "time" && !empty($webinar_data->auto_action_max_width)) {
    $auto_action_max_width = $webinar_data->auto_action_max_width;
    ?>
    <style>
        #overlayOrderBTN {
            max-width: <?php echo esc_attr( $auto_action_max_width ); ?>px !important;
            margin: auto;
        }
    </style>
    <?php
}
$max_width = isset($webinar_data->auto_action_max_width) ? $webinar_data->auto_action_max_width : 0;
if (!empty($max_width)) {
    if (preg_match('/^\d+$/', $max_width)) {
        // Append 'px' to $max_width if it's a pure number
        $max_width = "{$max_width}px; margin: 0 auto;";
    }
    $max_width = "width:{$max_width}; margin: 0 auto;";
} else {
    $max_width = "width:60%; margin: 0 auto;";
}

$align_class = '';
$align_style = ''; 

if(!empty($webinar_data->cta_alignment)){
    if($webinar_data->cta_alignment === 'Left'){
        $align_style = 'margin:0px !important';
    }
    elseif($webinar_data->cta_alignment === 'Center'){
        $align_style = 'margin:0px auto';
    }
    elseif($webinar_data->cta_alignment === 'Right'){
        $align_style = '';
        $align_class = 'right_cta_class';
    }
    else{
        $align_style = '';
        $align_class = '';
    }
}
$cta_transparency_single = absint(isset( $webinar_data->auto_action_transparency ) ? $webinar_data->auto_action_transparency : 0);

if ( $cta_transparency_single > 100 ) {
    $cta_transparency_single = 100;
}
$cta_shadow_transparency = 100 - $cta_transparency_single;
$cta_transparency_single        = $cta_shadow_transparency / 100;
?>
<?php
if ($show_main_cta) {
?>
<div class="timedUnderArea <?php echo esc_attr($cta_border_desktop . $cta_border_mobile); ?> timedUnderAreaOverlay wi-cta-tab <?php echo esc_attr($align_class); ?>" id="wi-cta-default-overlay" style="display:none;<?php echo esc_attr($max_width . $align_style); ?> <?php echo ( $cta_transparency_single < 1 ) ? 'background-color: rgba(255, 255, 255, ' . floatval( $cta_transparency_single ) . ') !important;' : ''; ?>" data-cta-index="default">
    <div id="overlayOrderBTNCopy" style="color:#212529; font-size:initial;">
        <?php
        if ($show_main_cta) {
	        include WEBINARIGNITION_PATH . 'inc/lp/partials/print_cta.php';
        }
        ?>
    </div>

    <div id="overlayOrderBTNArea">
        <?php if ( $show_main_cta ) {
            if ($webinar_data->auto_action_url != "") {
                $btn_id = wp_unique_id( 'orderBTN_' );
                $bg_color = empty( $webinar_data->replay_order_color ) ? '#6BBA40' : $webinar_data->replay_order_color;
                $text_color = webinarignition_get_text_color_from_bg_color($bg_color);
                $hover_color = webinarignition_get_hover_color_from_bg_color($bg_color);
                $text_hover_color = webinarignition_get_text_color_from_bg_color($hover_color);
                ?>
                <style>
                    #<?php echo esc_attr($btn_id); ?> {
                        background-color: <?php echo esc_attr($bg_color); ?>;
                        color: <?php echo esc_attr($text_color); ?>;
                        white-space: normal;
                    }
                    #<?php echo esc_attr($btn_id); ?>:hover {
                        background-color: <?php echo esc_attr($hover_color); ?>;
                        color: <?php echo esc_attr($text_hover_color); ?>;
                    }
                </style>
                <a
                        href="<?php webinarignition_display( $webinar_data->auto_action_url, "#" ); ?>"
                        id="<?php echo esc_attr($btn_id); ?>"
                        target="_blank"
                        class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block"
                        style="border: 1px solid rgba(0,0,0,0.20); width: 100%; margin-top:0px;"
                >
                    <?php webinarignition_display( $webinar_data->auto_action_btn_copy, __("Click Here To Grab Your Copy Now", "webinar-ignition") ); ?>
                </a>
                <?php
            }
        } ?>
    </div>

</div>
<?php
}
?>