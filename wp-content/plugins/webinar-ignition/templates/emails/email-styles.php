<?php defined( 'ABSPATH' ) || exit;

// Load colors.

$bg                                 = get_option( 'webinarignition_email_background_color', '#e7e9e8' );
$body                               = get_option( 'webinarignition_email_body_background_color', '#FFFFFF' );
$text                               = get_option( 'webinarignition_email_text_color', '#000000' );
$base                               = get_option( 'webinarignition_headings_color', '#000000' );
$base_text                          = wi_light_or_dark( $base, '#202020', '#ffffff' );
$ing_float                          = get_option( 'header_img_algnmnt', 'none' );
$footer_bg_color                    = get_option( 'webinarignition_branding_background_color', '#000' );
$footer_txt_color                   = get_option( 'webinarignition_footer_text_color', '#3f3f3f' );
$webinarignition_email_font_size    = get_option( 'webinarignition_email_font_size', '14' );
$heading_bg                         = get_option( 'webinarignition_heading_background_color', '#000' );
$heading_txt_color                  = get_option( 'webinarignition_heading_text_color', '#fff' );
$line_height                        = get_option( 'webinarignition_body_text_line_height', 'normal' );



// Pick a contrasting color for links.
$link_color = wi_hex_is_light( $base ) ? $base : $base_text;

if ( wi_hex_is_light( $body ) ) {
	$link_color = wi_hex_is_light( $base ) ? $base_text : $base;
}

$bg_darker_10    = wi_hex_darker( $bg, 10 );
$body_darker_10  = wi_hex_darker( $body, 10 );
$base_lighter_20 = wi_hex_lighter( $base, 20 );
$base_lighter_40 = wi_hex_lighter( $base, 40 );
$text_lighter_20 = wi_hex_lighter( $text, 20 );
$text_lighter_40 = wi_hex_lighter( $text, 40 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.



?>
<style>
	table, td, div, h1, p {
				font-family: Arial, sans-serif;
			}
	body {
		padding: 0;
			background-color: <?php echo wp_kses_post( $body ); ?>;
			line-height: <?php echo esc_attr( $line_height ); ?>;
	}

	#article {
	background-color: <?php echo wp_kses_post( $body ); ?>;
	}

	tr#template_header_image img {
		margin-left: 0;
		margin-right: 0;
			float: <?php echo esc_attr( $ing_float ); ?>;
	}

	tr#heading {
		background-color: <?php echo esc_attr( $heading_bg ); ?>;
	}

	tr#heading h1{
		color: <?php echo esc_attr( $heading_txt_color ); ?>;
	}


	tr#content_row {
		background-color: <?php echo esc_attr( $bg ); ?>;
	}

	table#container {
		color: <?php echo esc_attr( $text ); ?>;
		font-size: <?php echo esc_attr( $webinarignition_email_font_size ) . 'px'; ?>;
	}

	tr#credit {
		color: <?php echo esc_attr( $footer_txt_color ); ?>;
	}

	tr#footer_row {
		background-color: <?php echo esc_attr( $footer_bg_color ); ?>;
		color:#fff;
	}

	body p{
			line-height: <?php echo esc_attr( $line_height ); ?>;
	}

	tr#footer_row a{
		text-decoration:none;
		color:#fff;
	}
</style>