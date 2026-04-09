<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );

/**
 * @var $webinar_data
 * @var $data
 * @var $leadId
 * @var $instantTest
 * @var $autoDate_format
 * @var $autoTime
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 */
$webinar_type = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';
if($webinar_type == 'live'){
	$lead_timezone = $lead->lead_browser_and_os != '' ? $lead->lead_browser_and_os : $webinar_data->webinar_timezone;

	$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date. ' '. $webinar_data->webinar_start_time, new DateTimeZone( $webinar_data->webinar_timezone ) );	
	$webinarTime = $webinarDateObject->format($webinar_data->time_format);

	$webinar_duration =  isset( $webinar_data->webinar_start_duration ) ? sanitize_text_field($webinar_data->webinar_start_duration ) : 60;
	$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date. ' '. $webinar_data->webinar_start_time, new DateTimeZone( $webinar_data->webinar_timezone ) );	
	
	$webinarDateObject->setTimezone(new DateTimeZone($lead_timezone));
	$webinarTime = $webinarDateObject->format($webinar_data->time_format);
	$webinarDateObject = $webinarDateObject->format('m-d-Y');
	$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y', $webinarDateObject, new DateTimeZone( 'UTC' ) );
	$webinarTimestamp       = $webinarDateObject->getTimestamp();
	$date_format            = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : ( ( $webinar_data->webinar_date == 'AUTO' ) ? 'l, F j, Y' : get_option( 'date_format' ) );
	$date =  date_i18n( $date_format, $webinarTimestamp );
}else{
	if($webinar_data->lp_schedule_type == 'fixed'){
		$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->auto_date_fixed_submit. ' ' . $webinar_data->auto_time_fixed_submit, new DateTimeZone( $webinar_data->auto_timezone_fixed  ) );
		$lead_timezone = $lead->lead_timezone;
		$webinarDateObject->setTimezone(new DateTimeZone($lead->lead_timezone));
		$webinarTime = $webinarDateObject->format($webinar_data->time_format);
		$webinarDateObject = $webinarDateObject->format('m-d-Y');
		$webinarDateObject      = DateTime::createFromFormat( 'm-d-Y', $webinarDateObject, new DateTimeZone( 'UTC' ) );
		$webinarTimestamp       = $webinarDateObject->getTimestamp();
		$date_format            = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : ( ( $webinar_data->webinar_date == 'AUTO' ) ? 'l, F j, Y' : get_option( 'date_format' ) );
		$date =  date_i18n( $date_format, $webinarTimestamp );

	}else{
		list($date, $time) = explode(' ', $lead->date_picked_and_live);
		$webinarDateObject      = DateTime::createFromFormat( 'Y-m-d', $date );	
		$date_format            = ! empty( $webinar_data->date_format ) ? $webinar_data->date_format : ( ( $webinar_data->webinar_date == 'AUTO' ) ? 'l, F j, Y' : get_option( 'date_format' ) );
		$webinarTimestamp       = $webinarDateObject->getTimestamp();
		$date =  date_i18n( $date_format, $webinarTimestamp );
		$timestamp = strtotime( $time ); // Convert to timestamp
		$webinarTime = date_i18n( $webinar_data->time_format, $timestamp );
		$lead_timezone = $lead->lead_timezone;
	}
}

?>

<div class="eventDate ticketSectionNew ts" <?php echo esc_attr( $instantTest ); ?>>
	<div class="dateIcon">
		<div class="dateMonth">
		<?php // echo esc_html( webinarignition_get_localized_week_day( $webinar_data, $lead ) ); ?> 
		</div>
		<div class="dateDay">
			<?php
				// echo esc_html( webinarignition_get_live_date_day( $webinar_data, $lead ) );
				// echo ( substr( get_locale(), 0, 2 ) === 'en' ) ? '' : '.';
			?>
		</div>
		
		<div class="dateDayWeek">
			<?php // echo esc_html( webinarignition_get_locale_month( $webinar_data, $lead ) ); ?>
		</div>        
		
	</div>

	<div class="dateInfo">
		<div class="dateHeadline">
			<?php echo esc_html( $date ); ?>
		</div>
		<div class="dateSubHeadline">
			<?php echo esc_html( $webinar_data->lp_webinar_subheadline ? $webinar_data->lp_webinar_subheadline : esc_html__( 'At', 'webinar-ignition' ) . ' ' . esc_html( $webinarTime ) . ' '.  esc_html($lead_timezone) ); ?>
		</div>
		<?php if($webinar_type == 'live'){ ?>
			<div class="dateSubHeadline">
				<?php 
				/* translators: %s: Webinar duration in minutes. */	
				echo  esc_html( sprintf( __( 'Duration: %s minutes', 'webinar-ignition' ), $webinar_duration ) ); ?>
			</div>
		<?php } ?>
	</div>

	<br clear="left">
</div>
