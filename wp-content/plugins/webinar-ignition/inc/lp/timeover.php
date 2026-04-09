<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
?>
<!DOCTYPE html>
<html>
<head>

	<!-- META INFO -->
	<title><?php 
webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinar-ignition' ) );
?></title>
	<meta name="description" content="
	<?php 
webinarignition_display( $webinar_data->webinar_desc, __( 'Join this amazing webinar May the 4th, and discover industry trade secrets!', 'webinar-ignition' ) );
?>
	">
	<!-- SOCIAL INFO -->
	<meta property="og:title" content="<?php 
webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinar-ignition' ) );
?>"/>
	<meta property="og:image" content="<?php 
webinarignition_display( $webinar_data->ty_share_image, '' );
?>"/>

	<?php 
require 'css/webinar_css.php';
?>

	<!-- CUSTOM JS -->
	<script>
		<?php 
if ( isset( $webinar_data->custom_replay_js ) && !empty( $webinar_data->custom_replay_js ) ) {
    webinarignition_display( $webinar_data->custom_replay_js, '' );
}
?>
	</script>
	<!-- CUSTOM CSS -->
	<style>
		<?php 
if ( isset( $webinar_data->custom_replay_css ) && !empty( $webinar_data->custom_replay_css ) ) {
    webinarignition_display( $webinar_data->custom_replay_css, '' );
}
?>
	</style>

	<?php 
wp_head();
?>

</head>
<body class="webinar_closed">

<div class="topArea">
	<div class="bannerTop">
		<?php 
if ( !empty( $webinar_data->webinar_banner_image ) ) {
    printf( '<image src="%s" alt="" />', esc_url( $webinar_data->webinar_banner_image ) );
}
if ( isset( $_GET['preview'] ) && current_user_can( 'edit_posts' ) ) {
    //phpcs:ignore
    update_option( 'wi_lead_watch_time_[lead_id]', 0 );
}
?>
	</div>
</div>

<div class="mainWrapper">

	<!-- WEBINAR WRAPPER -->
	<div class="webinarWrapper container">
		<!-- CLOSED WEBINAR -->
		<div id="closed" class="webinarExtraBlock2">
			<?php 
?>
			
		</div>

	</div>

</div>

<?php 
require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php';
?>


<div id="fb-root"></div>
<?php 
require_once WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php';
require_once WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php';
?>


<!--Extra code-->
<?php 
webinarignition_footer( $webinar_data );
?>

<?php 
wp_footer();
?>
</body>
</html>
