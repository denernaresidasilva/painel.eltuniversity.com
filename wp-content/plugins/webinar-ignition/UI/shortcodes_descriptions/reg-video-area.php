<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */
return;
$webinarId = $webinar_data->id;
?>
<div class="wi-code-example-wrap">
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