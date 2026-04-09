<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
//phpcs:disable

$css = '';

/*TOP AREA CSS STUFF*/
$css .= '.topArea{';
	if ( 'hide' === $webinar_data->lp_banner_bg_style ) {
		$css .= 'display: none;';
	}

	$css .= sprintf( 'background-color:%s;', empty( $webinar_data->lp_banner_bg_color ) ? '#FFF' : $webinar_data->lp_banner_bg_color );

	if ( empty( $webinar_data->lp_banner_bg_repeater ) ) {
		$css .= 'border-top: 3px solid rgba(0,0,0,0.20); border-bottom: 3px solid rgba(0,0,0,0.20);';
	} else {
		$css .= "background-image: url($webinar_data->lp_banner_bg_repeater);";
	}
$css .= '}';

$css .= ' .mainWrapper{';
	$css .= sprintf( 'background-color:%s;', empty( $webinar_data->lp_background_color ) ? '#f1f1f1' : $webinar_data->lp_banner_bg_color );
	if ( empty( $webinar_data->lp_background_image ) ) {
		$css .= 'border-top: 3px solid rgba(0,0,0,0.05); border-bottom: 3px solid rgba(0,0,0,0.05);';
	} else {
		$css .= "background-image: url($webinar_data->lp_background_image);";
	}
$css .= '}';

$reg_CTA_BG = '#212121';
if ( isset( $webinar_data->lp_cta_bg_color ) && ! empty( $webinar_data->lp_cta_bg_color ) ) {
	$reg_CTA_BG = $webinar_data->lp_cta_bg_color;
}

if ( 'transparent' === $reg_CTA_BG ) {
	$css .= '.ctaArea {
		border:none;
	}
	.ctaArea.video {
		padding:0;
	}';
}

$css .= '.ctaArea.video {
	background-color:' . $reg_CTA_BG . ';
}';

$css .= '.innerHeadline{';
	$css .= sprintf( 'background-color:%s;', empty( $webinar_data->lp_sales_headline_color ) ? '#0496AC' : $webinar_data->lp_sales_headline_color );
$css .= '}';

$btn_color = empty( $webinar_data->lp_optin_btn_color ) ? '#74BB00' : $webinar_data->lp_optin_btn_color;
$hexCode   = ltrim( $btn_color, '#' );
if ( strlen( $hexCode ) === 3 ) {
	$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
}

if ( strlen( $hexCode ) === 3 ) {
	$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
}

$hoverCode = array_map( 'hexdec', str_split( $hexCode, 2 ) );

$adjustPercent = -0.05;
foreach ( $hoverCode as & $color ) {
	$adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
	$adjustAmount    = ceil( $adjustableLimit * $adjustPercent );

	$color = str_pad( dechex( $color + $adjustAmount ), 2, '0', STR_PAD_LEFT );
}

$hover_color = '#' . implode( $hoverCode );

$r          = hexdec( substr( $btn_color, 1, 2 ) );
$g          = hexdec( substr( $btn_color, 3, 2 ) );
$b          = hexdec( substr( $btn_color, 5, 2 ) );
$yiq        = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
$text_color = ( $yiq >= 198 ) ? 'black' : 'white';

$css .= '#optinBTN, #verifyEmailBTN, .wi_arrow_button {';
	$css .= 'background-color: ' . $btn_color . ';';
	$css .= 'color: ' . $text_color . ';';
$css .= '}';

$css .= '#optinBTN:hover, #verifyEmailBTN:hover, .wi_arrow_button:hover {';
	$css .= 'background-color: ' . $hover_color . ';';
	$css .= 'color: ' . $text_color . ';';
$css .= '}';

$css .= '#optinBTN.optinBTNimg, #verifyEmailBTN.optinBTNimg, .wi_arrow_button.optinBTNimg {
	display: inline-block;
	width: auto;
	max-width: 100%;
	border: none;
	background-color: transparent;
}';

return $css;
