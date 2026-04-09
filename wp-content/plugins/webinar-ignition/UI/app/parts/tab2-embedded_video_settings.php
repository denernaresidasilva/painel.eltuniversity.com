<?php
/**
 * @var $input_get
 * @var $webinar_data
 */
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->privacy_status,
    esc_html__( 'Video Privacy Status', 'webinar-ignition' ),
    'privacy_status',
    esc_html__( 'Choose Privacy Status for your Youtube Broadcasts', 'webinar-ignition' ),
    esc_html__( 'Unlisted', 'webinar-ignition' ) . ' [unlisted], ' . esc_html__( 'Public', 'webinar-ignition' ) . ' [public]'
);
webinarignition_display_textarea(
    $input_get['id'],
    $webinar_data->webinar_live_video,
    esc_html__( 'Live Video Embed Code', 'webinar-ignition' ),
    'webinar_live_video',
    esc_html__( 'This is the embed code for the live streaming for the webinar, can be Youtube, Vimeo, YouStream, etc...', 'webinar-ignition' ),
    esc_html__( 'Live video embed code...', 'webinar-ignition' )
);
webinarignition_display_option(
    $input_get['id'],
    $webinar_data->webinar_live_overlay,
    esc_html__( 'Video Controls', 'webinar-ignition' ),
    'webinar_live_overlay',
    esc_html__( "Choose whether or not to disable video player's left and right click functionality on the live page. Enabling this option will prevent users from being able to click any of the player controls. NB: This feature won't work with Zoom since users may need to sign in.", 'webinar-ignition' ),
    esc_html__( 'enabled', 'webinar-ignition' ) . ' [0], ' . esc_html__( 'disabled', 'webinar-ignition' ) . ' [1]' );
webinarignition_display_color(
    $input_get['id'],
    $webinar_data->webinar_live_bgcolor,
    esc_html__( 'Live Video Background Color', 'webinar-ignition' ),
    'webinar_live_bgcolor',
    esc_html__( 'This is the color for the area around the video...', 'webinar-ignition' ),
    '#000000' );
webinarignition_display_info(
    esc_html__( 'Note: Live Embed Code', 'webinar-ignition' ),
    esc_html__( 'This is the embed code the live streaming service gives you, it is automatically resized to fit: 920px by 518px...', 'webinar-ignition' )
);
