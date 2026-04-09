<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

?>

<div class="wi-code-example-wrap">
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_calendar_reminder"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
</div>


<div class="wi-code-example-wrap">
	<p class="wi-desc-top">
		<?php echo esc_html__('You can use Google cal and Outlook cal reminder separately using the shortcodes below', 'webinar-ignition'); ?>
	</p>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_calendar_reminder_google_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base">
		<?php echo esc_html__('You can use Google cal and Outlook cal reminder separately using the shortcodes below', 'webinar-ignition'); ?>
	</p>
</div>

<div class="wi-code-example-wrap">
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_calendar_reminder_outlook_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
</div>
<?php if (! empty($is_list)) { ?>
	<hr>
<?php } ?>