<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data;
 * @var $leadId;
 */
$is_preview = get_query_var( 'webinarignition_preview' );
$thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );

?>

<?php $wi_calendarOption = ! empty( $webinar_data->ty_add_to_calendar_option ) ? $webinar_data->ty_add_to_calendar_option : 'enable'; 		
$thankyou_URL      = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' ); ?>

<?php if ( 'enable' === trim($wi_calendarOption) ) : ?>
	<div class="ticketSection ticketCalendarArea wiTicketSection">
		<div class="optinHeadline12 wiOptinHeadline1">
		<?php
		webinarignition_display(
			$webinar_data->ty_calendar_headline,
			__( 'Add To Your Calendar', 'webinar-ignition' )
		);
		?>
		</div>

		<!-- AUTO CODE BLOCK AREA -->
		<?php if ( 'AUTO' === $webinar_data->webinar_date ) { ?>
			<?php
			if ( $is_preview ) {
				?>
				<!-- AUTO DATE -->
				<div class="wi-btns-wrap">
					<a href="#" class="small button wiButton wiButton-info wiButton-block"
						target="_blank">
						<i class="icon-google-plus"></i> 
						<?php
						webinarignition_display(
							$webinar_data->ty_calendar_google,
							__( 'Google Calendar', 'webinar-ignition' )
						);
						?>
					</a>
					<a href="#" class="small button wiButton wiButton-info wiButton-block" target="_blank">
						<i class="icon-calendar"></i> <?php webinarignition_display( $webinar_data->ty_calendar_ical, __( 'iCal / Outlook', 'webinar-ignition' ) ); ?>
					</a>
				</div>
				<?php
			} else {
				$googleCalendarURL = add_query_arg(
					array(
						'googlecalendarA' => '1', // This line ensures '=' is included
						'lid'             => $leadId,
					),
					$thankyou_URL
				);
				
				$iCalendarURL = add_query_arg(
					array(
						'icsA' => '1',
						'lid'  => $leadId,
					),
					$thankyou_URL
				);
				?>
				
				<!-- AUTO DATE -->
				<div class="wi-btns-wrap">
					<a href="<?php echo esc_url( $googleCalendarURL ); ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
						<i class="icon-google-plus"></i>
						<?php
						webinarignition_display(
							$webinar_data->ty_calendar_google,
							__( 'Google Calendar', 'webinar-ignition' )
						);
						?>
					</a>
					<a href="<?php echo esc_url( $iCalendarURL ); ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
						<i class="icon-calendar"></i>
						<?php
						webinarignition_display(
							$webinar_data->ty_calendar_ical,
							__( 'iCal / Outlook', 'webinar-ignition' )
						);
						?>
					</a>
				</div>
				<?php
			}//end if
			?>
		<?php } else { ?>
			<?php
			if ( $is_preview ) {
				?>
				<a href="#" class="small button wiButton wiButton-info wiButton-block">
					<i class="icon-google-plus"></i>
					<?php
					webinarignition_display(
						$webinar_data->ty_calendar_google,
						__( 'Google Calendar', 'webinar-ignition' )
					);
					?>
				</a>
				<a href="#" class="small button wiButton wiButton-info wiButton-block">
					<i class="icon-calendar"></i>
					<?php
					webinarignition_display(
						$webinar_data->ty_calendar_ical,
						__( 'iCal / Outlook', 'webinar-ignition' )
					);
					?>
				</a>
				<?php
			} else {
				$googleCalendarURL = add_query_arg(
					array(
						'googlecalendar' => '1', // Ensure '=' is included
						'lid'            => $leadId,
					),
					$thankyou_URL
				);
			
				$iCalendarURL = add_query_arg(
					array(
						'ics' => '1', // Ensure '=' is included
						'lid' => $leadId,
					),
					$thankyou_URL
				);
				?>
				<a href="<?php echo esc_url( $googleCalendarURL ); ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
					<i class="icon-google-plus"></i>
					<?php
					webinarignition_display(
						$webinar_data->ty_calendar_google,
						__( 'Google Calendar', 'webinar-ignition' )
					);
					?>
				</a>
				<a href="<?php echo esc_url( $iCalendarURL ); ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
					<i class="icon-calendar"></i>
					<?php
					webinarignition_display(
						$webinar_data->ty_calendar_ical,
						__( 'iCal / Outlook', 'webinar-ignition' )
					);
					?>
				</a>
				<?php
			}
			?>
			
			<?php
		}//end if
		?>
		<!-- END OF AUTO CODE BLOCK -->

	</div>
<?php endif; ?>
