<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );
$is_cta_aside            = false;

$is_cta_timed   = ! empty( $webinar_cta_by_position['is_time'] ) ? true : false;
$is_cta_aside   = ! empty( $webinar_cta_by_position['outer'] ) ? true : false;
$is_cta_overlay = ! empty( $webinar_cta_by_position['overlay'] ) ? true : false;

$webinar_aside         = array();
$webinar_tabs_settings = isset( $webinar_data->webinar_tabs ) ? $webinar_data->webinar_tabs : array();

foreach ( $webinar_tabs_settings as $i => $webinar_tabs_setting ) {
	$show = true;

	if ( ! empty( $webinar_tabs_setting['type'] ) && 'qa_tab' === $webinar_tabs_setting['type'] && 'hide' === trim($webinar_data->webinar_qa) ) {
		$show = false;
	}

	if ( ! empty( $webinar_tabs_setting['type'] ) && 'giveaway_tab' === $webinar_tabs_setting['type'] && 'hide' === trim($webinar_data->webinar_giveaway_toggle) ) {
		$show = false;
	}

	if ( $show ) {
		$tab_name    = ! empty( $webinar_tabs_setting['name'] ) ? $webinar_tabs_setting['name'] : '';
		$tab_content = ! empty( $webinar_tabs_setting['content'] ) ? $webinar_tabs_setting['content'] : '';
		$tab_id      = 'tab-' . sha1( $i . $tab_content );

		$webinar_aside[ $tab_id ] = array(
			'title'   => $tab_name,
			'content' => do_shortcode( wpautop( $tab_content ) ),
		);
	}
}//end foreach

if ( ! count( $webinar_aside ) ) {
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
}//end if

if ( $is_cta_aside ) {
	ob_start();
		include WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/webinar-cta.php';
	$cta_content = ob_get_clean();

	$cta_name = __( 'Click Here', 'webinar-ignition' );
	$tab_id   = 'tab-cta-sidebar';

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


if ( count( $webinar_aside ) > 0 || count( $webinar_cta_by_position['outer'] ) > 0 ) {
	$is_aside_visible = true;

	if ( count( $webinar_aside ) === 1 && $is_cta_aside && $is_cta_timed ) {
		$is_aside_visible = false;
	}

	if ( ! wp_validate_boolean( $is_aside_visible ) && count( $webinar_cta_by_position['outer'] ) > 0 ) {
		$is_aside_visible = true;
	}
	?>
	<aside id="webinarSidebarSlot" class="wi-col-12 <?php echo count( $webinar_aside ) > 0 ? 'wi-col-lg-3' : ''; ?>" <?php echo ! $is_aside_visible ? ' style="display:none;"' : ''; ?>>
		<div class="webinar-sidebar-slot">
			<div class="wi-row wi-g-0">
				<div class="wi-col-12">
					<?php
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
								$auto_actions = $webinar_cta_by_position['outer'] ?? array();

							foreach ( $auto_actions as $index => $single_action ) {
								$cta_position = ! empty( $single_action['cta_position'] ) ? $single_action['cta_position'] : $cta_position_default;

								if ( $cta_position !== $cta_position_allowed ) {
									continue;
								}

								$auto_action_title = __( 'Click here', 'webinar-ignition' );

								if ( ! empty( $single_action['auto_action_title'] ) ) {
									$auto_action_title = $single_action['auto_action_title'];
								} elseif ( $single_action['auto_action_btn_copy'] ) {
									$auto_action_title = $single_action['auto_action_btn_copy'];
								}

								?>
										<li
											class="wi-nav-item nav-item wi-cta-tab"
											style="display:none;"
										>
											<a
												class="wi-nav-link nav-link"
												data-toggle="tab"
												id="wi-cta-<?php echo absint( $index ); ?>-tab"
												href="#wi-cta-<?php echo absint( $index ); ?>"
												data-clicked="0"
											>
												<?php echo esc_html( $auto_action_title ); ?>
											</a>
										</li>
									<?php
							}//end foreach
							?>

								<?php
								foreach ( $webinar_aside as $slug => $data ) {
									if ( 'tab-cta-sidebar' === $slug && $is_cta_aside && $is_cta_timed ) {
										$i = 0;
									}
									?>
										<li class="wi-nav-item nav-item" <?php echo 0 === $i ? ' style="display:none;"' : ''; ?>>
											<a class="wi-nav-link nav-link<?php echo 1 === $i ? ' active' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>-tab" data-toggle="tab" href="#<?php echo esc_attr( $slug ); ?>" role="tab" aria-controls="<?php echo esc_attr( $slug ); ?>" aria-selected="true" <?php echo 'tab-cta-sidebar' === $slug ? ' data-default-text="' . esc_html__( 'Click Here', 'webinar-ignition' ) . '"' : ''; ?>>
											<?php echo esc_html( $data['title'] ); ?>
											</a>
										</li>
										<?php
										++$i;
								}
								?>

							</ul>
							<?php
					}//end if

					$i = 1;
					?>
					<style>
						aside#webinarSidebarSlot {
							width: 100%;
							background: rgba(0,0,0,0.1);
						}
						aside#webinarSidebarSlot #webinarTabsContent .webinarTabsContent-inner.webinarTabsContent-inner-absolute {
							position: static;
						}
					</style>
					<div id="webinarTabsContent" class="wi-p-3 h-auto d-inline-block">
						<div class="webinarTabsContent-inner webinarTabsContent-inner-absolute">
							<div class="wi-tab-content">
								<?php

								if (
									empty( $webinar_cta_by_position )
									|| empty( $webinar_cta_by_position['is_time'] )
									|| empty( $webinar_cta_by_position['outer'] )
								) {
									$auto_actions = array();
								} else {
									$auto_actions = $webinar_cta_by_position['outer'];
								}

								foreach ( $auto_actions as $index => $single_action ) {
									$cta_position = $cta_position_default;

									if ( ! empty( $single_action['cta_position'] ) ) {
										$cta_position = $single_action['cta_position'];
									}

									if ( $cta_position !== $cta_position_allowed ) {
										continue;
									}

									$max_width = '';

									if ( ! empty( $single_action['auto_action_max_width'] ) ) {
										$max_width = $single_action['auto_action_max_width'] . 'px';
									}
									?>
									<div class="wi-tab-pane action_item" style="position:initial;" id="wi-cta-<?php echo absint( $index ); ?>" data-max-width="<?php echo esc_attr( $max_width ); ?>">
										<div id="orderBTNCopy_<?php echo absint( $index ); ?>">
											<?php
											include WEBINARIGNITION_PATH . 'inc/lp/partials/print_cta.php';
											?>
										</div>

										<div id="orderBTNArea_<?php echo absint( $index ); ?>">
											<?php
											if ( ! empty( $single_action['auto_action_url'] ) ) :
												$btn_id     = wp_unique_id( 'orderBTN_' );
												$bg_color   = empty( $single_action['replay_order_color'] ) ? '#6BBA40' : $single_action['replay_order_color'];
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
												<a href="<?php webinarignition_display( $single_action['auto_action_url'], '#' ); ?>" id="<?php echo esc_attr( $btn_id ); ?>" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block" style="border: 1px solid rgba(0,0,0,0.20);">
													<?php webinarignition_display( $single_action['auto_action_btn_copy'], __( 'Click Here To Grab Your Copy Now', 'webinar-ignition' ) ); ?>
												</a>
											<?php endif ?>
										</div>
									</div>
									<?php
								}//end foreach
								?>

								<?php
								foreach ( $webinar_aside as $slug => $data ) {
									if ( 'tab-cta-sidebar' === $slug && $is_cta_aside && $is_cta_timed ) {
										$i = 0;
									}
									?>
										<div class="wi-tab-pane <?php echo 1 === $i || ! $is_aside_visible ? ' show active' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $slug ); ?>-tab">
										<?php echo wp_kses_post( $data['content'] ); ?>
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
		</div>
	</aside>
	<?php
}//end if
