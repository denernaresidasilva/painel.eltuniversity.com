<?php
/**
 * @var $webinar_data
 * @var $assets
 * @var $statusCheck
 * @var $room_id
 * @var $hundered_ms_management_token
 * @var $hundered_ms_template_subdomain
 */
$url = 'https://api.100ms.live/v2/room-codes/room/' . $room_id;
$admin_meeting_code = isset($webinar_data->admin_meeting_code) && !empty($webinar_data->admin_meeting_code) ? esc_html($webinar_data->admin_meeting_code) : '';
$attendee_meeting_code = isset($webinar_data->attendee_meeting_code) && !empty($webinar_data->attendee_meeting_code) ? esc_html($webinar_data->attendee_meeting_code) : '';
if ( current_user_can( 'administrator' ) ) {
    $meeting_code = $admin_meeting_code;
} else {
	$meeting_code = $attendee_meeting_code;
}
if(isset($_GET['preview']) && $_GET['preview'] == 'true') {
	$user_name = wp_get_current_user()->display_name;
}else{
	$user_name = $lead->name;
}
?>
<iframe
  src="https://<?php echo wp_kses_post($hundered_ms_template_subdomain); ?>/meeting/<?php echo wp_kses_post($meeting_code); ?>?name=<?php echo wp_kses_post($user_name); ?>"
  width="100%"
  height="700"
  allow="camera; microphone; fullscreen; display-capture"
  style="border: none;"
></iframe>