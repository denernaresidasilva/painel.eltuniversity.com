<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
?>
<div class="tabber wi-tab-design-and-shortcodes" id="tab9" style="display: none;">
	<div class="titleBar">
		<h2><?php esc_html_e('Design / Templates', 'webinar-ignition'); ?></h2>
		<p><?php esc_html_e('Here you can select which front-end theme you want and what webinar theme you want...', 'webinar-ignition'); ?></p>
	</div>
	<?php
	$input_get = array(
		'id' => isset($_GET['id']) ? sanitize_text_field(wp_unslash($_GET['id'])) : null
	);
	?>
	<div id="we_design_fe" class="" style="display:block;">


		<?php
		webinarignition_display_edit_toggle(
			' icon-picture',
			esc_html__('Themes and Layouts', 'webinar-ignition'),
			'wi_design_theme_layout',
			esc_html__('Select a theme for your webinar and registration pages.', 'webinar-ignition')
		);

		?>
		<div id="wi_design_theme_layout" class="we_edit_area p-12">


			<?php
			$fe_template 	= ! empty($webinar_data->fe_template) ? $webinar_data->fe_template : '';
			$template_items = "$sitePath" . "images/lp1.png [lp],
											$sitePath" . "images/lp2.png [ss],
											$sitePath" . 'images/lp3.png [cp]';
			$template_items = explode(',', $template_items);

			$webinar_template_selected = ! empty($webinar_data->webinar_template) ? $webinar_data->webinar_template : 'modern';

			$webinar_templates = array(
				'modern' => array(
					'preview' => $sitePath . 'images/webip-modern.png',
				),
				'classic' => array(
					'preview' => $sitePath . 'images/webip-classic.png',
				),
			);
			?>

			<div class="editSection wi-edit-section flex flex-col bg-white px-20 py-25">
				<div class="inputTitle" style="float: none;width: 100%;">
					<div class="inputTitleCopy text-md"><?php echo esc_html__('Registration Funnel Theme: ', 'webinar-ignition'); ?></div>
					<div class="inputTitleHelp"><?php echo esc_html__('You can choose between the styles on the right. This is for the landing page/registration page and for the thank you page styles...', 'webinar-ignition'); ?></div>
				</div>


				<div class="wi-wrap">
					<?php
					$i = 0; // Counter
					$selectedClass = '';

					foreach ($template_items as $item) {

						// parse value

						$item = explode('[', $item);
						$item[0] = trim($item[0]);
						$item[1] = str_replace(']', '', $item[1]);

						if ($fe_template == '' && $i == '0') {
							// Is First Element && Data is null
							$selectedClass = 'dub_select_image_selected';
						}

					?>
						<div class="dub_select_image ds_fe_template <?php echo esc_attr($selectedClass); ?> <?php if ($fe_template == $item[1]) {
																												echo 'dub_select_image_selected';
																											} ?>" dsData="<?php echo esc_attr($item[1]); ?>" dsID="fe_template">

							<img src="<?php echo esc_url($item[0]); ?>" />

						</div>
					<?php

						++$i; // add to counter
						$selectedClass = ''; // Reset Class
					} //end foreach

					?>
				</div>
				<br clear="all" />
				<input type='hidden' class="elem" name="fe_template" id="fe_template" value="<?php echo esc_attr(isset($webinar_data->fe_template) ? $webinar_data->fe_template : 'lp'); ?>" />
			</div>

			<?php
			if ($webinar_data && ! WebinarignitionPowerups::webinarignition_is_modern_template_enabled($webinar_data)) {
			?>
				<div style="display: none">
				<?php
			}
				?>
				<div class="editSection flex flex-col px-20 py-25 bg-white mt-7">
					<div class="inputTitle" style="float: none;width: 100%;margin-bottom: 25px;">
						<div class="inputTitleCopy text-md"><?php echo esc_html__('Webinar Page Layout: ', 'webinar-ignition'); ?></div>
						<div class="inputTitleHelp"><?php echo esc_html__('You can choose between the styles below. This is for the webinar page and replay page...', 'webinar-ignition'); ?></div>
					</div>


					<div class="wi-wrap">
						<?php
						$i = 0;
						$selectedClass = '';

						foreach ($webinar_templates as $slug => $item) {
						?>
							<div
								class="dub_select_image ds_webinar_template<?php echo $slug === $webinar_template_selected ? ' dub_select_image_selected' : ''; ?>"
								dsData="<?php echo esc_attr($slug); ?>"
								dsID="webinar_template">
								<img src="<?php echo esc_url($item['preview']); ?>" />
							</div>
						<?php
						} // foreachend
						?>
					</div>

					<br clear="all" />

					<input type='hidden' class="elem" name="webinar_template" id="webinar_template" value="<?php echo esc_attr($webinar_template_selected); ?>" />
				</div>
				</div>
				<?php
				if ($webinar_data && ! WebinarignitionPowerups::webinarignition_is_modern_template_enabled($webinar_data)) {
				?>
		</div>



	<?php } ?>


	<?php

	webinarignition_display_edit_toggle(
		' icon-globe',
		esc_html__('Global Shortcodes', 'webinar-ignition'),
		'wi_design_global_shortcodes',
		esc_html__('You can find global shortcodes i.e webinar info shortcodes, lead info shortcodes etc.', 'webinar-ignition')
	);

	?>


	<?php if (! WebinarignitionPowerupsShortcodes::webinarignition_is_enabled($webinar_data)) {	?>




		<div style="display: none;">


		<?php
	}
		?>


		<div id="wi_design_global_shortcodes" class="we_edit_area p-12">

			<div class="bg-white py-25 px-20">

				<?php

				webinarignition_display_option(
					$input_get['id'],
					! empty($webinar_data->custom_templates_styles) ? $webinar_data->custom_templates_styles : 'on',
					esc_html__('Shortcodes styles', 'webinar-ignition'),
					'custom_templates_styles',
					esc_html__('You can disable default shortcodes styles if you want to style all elements by your own.', 'webinar-ignition'),
					esc_html__('Enable styles', 'webinar-ignition') . ' [on],' . esc_html__('Disable styles', 'webinar-ignition') . ' [off]'
				);


				?>

				<?php
				webinarignition_display_global_shortcodes(
					$webinar_data,
					$input_get['id'],
					esc_html__('Global shortcodes', 'webinar-ignition'),
					esc_html__('This shortcodes can be used on any template page', 'webinar-ignition')
				);

				?>
			</div>
		</div>
		</div>

		<?php

		$pages = get_posts(array(
			'numberposts' => -1,
			'orderby'     => 'post_title',
			'order'       => 'ASC',
			'include'     => array(),
			'post_type'   => 'page',
		));

		$pages_options = array();

		if (! empty($pages)) {
			
			foreach ($pages as $page) {
				$url = get_permalink($page->ID);
				$pages_options[$page->ID] = array(
					'label' => $page->post_title,
					'url' 	=> $url,
				);
			}
			

			$custom_templates = WebinarignitionPowerupsShortcodes::webinarignition_get_available_templates();
			


			if ($webinar_data->webinar_date == 'AUTO') {
				unset($custom_templates['custom_closed_page']);
			}
			
			$available_shortcodes = WebinarignitionPowerupsShortcodes::webinarignition_get_available_shortcodes();
			$available_shortcodes_by_tpl = array();

			foreach ($available_shortcodes as $sh_key => $sh_data) {
				if ('registration' 	=== $sh_data['page']) {
					$available_shortcodes_by_tpl['custom_registration_page'][$sh_key] = $sh_data;
				} elseif ('thankyou' 	=== $sh_data['page']) {
					$available_shortcodes_by_tpl['custom_thankyou_page'][$sh_key] = $sh_data;
				} elseif ('webinar' 	=== $sh_data['page']) {
					$available_shortcodes_by_tpl['custom_webinar_page'][$sh_key] = $sh_data;
				} elseif ('countdown' 	=== $sh_data['page']) {
					$available_shortcodes_by_tpl['custom_countdown_page'][$sh_key] = $sh_data;
				} elseif ('replay' 	=== $sh_data['page']) {
					$available_shortcodes_by_tpl['custom_replay_page'][$sh_key] = $sh_data;
				} elseif ('closed' 	=== $sh_data['page']) {
					$available_shortcodes_by_tpl['custom_closed_page'][$sh_key] = $sh_data;
				}
			}

			

			global $webinarignition_shortcodes_is_list;
			$webinarignition_shortcodes_is_list = true;



			foreach ($custom_templates as $tpl_id => $tpl_data) {
				$tpl_selected_data = ! empty($webinar_data->{$tpl_id}) ? $webinar_data->{$tpl_id} : '';
				$shortcodes = ! empty($available_shortcodes_by_tpl[$tpl_id]) ? $available_shortcodes_by_tpl[$tpl_id] : array();
				$tpl_data_help = isset($tpl_data['help']) ? $tpl_data['help'] : '';
				webinarignition_display_template_dropdown_options(
					$webinar_data,
					$input_get['id'],
					$tpl_selected_data,
					$tpl_data['title'],
					$tpl_id,
					$tpl_data_help,
					$pages_options,
					$tpl_data['params'],
					$shortcodes,
					esc_html__('-- select page --', 'webinar-ignition')
				);
			}


			
			$webinarignition_shortcodes_is_list = false;
		} else {
		} //end if
		if (! WebinarignitionPowerupsShortcodes::webinarignition_is_enabled($webinar_data)) {
		?>
	</div>
<?php
		}
?>

<div class="bottomSaveArea">
	<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php esc_html_e('Save & Update', 'webinar-ignition'); ?></a>
</div>


</div>



<?php if (! empty($webinar_data->webinar_lang)) {
	restore_previous_locale();
} //end if
?>

</div>