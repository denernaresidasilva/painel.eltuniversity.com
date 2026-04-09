<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$pricing_page_link = admin_url( 'admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true' );
$upgrade_link      = $pricing_page_link;
?>
<div class="ultimate-container">
	<div class="ultimate-below-sec">
		<div class="ultimate-graphic">
			<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/logo-avatar.png' ); ?>" alt="Unlock potential">
			<div class="ultimate-below-container">
				<div class="ultimate-lock-text-cont"><img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/padlock.png' ); ?>" /></div>
				<div class="ultimate-text-container"><?php esc_html_e( 'Ultimate', 'webinar-ignition' ); ?></div>
			</div>
		</div>
		<div class="ultimate-content">
			<h2><?php esc_html_e( 'Upgrade to Ultimate Version and Unleash Your Potential', 'webinar-ignition' ); ?></h2>
			<p><?php esc_html_e( 'Get the features you are missing to collect, see, send leads, cut alignment and to create paid webinars and much more!', 'webinar-ignition' ); ?></p>
			</br>
			<a href="<?php echo esc_url( $upgrade_link ); ?>" class="ultimate-button" target="_blank"><?php echo esc_html__( 'Go Ultimate', 'webinar-ignition' ); ?></a>
		</div>
	</div>
</div>