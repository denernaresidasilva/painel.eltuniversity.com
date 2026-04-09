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
		do_action( 'webinarignition_webinar_closed_before_head', $webinar_data );	?>

</head>
<body class="webinar_closed">
	<?php
		if ( isset( $_GET['lid'] ) || isset( $_GET['lid'] )) {
			$lead_id = isset( $_GET['lid'] );
		}
		$lead_id = sanitize_text_field($_GET['lid']);
		global $wpdb;
		$webinar_id = $webinar_data->id;
		$table_db_name = $wpdb->prefix . (WebinarignitionManager::webinarignition_is_auto_webinar($webinar_data) ? 'webinarignition_leads_evergreen' : 'webinarignition_leads');
		$registration_url = '';
		$default_registration_page_id  = WebinarignitionManager::webinarignition_get_webinar_post_id($webinar_data->id);
		$selected_page_id    = isset($webinar_data->custom_registration_page) && get_post_type( $webinar_data->default_registration_page ) == 'page' ? (array) $webinar_data->custom_registration_page : array($default_registration_page_id);
		$selected_page_id    = is_array($selected_page_id) ? array_unique(array_filter($selected_page_id)) : $selected_page_id;

		$saved_registration_page = empty($webinar_data->default_registration_page) || get_post_type( $webinar_data->default_registration_page ) != 'page' ? absint( $default_registration_page_id ) :absint($webinar_data->default_registration_page);
		$registration_url = get_permalink( $saved_registration_page );
		$lead = $wpdb->get_row($wpdb->prepare(
			"SELECT app_id, name, email 
			FROM {$table_db_name} 
			WHERE ID = %d AND app_id = %d", 
			$lead_id, $webinar_id
		), OBJECT); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( empty( $lead ) ) {
			// Sanitize input values
			

			// Prepare and execute the query

			$lead = $wpdb->get_row($wpdb->prepare(
				"SELECT app_id, name, email 
				FROM {$table_db_name} 
				WHERE hash_ID = %s AND app_id = %d", 
				$lead_id, $webinar_id
			), OBJECT);// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}
		if ( isset( $_GET['unsubnextwebinar'] ) ) {
			
			$lead_email = $lead->email;
			$webinar_id = $webinar_data->id;
			$resitricted_mails = get_option( 'webinar_data_restricted_mails'.$webinar_id, array() );
			$resitricted_mails[] = $lead_email;
			update_option('webinar_data_restricted_mails'.$webinar_id, $resitricted_mails);
			$is_unsubscribed_to_all = update_option( 'lead_data_restricted_mails'.$lead_email, false );
			?>
				<!-- Main Area -->
				<div class="mainWrapper">

					<!-- WEBINAR WRAPPER -->
					<div class="webinarWrapper container">
						<!-- unsubscribe WEBINAR -->
						<div id="closed" class="webinarExtraBlock2">
							<div> <strong><?php echo esc_html__('Webinar Title:', 'webinar-ignition'); ?></strong> <?php echo esc_html($webinar_data->webinarURLName2); ?>
								<span style="margin: 0 10px;">|</span>
								<strong><?php echo esc_html__('Webinar Host:', 'webinar-ignition'); ?></strong> <?php echo esc_html($webinar_data->webinar_host); ?>	
							</div>
							<?php
							webinarignition_display($webinar_data->replay_closed, '<h1>');
							echo wp_kses_post(
							// Translators: %s is the registration URL for resubscribing.
								sprintf(
							        /* translators: %s is the registration URL for resubscribing. */
									__('You are unsubscribed from next webinar notification. <br> Resubscribe by <a href="%s">registering again</a>.', 'webinar-ignition'),
									esc_url($registration_url)
								)
							);
							?>
						</div>

					</div>

				</div>
			<?php
				
		}elseif ( isset( $_GET['unsuballwebinar'] ) ) {
			
			$lead_email = $lead->email;
			
			update_option('lead_data_restricted_mails'.$lead_email, true);
			$webinar_restricted_emails = get_option( 'webinar_data_restricted_mails'.$webinar_id, array() );

			if( in_array($lead_email, $webinar_restricted_emails)){
				$key = array_search($lead_email, $webinar_restricted_emails);
				if ($key !== false) {
					unset($webinar_restricted_emails[$key]);
				}
				update_option( 'webinar_data_restricted_mails'.$webinar_id, $webinar_restricted_emails);
			}		
			?>
				<!-- Main Area -->
				<div class="mainWrapper">

					<!-- WEBINAR WRAPPER -->
					<div class="webinarWrapper container">
						<!-- unsubscribe WEBINAR -->
						<div id="closed" class="webinarExtraBlock2">
							<div> <strong><?php echo esc_html__('Webinar Title:', 'webinar-ignition'); ?></strong> <?php echo esc_html($webinar_data->webinarURLName2); ?>
								<span style="margin: 0 10px;">|</span>
								<strong><?php echo esc_html__('Webinar Host:', 'webinar-ignition'); ?></strong> <?php echo esc_html($webinar_data->webinar_host); ?>	
							</div>
							<?php
							webinarignition_display($webinar_data->replay_closed, '<h1>');
							echo wp_kses_post(
							// Translators: %s is the registration URL for resubscribing.
								sprintf(
							        /* translators: %s is the registration URL for resubscribing. */
									__('You are unsubscribed from all webinar notification. <br> Resubscribe by <a href="%s">registering again</a>.', 'webinar-ignition'),
									esc_url($registration_url)
								)
							);
							?>
						</div>

					</div>

				</div>
			<?php
		}

	?>

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

<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?>


<div id="fb-root"></div>
<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php'; ?>
<?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php'; ?>


<!--Extra code-->
<?php echo wp_kses_post( $webinar_data->footer_code ); ?>
</body>
</html>
