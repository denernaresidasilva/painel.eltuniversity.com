<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registration page header template
 *
 * @var $template_number
 * @var $webinarId
 * @var $webinar_data
 * @var $is_webinar_available
 * @var $assets
 * @var $custom_lp_css_path
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- META INFO -->
	<title>
		<?php echo ! empty( $webinar_data->lp_metashare_title ) ? esc_html( $webinar_data->lp_metashare_title ) : esc_html__( 'Amazing Webinar', 'webinar-ignition' ); ?>
	</title>

	<meta name="description" content="<?php echo ! empty( $webinar_data->lp_metashare_desc ) ? esc_html( $webinar_data->lp_metashare_desc ) : esc_html__( 'Join this amazing webinar, and discover industry trade secrets!', 'webinar-ignition' ); ?>">

	<meta property="og:description" content="<?php echo ! empty( $webinar_data->lp_metashare_desc ) ? esc_html( $webinar_data->lp_metashare_desc ) : esc_html__( 'Join this amazing webinar, and discover industry trade secrets!', 'webinar-ignition' ); ?>">

	<?php if ( ! empty( $webinar_data->ty_share_image ) ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $webinar_data->ty_share_image ); ?>" />
	<?php endif; ?>

	<?php wp_head(); ?>

	<?php
		$dynamically_generated_css = require $custom_lp_css_path;

		/**
		 * Hook for webinar registration page header.
		 *
		 * @param obj    $webinar_data The current webinar data.
		 * @param int    $template_number The loading template presets number.
		 * @param string $custom_lp_css_path Dynamically generated css path.
		 */
		do_action( 'webinarignition/webinar_page_header', $webinar_data, $template_number, $dynamically_generated_css );
	?>
</head>

<body id="webinarignition" class="<?php echo 'wi_registration registration-tpl-' . esc_attr( $template_number ) . ' wi-version-' . esc_attr( WEBINARIGNITION_VERSION ); ?>">