<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $leadinfo
 */
$webinar_preview_url = add_query_arg(array(
	'live' => '',
	'lid' => $leadId,
), get_the_permalink($webinarId));
?>
<a href="<?php 
echo esc_url( $webinar_preview_url ); ?>"
		class="ticketCDAreaBTN button alert radius disabled addedArrow  wiButton wiButton-success wiButton-block wiButton-lg"
		id="webinarBTNNN">
		<?php 
		webinarignition_display( $webinar_data->tycd_countdown, __( 'Webinar Starts Soon:', 'webinar-ignition' ) ); ?>
		<div id="defaultCountdown"></div>
	</a>


<br clear="left"/>

<?php if ( 'AUTO' === $webinar_data->webinar_date ) {
		$date_format = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : ( ( 'AUTO' === $webinar_data->webinar_date ) ? 'l, F j, Y' : get_option( 'date_format' ) );
	if ( ! empty( $webinar_data->time_format ) && ( '12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format ) ) { // old formats
		$webinar_data->time_format = get_option( 'time_format', 'H:i' );
	}
		$time_format = $webinar_data->time_format;

	if ( ! empty( $leadinfo ) ) {
		$autoDate_info = explode( ' ', $leadinfo->date_picked_and_live );
		$autoDate      = $autoDate_info[0];

		$localized_date = webinarignition_get_localized_date( $webinar_data, $leadinfo );
		$timeonly       = ( empty( $webinar_data->display_tz ) || ( ! empty( $webinar_data->display_tz ) && ( 'yes' === $webinar_data->display_tz ) ) ) ? false : true;
		$autoTime       = webinarignition_get_time_tz( $autoDate_info[1], $time_format, false, false, $timeonly );

		$autoDate2 = $localized_date . ' - ' . $autoTime;

		echo "<div class='cd_auto_date' >" . esc_html( $autoDate2 ) . '</div>';
	}
}//end if
?>
