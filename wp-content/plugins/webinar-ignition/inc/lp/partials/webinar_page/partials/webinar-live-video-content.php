<?php

/**
 * @var $webinar_data
 * @var $assets
 * @var $statusCheck
 */
$hundered_ms_management_token = get_option( 'hundered_ms_management_token', '' );
$hundered_ms_template_subdomain = ( !empty( $webinar_data->hundered_ms_template_subdomain ) ? esc_html( $webinar_data->hundered_ms_template_subdomain ) : '' );
webinarignition_display( do_shortcode( $webinar_data->webinar_live_video ), '<img class="img-fluid" style="width: 85%;max-width: 100%;height: auto;" src="' . $assets . '/images/videoplaceholder.png" />' );