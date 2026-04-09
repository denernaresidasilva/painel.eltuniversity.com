<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html>
<head>

	<!-- META INFO -->
	<title><?php webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinar-ignition' ) ); ?></title>
	<meta name="description" content="<?php webinarignition_display( $webinar_data->webinar_desc,
	__( 'Join this amazing webinar May the 4th, and discover industry trade secrets!', 'webinar-ignition' ) ); ?>">
	<!-- SOCIAL INFO -->
	<meta property="og:title" content="<?php webinarignition_display( $webinar_data->webinar_desc, __( 'Amazing Webinar Training 101', 'webinar-ignition' ) ); ?>"/>
	<meta property="og:image" content="<?php webinarignition_display( $webinar_data->ty_share_image, '' ); ?>"/>

	<?php require 'css/webinar_css.php'; ?>

	<?php wp_head(); ?>
	<?php
		do_action( 'webinarignition_webinar_closed_before_head', $webinar_data );
	?>

</head>
<body class="webinar_closed">


<!-- TOP AREA -->
<div class="topArea">
	<div class="bannerTop">
		<?php
		if ( ! empty( $webinar_data->webinar_banner_image ) ) {
			echo '<img src="' . esc_url( $webinar_data->webinar_banner_image ) . '" />';
		}
		?>
	</div>
</div>

<!-- Main Area -->
<div class="mainWrapper">

	<!-- WEBINAR WRAPPER -->
	<div class="webinarWrapper container">
		<!-- CLOSED WEBINAR -->
		<div id="closed" class="webinarExtraBlock2">
			<?php webinarignition_display( $webinar_data->replay_closed, '<h1>' . __( 'Webinar Is Over', 'webinar-ignition' ) . '</h1>' ); ?>
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
