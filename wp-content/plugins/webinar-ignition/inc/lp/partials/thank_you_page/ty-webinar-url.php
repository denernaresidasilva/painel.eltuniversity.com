<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
/**
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $webinarId
 */

$prefix = 'tyCountdown-';
$uid = wp_unique_id( $prefix );
$url = '';
if ( isset( $webinar_data->ty_webinar_url ) && 'custom' === $webinar_data->ty_webinar_url && ! empty( $webinar_data->ty_werbinar_custom_url ) ) {
	$url = $webinar_data->ty_werbinar_custom_url;
} else {
	$watch_type = 'live';
	$url = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'webinar' );

	if ( ( ! isset( $leadId ) || empty( $leadId ) ) && ( isset( $getLiveIDByEmail->id ) && ! empty( $getLiveIDByEmail->id ) ) ) {
		$leadId = $getLiveIDByEmail->id;
	}

	$webinar_page_query_args = array(
		'live' => '',
		'webinar' => '',
		'lid'  => $leadId,
		'watch_type'  => $watch_type,
	);

	// append paid_code to the URL
	if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
		$webinar_page_query_args[ md5( $webinar_data->paid_code ) ] = '';
	}

	$url = add_query_arg( $webinar_page_query_args, $url );
}//end if
?>

<div class="webinarURLArea">
	<div class="webinarURLHeadline">
		<i class="icon-bookmark" style="margin-right: 10px; color: #878787;"></i>
		<?php
			$url_message = __( 'Here Is Your Webinar Event URL...', 'webinar-ignition' );
			webinarignition_display( $webinar_data->ty_webinar_headline, $url_message );
		?>
	</div>

	<!-- AUTO CODE BLOCK AREA -->
	<div class="wiFormGroup wiFormGroup-lg">
		<input type="url" id="webbyURL" class="radius fieldRadius wiRegForm optNamer wiFormControl" value="<?php echo esc_url( $url ); ?>">
	</div>
	<!-- END AUTO CODE BLOCK AREA -->

	<div class="webinarURLHeadline2">
		<?php
			$save_bkmrk_msg = __( 'Save and bookmark this URL so you can get access to the live webinar and webinar replay...', 'webinar-ignition' );
			webinarignition_display( $webinar_data->ty_webinar_subheadline, $save_bkmrk_msg );
		?>
	</div>
</div>
