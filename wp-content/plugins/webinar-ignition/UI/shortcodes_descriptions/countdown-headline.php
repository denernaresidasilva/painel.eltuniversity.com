<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;
?>
<div class="wi-code-example-wrap grid-col-start grid-col-end-3 mt-20">
	<?php if (! empty($is_list)) { ?>
		<div class="wi-countdown-headline">
			<?php
			webinarignition_display(
				$webinar_data->cd_headline,
				'<h4 class="subheader">' . __('You Are Viewing A Webinar That Is Not Yet Live', 'webinar-ignition') . ' - <b>' . __('We Go Live Soon!', 'webinar-ignition') . '</b></h4>'
			);
			?>
		</div>
	<?php } ?>
</div>

<div class="wi-code-example-wrap">
	<?php if (! empty($is_list)) { ?>
		<p class="wi-desc-top"><?php echo esc_html__('Countdown Headline', 'webinar-ignition'); ?></p>
		<p class="code-example">
			<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="countdown_headline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
		</p>
		<p class="wi-desc-bottom text-light text-base"> <?php echo esc_html__('Countdown Headline', 'webinar-ignition'); ?> </p>
	<?php } ?>
</div>