<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ultimate-container">
	<div class="ultimate-below-sec">
		<div class="ultimate-graphic">
			<img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/logo-avatar.png' ); ?>" alt="Unlock potential">
			<div class="ultimate-below-container">
				<div class="ultimate-lock-text-cont"><img src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/padlock.png' ); ?>" /></div>
				<div class="ultimate-text-container">Ultimate</div>
			</div>
		</div>
		<div class="ultimate-content">
			<h2>Upgrade to Ultimate Version and Unleash Your Potential</h2>
			<p>
				<div class="download-legacy-version" href="<?php echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true' ) ); ?>">
					<a href="<?php echo esc_url( $account_page_link ); ?>">To get updates and support use ultimate plan again</a>
					<a href="<?php echo esc_url( $pricing_page_link ); ?>">click here to buy</a>,
				</div> or switch to <a class="download-legacy-version" href="<?php echo esc_url( admin_url( 'plugin-install.php?s=webinarignition&tab=search&type=term' ) ); ?>">free version</a></p>
			<a href="<?php echo esc_url( $upgrade_link); ?>" class="ultimate-button">Go Ultimate</a>
		</div>
	</div>
</div>