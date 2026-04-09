<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
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
?>
<style type="text/css">
.wi-timeout-overlay {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: rgba(0, 0, 0, 0.7);
	transition: opacity 500ms;
	visibility: visible;
	opacity: 1;
}
.wi-timeout-overlay:target {
	visibility: visible;
	opacity: 1;
}

.wi-timeout-popup {
	margin: 70px auto;
	padding: 20px;
	background: #fff;
	border-radius: 5px;
	width: 30%;
	position: relative;
	transition: all 5s ease-in-out;
}

.wi-timeout-popup h2 {
	margin-top: 0;
	color: #333;
	font-family: Tahoma, Arial, sans-serif;
}
.wi-timeout-popup .close {
	position: absolute;
	top: 20px;
	right: 30px;
	transition: all 200ms;
	font-size: 30px;
	font-weight: bold;
	text-decoration: none;
	color: #333;
}
.wi-timeout-popup .close:hover {
	color: #06d85f;
}
.wi-timeout-popup .content {
	max-height: 30%;
	overflow: auto;
}

@media screen and (max-width: 700px) {
	.wi-timeout-box {
	width: 70%;
	}
	.wi-timeout-popup {
	width: 70%;
	}
}
</style>
<div id="wi-timeout-popup1" class="wi-timeout-overlay">
	<div class="wi-timeout-popup">
		<h2><?php 
esc_html_e( 'Webinar is closed', 'webinar-ignition' );
?></h2>
		<a class="close" href="#">&times;</a>
		<div class="content">
			<?php 
?>
		</div>
	</div>
</div>