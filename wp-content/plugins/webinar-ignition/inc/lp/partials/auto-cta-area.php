<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * @var $webinar_data
 */
?>

<?php 
require WEBINARIGNITION_PATH . 'inc/lp/partials/main-cta.php';
$site_url = get_site_url();
$statusCheck = new stdClass();
$statusCheck->switch = 'free';
$statusCheck->slug = 'free';
$statusCheck->licensor = '';
$statusCheck->is_free = 1;
$statusCheck->is_dev = '';
$statusCheck->is_registered = '';
$statusCheck->title = 'Free';
$statusCheck->member_area = '';
$statusCheck->is_pending_activation = 1;
$statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
$statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
$statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
$statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
$statusCheck->name = '';
$webinar_template = ( !empty( $webinar_data->webinar_template ) ? $webinar_data->webinar_template : 'classic' );
if ( 'classic' === $webinar_template ) {
    include WEBINARIGNITION_PATH . 'inc/lp/partials/additional-cta.php';
}