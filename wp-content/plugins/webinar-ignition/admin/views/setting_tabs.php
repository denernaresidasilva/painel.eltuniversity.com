<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$tab = sanitize_text_field( filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS ) );
$active_tab = $tab ?? 'general';
$tab_url = add_query_arg( 'page', 'webinarignition_settings', admin_url( 'admin.php' ) );
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

<div class="nav-tab-wrapper">
	<a href="<?php 
echo esc_url( add_query_arg( 'tab', 'general', $tab_url ) );
?>" class="nav-tab <?php 
echo ( $active_tab === 'general' ? 'nav-tab-active' : '' );
?>"><?php 
esc_html_e( 'General', 'webinar-ignition' );
?></a>
	<!-- <a href="<?php 
// echo add_query_arg('tab', 'smtp-settings', $tab_url);
?>" class="nav-tab <?php 
// echo ($active_tab === 'smtp-settings') ? 'nav-tab-active' : '';
?>">SMTP</a> -->
	<a href="<?php 
echo esc_url( add_query_arg( 'tab', 'spam-test', $tab_url ) );
?>" class="nav-tab <?php 
echo ( $active_tab === 'spam-test' ? 'nav-tab-active' : '' );
?>"><?php 
esc_html_e( 'Email Spammyness', 'webinar-ignition' );
?></a>
	<a href="<?php 
echo esc_url( add_query_arg( 'tab', 'email-templates', $tab_url ) );
?>" class="nav-tab <?php 
echo ( $active_tab === 'email-templates' ? 'nav-tab-active' : '' );
?>"><?php 
esc_html_e( 'Email Templates', 'webinar-ignition' );
?></a>
	<?php 
if ( !defined( 'WEBINAR_IGNITION_DISABLE_WEBHOOKS' ) ) {
    ?>
		<a href="<?php 
    echo esc_url( add_query_arg( 'tab', 'webhooks', $tab_url ) );
    ?>" class="nav-tab <?php 
    echo ( $active_tab === 'webhooks' ? 'nav-tab-active' : '' );
    ?>"><?php 
    esc_html_e( 'Webhooks', 'webinar-ignition' );
    ?></a>
			<?php 
}
?>
</div>
