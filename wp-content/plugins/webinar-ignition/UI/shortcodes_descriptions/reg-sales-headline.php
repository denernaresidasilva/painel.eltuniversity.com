<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;
?>
<div class="wi-code-example-wrap">
	<?php if (! empty($is_list)) {	?>
		<p class="wi-desc-top"><?php esc_html_e('Sales Copy Headline', 'webinar-ignition'); ?>: <strong><?php webinarignition_display($webinar_data->lp_sales_headline, __('What You Will Learn On The Webinar...', 'webinar-ignition')); ?></strong> </p>
	<?php } else ?>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_sales_headline"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<?php if (! empty($is_list)) {	?>
		<p class="wi-desc-bottom text-light text-base"><?php esc_html_e('Sales Copy Headline', 'webinar-ignition'); ?>: <span><?php webinarignition_display($webinar_data->lp_sales_headline, __('What You Will Learn On The Webinar...', 'webinar-ignition')); ?>
	</span></p>
	<?php } else ?>

</div>