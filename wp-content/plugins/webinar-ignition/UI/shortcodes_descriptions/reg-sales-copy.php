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
		<p class="wi-desc-top"> <?php esc_html_e('Main Sales Copy', 'webinar-ignition'); ?> </p>
		<div class="wi-desc-top"><?php
									webinarignition_display(
										do_shortcode($webinar_data->lp_sales_copy),
										'<p>' . esc_html_e('Your Amazing sales copy for your webinar would show up here...', 'webinar-ignition') . '</p>'
									);
									?>
		</div>
	<?php } ?>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_sales_copy"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<?php if (! empty($is_list)) {	?>
		<p class="wi-desc-bottom text-light text-base"> <?php esc_html_e('Your Amazing sales copy for your webinar would show up here...', 'webinar-ignition'); ?></p>
	<?php } ?>

</div>