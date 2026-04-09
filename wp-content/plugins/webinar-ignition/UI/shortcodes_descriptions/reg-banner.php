<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 * Note: This file executes first time when printing shortcodes so the recommended shortcode section code is in it. if another file will be execute * to display shortcode first so the code should be moved there
 */
 $webinarId = $webinar_data->id;

?>

<div class="wi-code-example-wrap grid-col-start mt-20 grid-col-end-3">
	<h4>
		<?php echo esc_html__('Recommended Shortcodes', 'webinar-ignition'); ?>
	</h4>
</div>

<div class="wi-code-example-wrap wi-recommended">
	<?php if (! empty($is_list)) {	?>
		<p class="wi-desc-top"> <?php esc_html_e('CTA Area - Video / Image Settings', 'webinar-ignition'); ?> </p>
	<?php } ?>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_video_area" custom_video_url="" border="false"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<?php if (! empty($is_list)) {	?>
		<p class="wi-desc-bottom text-light text-base"> <?php esc_html_e('CTA Area - Video / Image Settings', 'webinar-ignition'); ?> </p>
	<?php } ?>
</div>

<div class="wi-code-example-wrap wi-recommended">
	<p class="wi-desc-top">
		<?php echo esc_html__('This shortcode will show optin section with webinar dates selection and registration form together in one column', 'webinar-ignition'); ?>
	</p>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_optin_section" readonly="false" background_color="example: transparent, #000" contrast_color="example: #fff" ]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base">
		<?php echo esc_html__('This shortcode will show optin section with webinar dates selection and registration form together in one column', 'webinar-ignition'); ?>
	</p>
</div>


<div class="wi-code-example-wrap grid-col-start mt-20 grid-col-end-3">
	<h4>
		<?php echo esc_html__('All Shortcodes', 'webinar-ignition'); ?>
	</h4>
</div>


<div class="wi-code-example-wrap">
	<?php
	$webinarId = esc_attr($webinar_data->id);
	if (! empty($is_list)) { ?>
		<p class="wi-desc-top"><?php echo esc_html__('This shortcode display webinar banner image', 'webinar-ignition'); ?> </p>
	<?php }	?>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="reg_banner"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<?php if (! empty($is_list)) { ?>
		<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('For display of webinar banner image', 'webinar-ignition'); ?> </p>
	<?php }	?>
	<?php if (! empty($is_list)) { ?>
		<hr>
	<?php } ?>
</div>