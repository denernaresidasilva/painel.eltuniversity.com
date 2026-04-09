<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */
$webinarId = $webinar_data->id;
?>
<div class="wi-code-example-wrap grid-col-start mt-20 grid-col-end-3">
	<h4>
		<?php echo esc_html__('Recommended Shortcodes', 'webinar-ignition'); ?>
	</h4>
</div>

<div class="wi-code-example-wrap">
	<?php if (! empty($is_list)) { 	?>
		<p class="wi-desc-top"> <?php esc_html_e('If you need to show video and sidebar sections together, you can use two shortcodes below. Only working with first, left, new webinar room design!', 'webinar-ignition'); ?></p>
	<?php } ?>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="webinar_video_cta_sidebar"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
	<?php if (! empty($is_list)) { 	?>
		<p class="wi-desc-bottom text-light text-base"> <?php esc_html_e('If you need to show video and sidebar sections together, you can use two shortcodes below. Only working with first, left, new webinar room design!', 'webinar-ignition'); ?></p>
	<?php } ?>
</div>

<div class="wi-code-example-wrap grid-col-start mt-20 grid-col-end-3">
	<h4>
		<?php echo esc_html__('All Shortcodes', 'webinar-ignition'); ?>
	</h4>
</div>

<div class="wi-code-example-wrap">
	<?php
	if (! empty($is_list)) { 	?>
		<p class="wi-desc-top"> <?php esc_html_e('Webinar video and Call To Actions section together', 'webinar-ignition'); ?> </p>
	<?php } ?>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="webinar_video_cta"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<?php
	if (! empty($is_list)) { 	?>
		<p class="wi-desc-bottom text-light text-base"> <?php esc_html_e('Webinar video and Call To Actions section together', 'webinar-ignition'); ?> </p>
	<?php } ?>
</div>