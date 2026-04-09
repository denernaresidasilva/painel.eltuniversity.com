<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;
?>
<div class="wi-code-example-wrap">
	<?php
	if (! empty($is_list)) { ?>
		<p class="wi-desc-top"><?php esc_html_e('Thank You Message Area - Copy / Video / Image Settings', 'webinar-ignition'); ?></p>
	<?php }	?>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_video_area" custom_video_url="" border="false"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base"><?php esc_html_e('Thank You Message Area - Copy / Video / Image Settings', 'webinar-ignition'); ?></p>
	<?php if (! empty($is_list)) { ?>
		<hr>
	<?php } ?>
</div>