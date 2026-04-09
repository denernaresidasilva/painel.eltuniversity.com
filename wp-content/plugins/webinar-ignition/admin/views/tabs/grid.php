<?php
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$assets_url = trailingslashit(WEBINARIGNITION_URL . 'inc/lp');
$upgrade_link = admin_url('admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true');
?>
<div class="wi-grid-wrapper">
	<div class="wi-grid-box">
		<div class="wi-title-wrap">
			<div class="wi-left">
				<h1><svg fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
						viewBox="0 0 330 330" xml:space="preserve">
						<g id="XMLID_509_">
							<path id="XMLID_510_" d="M65,330h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15h-15V85c0-46.869-38.131-85-85-85
		S80,38.131,80,85v45H65c-8.284,0-15,6.716-15,15v170C50,323.284,56.716,330,65,330z M180,234.986V255c0,8.284-6.716,15-15,15
		s-15-6.716-15-15v-20.014c-6.068-4.565-10-11.824-10-19.986c0-13.785,11.215-25,25-25s25,11.215,25,25
		C190,223.162,186.068,230.421,180,234.986z M110,85c0-30.327,24.673-55,55-55s55,24.673,55,55v45H110V85z" />
						</g>
					</svg> <?php echo esc_html__('Webinar Grid Layout', 'webinar-ignition'); ?></h1>
				<p class="wi-desc"><?php echo
					// translators: %s is a link to the global settings page for setting colors.
					esc_html__('With the Webinar Grid Layout premium feature, you can display your webinars in a visually appealing grid format, similar to how eCommerce products are shown (as seen in the image below). Hint: <a href="%s">Set colors</a> in global settings.).', 'webinar-ignition'); ?></p>
			</div>
			<div class="wi-btn-wrap">
				<!-- button kb -->
				<div>
					<a href="https://webinarignition.tawk.help/article/display-your-webinars-in-a-grid-layout" target="_blank" class="btn-kb"><?php echo esc_html__('Knowledge Base', 'webinar-ignition'); ?></a>
				</div>
				<div>
					<a href="<?php echo esc_url($upgrade_link); ?>" class="btn-go-ultimate"><svg fill="#fff" height="12px" width="12px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
							viewBox="0 0 330 330" xml:space="preserve">
							<g id="XMLID_509_">
								<path id="XMLID_510_" d="M65,330h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15h-15V85c0-46.869-38.131-85-85-85
		S80,38.131,80,85v45H65c-8.284,0-15,6.716-15,15v170C50,323.284,56.716,330,65,330z M180,234.986V255c0,8.284-6.716,15-15,15
		s-15-6.716-15-15v-20.014c-6.068-4.565-10-11.824-10-19.986c0-13.785,11.215-25,25-25s25,11.215,25,25
		C190,223.162,186.068,230.421,180,234.986z M110,85c0-30.327,24.673-55,55-55s55,24.673,55,55v45H110V85z" />
							</g>
						</svg> <?php echo esc_html__('Go Ultimate', 'webinar-ignition'); ?></a>
				</div>
			</div>
		</div>
		<div class="img-wrap">
			<img src="<?php echo esc_url($assets_url . 'images/wi-grid-02.jpg'); ?>" alt="<?php esc_attr_e('Grid layout preview', 'webinar-ignition'); ?>" height="auto" />
		</div>
	</div>
</div>