<?php
/**
 * @var $webinar_data
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- META INFO -->
	<title><?php webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinar-ignition' ) ); ?></title>
	<meta name="description" content="<?php webinarignition_display( $webinar_data->webinar_desc,
	__( 'Join this amazing webinar May the 4th, and discover industry trade secrets!', 'webinar-ignition' ) ); ?>">
	<!-- SOCIAL INFO -->
	<meta property="og:title" content="<?php webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinar-ignition' ) ); ?>"/>
	<meta property="og:image" content="<?php webinarignition_display( $webinar_data->ty_share_image, '' ); ?>"/>
	<meta http-equiv="refresh" content="10;URL='<?php echo esc_url( WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'registration' ) ); ?>'" />

	<?php require 'css/webinar_css.php'; ?>

	<!-- CUSTOM JS -->
	<script>
		<?php if ( ! empty( $webinar_data->custom_replay_js ) ) { webinarignition_display( $webinar_data->custom_replay_js, '' ); } ?>
	</script>
	<!-- CUSTOM CSS -->
	<style>
		<?php if ( isset( $webinar_data->custom_replay_css ) ) { webinarignition_display( $webinar_data->custom_replay_css, '' ); } ?>
	</style>

	<?php wp_head(); ?>

</head>
<body class="webinar_closed" style="text-align: center;">


<!-- TOP AREA -->
<div class="topArea">
	<div class="bannerTop">
		<?php
		if ( ! empty( $webinar_data->webinar_banner_image ) ) {
			printf( '<img src="%s" alt="" />', esc_url( $webinar_data->webinar_banner_image ) );
		}
		?>
	</div>
</div>

<!-- Main Area -->
<div class="mainWrapper">

	<!-- WEBINAR WRAPPER -->
	<div class="webinarWrapper container">
		<!-- CLOSED WEBINAR -->
		<div id="wi_single_lead_notice" class="webinarExtraBlock2">
			<p style="margin: 5px">
				<?php esc_html_e( 'Looks like you already watching this webinar on another device/browser.', 'webinar-ignition' ); ?>
			</p>
			<p style="margin: 5px">
				<?php esc_html_e( 'You will be redirected to registration page shortly.', 'webinar-ignition' ); ?>
			</p>
		</div>

	</div>

</div>

<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>


<div id="fb-root"></div>
<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php'; ?>
<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php'; ?>


<!--Extra code-->
<?php webinarignition_footer($webinar_data); ?>

</body>
</html>
