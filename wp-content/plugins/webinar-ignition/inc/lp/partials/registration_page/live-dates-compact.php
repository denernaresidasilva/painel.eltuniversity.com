<?php
/**
 * @var $webinar_data
 * @var $uid
 * @var $liveEventMonth
 * @var $liveEventDateDigit
 * @var $autoDate_format
 * @var $autoTime
 * @var $is_compact
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="eventDateContainer <?php echo esc_attr( $uid ); ?>">
	<span class="eventDate">
		<?php echo esc_html( $localized_date ); ?>
	</span>
	<br>
	<span class="eventTime">
		<?php webinarignition_get_time_inline( $webinar_data, true ); ?>
	</span>
	<span class="eventTimezone">
		(<?php webinarignition_get_timezone_inline( $webinar_data, true ); ?>)
	</span>
</div>
