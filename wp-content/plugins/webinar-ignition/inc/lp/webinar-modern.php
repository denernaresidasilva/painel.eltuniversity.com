<?php
/**
 * @var $webinar_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action( 'admin_bar_menu', 'webinarignition_admin_bar_links', 999 );
// Add custom links to admin bar
if ( ! function_exists( 'webinarignition_admin_bar_links' ) ) {
	function webinarignition_admin_bar_links() {
		global $wpdb;
		// Check if HTTPS is set and non-empty
		$protocol = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS']) ? 'https://' : 'http://';
		$host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		// Get the current URL with the determined protocol
		$url     = $protocol . $host . $request_uri;
		$pattern = '/\/([^\/?]+)\/?\?/';
		preg_match( $pattern, $url, $matches );
		if ( isset( $matches[1] ) ) {
			$webinar_name = sanitize_title($matches[1]);
			$website_url  = home_url();
			if ( isset( $_GET['page_id'] ) && is_numeric( $_GET['page_id'] ) && $_GET['page_id'] > 0 ) { //phpcs:ignore
				$page_id = absint( wp_unslash( $_GET['page_id'] ) );//phpcs:ignore
				$console_link = "$website_url?page_id=" . $page_id . '&console#/dashboard';
			} else {
				$console_link = "$website_url/$webinar_name/?console#/dashboard";
			}

			if ( is_super_admin() ) {
				global $wp_admin_bar;
				// Add parent dropdown menu
				$wp_admin_bar->add_menu(
					array(
						'id'     => 'webinarignition_menu',
						'title'  => __( 'WebinarIgnition', 'webinar-ignition' ),
						'href'   => '#',
						'parent' => 'top-secondary', // Place it in the secondary top-level menu
						'meta'   => array( 'class' => 'custom-menu' ),
					)
				);

				// Add child links
				$wp_admin_bar->add_menu(
					array(
						'id'     => 'webinar_console',
						'title'  => __( 'Live Console', 'webinar-ignition' ),
						'href'   => $console_link,
						'parent' => 'webinarignition_menu', // Attach to the parent dropdown menu
						'meta'   => array( 'class' => 'custom-link', 'target' => '_blank' ),
					)
				);
			}//end if
		}//end if

		$show_setting_link = false;
		$first_post_id     = 0;
		$second_post_id    = 0;

		if ( isset( $_GET['page_id'] ) && is_numeric( $_GET['page_id'] ) && $_GET['page_id'] > 0 ) {
			$first_post_id     = absint( wp_unslash( $_GET['page_id'] ) );
			$show_setting_link = true;
		} else {

			$results = $wpdb->get_results( $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_name = %s ORDER BY ID ASC", $webinar_name) );
			// Check if post_id is found
			if ( $results ) {
				/**
				 * The previous system was generating two posts on a single webinar
				 * so that's why it was requirement to get two post id's and match them with the webinar table
				 */
				$first_post_id     = isset( $results[0]->ID ) ? $results[0]->ID : 0;
				$second_post_id    = isset( $results[1]->ID ) ? $results[1]->ID : 0;
				$show_setting_link = true;
			}
		}//end if

		if ( $show_setting_link ) {
			$tbl_webinarignition = $wpdb->prefix . 'webinarignition';

			

			// Execute the query and get id
			$webinar_id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM {$tbl_webinarignition} WHERE postID = %d OR postID = %d LIMIT 1", $first_post_id, $second_post_id) );
			if ( ! is_null( $webinar_id ) && $webinar_id > 0 ) {
				$webinar_setting_link = admin_url() . 'admin.php?page=webinarignition-dashboard&id=' . $webinar_id;
				if(isset($wp_admin_bar) && $wp_admin_bar) {
					$wp_admin_bar->add_menu(
					array(
						'id'     => 'webinar_settings',
						'title'  => __( 'Settings Dashboard', 'webinar-ignition' ),
						'href'   => $webinar_setting_link,
						'parent' => 'webinarignition_menu', // Attach to the parent dropdown menu
						'meta'   => array( 'class' => 'custom-link','target' => '_blank' ),
					)
				);}
			}
		}//end if
	}
}//end if



$webinar_type = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';

$is_cta_aside   = false;
$is_cta_overlay = false;
$is_cta_timed   = false;

$webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );
if ( ! empty( $webinar_cta_by_position ) ) {
	if ( ! empty( $webinar_cta_by_position['is_time'] ) ) {
		$is_cta_timed = true;
	}
	if ( ! empty( $webinar_cta_by_position['outer'] ) ) {
		$is_cta_aside = true;
	}
	if ( ! empty( $webinar_cta_by_position['overlay'] ) ) {
		$is_cta_overlay = true;
	}
}

$webinarId     = $webinar_data->id;
$webinar_aside = array();

$default_webinar_tabs_settings = array();
$webinar_tabs_settings         = isset( $webinar_data->webinar_tabs ) ? $webinar_data->webinar_tabs : $default_webinar_tabs_settings;
foreach ( $webinar_tabs_settings as $i => $webinar_tabs_setting ) {
	$show                 = true;
	$webinar_tabs_setting = (array) $webinar_tabs_setting;

	if ( ! empty( $webinar_tabs_setting['type'] ) && 'qa_tab' === $webinar_tabs_setting['type'] ) {
		$show = true;
	}

	if ( ! empty( $webinar_tabs_setting['type'] ) && 'giveaway_tab' === $webinar_tabs_setting['type'] ) {
		$show = true;
	}

	if ( $show ) {
		$tab_name    = ! empty( $webinar_tabs_setting['name'] ) ? $webinar_tabs_setting['name'] : '';
		$tab_content = ! empty( $webinar_tabs_setting['content'] ) ? $webinar_tabs_setting['content'] : '';

		$tab_id = 'tab-' . sha1( $i . $tab_content );

		$webinar_aside[ $tab_id ] = array(
			'title'   => $tab_name,
			'content' => do_shortcode( wpautop( $tab_content ) ),
		);
	}
}//end foreach

// if ( ! count( $webinar_aside ) ) {
	if ( 'hide' !== trim($webinar_data->webinar_qa) ) {
		$tab_content = webinarignition_get_webinar_qa( $webinar_data, false );
		$tab_name    = ! empty( $webinar_data->webinar_qa_section_title ) ? $webinar_data->webinar_qa_section_title : __( 'Q&A', 'webinar-ignition' );
		$tab_id      = 'tab-' . sha1( 'qa' . $tab_content );

		$webinar_aside[ $tab_id ] = array(
			'title'   => $tab_name,
			'content' => $tab_content,
		);
	}

	if ( 'hide' !== trim($webinar_data->webinar_giveaway_toggle) ) {
		$tab_content = webinarignition_get_webinar_giveaway_compact( $webinar_data );
		$tab_name    = ! empty( $webinar_data->webinar_giveaway_title ) ? $webinar_data->webinar_giveaway_title : __( 'Giveaway', 'webinar-ignition' );
		$tab_id      = 'tab-' . sha1( 'giveaway' . $tab_content );

		$webinar_aside[ $tab_id ] = array(
			'title'   => $tab_name,
			'content' => $tab_content,
		);
	}
// }//end if

if (
    ( 'live' === $webinar_type && count( $webinar_aside ) > 0 ) || 
    ( isset( $webinar_data->live_webinar_ctas_position_radios ) && $webinar_data->live_webinar_ctas_position_radios == 'outer' )
) {
	$is_cta_aside = true;
}
elseif('live' ===  $webinar_type ){
	$is_cta_aside = true;
}

if ( $is_cta_aside && 'live' != $webinar_type ) {
	ob_start();
	include WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/webinar-cta.php';
	$cta_content = ob_get_clean();
	$cta_name    = __( 'Click Here', 'webinar-ignition' );
	$tab_id      = 'tab-cta-sidebar';

	$webinar_aside_tmp = $webinar_aside;
	$webinar_aside     = array();

	if ( ! $is_cta_timed ) {
		$webinar_aside[ $tab_id ] = array(
			'title'   => $webinar_cta_by_position['outer'][0]['auto_action_title'],
			'content' => $cta_content,
		);
	}

	$webinar_aside = array_merge( $webinar_aside, $webinar_aside_tmp );
}
// elseif ($is_cta_aside && 'live' === $webinar_type && $webinar_data->cta_position == 'outer') {
// 	ob_start();
// 	include WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/webinar-cta.php';
// 	$cta_content = ob_get_clean();
// 	$cta_name    = __( 'Click Here', 'webinar-ignition' );
// 	$tab_id      = 'tab-cta-sidebar';

// 	$webinar_aside_tmp = $webinar_aside;
// 	$webinar_aside     = array();

// 	if ( ! $is_cta_timed ) {
// 		$webinar_aside[ $tab_id ] = array(
// 			'title'   => isset($webinar_data->air_tab_copy),
// 			'content' => $cta_content,
// 		);
// 	}

// 	$webinar_aside = array_merge( $webinar_aside, $webinar_aside_tmp );
// }

$webinar_modern_background_color = ! empty( $webinar_data->webinar_modern_background_color ) ? $webinar_data->webinar_modern_background_color : '#ced4da';
$webinar_modern_text_color       = webinarignition_get_text_color_from_bg_color( $webinar_modern_background_color );
$webinar_live_bgcolor            = empty( $webinar_data->webinar_live_bgcolor ) ? '#000' : $webinar_data->webinar_live_bgcolor;
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>
		<?php
		if ( empty( $webinar_data->meta_site_title_webinar ) ) {
			webinarignition_display( $webinar_data->lp_metashare_title, __( 'Amazing Webinar', 'webinar-ignition' ) );
		} else {
			echo esc_html( $webinar_data->meta_site_title_webinar );
		}
		?>
	</title>

	<meta name="description" content="
	<?php
	if ( empty( $webinar_data->meta_desc_webinar ) ) {
		webinarignition_display( $webinar_data->lp_metashare_desc, __( 'Join this amazing webinar, and discover industry trade secrets!', 'webinar-ignition' ) );
	} else {
		echo esc_html( $webinar_data->meta_desc_webinar );
	}
	?>
	">

	<?php if ( ! empty( $webinar_data->ty_share_image ) ) : ?>
		<meta property="og:image" content="<?php webinarignition_display( $webinar_data->ty_share_image, '' ); ?>"/>
	<?php endif ?>

	<?php wp_head(); ?>

	<style>
		html {
			font-size: 16px !important;
		}
		.webinarVideoCtaCombined {
			position: relative;
		}
		#webinarVideo,
		#webinarSidebar ul.wi-bg-light {
			background-color: <?php echo esc_html( $webinar_live_bgcolor ); ?> !important;
		}

		.webinarVideoCtaCombined .webinarVideoCTA {
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			height: auto;
		}

		#webinarHeader > .wi-row.wi-bg-light,
		#webinarFooter {
			background-color: <?php echo esc_html( $webinar_modern_background_color ); ?> !important;
		}

		#cta-controls-bar {
			background-color: <?php echo esc_html( $webinar_modern_background_color ); ?> !important;
		}

		.showOnVideo{
			background-color: #ced4d969 !important;
		}
		.showOnVideo:hover{
			background-color:rgb(206, 212, 217) !important;
		}
		.showOnVideo svg path {
			stroke: <?php echo esc_html( $webinar_modern_text_color ); ?> !important;
		}

		.sidebar-toggle-container:hover{
			background-color: <?php echo esc_html( $webinar_modern_background_color ); ?> !important;
		}

		#webinarHeader > .wi-row.wi-bg-light .wi-h4,
		#webinarFooter > .wi-row.wi-bg-light,
		#webinarHeader a,
		#webinarFooter a {
			color: <?php echo esc_html( $webinar_modern_text_color ); ?> !important;
		}

		#webinarSidebar ul.wi-bg-light .nav-link {
			color: <?php echo esc_html( webinarignition_get_text_color_from_bg_color( $webinar_live_bgcolor ) ); ?> !important;
		}

		#webinarSidebar ul.wi-bg-light .nav-link.active{
			color: black !important;
		}

		#webinarVideo, #webinarSidebar {
			-webkit-transition: width 0.3s ease, margin 0.3s ease;
			-moz-transition: width 0.3s ease, margin 0.3s ease;
			-o-transition: width 0.3s ease, margin 0.3s ease;
			transition: width 0.3s ease, margin 0.3s ease;
		}

		#webinarLoader {
			background-color: <?php echo esc_html( $webinar_modern_background_color ); ?> !important;
		}
	</style>
</head>

<body class="webinar_page" id="webinarignition">

<div id="webinarHeader">
	<div class="webinar-modern-header-container">
		<div class="webinar-modern-title-container">
			<div class="wi-h4 wi-mm-0">
				<?php esc_html_e( 'Webinar:', 'webinar-ignition' ); ?> <?php webinarignition_get_webinar_title( $webinar_data, true ); ?>
			</div>
		</div>

		<div class="webinar-host-name-container">
			<div class="wi-h4 wi-mm-0">
				<?php esc_html_e( 'Host:', 'webinar-ignition' ); ?> <?php webinarignition_get_host_name( $webinar_data, true ); ?>
			</div>
		</div>
	</div>
	<?php 
		if('live' === $webinar_type && current_user_can('administrator')) {
			?>

			<div id="cta-toggle-parent-container">
			</div>
	<?php
		}
	?>
</div>
<?php
	$leadInfo = webinarignition_get_lead_info( $_GET['lid'], $webinar_data, false );
if(!empty($leadInfo) && ! get_option('webinarignition_lead_confirmed_'.$leadInfo->ID)){
	do_action('webinarignition_wp2leads_lead_confirmed', $leadInfo->ID, $webinar_data->id);
	
}
$hide_top_admin_bar = get_option('webinarignition_hide_top_admin_bar', 1); // Default to '0' if not set
?>
<input id="admin-bar-hidden" type="hidden" value="<?php echo esc_html( $hide_top_admin_bar ); ?>">
<input id="is-user-admin" type="hidden" value="<?php echo esc_html( current_user_can('administrator') ); ?>">
<input id="is-video-control-enabeled" type="hidden" value="<?php echo isset($webinar_data->webinar_show_videojs_controls) ? esc_html(absint($webinar_data->webinar_show_videojs_controls)) : ''; ?>">

<div id="webinarContent" style="overflow: hidden;">
	<div class="wi-row wi-g-0" id="webinar-content-container-X">
		<?php
		$is_aside_visible = true;

		if ( count( $webinar_aside ) === 1 && $is_cta_aside && $is_cta_timed ) {
			$is_aside_visible = true;
		}

		if ( ! wp_validate_boolean( $is_aside_visible ) && count( $webinar_cta_by_position['outer'] ) > 0 ) {
			$is_aside_visible = true;
		}

		if ( ( wp_validate_boolean( $is_cta_aside ) || count( $webinar_aside ) > 0 ) && ( ! is_null( $webinar_aside ) || count( $webinar_cta_by_position['outer'] ) > 0 ) && $is_aside_visible ) {
			?>
		<main id="webinarVideo" class="wi-col-12 <?php echo ( ! is_null( $webinar_aside ) && count( $webinar_aside ) > 0 ) ? 'wi-col-lg-9' : ''; ?>">
			<?php
		} else {
			?>
		<main id="webinarVideo" class="wi-col-12">
			<?php
		}
		?>

			<div class="wi-row wi-g-0">
				<div class="wi-col-12">
					<?php webinarignition_get_webinar_video_cta_comb( $webinar_data, true ); ?>
				</div>
			</div>
			<?php
				if( 'live' === $webinar_type ){
					?>
					<div class="sidebar-toggle-container <?php echo count( $webinar_aside ) > 0 ? 'sidebar-visible' : ''; ?>">
						<svg id="sidebarToggleIcon" width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<!-- Initially, this is the "Close" icon  -->
							<path d="M9 6L15 12L9 18" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<?php
				}
				?>
		</main>

		<?php

		if ( 
			(
				( wp_validate_boolean( $is_cta_aside ) || ( is_array( $webinar_aside ) && count( $webinar_aside ) > 0 ) ) && 
				( ( is_array( $webinar_aside ) && count( $webinar_aside ) > 0 ) || 
				  ( isset( $webinar_cta_by_position['outer'] ) && is_array( $webinar_cta_by_position['outer'] ) && count( $webinar_cta_by_position['outer'] ) > 0 ) 
				) 
			) || 
			'live' === $webinar_type 
		) {
			$is_aside_visible = true;

			if ( ! is_null( $webinar_aside ) && count( $webinar_aside ) === 1 && $is_cta_aside && $is_cta_timed ) {
				$is_aside_visible = true;
			}
			// if ( wp_validate_boolean( $is_aside_visible ) && count( $webinar_cta_by_position['outer'] ) > 0 ) {
			if ( wp_validate_boolean( $is_aside_visible ) && isset( $webinar_cta_by_position['outer'] ) && is_array( $webinar_cta_by_position['outer'] ) && count( $webinar_cta_by_position['outer'] ) > 0 ) {
				$is_aside_visible = true;
			}
			?>
			<aside id="webinarSidebar" class="wi-col-12 <?php echo count( $webinar_aside ) > 0 ? 'wi-col-lg-3' : ''; ?>" <?php echo ! $is_aside_visible ? ' style="display:none;"' : ''; ?>>
				<input value="<?php echo esc_attr($webinar_data->webinar_qa); ?>" type="hidden" id="qa-tab-webinar-modern" />
				<input value="<?php echo esc_attr($webinar_data->webinar_giveaway_toggle); ?>" type="hidden" id="giveaway-tab-webinar-modern" />
				<div class="wi-row wi-g-0">
					<div class="wi-col-12">
						<?php
						if ( !  $webinar_cta_by_position ) {
							$webinar_cta_by_position = array();
						}

						if ( ! isset( $webinar_cta_by_position['outer'] ) ) {
							$webinar_cta_by_position['outer'] = array();
						}

						if ( ! is_array( $webinar_cta_by_position['outer'] ) && ! is_object( $webinar_cta_by_position['outer'] ) ) {
							$webinar_cta_by_position['outer'] = array();
						}

						if ( count( $webinar_aside ) > 0 || count( $webinar_cta_by_position['outer'] ) > 0 ) {
							$i = 1;
							?>
							<ul class="wi-nav wi-nav-tabs wi-bg-light wi-pt-1" id="webinarTabs" role="tablist">
								<?php

								if (
									empty( $webinar_cta_by_position )
									|| empty( $webinar_cta_by_position['is_time'] )
									|| empty( $webinar_cta_by_position['outer'] )
								) {
									$additional_autoactions = array();
								} else {
									$additional_autoactions = $webinar_cta_by_position['outer'];
								}
								if ( is_array($additional_autoactions) && !empty($additional_autoactions)) {
									foreach ( $additional_autoactions as $index => $additional_autoaction ) {
										$cta_position = $cta_position_default;
	
										if ( is_array($additional_autoaction) && ! empty( $additional_autoaction['cta_position'] ) ) {
											$cta_position = $additional_autoaction['cta_position'];
										}
	
										if ( $cta_position !== $cta_position_allowed ) {
											continue;
										}
	
										$auto_action_title = __( 'Click here', 'webinar-ignition' );
										if ( ! empty( $additional_autoaction['auto_action_title'] ) ) {
											$auto_action_title = $additional_autoaction['auto_action_title'];
										} elseif ( isset($additional_autoaction['auto_action_btn_copy']) && $additional_autoaction['auto_action_btn_copy'] ) {
											$auto_action_title = $additional_autoaction['auto_action_btn_copy'];
										}
										?>
										<li class="wi-nav-item nav-item wi-cta-tab" style="display:none;"><a class="wi-nav-link nav-link" data-toggle="tab" id="wi-cta-<?php echo absint( $index ); ?>-tab" href="#wi-cta-<?php echo absint( $index ); ?>" data-clicked="0"><?php echo esc_html( $auto_action_title ); ?></a></li>
										<?php
									}//end foreach
								}
								?>
								<?php
								foreach ( $webinar_aside as $slug => $data ) {
									if ( 'tab-cta-sidebar' === $slug && $is_cta_aside && $is_cta_timed ) {
										$i = 0;
									}
									?>
									<li class="wi-nav-item nav-item"<?php echo 0 === $i ? ' style="display:none;"' : ''; ?>>
										<a
											class="wi-nav-link nav-link<?php echo 1 === $i ? ' active' : ''; ?>"
											id="<?php echo esc_html( $slug ); ?>-tab"
											data-toggle="tab"
											href="#<?php echo esc_html( $slug ); ?>"
											role="tab"
											aria-controls="<?php echo esc_html( $slug ); ?>"
											aria-selected="true"
											<?php echo 'tab-cta-sidebar' === $slug ? ' data-default-text="' . esc_html__( 'Click Here', 'webinar-ignition' ) . '"' : ''; ?>
										>
											<?php echo esc_html( $data['title'] ); ?>
										</a>
									</li>
									<?php
									++$i;
								}//end foreach
								?>

							</ul>
							<?php
						}//end if

							$i = 1;
						?>
							<style>
								#webinarTabsContent {
									position: relative;
								}
								#webinarTabsContent .webinarTabsContent-inner.webinarTabsContent-inner-absolute {
									/* position: absolute;
									top: 1rem !important;
									right: 1rem !important;
									bottom: 1rem !important;
									left: 1rem !important;
									overflow: hidden auto;
									height:auto;
									z-index: 100;*/
								}
								#webinarSidebar .additional_autoaction_item{
									/* display:none; */
									/* visibility: hidden; */
									/* opacity: 0; */
									height: 75vh !important;
									overflow:scroll;
									/* z-index: -1; */
								}
								<?php 
									if (isset($webinar_data->cta_position) && $webinar_data->cta_position == 'outer') {
										?>
										#tab-cta-sidebar{
											overflow: auto;
											height: 73vh !important;
										}
										<?php
									}
								?>
							</style>
							<div id="webinarTabsContent" class="wi-p-3">
								<div class="webinarTabsContent-inner default-template-sidebar webinarTabsContent-inner-absolute">
									<div class="wi-tab-content">
										<?php

										if (
											empty( $webinar_cta_by_position )
											|| empty( $webinar_cta_by_position['is_time'] )
											|| empty( $webinar_cta_by_position['outer'] )
										) {
											$additional_autoactions = array();
										} else {
											$additional_autoactions = $webinar_cta_by_position['outer'];
										}

										if ( is_array($additional_autoactions) && !empty($additional_autoactions)) {
											foreach ( $additional_autoactions as $index => $additional_autoaction ) {
												$cta_position = $cta_position_default;

												if ( is_array($additional_autoaction) && ! empty( $additional_autoaction['cta_position'] ) ) {
													$cta_position = $additional_autoaction['cta_position'];
												}

												if ( $cta_position !== $cta_position_allowed ) {
													continue;
												}

												$max_width = '';

												if ( ! empty( $additional_autoaction['auto_action_max_width'] ) ) {
													$max_width = $additional_autoaction['auto_action_max_width'];
												}
												?>
												<div class="wi-tab-pane additional_autoaction_item" id="wi-cta-<?php echo absint( $index ); ?>" data-max-width="<?php echo absint( $max_width ); ?>">
													<div id="orderBTNCopy_<?php echo absint( $index ); ?>">
														<?php
														include WEBINARIGNITION_PATH . 'inc/lp/partials/print_cta.php';
														?>
													</div>

													<div id="orderBTNArea_<?php echo absint( $index ); ?>">
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
															<a href="<?php webinarignition_display( $additional_autoaction['auto_action_url'], '#' ); ?>"
																id="<?php echo esc_html( $btn_id ); ?>"
																target="_blank"
																class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-evergreen-btn"
																style="border: 1px solid rgba(0,0,0,0.20);">
																<?php webinarignition_display( $additional_autoaction['auto_action_btn_copy'], __( 'Click Here To Grab Your Copy Now', 'webinar-ignition' ) ); ?>
															</a>
														<?php endif ?>
													</div>
												</div>
												<?php
											}//end foreach
										}
										?>
										<?php
										foreach ( $webinar_aside as $slug => $data ) {
											if ( 'tab-cta-sidebar' === $slug && $is_cta_aside && $is_cta_timed ) {
												$i = 0;
											}
											?>
											<div class="wi-tab-pane <?php echo 1 === $i || ! $is_aside_visible ? ' active' : ''; ?>" id="<?php echo esc_html( $slug ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_html( $slug ); ?>-tab">
												<?php 
													$all_html_tags = array(
																	'a' => true,
																	'abbr' => true,
																	'address' => true,
																	'area' => true,
																	'article' => true,
																	'aside' => true,
																	'audio' => true,
																	'b' => true,
																	'base' => true,
																	'bdi' => true,
																	'bdo' => true,
																	'blockquote' => true,
																	'body' => true,
																	'br' => true,
																	'button' => true,
																	'canvas' => true,
																	'caption' => true,
																	'cite' => true,
																	'code' => true,
																	'col' => true,
																	'colgroup' => true,
																	'data' => true,
																	'datalist' => true,
																	'dd' => true,
																	'del' => true,
																	'details' => true, 
																	'dfn' => true,
																	'dialog' => true,
																	'div' => true,
																	'dl' => true,
																	'dt' => true,
																	'em' => true,
																	'embed' => true,
																	'fieldset' => true,
																	'figcaption' => true,
																	'figure' => true,
																	'footer' => true,
																	'form' => true,
																	'h1' => true,
																	'h2' => true,
																	'h3' => true,
																	'h4' => true,
																	'h5' => true,
																	'h6' => true,
																	'head' => true,
																	'header' => true,
																	'hgroup' => true,
																	'hr' => true,
																	'html' => true,
																	'i' => true,
																	'iframe' => true,
																	'img' => true,
																	'input' => true,
																	'ins' => true,
																	'kbd' => true,
																	'keygen' => true,
																	'label' => true,
																	'legend' => true,
																	'li' => true,
																	'link' => true,
																	'main' => true,
																	'map' => true,
																	'mark' => true,
																	'menu' => true,
																	'menuitem' => true,
																	'meta' => true,
																	'meter' => true,
																	'nav' => true,
																	'noscript' => true,
																	'object' => true,
																	'ol' => true,
																	'optgroup' => true,
																	'option' => true,
																	'output' => true,
																	'p' => true,
																	'param' => true,
																	'picture' => true,
																	'pre' => true,
																	'progress' => true,
																	'q' => true,
																	'rp' => true,
																	'rt' => true,
																	'ruby' => true,
																	's' => true,
																	'samp' => true,
																	'script' => true,
																	'section' => true,
																	'select' => true,
																	'small' => true,
																	'source' => true,
																	'span' => true,
																	'strong' => true,
																	'style' => true,
																	'sub' => true,
																	'summary' => true,
																	'sup' => true,
																	'table' => true,
																	'tbody' => true,
																	'td' => true,
																	'textarea' => true,
																	'tfoot' => true,
																	'th' => true,
																	'thead' => true,
																	'time' => true,
																	'title' => true,
																	'tr' => true,
																	'track' => true,
																	'u' => true,
																	'ul' => true,
																	'var' => true,
																	'video' => true,
																	'wbr' => true
																);
																
													foreach ($all_html_tags as $tag => $attributes) {
														$all_html_tags[$tag] = array_fill_keys(['class', 'id', 'style', 'src', 'href', 'alt', 'title', 'type', 'value', 'name', 'target', 'action', 'method', 'checked', 'selected', 'placeholder', 'width', 'height', 'border', 'align', 'valign', 'lang', 'xml:lang', 'aria-label', 'role', 'data-*', 'aria-hidden', 'aria-labelledby', 'aria-describedby', 'rel', 'media', 'accept', 'accept-charset', 'charset', 'async', 'defer', 'property', 'http-equiv', 'content', 'viewBox', 'd', 'x', 'y', 'viewbox', 'preserveAspectRatio', 'xmlns', 'version', 'baseProfile', 'required'], true);
													}
													echo wp_kses(
														$data['content'],
														array_merge(
															wp_kses_allowed_html('post'), // Allow default WordPress post tags and attributes.
															array(
																'*' => array( // Allow all tags.
																	'style' => true, // Allow inline CSS on all tags.
																	'class' => true, // Allow CSS classes.
																	'id'    => true, // Allow IDs.
																	'data-*' => true, // Allow data attributes.
																	'required' => true, // Allow required attribute on all tags.
																),
															),
															$all_html_tags
														)
													);
												?>
												
											</div>
											<?php
											++$i;
										}
										?>
									</div>
								</div>
							</div>
					</div>
				</div>
			</aside>
			<?php
		}//end if
		?>
	</div>
</div>

<div id="webinarFooter">
	<div class="wi-row wi-g-0 wi-p-2">
		<div style="text-align: center;"><?php require_once WEBINARIGNITION_PATH . 'inc/lp/partials/powered_by.php'; ?></div>
	</div>
</div>

<div id="webinarLoader">
	<div class="box">
		<div class="box-inner">
			<div class="loader-14"></div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>

<?php echo isset( $webinar_data->footer_code ) ? do_shortcode( $webinar_data->footer_code ) : ''; ?>
</body>
</html>
<?php

