<!-- ON AIR AREA -->
<?php 
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
$checkKeys = [
    'air_btn_copy',
    'air_tab_copy',
    'air_btn_url',
    'air_btn_color',
    'air_broadcast_message_width',
    'air_broadcast_message_bg_transparency',
    'live_webinar_ctas_position_radios',
    'live_webinar_ctas_alignment_radios',
    'air_html'
];
$shouldGenerateLiveCtas = false;
foreach ( $checkKeys as $key ) {
    error_log( "looping the keys: {$key}" );
    if ( isset( $results->{$key} ) && $results->{$key} !== '' ) {
        error_log( "match found for {$key} with value: " . print_r( $results->{$key}, true ) );
        $shouldGenerateLiveCtas = true;
        break;
    }
}
if ( $shouldGenerateLiveCtas ) {
    $key_map = [
        'air_toggle'                            => 'air_toggle',
        'air_amelia_toggle'                     => 'air_amelia_toggle',
        'air_html'                              => 'response',
        'air_btn_copy'                          => 'button_text',
        'air_btn_url'                           => 'button_url',
        'air_btn_color'                         => 'button_color',
        'air_tab_copy'                          => 'tab_text',
        'air_broadcast_message_width'           => 'box_width',
        'air_broadcast_message_bg_transparency' => 'bg_transparency',
        'live_webinar_ctas_alignment_radios'    => 'box_alignment',
        'live_webinar_ctas_position_radios'     => 'cta_position',
    ];
    $formatted_result = [
        'live_ctas' => [
            1 => [],
        ],
    ];
    foreach ( $key_map as $source => $target ) {
        $formatted_result['live_ctas'][1][$target] = ( isset( $results->{$source} ) ? $results->{$source} : null );
    }
    $results->live_ctas = $formatted_result['live_ctas'];
    // Remove unwanted keys
    foreach ( $checkKeys as $key ) {
        if ( isset( $results->{$key} ) ) {
            unset($results->{$key});
        }
    }
    if ( isset( $results->air_toggle ) ) {
        unset($results->air_toggle);
    }
    if ( isset( $results->air_amelia_toggle ) ) {
        unset($results->air_amelia_toggle);
    }
    update_option( 'webinarignition_campaign_' . $ID, $results );
}
$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
$live_ctas = ( !empty( $webinar_data->live_ctas ) ? $webinar_data->live_ctas : [] );
// print_r($live_ctas);
?>
<script>
    const existingCTAs = <?php 
echo json_encode( $live_ctas );
?>;
</script>
<div id="onairTab" style="display:none;" class="consoleTabs">
	<div class="statsDashbord">
		<div class="statsTitle statsTitle-Air">
			<div class="statsTitleIcon">
				<i class="icon-microphone icon-2x"></i>
			</div>

			<div class="statsTitleCopy">
				<h2><?php 
esc_html_e( 'On Air', 'webinar-ignition' );
?></h2>
				<p><?php 
esc_html_e( 'Manage the live broadcasting message to live viewers...', 'webinar-ignition' );
?></p>
			</div>

			<br clear="left" />
		</div>
	</div>

	<div class="innerOuterContainer">
		<div class="innerContainer">
			

			<div id="wi-notification-overlay" class="wi-notification-overlay wi-hidden"></div>
			<div id="wi-notification-box" class="wi-hidden">
				<div class="wi-notification-content">
					<span id="wi-notification-message"></span>
					<button id="wi-close-notification"><?php 
esc_html_e( 'Close', 'webinar-ignition' );
?></button>
				</div>
			</div>

			<div class="cta-new-implementation">

				<div id="cta-container">
					<!-- CTA Blocks will be injected here -->
				</div>

				<div class="btn-container-new">
					<button id="add-cta" class="button button-primary">+ Create New CTA</button>
					<button id="save-cta" class="button button-primary" style="background: #6bba40;"><i class="icon-save"></i> 
						<span id="saveAirText"><?php 
esc_html_e( 'Save', 'webinar-ignition' );
?></span>
					</button>
				</div>
				
			</div>
		
		</div>
	</div>

</div>
</div>

<!-- Template for cloning (can be hidden or loaded via AJAX) -->

<script type="text/template" id="cta-template">
	<div class="additional_auto_action_item" data-cta-id="{{ctaId}}">
		<!-- <div class="airSwitchRight airSwitchOnTeepa">
			<p class="field switch">
				<input type="hidden" id="airToggle{{ctaId}}" name="airToggle{{ctaId}}" value="off">
				<label class="cb-enable airToggleO" for="airToggleOn{{ctaId}}" id="airToggleLableOn{{ctaId}}"><span>On</span></label>
				<label class="cb-disable airToggleO selected" id="airToggleLableOff{{ctaId}}"><span>Off</span></label>
			</p>
		</div> -->
		<div class="auto_action_header">
			<h4> CTA <span class="index_holder">{{ctaId}}</span>:
				<span class="cta_position_desc_holder">Sidebar</span>
				<span class="cta_position_desc_holder_comma"></span>
				<span class="auto_action_desc_holder"></span>
				<i class="icon-arrow-up"></i>
				<i class="icon-arrow-down" style="display :none;"></i>
			</h4>
		</div>
		<div class="auto_action_body">
			<div class="airSwitch">
				<div class="airSwitchLeft">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle">Display CTA in iFrame</span>
						
					</div>
				</div>

		<?php 
if ( is_plugin_active( 'advanced-iframe/advanced-iframe.php' ) || is_plugin_active( 'advanced-iframe-pro/advanced-iframe-pro.php' ) ) {
    ?>
				<div class="airSwitchRight">
					<p class="field switch ameliaSwitch">
						<input type="hidden" class="input-hidden-fields" id="isAdvaceIframeActive{{ctaId}}" value="1" />
						<input type="hidden" class="input-hidden-fields" id="airAmeliaToggle{{ctaId}}" value="off">
						<label for="airAmeliaToggleOn{{ctaId}}" id="airAmeliaToggleLableOn{{ctaId}}" class="cb-enable airAmeliaToggleO ">
							<span>On</span></label>
						<label for="airAmeliaToggleOff{{ctaId}}" id="airAmeliaToggleLableOff{{ctaId}}" class="cb-disable airAmeliaToggleO selected">
							<span>Off</span>
						</label>
					</p>
				</div>
			<?php 
} else {
    ?>
				<div class="airSwitchRight">
					<a href="<?php 
    echo esc_url( self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=advanced-iframe' ) );
    ?>" 
						target="_blank" class="wi-advanced-iframe-btn">
						<?php 
    esc_html_e( 'Install Advanced Iframe', 'webinar-ignition' );
    ?>
					</a>
				</div>
				<input type="hidden" id="isAdvaceIframeActive{{ctaId}}" value="0" />


				<?php 
}
?>

				<br clear="all" />
			</div>
			<div class="airExtraOptions mt-20">
				<span class="airSwitchTitle"><?php 
esc_html_e( 'CTA Position', 'webinar-ignition' );
?></span>
				<span class="airSwitchInfo"><?php 
esc_html_e( 'If you select overlay, CTA section will cover your webinar video.', 'webinar-ignition' );
?></span>
				<div class="PositionRadiosForLiveWebinarCTAs{{ctaId}}">
					<input class="live_webinar_ctas_position_radios input-radio-fields" type="radio" id="OverlayPos{{ctaId}}" name="ctaPosition{{ctaId}}" value="overlay">
					<label class="live_webinar_ctas_position_label" id="OverlayLabel{{ctaId}}" for="OverlayPos{{ctaId}}"> <?php 
esc_html_e( 'Overlay', 'webinar-ignition' );
?></label>

					<input class="live_webinar_ctas_position_radios input-radio-fields" type="radio" id="TabPos{{ctaId}}" name="ctaPosition{{ctaId}}" value="outer">
					<label class="live_webinar_ctas_position_label" id="TabLabel{{ctaId}}" for="TabPos{{ctaId}}"><?php 
esc_html_e( 'Sidebar', 'webinar-ignition' );
?></label>
				</div>
			</div>
			<div class="airExtraOptions">
				<span class="airSwitchTitle"><?php 
esc_html_e( 'CTA/Tab name', 'webinar-ignition' );
?></span>
				<span class="airSwitchInfo"><?php 
esc_html_e( 'This is the tab name that is displayed on the sidebar...', 'webinar-ignition' );
?></span>
				<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_html_e( 'Ex: Default Title', 'webinar-ignition' );
?>" id="air_tab_copy{{ctaId}}" value="">
				<input type="hidden" class="input-hidden-fields" id="isTabNameAvailable{{ctaId}}" value="1" />
			</div>

			<span class="airSwitchInfo code-display">
				<p><?php 
esc_html_e( 'Shortcode examples (need free plugin to work):', 'webinar-ignition' );
?></p>
				<code class="copy-code" data-code='[booking id="1"]'>[booking id="1"] needs any appointment/booking plugin</code><br>
				<code class="copy-code" data-code='[democracy id="1"]'>[democracy id="1"] needs free Democracy Poll plugin. Works nicely with the admin reload CTA function, to update results after all voted</code><br>
				<code class="copy-code" data-code='[wise-chat channel="Welcome to QA" collect_user_stats="0" allow_post_links="0" enable_twitter_hashtags="0" show_users="0" show_users_counter="1" user_name_prefix="Visitor" messages_limit="60" messages_order="" multiline_support="1" allow_change_text_color="0" enable_images_uploader="0" enable_attachments_uploader="0" theme="airflow" chat_height="90dvh"]'>[wise-chat channel="Welcome to QA" collect_user_stats="0" allow_post_links="0" enable_twitter_hashtags="0" show_users="0" show_users_counter="1" user_name_prefix="Visitor" messages_limit="60" messages_order="" multiline_support="1" allow_change_text_color="0" enable_images_uploader="0" enable_attachments_uploader="0" theme="airflow" chat_height="90dvh"] Needs free Wise Chat plugin. Not translated!</code>
				<p><?php 
esc_html_e( 'Hint: Above shortcodes needs to be loaded in iframe to work.', 'webinar-ignition' );
?></p>
					<br><br>
				<p><?php 
esc_html_e( 'External services examples:', 'webinar-ignition' );
?></p>
				<code class="copy-code" data-code='[advanced_iframe src="https://checkout.freemius.com/plugin/7606/plan/23433/?coupon=&billing_cycle=monthly&hide_coupon=true&trial=paid&affiliate_user_id=0&currency=auto&locale=auto&user_email={EMAIL}&user_firstname={FIRSTNAME}&user_lastname={LASTNAME}" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]'>[advanced_iframe src="https://checkout.freemius.com/plugin/7606/plan/23433/?coupon=&billing_cycle=monthly&hide_coupon=true&trial=paid&affiliate_user_id=0&currency=auto&locale=auto&user_email={EMAIL}&user_firstname={FIRSTNAME}&user_lastname={LASTNAME}" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]</code>
					<br>
					<br>
				<code class="copy-code" data-code='[advanced_iframe src="https://www.digistore24.com/product/581652?quantity=1&email={EMAIL}&first_name={FIRSTNAME}&last_name={LASTNAME}&aff=&affiliate=&voucher=" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]'>[advanced_iframe src="https://www.digistore24.com/product/581652?quantity=1&email={EMAIL}&first_name={FIRSTNAME}&last_name={LASTNAME}&aff=&affiliate=&voucher=" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]</code>
					<br><br>
				<code class="copy-code" data-code='[advanced_iframe src="https://tawk.to/chat/60041d64a9a34e36b96d49b5/1es8166a2" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]'>[advanced_iframe src="https://tawk.to/chat/60041d64a9a34e36b96d49b5/1es8166a2" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"] Do answer questions directly in their app. Needs an free https://www.tawk.to/ account. For little money they offer AI...</code>
					<br><br>
				<code class="copy-code" data-code='[advanced_iframe src="https://demo.webinarignition.com/checkout/?add-to-cart=296&alg_apply_coupon=50off" width=”100%” height="100vh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]'>[advanced_iframe src="https://demo.webinarignition.com/checkout/?add-to-cart=296&alg_apply_coupon=50off" width=”100%” height="100vh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"] No URL parameter needed we handover name / email. If WebinarIgnition and WooCommerce are on the same domain, the fields are filled automatically. Other cases need a same site tweak see KB.</code>
					<br><br>					
				<p><?php 
esc_html_e( 'External interaction services examples:', 'webinar-ignition' );
?></p>
				<code class="copy-code" data-code='[advanced_iframe src="https://tidycal.com/hajocampfiremarketing/strategiegespraech-webinar-45min-wi-m8ejqn2?email={EMAIL}&name={FIRSTNAME}&lastname={LASTNAME}&phone={PHONENUM}" width=”100%” height="100vh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]'>[advanced_iframe src="https://tidycal.com/hajocampfiremarketing/strategiegespraech-webinar-45min-wi-m8ejqn2?email={EMAIL}&name={FIRSTNAME}&lastname={LASTNAME}&phone={PHONENUM}" width=”100%” height="100vh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"] Tidycal.com link. Hint: Keep text short.</code>
					<br>
				<code class="copy-code" data-code='[advanced_iframe src="https://calendly.com/your-meeting-link?name={FIRSTNAME}%20{LASTNAME}&email={EMAIL}&a1={PHONE}&a2=&a3=" width=”100%” height="100vh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]'>[advanced_iframe src="https://calendly.com/your-meeting-link?name={FIRSTNAME}%20{LASTNAME}&email={EMAIL}&a1={PHONE}&a2=&a3=" width=”100%” height="100vh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"] calendly.com. Hint: Keep text short.</code>
					<br>	
			</span>
			<span class="airSwitchInfo code-display special">
				<a href="https://webinarignition.tawk.help/article/sell-anything-inside-webinar" target="_blank" >Handbook: Sell, interact inside Webinar</a>
			</span>
			<textarea type="text" id="aircodyEditor{{ctaId}}" class="wp-editor-area" placeholder="write your cta's content" ></textarea>

			<div style="margin-top: 10px;display:flex;">
				<p class="wi-heading" ><?php 
esc_html_e( 'Supported Placeholder:', 'webinar-ignition' );
?> <?php 
wiShowTooltip( 'The placeholder are available in the editor above. See "Insert Placeholder" drop-down.' );
?></p>
			</div>
			<?php 
echo '{EMAIL}: ' . esc_html__( 'Lead Email, ', 'webinar-ignition' ) . '{FIRSTNAME}: ' . esc_html__( 'Lead First Name', 'webinar-ignition' );
?>

			<div class="flex items-center gap-3 mt-20 wi-grid-item-heading">
				<img src="<?php 
echo esc_url( WEBINARIGNITION_URL . 'images/hand.svg' );
?>" />
				<p class="wi-heading"><?php 
esc_html_e( 'Button Settings', 'webinar-ignition' );
?> </p>
			</div>
			<div class="flex gap-15 wi-cta-btn-wrap">
				<div class="airExtraOptions">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle">
							<?php 
echo esc_html__( 'Text', 'webinar-ignition' );
?>
						</span>
						<?php 
wiShowTooltip( 'This is the text that will be displayed on the call to action button.' );
?>
					</div>
					<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_html_e( 'Ex: Click Here To Download Your Copy', 'webinar-ignition' );
?>" id="air_btn_copy{{ctaId}}" value="">
				</div>

				<div class="airExtraOptions">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( 'URL', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'This is the url the button goes to (leave blank if you don\'t want the button to appear).' );
?>
					</div>
					<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_html_e( 'Ex: http://yoursite.com/order-now', 'webinar-ignition' );
?>" id="air_btn_url{{ctaId}}" value="">
				</div>
				<div class="airExtraOptions">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( 'Color', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'This is the color of the call to action button.' );
?>
					</div>
					<?php 
$air_btn_color = ( !empty( $webinar_data->air_btn_color ) ? $webinar_data->air_btn_color : '#6BBA40' );
?>
					<input type="color" id="air_btn_color{{ctaId}}" name="air_btn_color{{ctaId}}" value="">
				</div>
			</div>
			<div class="flex items-center gap-3 mt-20 wi-grid-item-heading">
				<img src="<?php 
echo esc_url( WEBINARIGNITION_URL . 'images/broadcast.svg' );
?>" />
				<p class="wi-heading"><?php 
esc_html_e( 'Broadcast Message Settings', 'webinar-ignition' );
?></p>
			</div>
			<div class="grid gap-15 wi-cta-btn-wrap wi-broadcast-wrap">
				<div class="airExtraOptions console-if-overlay-container{{ctaId}}">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( ' Background Transparency', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'Set BG transparancy from 0 to 100, where 100 - totally transparent' );
?>
					</div>

					<!-- <span class="airSwitchInfo"><?php 
esc_html_e( 'Set BG transparancy from 0 to 100, where 100 - totally transparent', 'webinar-ignition' );
?></span> -->
					<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_attr_e( 'Ex: 10', 'webinar-ignition' );
?>" id="air_broadcast_message_bg_transparency{{ctaId}}" value="">
				</div>
				<div class="airExtraOptions console-if-outer-container{{ctaId}}">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( ' Background Transparency', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'Set BG transparancy from 0 to 100, where 100 - totally transparent' );
?>
					</div>

					<span class="not-applicable"><?php 
esc_html_e( 'No BG transparency is applicable for sidebar CTA', 'webinar-ignition' );
?></span>
					<!-- <span class="airSwitchInfo"><?php 
esc_html_e( 'Set BG transparancy from 0 to 100, where 100 - totally transparent', 'webinar-ignition' );
?></span> -->
					<input type="hidden" class="input-hidden-fields" style="margin-top: 10px;" id="air_broadcast_message_bg_transparency{{ctaId}}" value="0">
				</div>
				<div class="airExtraOptions console-if-overlay-container{{ctaId}}">

					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( 'Width', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA 60% width' );
?>
					</div>


					<!-- <span class="airSwitchInfo"><?php 
esc_html_e( 'Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA 60% width', 'webinar-ignition' );
?></span> -->
					<input type="text" style="margin-top: 10px;" placeholder="<?php 
esc_attr_e( 'Ex: 60%', 'webinar-ignition' );
?>" id="air_broadcast_message_width{{ctaId}}" value="">
				</div>
				<div class="airExtraOptions console-if-outer-container{{ctaId}}">

					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( 'Width', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA 60% width' );
?>
					</div>

					<span class="not-applicable"><?php 
esc_html_e( 'No width is applicable for sidebar CTA', 'webinar-ignition' );
?></span>
					<!-- <span class="airSwitchInfo"><?php 
esc_html_e( 'Set maximum width for default CTA section. Left blank or set 0 if you want to set CTA 60% width', 'webinar-ignition' );
?></span> -->
					<input type="hidden" class="input-hidden-fields" style="margin-top: 10px;" id="air_broadcast_message_width{{ctaId}}" value="100%">
				</div>
				<div class="airExtraOptions console-if-overlay-container{{ctaId}}">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( 'Alignment', 'webinar-ignition' );
?></span>

						<?php 
wiShowTooltip( 'Set alignment for default CTA section. If not selected then center will be the default option.' );
?>
					</div>
					<?php 
include WEBINARIGNITION_PATH . 'templates/notices/go-pro-btn.php';
?>
				</div>
				<div class="airExtraOptions console-if-outer-container{{ctaId}}">
					<div class="flex justify-left gap-3">
						<span class="airSwitchTitle"><?php 
esc_html_e( 'Alignment', 'webinar-ignition' );
?></span>
						<?php 
wiShowTooltip( 'Set alignment for default CTA section. If not selected then center will be the default option.' );
?>
					</div>
					<span class="not-applicable"><?php 
esc_html_e( 'Broadcast Message Alignment Not Applicable on Sidebar CTA', 'webinar-ignition' );
?></span>
					<input class="live_webinar_ctas_alignment_radios" 
					<?php 
echo ( isset( $cta['box_alignment'] ) ? checked( $cta['box_alignment'], 'center', 'center' ) : '' );
?>
						type="checkbox" id="centerAlign{{ctaId}}" name="alignment" value="center">
				</div>
		</div>

		<div class="auto_action_footer" style="padding: 15px;">
			<button type="button" class="blue-btn-44 btn clone-cta" data-cta-id="{{ctaId}}" style="color:#FFF;float:none;">
				<i class="icon-copy"></i> <span><?php 
esc_html_e( 'copy', 'webinar-ignition' );
?></span>
			</button>
			<button type="button" class="blue-btn btn remove_cta" style="color:#FFF;float:none;">
				<i class="icon-remove"></i> <span><?php 
esc_html_e( 'delete', 'webinar-ignition' );
?></span>
			</button>
		</div>
	</div>
</script>

<!-- Template for pro notice when free users try to add more CTAs -->
<script type="text/template" id="pro-cta-notice-template">
    <div class="additional_auto_action_item">
        <div class="additional_auto_action_item auto_action_item auto_action_item_active">
            <div class="auto_action_header">
                <h4>
                    <?php 
echo esc_html__( 'CTA ', 'webinar-ignition' );
?><span class="index_holder"></span>:
                    <span class="cta_position_desc_holder"></span>
                    <span class="cta_position_desc_holder_comma"></span>
                    <span class="auto_action_desc_holder"></span>
                    <i class="icon-arrow-up"></i>
                    <i class="icon-arrow-down" style="display :none;"></i>
                </h4>
            </div>
            <div class="auto_action_body">
                <?php 
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?>
            </div>
        </div>
    </div>
</script>