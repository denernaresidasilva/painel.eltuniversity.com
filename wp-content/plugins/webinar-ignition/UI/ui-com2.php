<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly



function wiShowTooltip($helper_text)
{
?>
	<div class="wi-tooptip-wrap wi-help-icon-wrap relative">
		<svg class="cursor-pointer wi-help-icon" width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M12 19.5C16.1421 19.5 19.5 16.1421 19.5 12C19.5 7.85786 16.1421 4.5 12 4.5C7.85786 4.5 4.5 7.85786 4.5 12C4.5 16.1421 7.85786 19.5 12 19.5ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM12.75 15V16.5H11.25V15H12.75ZM10.5 10.4318C10.5 9.66263 11.1497 9 12 9C12.8503 9 13.5 9.66263 13.5 10.4318C13.5 10.739 13.3151 11.1031 12.9076 11.5159C12.5126 11.9161 12.0104 12.2593 11.5928 12.5292L11.25 12.7509V14.25H12.75V13.5623C13.1312 13.303 13.5828 12.9671 13.9752 12.5696C14.4818 12.0564 15 11.3296 15 10.4318C15 8.79103 13.6349 7.5 12 7.5C10.3651 7.5 9 8.79103 9 10.4318H10.5Z" fill="#000000" />
		</svg>
		<div class="wi-help-content absolute">
		<p class="wi-tooltip-text"><?php echo wp_kses_post( $helper_text ); ?></p>
		</div>
	</div>
<?php
}


// DISPLAY A COLOR PICKER

function webinarignition_display_color($num, $data, $title, $id, $help, $placeholder, $show_help = true)
{

	// Output HTML

?>

	<div class="editSection">

		<div class="inputTitle <?php if(false == $show_help){
			echo "flex justify-left gap-3";
		} ?>">
			<div class="inputTitleCopy"><?php echo esc_html($title); ?></div>
			<?php if ($show_help) { ?>
				<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
			<?php } ?>

			

			<?php if(false == $show_help) {
				wiShowTooltip($help);
			} ?>

				
		</div>

		<div class="inputSection ">
			<input class="inputField elem  cp-picker color-field-picker" placeholder="<?php echo esc_html($placeholder); ?>"
				type="text" name="<?php echo esc_html($id); ?>" id="<?php echo esc_attr($id); ?>"
				value="<?php echo esc_attr(stripcslashes($data ?? '')); ?>">
		</div>
		<br clear="left">

	</div>

<?php
}

/**
 * Displays pickadate.js date picker
 *
 * @param str $format               The format that the passed in string is in.
 * @param str $webinar_date_format  The format that user has chosen in webinar settings
 */
function webinarignition_display_date_picker($num, $data, $format, $title, $id, $help, $placeholder, $webinar_date_format = null)
{

	$webinar_date_format        = ! empty($webinar_date_format) ? $webinar_date_format : get_option('date_format', 'l, F j, Y');

	// Ensure $data is not null before calling stripcslashes()
	$data = isset($data) ? $data : '';

	$webinarDateObject = DateTime::createFromFormat($format, htmlspecialchars(stripcslashes($data)));
	if (is_object($webinarDateObject)) {
		$webinarTimestamp = $webinarDateObject->getTimestamp();
		$date = date_i18n($webinar_date_format, $webinarTimestamp);
	}

?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy"><?php echo esc_html($title); ?></div>
			<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
		</div>

		<div class="inputSection ">
			<input class="inputField elem dp-date date-field-picker" placeholder="<?php echo esc_html($placeholder); ?>"
				type="text" name="<?php echo esc_html($id); ?>" id="<?php echo esc_attr($id); ?>"
				value="<?php echo ! empty($date) ? esc_attr($date) : ''; ?>">
		</div>
		<br clear="left">
	</div>
<?php
}

// DISPLAY A TIME PICKER - 24hr
function webinarignition_display_time_picker($num, $data, $title, $id, $help, $placeholder = '', $webinar_data = false)
{
?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy"><?php echo esc_html($title); ?></div>
			<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
		</div>

		<div class="inputSection ">

			<?php if (empty($data)) {
				$data = '18:00';
			} ?>

			<input class="timepicker inputField inputFieldDash elem" placeholder="<?php echo esc_html($placeholder); ?>"
				type="text" name="<?php echo esc_html($id); ?>" id="<?php echo esc_attr($id); ?>"
				value="<?php echo esc_attr(webinarignition_get_localized_time($data, $webinar_data)); ?>"
				data-time-format="<?php echo $webinar_data && $webinar_data->time_format === 'H:i' ? 'H' : 'false'; ?>">

		</div>
		<br clear="left">

	</div>

<?php
}
function webinarignition_display_edit_toggle($icon, $title, $ID, $exta)
{
?>
	<div class="editableSectionHeading" editSection="<?php echo esc_html($ID); ?>">
		<div class="wi-icon-title-wrap">
			<div class="editableSectionIcon">
				<i class="icon-<?php echo esc_attr($icon); ?> icon-2x"></i>
			</div>

			<div class="editableSectionTitle">
				<p class="wi-heading">
					<?php echo esc_html($title); ?>
				</p>
				<p class="editableSectionTitleSmall">
					<?php
					if (empty($exta)) {
						echo esc_html__('Not Setup yet...', 'webinar-ignition');
					} else {
						echo esc_html($exta);
					}
					?>
				</p>
			</div>
		</div>
		<div class="editableSectionToggle">
			<i class="toggleIcon icon-chevron-down icon-2x"></i>
		</div>
	</div>
	<div class="editableSectionSep"></div>
<?php
}


// Display Info Block
function webinarignition_display_info($title, $info)
{
?>
	<div class="editSection infoSection">
		<h4><i class="icon-question-sign"></i> <?php echo esc_html($title); ?></h4>

		<p><?php echo wp_kses_post($info); ?></p>
	</div>
<?php
}

// Display Info Block
function webinarignition_display_two_col_info($title, $info = '', $content = '')
{
?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy">
				<?php echo esc_html($title); ?>
			</div>

			<?php
			if (! empty($info)) {
			?>
				<div class="inputTitleHelp">
					<?php echo esc_html($info); ?>
				</div>
			<?php
			}
			?>
		</div>

		<div class="inputSection ">
			<?php echo wp_kses_post(wpautop($content)); ?>
		</div>

		<br clear="left">
	</div>
<?php
}


// Display TIMEZONES
function webinarignition_display_timezone_identifiers($num, $data, $title, $id, $help, $placeholder)
{
?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy"><?php echo esc_html($title); ?></div>
			<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
		</div>
		<?php
		// Use the passed-in value or the default WordPress timezone if none is specified
		$selected_timezone = !empty($data) ? $data : get_option('timezone_string');
		?>
		<div class="inputSection">
			<select name="<?php echo esc_attr($id); ?>"
				id="<?php echo esc_attr($id); ?>"
				class="inputField elem timezoneSelect"
				style="width: 100%; border: 1px solid #ccc; border-radius: 4px;">
				<?php echo wp_timezone_choice($selected_timezone); ?>
			</select>
		</div>

		<br clear="left">
	</div>
<?php
}

function webinarignition_get_select_start_time_options($id, $starttimeTZ, $template, $webinar_data = false)
{
	// Check if the saved value needs conversion
	if ($webinar_data && isset($webinar_data->time_format) && $webinar_data->time_format === 'H:i') {
		// Convert $starttimeTZ to 24-hour format if it is in 12-hour format
		if (strpos($starttimeTZ, 'AM') !== false || strpos($starttimeTZ, 'PM') !== false) {
			$date = DateTime::createFromFormat('h:i A', $starttimeTZ); // 12-hour format
			$starttimeTZ = $date->format('H:i'); // Convert to 24-hour format
		}

		// Define the 24-hour format options
		$time_options = array(
			'00:00' => '00:00',
			'00:30' => '00:30',
			'01:00' => '01:00',
			'01:30' => '01:30',
			'02:00' => '02:00',
			'02:30' => '02:30',
			'03:00' => '03:00',
			'03:30' => '03:30',
			'04:00' => '04:00',
			'04:30' => '04:30',
			'05:00' => '05:00',
			'05:30' => '05:30',
			'06:00' => '06:00',
			'06:30' => '06:30',
			'07:00' => '07:00',
			'07:30' => '07:30',
			'08:00' => '08:00',
			'08:30' => '08:30',
			'09:00' => '09:00',
			'09:30' => '09:30',
			'10:00' => '10:00',
			'10:30' => '10:30',
			'11:00' => '11:00',
			'11:30' => '11:30',
			'12:00' => '12:00',
			'12:30' => '12:30',
			'13:00' => '13:00',
			'13:30' => '13:30',
			'14:00' => '14:00',
			'14:30' => '14:30',
			'15:00' => '15:00',
			'15:30' => '15:30',
			'16:00' => '16:00',
			'16:30' => '16:30',
			'17:00' => '17:00',
			'17:30' => '17:30',
			'18:00' => '18:00',
			'18:30' => '18:30',
			'19:00' => '19:00',
			'19:30' => '19:30',
			'20:00' => '20:00',
			'20:30' => '20:30',
			'21:00' => '21:00',
			'21:30' => '21:30',
			'22:00' => '22:00',
			'22:30' => '22:30',
			'23:00' => '23:00',
			'23:30' => '23:30',
		);
	} else {
		// Convert $starttimeTZ to 12-hour format if it is in 24-hour format
		if (strpos($starttimeTZ, ':') !== false && strpos($starttimeTZ, 'AM') === false && strpos($starttimeTZ, 'PM') === false) {
			$date = DateTime::createFromFormat('H:i', $starttimeTZ); // 24-hour format
			$starttimeTZ = $date->format('h:i A'); // Convert to 12-hour format
		}

		// Define the 12-hour format options
		$time_options = array(
			'12:00 AM' => '12:00 AM',
			'12:30 AM' => '12:30 AM',
			'01:00 AM' => '01:00 AM',
			'01:30 AM' => '01:30 AM',
			'02:00 AM' => '02:00 AM',
			'02:30 AM' => '02:30 AM',
			'03:00 AM' => '03:00 AM',
			'03:30 AM' => '03:30 AM',
			'04:00 AM' => '04:00 AM',
			'04:30 AM' => '04:30 AM',
			'05:00 AM' => '05:00 AM',
			'05:30 AM' => '05:30 AM',
			'06:00 AM' => '06:00 AM',
			'06:30 AM' => '06:30 AM',
			'07:00 AM' => '07:00 AM',
			'07:30 AM' => '07:30 AM',
			'08:00 AM' => '08:00 AM',
			'08:30 AM' => '08:30 AM',
			'09:00 AM' => '09:00 AM',
			'09:30 AM' => '09:30 AM',
			'10:00 AM' => '10:00 AM',
			'10:30 AM' => '10:30 AM',
			'11:00 AM' => '11:00 AM',
			'11:30 AM' => '11:30 AM',
			'12:00 PM' => '12:00 PM',
			'12:30 PM' => '12:30 PM',
			'01:00 PM' => '01:00 PM',
			'01:30 PM' => '01:30 PM',
			'02:00 PM' => '02:00 PM',
			'02:30 PM' => '02:30 PM',
			'03:00 PM' => '03:00 PM',
			'03:30 PM' => '03:30 PM',
			'04:00 PM' => '04:00 PM',
			'04:30 PM' => '04:30 PM',
			'05:00 PM' => '05:00 PM',
			'05:30 PM' => '05:30 PM',
			'06:00 PM' => '06:00 PM',
			'06:30 PM' => '06:30 PM',
			'07:00 PM' => '07:00 PM',
			'07:30 PM' => '07:30 PM',
			'08:00 PM' => '08:00 PM',
			'08:30 PM' => '08:30 PM',
			'09:00 PM' => '09:00 PM',
			'09:30 PM' => '09:30 PM',
			'10:00 PM' => '10:00 PM',
			'10:30 PM' => '10:30 PM',
			'11:00 PM' => '11:00 PM',
			'11:30 PM' => '11:30 PM',

		);
	}
	ob_start();
	$id_array = explode('__', $id);

	if (1 < count($id_array) && 'multiple' === $id_array[0]) {
		$name = $id_array[0] . '__' . $id_array[1] . '[]';
	} else {
		$name = $id;
	}
?>
	<select name="<?php echo esc_html($name); ?>" id="<?php echo esc_attr($id); ?>" class="inputField elem select_auto_time" <?php echo  $template  ? ' disabled' : ''; ?>>
		<?php
		foreach ($time_options as $val => $label) {
		?>
			<option value="<?php echo esc_html($val); ?>" <?php if ($starttimeTZ == $val) {
																echo 'selected';
															} ?>><?php echo esc_html($label); ?>
			</option>
		<?php
		}
		?>
	</select>
<?php
	$html = ob_get_clean();

	return $html;
}

function webinarignition_get_select_weekdays_options($id, $weekdays, $template)
{
	$weekdays_available = array(
		'mon' => __('Monday', 'webinar-ignition'),
		'tue' => __('Tuesday', 'webinar-ignition'),
		'wed' => __('Wednesday', 'webinar-ignition'),
		'thu' => __('Thursday', 'webinar-ignition'),
		'fri' => __('Friday', 'webinar-ignition'),
		'sat' => __('Saturday', 'webinar-ignition'),
		'sun' => __('Sunday', 'webinar-ignition'),
	);

	if (false === $weekdays) {
		$weekdays = array();
	}
	// else{
	// 	$weekdays = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
	// }
	ob_start();
	$id_array = explode('__', $id);

	if (1 < count($id_array) && 'multiple' === $id_array[0] && isset($id_array[2])) {
		$index = $id_array[2] - 1;
		$name = $id_array[0] . '__' . $id_array[1] . '[' . $index . '][]';
	} else {
		$name = $id . '[]';
	}
?>
	<div class="auto_weekdays" style="margin-top: 15px;display:block;">
		<select
			multiple name="<?php echo esc_html($name); ?>"
			id="<?php echo esc_attr($id); ?>"
			class="inputField elem select_auto_weekday" <?php echo $template ? ' disabled' : ''; ?>
			style="height: 155px !important;padding: 10px;">
			<?php
			foreach ($weekdays_available as $val => $label) {
			?>
				<option value="<?php echo esc_html($val); ?>" <?php if (in_array($val, $weekdays)) {
																	echo 'selected';
																} ?>><?php echo esc_html($label); ?>
				</option>
			<?php
			}
			?>
		</select>
	</div>
<?php
	$html = ob_get_clean();

	return $html;
}

// DISPLAY A TIME PICKER - 24hr
function webinarignition_display_time_auto($num, $data, $weekdays, $title, $id, $weekdays_id, $help, $template = false, $webinar_data = false)
{
	// Output HTML
	if (empty($data)) {
		$data = '16:00';
	}
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data($num);

	$is_multiple_auto_time_enabled = WebinarignitionPowerups::webinarignition_is_multiple_auto_time_enabled($webinar_data);
?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy"><?php echo wp_kses_post($title); ?></div>
			<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
		</div>

		<div class="inputSection ">
			<?php
			// Start Time Options
			$starttimeTZ = $data;
			$start_time_options = webinarignition_get_select_start_time_options($id, $starttimeTZ, $template, $webinar_data);
			echo wp_kses($start_time_options, array(
				'select' => array(
					'name' => array(),
					'id' => array(),
					'class' => array(),
				),
				'option' => array(
					'value' => array(),
					'selected' => array(),
				),
			));
			?>
			<div<?php echo $is_multiple_auto_time_enabled ? '' : ' style="display:none;"'; ?>>
				<?php
				// Weekday Options
				$weekday_options = webinarignition_get_select_weekdays_options($weekdays_id, $weekdays, $template);
				echo wp_kses($weekday_options, array(
					'select' => array(
						'multiple' => array(),
						'name' => array(),
						'id' => array(),
						'class' => array(),
						'style' => array(),
					),
					'option' => array(
						'value' => array(),
						'selected' => array(),
					),
				));
				?>
		</div>
	</div>
	<br clear="left">
	</div>
<?php
}

function webinarignition_display_global_shortcodes($webinar_data, $num, $title, $help)
{
	$available_shortcodes = WebinarignitionPowerupsShortcodes::webinarignition_get_available_shortcodes();
	$global_shortcodes = array();

	foreach ($available_shortcodes as $shortcode => $settings) {
		if (! empty($settings['page']) && $settings['page'] == 'global') {
			$global_shortcodes[$shortcode] = $settings;
		}
	}
?>
	<div class="editSection flex flex-col">
		<div class="wi-title-wrap-global-shortcodes">
			<div class="inputTitle w-full">
				<div class="inputTitleCopy text-md"><?php echo esc_html($title); ?></div>
				<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
			</div>
		</div>

		<div class="inputSection shortcodesList w-full">
			<?php
			foreach ($global_shortcodes as $shortcode => $data) {
				WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description($shortcode, $webinar_data);
			}
			?>
		</div>
		<br clear="left">
	</div>
<?php
}


// Display TIMEZONES
function webinarignition_display_template_dropdown_options($webinar_data, $num, $data, $title, $id, $help, $options, $params, $shortcodes, $placeholder)
{
	$is_webinar_public = WebinarignitionManager::webinarignition_is_webinar_public($webinar_data);
	$public_params = str_replace('{{webinar_id}}', $num, $params);
	$protected_params = str_replace('{{webinar_id}}', isset($webinar_data->hash_id) ? $webinar_data->hash_id : '', $params);


	$name     = $id;
	$multiple = '';
	$class    = '';
	$data = (array) $data;
	$data = array_unique(array_filter($data));
	if ('custom_registration_page' === $id) {
		$icon = 'icon-calendar';
		$view_label = __('Preview Registration Page', 'webinar-ignition');
		$name      .= '[]';
		$multiple   = 'multiple';
		$class      = 'multiSelectField';
	} elseif ('custom_thankyou_page' === $id) {
		$icon = 'icon-copy';
		$view_label = __('Preview Thank You Page', 'webinar-ignition');
	} elseif ('custom_webinar_page' === $id) {
		$icon = 'icon-microphone';
		$view_label = __('Preview Webinar Page', 'webinar-ignition');
	} elseif ('custom_countdown_page' === $id) {
		$icon = 'icon-time';
		$view_label = __('Preview Countdown Page', 'webinar-ignition');
	} elseif ('custom_replay_page' === $id) {
		$icon = 'icon-film';
		$view_label = __('Preview Replay Page', 'webinar-ignition');
	} elseif ('custom_closed_page' === $id) {
		$icon = 'icon-remove';
		$view_label = __('Preview Closed Page', 'webinar-ignition');
	} //end if
	$sec_identifier = "wi_design_".$id;
	$section_titles = [
		"custom_registration_page" => [
			__('Registration Page Shortcodes', 'webinar-ignition'),
			__('Select your desired page for webinar registration shortcodes.', 'webinar-ignition'),
			" icon-user"
		],
		"custom_thankyou_page" => [
			__('Thank You Page Shortcodes', 'webinar-ignition'),
			__('Select your desired thank you page shortcodes shortcodes.', 'webinar-ignition'),
			" icon-copy"
		],
		"custom_webinar_page" => [
			__('Webinar Page Shortcodes', 'webinar-ignition'),
			__('Select your desired webinar page shortcodes.', 'webinar-ignition'),
			" icon-desktop"
		],
		"custom_countdown_page" => [
			__('Webinar Countdown page Shortcodes and templates', 'webinar-ignition'),
			__('Select your desired webinar countdown page shortcodes.', 'webinar-ignition'),
			" icon-time"
		],
		"custom_replay_page" => [
			__('Webinar Replay Page Templates and Shortcodes', 'webinar-ignition'),
			__('Select your desired shortcodes for webinar replay page.', 'webinar-ignition'),
			" icon-reply"
		],
	];


	webinarignition_display_edit_toggle(
		$section_titles[$id][2],
		$section_titles[$id][0],
		$sec_identifier,
		$section_titles[$id][1]
	);
?>
<div id="<?php echo esc_html($sec_identifier); ?>" class="we_edit_area p-12 wi-design">
	<div class="bg-white py-25 px-20">
	<div class="editSection wi-edit-sec p-0">

		<div class="wi-sec">
			<div class="inputTitle">
				<div class="inputTitleCopy text-md"><?php echo esc_html($title); ?></div>
				<div class="inputTitleHelp"><?php echo esc_html($help); ?></div>
			</div>
			<?php
			$default_webinar_page_id = WebinarignitionManager::webinarignition_get_webinar_post_id($webinar_data->id);
			$default_paid_thank_you_url = '';
			if ($default_webinar_page_id) {
				$default_paid_thank_you_url = get_the_permalink($default_webinar_page_id);
			}
			?>

			<?php
			$paid_code = isset($webinar_data->paid_code) ? $webinar_data->paid_code : '';
			?>
			
			
			<!-- this span was displaying nothing i think its only for javascript to hold thank you page url -->
			<span id="default_paid_thank_you_url" data-url="<?php echo esc_url(add_query_arg(esc_attr($paid_code), '', esc_url($default_paid_thank_you_url))); ?>" style="display:none"></span>


			<div class="inputSection">
				<?php if ('custom_registration_page' === $id) : ?>
					<?php
					$selected_page_links = array();

					$default_registration_page = empty($webinar_data->default_registration_page) ? $default_webinar_page_id : intval($webinar_data->default_registration_page);

					$selected = '';
					$i_class  = '';

					if (! empty($default_webinar_page_id) && (empty($webinar_data->default_registration_page) || $webinar_data->default_registration_page == $default_webinar_page_id) && ! in_array($default_webinar_page_id, $data)) {
						if ($default_registration_page == $default_webinar_page_id) {
							$selected = 'checked';
							$i_class  = 'icon-circle';
						}

						
						$selected_page_links[] = sprintf(
							'<div class="wi_webinar_preview_box wi_webinar_preview_box_%d %s"><input data-page_url="%s" name="default_registration_page" class="default_registration_page" value="%d" type="radio" %s><i class="icon %s"></i>%s<a href="%s" target="blank" class="wi_page_link"><i class="icon-external-link"></i> %s</a></div>',
							$default_webinar_page_id,
							$selected,
							get_permalink($default_webinar_page_id),
							$default_webinar_page_id,
							$selected,
							$i_class,
							get_the_title($default_webinar_page_id),
							get_permalink($default_webinar_page_id),
							esc_html__('Preview', 'webinar-ignition')
						);
					}

					if ($default_registration_page !== $default_webinar_page_id && ! in_array($default_registration_page, $data)) {
						$default_registration_page = reset($data);
					}

					foreach ($data as $page_id) {
						if ($default_registration_page == $page_id) {
							$selected = 'checked';
							$i_class  = 'icon-circle';
						} else {
							$selected = '';
							$i_class  = 'icon-circle-blank';
						}

						if (empty($page_id)) {
							continue;
						}

						$selected_page_links[] = sprintf(
							'<div class="wi_register_page_preview_box mt-5 wi_webinar_preview_box wi_webinar_preview_box_%d %s">
								<input data-page_url="%s" name="default_registration_page" class="default_registration_page" value="%d" type="radio" %s>
								%s
								<a href="%s" target="blank" class="wi_page_link">
								    <i class="icon-external-link"></i>
									 %s
								</a>
							</div>',
							$page_id,
							$selected,
							get_permalink($page_id),
							$page_id,
							$selected,
							get_the_title($page_id),
							get_permalink($page_id),
							esc_html__('Preview', 'webinar-ignition')
						);
					}

					if (! in_array($default_registration_page, $data)) {
						$data[] = $default_registration_page;
					}

					if (! empty($selected_page_links)) : ?>
						<?php
						if (! empty($selected_page_links) && is_array($selected_page_links)) {
							foreach ($selected_page_links as $link) {
								echo wp_kses($link, array(
									'a' => array(
										'href' => array(),
										'title' => array(),
										'class' => array(),
										'target' => array(),
									),
									'div' => array(
										'class' => array(),
									),
									'span' => array(
										'class' => array(),
									),
									'i' => array(
										'class' => array(),
									),
								));
							}
						}
						?>
						
					<?php
				
				endif; ?>
					
				<?php endif; ?>

				<select
					name="<?php echo esc_html($name); ?>"
					id="<?php echo esc_attr($id); ?>"
					class="inputField inputFieldTemplateSelect elem <?php echo esc_html($class); ?>"
					style="width: 100%; max-width: 100%; margin-bottom: 15px;"
					data-webinar-access="<?php echo $is_webinar_public ? 'public' : 'protected'; ?>"
					<?php echo esc_html($multiple); ?>>
					<?php
					$selected_url = '';
					$selected_url_params = '';

					if (! empty($placeholder)) {
					?>
						<option value="" data-url="<?php echo esc_html__('select page to see preview URL', 'webinar-ignition'); ?>">
							<?php echo esc_html($placeholder); ?>
						</option>
						<?php
					}


					krsort($options);

					foreach ($options as $val => $item) {
						if (! empty(trim($val)) && ! empty(trim($item['label']))) {
							$is_selected = in_array($val, $data);

							if (false !== strpos($item['url'], '?')) {
								$url_params = '&' . $public_params;
							} else {
								$url_params = '?' . $public_params;
							}

							if (false !== strpos($item['url'], '?')) {
								$protected_url_params = '&' . $protected_params;
							} else {
								$protected_url_params = '?' . $protected_params;
							}

							$paid_code = isset($webinar_data->paid_code) ? $webinar_data->paid_code : '';

							$paid_thank_you_url = add_query_arg($paid_code, '', get_the_permalink($val));
							$selected_protected_url_params = '';
							if ($is_selected) {
								$selected_url = $item['url'];
								$selected_url_params = $url_params;
								$selected_protected_url_params = $protected_url_params;
							}

							$public_url = $item['url'] . $url_params;
							$protected_url = $item['url'] . $protected_url_params;

							// Add preview parameter
							$public_url = add_query_arg('preview', 'true', $public_url);
							$protected_url = add_query_arg('preview', 'true', $protected_url);
						?>
							<option
								data-url="<?php echo esc_url($item['url'] . esc_url($url_params)); ?>"
								data-public-url="<?php echo esc_url($public_url); ?>"
								data-protected-url="<?php echo esc_url($protected_url); ?>"
								data-paid-thank-you-url="<?php echo esc_url($paid_thank_you_url); ?>"
								value="<?php echo esc_html($val); ?>"
								<?php if ($is_selected) echo 'selected'; ?>>
								<?php echo esc_html($val); ?> - <?php echo esc_html($item['label']); ?>
							</option>
					<?php
						}
					}
					?>
				</select>
			</div>


		</div>

		<?php
		if (true) {
			if ($is_webinar_public) {
				$selected_url .= $selected_url_params;
			} else {
				$selected_url .= $selected_protected_url_params;
			}

			if ($selected_url) {
				$selected_url = add_query_arg('preview', 'true', $selected_url);
			} else if ($id === 'custom_thankyou_page') {
				$selected_url = add_query_arg(array(
					'thankyou' => '',
					'lid' => '[lead_id]',
					'preview' => 'true',
				), $default_paid_thank_you_url);
			} else if ($id === 'custom_webinar_page') {
				$selected_url = add_query_arg(array(
					'webinar' => '',
					'lid' => '[lead_id]',
					'preview' => 'true',
				), get_permalink($default_webinar_page_id));
			} else if ($id === 'custom_replay_page') {
				$selected_url = add_query_arg(array(
					'replay' => '',
					'lid' => '[lead_id]',
					'preview' => 'true',
				), get_permalink($default_webinar_page_id));
			} else if ($id === 'custom_countdown_page') {
				if ($webinar_data->webinar_date !== 'AUTO') {
					$selected_url = add_query_arg(array(
						'countdown' => '',
						'lid' => '[lead_id]',
						'preview' => 'true',
					), get_permalink($default_webinar_page_id));
				}
			}
		} else {
			$selected_url = '';
		}
		?>

		<input class="webinarPreviewLinkInput" type="hidden" value="<?php echo esc_html($selected_url); ?>" data-page="<?php echo esc_html($id); ?>">

		<div class="webinarPreviewItem">
			<!-- <div class="webinarPreviewIcon"><i class="<?php // echo esc_html($icon); ?> icon-2x"></i></div> -->
			<div class="webinarPreviewTitle">
				<?php
				if (! empty($selected_url)) {
				?>
					<a
						class="webinarPreviewLinkHolder <?php echo esc_html($id); ?>-webinarPreviewLinkHolder"
						href="<?php echo esc_html($selected_url); ?>"
						target="_blank">
						<!-- <i class="icon-external-link"></i> -->
						<?php echo esc_html($view_label); ?>
					</a>
					<a class="webinarPreviewLinkEmptyHolder <?php echo esc_html($id); ?>-webinarPreviewLinkEmptyHolder" style="display: none;">
						<?php echo esc_html__('select page to see preview URL', 'webinar-ignition'); ?>
					</a>
				<?php
				} else {
				?>
					<a
						class="webinarPreviewLinkHolder <?php echo esc_html($id); ?>-webinarPreviewLinkHolder"
						href="<?php echo esc_html($selected_url); ?>"
						target="_blank" style="display: none;">
						<i class="icon-external-link"></i>
						<?php echo esc_html($view_label); ?>
					</a>
					<a class="webinarPreviewLinkEmptyHolder <?php echo esc_html($id); ?>-webinarPreviewLinkEmptyHolder">
						<?php echo esc_html__('select page to see preview URL', 'webinar-ignition'); ?>
					</a>
				<?php
				} //end if
				?>
			</div>
			<br clear="both">
		</div>

		<?php
		if (! empty($shortcodes)) {
		?>



			<div class="wi-sec">
				<?php if($id != "custom_registration_page" && $id != "custom_webinar_page"){  ?>
				<div class="inputTitle">
					<div class="inputTitleCopy text-md mb-15"><?php echo esc_html__('Available shortcodes', 'webinar-ignition'); ?></div>
				</div>
				<?php } ?>

				<div class="inputSection shortcodesList <?php echo esc_html($sec_identifier); ?>">
					<?php
					foreach ($shortcodes as $shortcode => $shortcode_data) {
						
						WebinarignitionPowerupsShortcodes::webinarignition_show_shortcode_description($shortcode, $webinar_data);
					}
					?>
				</div>
			</div>

		<?php
		}
		?>
		</div>
	</div>
</div>

<?php
}

function webinarignition_display_webinar_tabs_section($webinar_data)
{

	$webinarId = $webinar_data->id;
	$default_webinar_tabs_settings = array();
	$webinar_tabs_settings = isset($webinar_data->webinar_tabs) ? $webinar_data->webinar_tabs : $default_webinar_tabs_settings;

?>
	<div id="webinar_tabs_container" class="webinar_tabs_container">
		<?php
		if (! empty($webinar_tabs_settings)) {
			foreach ($webinar_tabs_settings as $i => $webinar_tabs_setting) {
				$webinar_tabs_setting = (array) $webinar_tabs_setting;
				$tab_name = ! empty($webinar_tabs_setting['name']) ? $webinar_tabs_setting['name'] : '';
				$tab_content = ! empty($webinar_tabs_setting['content']) ? $webinar_tabs_setting['content'] : '';
				$tab_type = ! empty($webinar_tabs_setting['type']) ? $webinar_tabs_setting['type'] : 'editor_tab';
		?>
				<div class="additional_auto_action_item auto_action_item webinar_tab_item">
					<div class="auto_action_header">
						<h4>
							<?php esc_html_e('Webinar Tab', 'webinar-ignition'); ?>
							<span class="index_holder"><?php echo esc_html($i + 1); ?></span>
							<span class="auto_action_desc_holder"> </span>
							<i class="icon-arrow-up"></i>
							<i class="icon-arrow-down"></i>
						</h4>
					</div>

					<div class="auto_action_body">
						<div class="editSection">
							<div class="inputTitle">
								<div class="inputTitleCopy"><?php esc_html_e('Tab title', 'webinar-ignition'); ?></div>
								<div class="inputTitleHelp">
									<?php esc_html_e('Try to use short title to keep tabs template compact', 'webinar-ignition'); ?>
								</div>
							</div>

							<div class="inputSection">
								<input
									class="inputField elem webinar_tabs_name"
									placeholder="<?php esc_html_e('Input Tag Name', 'webinar-ignition'); ?>"
									type="text"
									id="webinar_tabs_name_<?php echo esc_html($i); ?>"
									name="webinar_tabs[<?php echo esc_html($i); ?>][name]"
									value="<?php echo esc_html($tab_name); ?>"
									inputmode="text">

								<input
									class="webinar_tabs_type"
									type="hidden"
									id="webinar_tabs_type_<?php echo esc_html($i); ?>"
									name="webinar_tabs[<?php echo esc_html($i); ?>][type]"
									value="<?php echo esc_html($tab_type); ?>">
							</div>
							<br clear="left">
						</div>

						<div class="editSection">
							<div class="inputTitle">
								<div class="inputTitleCopy"><?php esc_html_e('Tab content', 'webinar-ignition'); ?></div>
								<div class="inputTitleHelp">
									<?php esc_html_e('Put any html code or shortcode inside. If you are using shortcodes, please test it before publishing webinar', 'webinar-ignition'); ?>
								</div>
							</div>

							<div class="inputSection">
								<?php
								$txt_id = 'webinar_tabs_content_' . $i;
								$txt_name = 'webinar_tabs[' . $i . '][content]';
								$txt_content = 'webinar_tabs[' . $i . '][content]';

								$settings = array(
									'wpautop' => true, // use wpautop - add p tags when they press enter
									'teeny' => false, // output the minimal editor config used in Press This
									'textarea_name' => $txt_name,
									'tinymce' => array(
										'height' => '250', // the height of the editor
									),
								);

								wp_editor(stripcslashes($tab_content), $txt_id, $settings);
								?>
							</div>
							<br clear="left">
						</div>
					</div>

					<div class="auto_action_footer" style="padding: 15px;">
						<button type="button" class="blue-btn btn deleteWebinarTab" style="color:#FFF;float:none;">
							<i class="icon-remove"></i> <?php esc_html_e('delete', 'webinar-ignition'); ?>
						</button>
					</div>
				</div>
		<?php
			} //end foreach
		} //end if
		?>
	</div>
	<?php

	if (WebinarignitionPowerups::webinarignition_is_modern_template_enabled($webinar_data)) {
	?>
		<div class="additional_auto_action_control editSection">
			<button type="button" id="createWebinarTab" class="blue-btn-44 btn" style="color:#FFF;float:none;">
				<i class="icon-plus"></i> <?php esc_html_e('New Tab', 'webinar-ignition'); ?>
			</button>

			<button
				type="button"
				id="createWebinarQATab"
				class="blue-btn-44 btn shortcode_tab"
				data-title="<?php esc_html_e('Q&A', 'webinar-ignition'); ?>"
				data-content='[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="webinar_qa_compact"]'
				data-type="qa_tab"
				style="color:#FFF;float:none;">
				<i class="icon-plus"></i> <?php esc_html_e('Q&A Tab', 'webinar-ignition'); ?>
			</button>

			<button
				type="button"
				id="createWebinarGiveawayTab"
				class="blue-btn-44 btn shortcode_tab"
				data-title="<?php esc_html_e('Your Gift', 'webinar-ignition'); ?>"
				data-content='[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="webinar_giveaway_compact"]'
				data-type="giveaway_tab"
				style="color:#FFF;float:none;">
				<i class="icon-plus"></i> <?php esc_html_e('Giveaway Tab', 'webinar-ignition'); ?>
			</button>
		</div>

		<div
			id="webinar_tabs_template_container"
			data-title="<?php echo esc_html__('Webinar Tab Settings', 'webinar-ignition'); ?>"
			style="display: none">
			<div class="additional_auto_action_item auto_action_item webinar_tab_item">
				<div class="auto_action_header">
					<h4>
						<?php esc_html_e('Webinar Tab', 'webinar-ignition'); ?>
						<span class="index_holder"></span>
						<span class="auto_action_desc_holder"> </span>
						<i class="icon-arrow-up"></i>
						<i class="icon-arrow-down"></i>
					</h4>
				</div>

				<div class="auto_action_body">
					<div class="editSection">
						<div class="inputTitle">
							<div class="inputTitleCopy"><?php esc_html_e('Tab title', 'webinar-ignition'); ?></div>
							<div class="inputTitleHelp">
								<?php esc_html_e('Try to use short title to keep tabs template compact', 'webinar-ignition'); ?>
							</div>
						</div>

						<div class="inputSection">
							<input class="inputField elem webinar_tabs_name" placeholder="<?php echo esc_attr('Input Tag Name', 'webinar-ignition'); ?>" type="text" name="" value="" inputmode="text">
							<input class="webinar_tabs_type" type="hidden" name="" value="editor">
						</div>
						<br clear="left">
					</div>

					<div class="editSection">
						<div class="inputTitle">
							<div class="inputTitleCopy"><?php esc_html_e('Tab content', 'webinar-ignition'); ?></div>
							<div class="inputTitleHelp">
								<?php esc_html_e('Put any html code or shortcode inside. If you are using shortcodes, please test it before publishing webinar', 'webinar-ignition'); ?>
							</div>
						</div>

						<div class="inputSection">
							<textarea name="" placeholder="<?php esc_html_e('Tab content', 'webinar-ignition'); ?>" class="inputTextarea elem webinar_tabs_content"></textarea>
						</div>
						<br clear="left">
					</div>
				</div>

				<div class="auto_action_footer" style="padding: 15px;">
					<button type="button" class="blue-btn btn deleteWebinarTab" style="color:#FFF;float:none;">
						<i class="icon-remove"></i> <?php esc_html_e('delete', 'webinar-ignition'); ?>
					</button>
				</div>
			</div>
		</div>
	<?php
	} //end if
}

function webinarignition_display_time_tags_section($webinar_data)
{
	if (WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled($webinar_data)) {
	?>
		<div class="additional_auto_action_control editSection">
			<h3 style="margin: 0;"><?php esc_html_e('Tracking Settings', 'webinar-ignition'); ?></h3>
		</div>

		<div
			class="tracking_tags_template_container"
			data-title="<?php echo esc_html__('Additional CTA Settings', 'webinar-ignition'); ?>"
			style="display: none">
			<div class="additional_auto_action_item auto_action_item tracking_tag_item">
				<div class="auto_action_header">
					<h4>
						<?php esc_html_e('Tracking Tag', 'webinar-ignition'); ?>
						<span class="index_holder"></span>
						<span class="auto_action_desc_holder"> </span>
						<i class="icon-arrow-up"></i>
						<i class="icon-arrow-down"></i>
					</h4>
				</div>

				<div class="auto_action_body">
					<div class="editSection">
						<div class="inputTitle">
							<div class="inputTitleCopy"><?php esc_html_e('Tracking Tag Time :: Minutes:Seconds', 'webinar-ignition'); ?></div>
							<div class="inputTitleHelp">
								<?php esc_html_e("This is when you want your webinar time tracked. Ie. when your video gets to (or passed) 1 min 59 sec, it will be tracked. NB: Minute mark should be clear like '1' second - '59'", 'webinar-ignition'); ?>
							</div>
						</div>

						<div class="inputSection">
							<input class="inputField elem min_sec_mask_field tracking_tags_time" placeholder="<?php echo esc_attr('f.e. 1:59', 'webinar-ignition'); ?>" type="text" name="" value="" inputmode="text">
						</div>
						<br clear="left">

					</div>

					<div class="editSection">
						<div class="inputTitle">
							<div class="inputTitleCopy"><?php esc_html_e('Tracking Tag Name', 'webinar-ignition'); ?></div>
							<div class="inputTitleHelp">
								<?php esc_html_e('Put tag name which will be saved for lead tracking tags field', 'webinar-ignition'); ?>
							</div>
						</div>

						<div class="inputSection">
							<input class="inputField elem tracking_tags_name" placeholder="<?php echo esc_attr('Input Tag Name', 'webinar-ignition'); ?>" type="text" name="" value="" inputmode="text">
						</div>
						<br clear="left">

					</div>

					<div class="editSection">
						<div class="inputTitle">
							<div class="inputTitleCopy"><?php esc_html_e('Tracking Tag Field Name', 'webinar-ignition'); ?></div>
							<div class="inputTitleHelp">
								<?php esc_html_e('If you want your tracking tags save into separate field, provide tracking field name', 'webinar-ignition'); ?>
							</div>
						</div>

						<div class="inputSection">
							<input class="inputField elem tracking_tags_slug" placeholder="<?php echo esc_attr('Input Tag Field Name', 'webinar-ignition'); ?>" type="text" name="" value="" inputmode="text">
						</div>
						<br clear="left">

					</div>

					<div class="editSection">

						<div class="inputTitle">
							<div class="inputTitleCopy"><?php esc_html_e('Tracking Pixel Code', 'webinar-ignition'); ?></div>
							<div class="inputTitleHelp">
								<?php echo esc_html(__('Put your tracking pixel code here. It will be added to <head> tag', 'webinar-ignition')); ?>
							</div>
						</div>

						<div class="inputSection">
							<textarea name="" class="inputTextarea elem tracking_tags_pixel"></textarea>
						</div>
						<br clear="left">

					</div>
				</div>

				<div class="auto_action_footer" style="padding: 15px;">
					<button type="button" class="blue-btn-44 btn cloneTrackingTag" style="color:#FFF;float:none;">
						<i class="icon-copy"></i> <?php esc_html_e('delete', 'webinar-ignition'); ?>
					</button>

					<button type="button" class="blue-btn btn deleteTrackingTag" style="color:#FFF;float:none;">
						<i class="icon-remove"></i> <?php esc_html_e('delete', 'webinar-ignition'); ?>
					</button>
				</div>
			</div>
		</div>
	<?php
	} //end if

	if (! WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled($webinar_data)) {
	?><div style="display: none;"><?php
								}

								$default_tracking_tags_settings = array();
								$tracking_tags_settings = isset($webinar_data->tracking_tags) ? $webinar_data->tracking_tags : $default_tracking_tags_settings;
									?>
		<div id="tracking_tags_container" class="tracking_tags_container">
			<?php
			if (! empty($tracking_tags_settings) && is_array($tracking_tags_settings)) {
				foreach ($tracking_tags_settings as $tti => $tracking_tag) {
			?>
					<div class="additional_auto_action_item auto_action_item tracking_tag_item">
						<div class="auto_action_header">
							<h4>
								<?php esc_html_e('Tracking Tag', 'webinar-ignition'); ?>
								<span class="index_holder"><?php echo esc_html($tti + 1); ?></span>
								<span class="auto_action_desc_holder">
									(<?php echo esc_html($tracking_tag['time']); ?> - <?php echo esc_html($tracking_tag['name']); ?>)
								</span>
								<i class="icon-arrow-up"></i>
								<i class="icon-arrow-down"></i>
							</h4>
						</div>

						<div class="auto_action_body">
							<div class="editSection">
								<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e('Tracking Tag Time :: Minutes:Seconds', 'webinar-ignition'); ?></div>
									<div class="inputTitleHelp">
										<?php esc_html_e("This is when you want your webinar time tracked. Ie. when your video gets to (or passed) 1 min 59 sec, it will be tracked. NB: Minute mark should be clear like '1' second - '59'", 'webinar-ignition'); ?>
									</div>
								</div>

								<div class="inputSection">
									<input
										class="inputField elem min_sec_mask_field tracking_tags_time"
										placeholder="<?php esc_html_e('f.e. 1:59', 'webinar-ignition'); ?>"
										type="text"
										name="tracking_tags[<?php echo esc_attr($tti); ?>][time]"
										id="tracking_tags_time_<?php echo esc_attr($tti); ?>"
										value="<?php echo esc_attr($tracking_tag['time']); ?>"
										inputmode="text">
								</div>
								<br clear="left">

							</div>

							<div class="editSection">
								<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e('Tracking Tag Name', 'webinar-ignition'); ?></div>
									<div class="inputTitleHelp">
										<?php esc_html_e('Put tag name which will be saved for lead tracking tags field', 'webinar-ignition'); ?>
									</div>
								</div>

								<div class="inputSection">
									<input
										class="inputField elem tracking_tags_name"
										placeholder="<?php esc_html_e('f.e. 1:59', 'webinar-ignition'); ?>"
										type="text"
										name="tracking_tags[<?php echo esc_attr($tti); ?>][name]"
										id="tracking_tags_name_<?php echo esc_attr($tti); ?>"
										value="<?php echo esc_attr($tracking_tag['name']); ?>"
										inputmode="text">
								</div>
								<br clear="left">

							</div>

							<div class="editSection">
								<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e('Tracking Tag Field Name', 'webinar-ignition'); ?></div>
									<div class="inputTitleHelp">
										<?php esc_html_e('If you want your tracking tags save into separate field, provide tracking field name', 'webinar-ignition'); ?>
									</div>
								</div>

								<div class="inputSection">
									<input
										class="inputField elem tracking_tags_slug"
										placeholder="<?php esc_html_e('f.e. 1:59', 'webinar-ignition'); ?>"
										type="text"
										name="tracking_tags[<?php echo esc_attr($tti); ?>][slug]"
										id="tracking_tags_slug_<?php echo esc_attr($tti); ?>"
										value="<?php echo esc_attr($tracking_tag['slug']); ?>"
										inputmode="text">
								</div>
								<br clear="left">

							</div>

							<div class="editSection">

								<div class="inputTitle">
									<div class="inputTitleCopy"><?php esc_html_e('Tracking Pixel Code', 'webinar-ignition'); ?></div>
									<div class="inputTitleHelp">
										<?php echo esc_html(htmlspecialchars(__('Put your tracking pixel code here. It will be added to <head> tag', 'webinar-ignition'))); ?>
									</div>
								</div>

								<div class="inputSection">
									<textarea
										name="tracking_tags[<?php echo esc_attr($tti); ?>][pixel]"
										id="tracking_tags_pixel_<?php echo esc_attr($tti); ?>"
										class="inputTextarea elem tracking_tags_pixel"><?php
																						$tracking_pixel = ! empty($tracking_tag['pixel']) ? $tracking_tag['pixel'] : '';
																						echo esc_html(htmlspecialchars(stripcslashes($tracking_pixel)));
																						?></textarea>
								</div>
								<br clear="left">

							</div>



							<div class="auto_action_footer" style="padding: 15px;">
								<button type="button" class="blue-btn-44 btn cloneTrackingTag" style="color:#FFF;float:none;">
									<i class="icon-copy"></i> <?php esc_html_e('copy', 'webinar-ignition'); ?>
								</button>

								<button type="button" class="blue-btn btn deleteTrackingTag" style="color:#FFF;float:none;">
									<i class="icon-remove"></i> <?php esc_html_e('delete', 'webinar-ignition'); ?>
								</button>
							</div>
						</div>
					</div>
			<?php
				} //end foreach
			} //end if
			?>
		</div>
		<?php

		if (! WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled($webinar_data)) {
		?>
		</div><?php
			}

			if (WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled($webinar_data)) {
				?>
		<div class="additional_auto_action_control editSection" style="border-bottom: 3px solid #e4e4e4;">
			<button type="button" id="createTrackingTag" class="blue-btn-44 btn" style="color:#FFF;float:none;">
				<i class="icon-plus"></i> <?php esc_html_e('Create New Tag', 'webinar-ignition'); ?>
			</button>
		</div>
<?php
			}
		}
