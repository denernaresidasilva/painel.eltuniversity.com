<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
?>
<div class="webinar_welcome_user_video_parent_container">
	<div class="webinar_welcome_left_container">
<div class="webinar_welcome_user_container webinar_dashboard_box_design">
	<?php 
$current_user_data = wp_get_current_user();
$current_username = $current_user_data->display_name;
?>
	<p class="webinar_welcom_user_text"><?php 
echo esc_html__( 'Hello', 'webinar-ignition' ) . ' ' . esc_html( $current_username );
?>,</p>
	<h2><?php 
esc_html_e( 'Welcome to WebinarIgnition', 'webinar-ignition' );
?></h2>
	
	<p><?php 
esc_html_e( 'Run unlimited LIVE, AUTOMATED, and EVERGREEN WEBINARS on WordPress. Sell with WooCommerce, Digistore24, Stripe, or PayPal embedded directly in your webinar room—no redirects, no drop-off.', 'webinar-ignition' );
?></p>
	
	<!-- Video Playlist Section -->
	<div class="wi-video-section" style="margin: 30px 0;">
		<h3 style="margin-bottom: 10px;">
			🎥 <?php 
esc_html_e( 'Quick Start Video Playlist', 'webinar-ignition' );
?>
		</h3>
		<p style="margin-bottom: 15px;">
			<strong><?php 
esc_html_e( 'Watch this step-by-step series to set up your first webinar in minutes:', 'webinar-ignition' );
?></strong>
		</p>
		
		<div class="wi-video-embed" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; margin: 20px 0;">
			<iframe 
				style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
				src="https://www.youtube-nocookie.com/embed/videoseries?si=UOEvSGxB6tfH6qoI&amp;list=PLlmyTY2pWUgdkumwHqaToKsTGY69XXRQb" 
				title="<?php 
esc_attr_e( 'WebinarIgnition Quick Start Playlist', 'webinar-ignition' );
?>" 
				frameborder="0" 
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
				referrerpolicy="strict-origin-when-cross-origin" 
				allowfullscreen>
			</iframe>
		</div>
		
			<p style="font-size: 13px; color: #666;"> 💡 
				<a href="https://webinarignition.com/webinarignition-quick-start-videos-links/" target="_blank" style="margin-right: 10px;"><strong><?php 
esc_html_e( 'Access the content mentioned in the videos:', 'webinar-ignition' );
?></strong> </a>
			</p>
	</div>
	
	<!-- Benefits Section -->
	<div style="margin: 30px 0;">
		<h3><?php 
esc_html_e( 'What You Can Do With WebinarIgnition:', 'webinar-ignition' );
?></h3>
		<ul style="list-style: none; padding-left: 0;">
			<li style="margin-bottom: 8px;">
				✅ <strong><?php 
esc_html_e( 'Sell WooCommerce products inside webinars', 'webinar-ignition' );
?></strong> 
				<?php 
esc_html_e( '(55% conversion vs 10% with redirects)', 'webinar-ignition' );
?>
			</li>
			<li style="margin-bottom: 8px;">
				✅ <strong><?php 
esc_html_e( 'Unlimited CTAs & automation', 'webinar-ignition' );
?></strong> 
				<?php 
esc_html_e( '(polls, forms, checkout, booking)', 'webinar-ignition' );
?>
			</li>
			<li style="margin-bottom: 8px;">
				✅ <strong><?php 
esc_html_e( 'Auto-fill customer details', 'webinar-ignition' );
?></strong> 
				<?php 
esc_html_e( '(seamless one-click purchases)', 'webinar-ignition' );
?>
			</li>
			<li style="margin-bottom: 8px;">
				✅ <strong><?php 
esc_html_e( 'Scale to 10,000+ participants', 'webinar-ignition' );
?></strong> 
				<?php 
esc_html_e( '(no attendee limits)', 'webinar-ignition' );
?>
			</li>
			<li style="margin-bottom: 8px;">
				✅ <strong><?php 
esc_html_e( 'Works with any page builder', 'webinar-ignition' );
?></strong> 
				<?php 
esc_html_e( '(Elementor, Gutenberg, Divi, etc.)', 'webinar-ignition' );
?>
			</li>
		</ul>
	</div>
	
	<!-- Trial CTA Box -->
	<?php 
if ( !webinarignition_fs()->is_registered() || webinarignition_fs()->is_free_plan() ) {
    ?>
	<div class="wi-trial-cta-box" style="background: #f0f9ff; border-left: 4px solid #0073aa; padding: 20px; margin: 30px 0; border-radius: 4px;">
		<h3 style="margin-top: 0;"><?php 
    esc_html_e( '🚀 Unlock All Features with 30-Day FREE Trial', 'webinar-ignition' );
    ?></h3>
		<p>
			<?php 
    esc_html_e( 'Get unlimited CTAs, WooCommerce checkout inside webinars, webhooks, analytics, and Done4U setup service—', 'webinar-ignition' );
    ?>
			<strong><?php 
    esc_html_e( 'plus save $99!', 'webinar-ignition' );
    ?></strong>
		</p>
		<a href="<?php 
    echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true' ) );
    ?>" class="button button-primary button-hero" style="margin-right: 10px;">
			<?php 
    esc_html_e( 'Start Free Trial (Save $99)', 'webinar-ignition' );
    ?>
		</a>
		<p style="font-size: 12px; color: #666; margin-top: 10px;">
			<?php 
    esc_html_e( 'E-mail reminder 7 days before trial ends. Cancel anytime.', 'webinar-ignition' );
    ?>
		</p>
	</div>
	<?php 
}
?>
	
	<!-- Next Steps -->
	<div style="margin: 30px 0;">
		<h3><?php 
esc_html_e( '🎯 Next Steps:', 'webinar-ignition' );
?></h3>
		<ol>
			<li style="margin-bottom: 8px;">
				<strong><?php 
esc_html_e( 'Watch the playlist above', 'webinar-ignition' );
?></strong> 
				<?php 
esc_html_e( '(Save time and do along with video)', 'webinar-ignition' );
?>
			</li>
			<li style="margin-bottom: 8px;">
				<strong><?php 
esc_html_e( 'Create your first webinar', 'webinar-ignition' );
?></strong> 
				→ <?php 
esc_html_e( 'Click the button below', 'webinar-ignition' );
?>
			</li>
			<?php 
if ( !webinarignition_fs()->is_registered() || webinarignition_fs()->is_free_plan() ) {
    ?>
			<li style="margin-bottom: 8px;">
				<strong><?php 
    esc_html_e( 'Activate your free trial', 'webinar-ignition' );
    ?></strong> 
				→ <?php 
    esc_html_e( 'Get unlimited CTAs + $99 savings', 'webinar-ignition' );
    ?>
			</li>
			<?php 
}
?>
		</ol>
	</div>
	
	<!-- Action Buttons -->
	<div style="margin-top: 30px;">
		<a class="blue-btn-2 btn newWebinarBTN" href="<?php 
echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&create' ) );
?>" style="margin-right: 10px;">
			<i class="icon-plus-sign" style="margin-right: 5px;"></i>
			<?php 
esc_html_e( 'Create a New Webinar', 'webinar-ignition' );
?>
		</a>
		<a class="blue-btn-2 btn newWebinarBTN" href="<?php 
echo esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&webinars' ) );
?>" style="margin-right: 10px;">
			<?php 
esc_html_e( 'Show All Webinars', 'webinar-ignition' );
?>
		</a>
	</div>
	
	<!-- Help Section -->
	<p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 13px;">
		<strong><?php 
esc_html_e( 'Need help?', 'webinar-ignition' );
?></strong> 
		<a href="https://webinarignition.com/meet-us/" target="_blank">
			<?php 
esc_html_e( 'Book a free onboarding call', 'webinar-ignition' );
?>
		</a>
	</p>
</div>
	</div>
	<div class="webinar_welcome_right_container">

		<?php 
$is_pending_activation = webinarignition_fs()->is_pending_activation();
$is_registered = webinarignition_fs()->is_registered() && webinarignition_fs()->is_tracking_allowed();
$sandbox_wp_environment_domains = array(
    'instawp.xyz',
    'tastewp.com',
    'playground.wordpress.net',
    'localhost',
    '127.0.0.1',
    'local'
);
// Get the current host
$current_host = $_SERVER['HTTP_HOST'];
// Check if current host matches any of the sandbox or local domains
$is_sandbox = false;
foreach ( $sandbox_wp_environment_domains as $sandbox_domain ) {
    if ( strpos( $current_host, $sandbox_domain ) !== false ) {
        $is_sandbox = true;
        break;
    }
}
if ( !$is_sandbox ) {
    if ( wp_validate_boolean( $is_pending_activation ) || !$is_registered ) {
        require WEBINARIGNITION_PATH . 'UI/opt-in/optin-popup-section-link.php';
    }
}
?>
<div class="webinar_dashboard_box_design wi_licence_box">
<?php 
echo wp_kses_post( sprintf(
    /* translators:
     * 1: signup link,
     * 2: free setup/training/consulting link,
     * 3: live webinar (EN) link,
     * 4: live webinar (DE) link,
     * 5: on-demand demo link
     */
    '<strong>1. %s</strong><br>
    %s – %s!<br><br>
    <strong>2. %s</strong><br>
    %s<br>
    %s %s %s',
    esc_html__( 'Try WebinarIgnition for free:', 'webinar-ignition' ),
    '<a href="' . esc_url( admin_url( 'admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true&trial=paid' ) ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Sign up now for a free 30-day trial of the Plus Plan.', 'webinar-ignition' ) . '</a>',
    sprintf( 
        /* translators: %s: website page link. */
        esc_html__( 'including %s', 'webinar-ignition' ),
        '<a href="https://webinarignition.com/meet-us/#unlimited-plus-license" target="_blank" rel="noopener noreferrer">' . esc_html__( 'free setup, training and consulting', 'webinar-ignition' ) . '</a>'
     ),
    esc_html__( 'Experience WebinarIgnition live:', 'webinar-ignition' ),
    esc_html__( 'Want to see WebinarIgnition in action?', 'webinar-ignition' ),
    sprintf( 
        /* translators: %s: website page link. */
        esc_html__( 'Join the %s', 'webinar-ignition' ),
        '<a href="https://webinarignition.com/the-webinar-driving-school/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'live webinar next Thursday (English)', 'webinar-ignition' ) . '</a>'
     ),
    esc_html__( 'or the', 'webinar-ignition' ) . ' ' . '<a href="https://webinar.campfiremarketing.de/webinar-fahrschule/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Wednesday webinar in German', 'webinar-ignition' ) . '</a>',
    esc_html__( '– or watch an', 'webinar-ignition' ) . ' ' . '<a href="https://demo.webinarignition.com/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'on-demand demo webinar', 'webinar-ignition' ) . '</a>.'
) );
?>
</div>

		<div class="webinar_dashboard_box_design wi_video">
			<iframe width="100%" height="300px" src="https://www.youtube.com/embed/7IDiVQXnwZI?si=76TDj9zrlYR8u_OJ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
		<div class="webinar_dashboard_box_design wi_video">
			<iframe width="100%" height="300px" src="https://www.youtube.com/embed/L12pNLZUfSI?si=v7J554IDZFamuGz1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
</div>