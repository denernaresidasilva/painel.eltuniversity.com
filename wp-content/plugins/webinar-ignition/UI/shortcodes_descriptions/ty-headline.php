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
	if (! empty($is_list)) {
	?>
		<p class="wi-desc-top">
			<?php esc_html_e('Main Headline and Ticket Sub Headline', 'webinar-ignition'); ?>:
		</p>

		<div class="wi-congrats wi-desc-top">
			<h2><?php webinarignition_display($webinar_data->ty_ticket_headline, __('Congratulations! You are signed up!', 'webinar-ignition')); ?></h2>
			<h3 style=""><?php webinarignition_display($webinar_data->ty_ticket_subheadline, __('Below is all the information you need for the webinar...', 'webinar-ignition')); ?></h3>
		</div>
	<?php } ?>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_headline"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base">
		<?php esc_html_e('Main Headline and Ticket Sub Headline', 'webinar-ignition'); ?>:
	</p>
	<?php if (! empty($is_list)) { ?>
		<hr>
	<?php } ?>
</div>