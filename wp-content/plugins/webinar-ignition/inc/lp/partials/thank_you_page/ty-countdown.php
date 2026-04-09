<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
/**
 * @var $webinarId
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $instantTest
 * @var $autoDate_format
 * @var $autoTime
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 */

$prefix = 'tyCountdown-';
$uid = wp_unique_id( $prefix );
$is_public = WebinarignitionManager::webinarignition_is_webinar_public( $webinar_data );
$watch_type = sanitize_text_field( filter_input( INPUT_GET, 'watch_type', FILTER_SANITIZE_SPECIAL_CHARS ) );
if ( empty( $watch_type ) ) {
	$watch_type = 'live';
}

if ( ! $is_public ) {
	$webinarIdUrl = $webinar_data->hash_id;
} else {
	$webinarIdUrl = $webinarId;
}
?>

<div id="<?php echo esc_attr( $uid ); ?>" class="tyCountdown tyCountdown-<?php echo esc_attr( $webinarId ); ?> ticketCDArea ticketSection ticketSectionNew">
	<?php
	if ( isset( $webinar_data->ty_webinar_url ) && 'custom' === $webinar_data->ty_webinar_url && ! empty( $webinar_data->ty_werbinar_custom_url ) ) {
		$liveWebinarUrl = $webinar_data->ty_werbinar_custom_url;
	} else {

		$liveWebinarUrl = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'webinar' );
		$liveWebinarUrl = add_query_arg( 'live', '', $liveWebinarUrl );
		$liveWebinarUrl = add_query_arg( 'webinar', '', $liveWebinarUrl );

		if ( empty( $leadId ) && isset( $getLiveIDByEmail->id ) && ! empty( $getLiveIDByEmail->id ) ) {
			$leadId = $getLiveIDByEmail->id;
		}

		$liveWebinarUrl = add_query_arg( 'lid', $leadId, $liveWebinarUrl );
		if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
			$liveWebinarUrl = add_query_arg( md5( $webinar_data->paid_code ), '', $liveWebinarUrl );
		}

		$liveWebinarUrl = add_query_arg( 'watch_type', $watch_type, $liveWebinarUrl );
	}
	?>
	<a href="<?php echo esc_url( $liveWebinarUrl ); ?>"
		class="ticketCDAreaBTN button alert radius disabled addedArrow  wiButton wiButton-success wiButton-block wiButton-lg"
		id="webinarBTNNN">
		<?php webinarignition_display( $webinar_data->tycd_countdown, __( 'Webinar Starts Soon:', 'webinar-ignition' ) ); ?>
		<div id="defaultCountdown"></div>
	</a>
</div>
