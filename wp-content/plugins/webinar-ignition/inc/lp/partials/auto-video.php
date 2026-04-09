<?php

/**
 * @var $webinar_data
 * @var $is_preview
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$main = false;
$timeover = false;
if ( isset( $_GET['lid'] ) ) {
    $lead_id = sanitize_text_field( $_GET['lid'] );
    $watch_time = get_option( 'wi_lead_watch_time_' . $lead_id, true );
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
}
//end if
?>

<?php 
if ( !$timeover ) {
    $show_controls = !empty( $webinar_data->webinar_show_videojs_controls ) || !empty( $is_preview );
    ?>

<video disablePictureInPicture 
		<?php 
    echo ( $show_controls ? 'controls' : '' );
    ?> 
		id="autoReplay" class="video-js vjs-default-skin">
	<source src="<?php 
    echo esc_url( $webinar_data->auto_video_url );
    ?>" type='video/mp4'/>
	<source src="<?php 
    echo esc_url( $webinar_data->auto_video_url2 );
    ?>" type="video/webm"/>
</video>

<input type="hidden" id="autoVideoTime">
<?php 
} else {
    ?>
	<h3>
	<?php 
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
}