<?php
/**
 * @var $is_host
 * @var $is_support
 * @var $webinar_id
 * @var $webinar_data
 * @var $post
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 switch_to_locale( $webinar_data->webinar_lang );
 unload_textdomain( 'webinar-ignition' );
 load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );

?><!DOCTYPE html>
<html lang="en" style="margin-top:0 !important;">
<head>
	<title><?php esc_html_e( 'WebinarIgnition - Live Webinar Console', 'webinar-ignition' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<?php wp_head(); ?>
</head>

<body 
    id="webinarignition_console"
    rel="js-data-provider"
    class="webinarignition console wi-console"
    data-webinar-id="<?php echo esc_attr( $webinar_id ); ?>"
    data-webinar-type="<?php echo esc_attr( webinarignition_is_auto( $webinar_data ) ? 'evergreen' : 'live' ); ?>"
    data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
    data-post-url="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
    data-webinar-url="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"
    data-webinarignition-url="<?php echo esc_url( WEBINARIGNITION_URL ); ?>"
    data-ajax-nonce="<?php echo esc_attr( wp_create_nonce( 'webinarignition_ajax_nonce' ) ); ?>"
    data-is-support="<?php echo esc_attr( empty( $is_support ) ? 'false' : 'true' ); ?>"
>

	
	<?php if ( ! $is_support && ! current_user_can( 'manage_options' ) ) : ?>
			<center>
				<h2 style="margin-top: 30px;"><?php esc_html_e( 'Not Available - Only Viewable<br> By Admin / Webinar Host', 'webinar-ignition' ); ?></h2>

				<p><?php esc_html_e( '* If you are seeing this as an error, please log into your WP Admin area... *', 'webinar-ignition' ); ?></p>
			</center>
	<?php die(); endif; ?>

	<!-- TOP AREA -->
	<div class="topArea">
		<div class="consoleLogo">
			<?php
			$logo = $assets . 'images/wi-logo.png';
			if ( ! empty( $webinar_data->live_console_logo ) ) {
				$logo = $webinar_data->live_console_logo;
			}
			?>
			<img src="<?php echo esc_url( $logo ); ?>">
		</div>
	</div>


	<!-- Main Area -->
	<div class="mainWrapper">

			<div class="activeQuestionsHeadline">



			<?php if (! $is_support) : ?>
				<a href="#" class="dashTopBTN button lc-btn small" tabID="dashboardTab">
					<i class="icon-cogs"></i>
					<?php esc_html_e('Console Dashboard', 'webinar-ignition'); ?>
				</a>
				<?php if (! $is_host) : ?>
					<a

						href="<?php echo esc_url(admin_url()) . 'admin.php?page=webinarignition-dashboard&id=' . esc_html($webinar_data->id); ?>" class="button small secondary lc-btn"
						target="_blank">
						<i class="icon-cog"></i><?php esc_html_e('Settings', 'webinar-ignition'); ?>
					</a>
					<?php
					global $wpdb;
					$tbl_webinarignition = $wpdb->prefix . 'webinarignition';
					// Check if post_id is found



					$webinar_id = $wpdb->get_var($wpdb->prepare("SELECT postID FROM {$tbl_webinarignition}  WHERE ID = %d LIMIT 1", $webinar_data->id));
					?>

					<?php if (! is_null($webinar_id)) : ?>
						<?php
						$webinar_preview_url = add_query_arg(array(
							'webinar' => '',
							'lid' => '[lead_id]',
							'preview' => 'true',
						), get_the_permalink($webinar_id));
						?>
						<a

							href="<?php echo esc_url($webinar_preview_url); ?>" class="button small secondary lc-btn"
							target="_blank">
							<i class="icon-desktop"></i><?php esc_html_e('Webinar', 'webinar-ignition'); ?>
						</a>
					<?php endif; ?>
				<?php endif; ?>


				<?php if (! $is_host) : ?>
					<a
						href="#"
						<?php echo ('AUTO' === $webinar_data->webinar_date) ? ' style="display:none;"' : ''; ?>
						class="dashTopBTN button secondary small lc-btn" tabID="onairTab">
						<i class="icon-microphone"></i> <?php esc_html_e('On Air', 'webinar-ignition'); ?>
					</a>
				<?php endif; ?>
				<a href="#" class="dashTopBTN button secondary small lc-btn" tabID="questionTab" id="questionTabBTN"><i class="icon-question-sign"></i> <?php esc_html_e('Manage Questions', 'webinar-ignition'); ?></a>
				<a href="#" class="dashTopBTN button secondary small lc-btn" tabID="leadTab" id="leadTabBTN"><i class="icon-group"></i> <?php esc_html_e('Manage Registrants', 'webinar-ignition'); ?> </a>
			<?php endif; ?>
		</div>


		<?php
		if ( ! $is_support ) {
			include 'dash.php';
			if ( ! $is_host ) {
				include 'air.php';
			}
		}

		// Questions
		require 'question.php';
		if ( ! $is_support ) {
			require 'lead.php';
		}
		?>
	</div>

	<?php require 'partials/footerArea.php'; ?>

	<div id="overlay" style="position: fixed; display: none;  width: 100%;  height: 100%;   top: 0;  left: 0;  right: 0; bottom: 0;  background-color: rgba(0,0,0,0.5);   z-index: 2;  cursor: pointer;"></div>
	<?php echo isset( $webinar_data->live_console_footer_code ) ? do_shortcode( $webinar_data->live_console_footer_code ) : ''; ?>
	<?php wp_footer(); 
	restore_previous_locale();
	?>


	<style>

		#webinarignition_console [class^="icon-"], #webinarignition_console [class*=" icon-"], #webinarignition_console [class^="icon-"]:before, #webinarignition_console [class*=" icon-"]:before {
			font-family: FontAwesome !important;
		}

	</style>

</body>
</html>
