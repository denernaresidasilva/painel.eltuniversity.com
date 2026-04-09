<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if (! empty($is_list)) { ?>

<?php }  ?>
<div class="wi-code-example-wrap">
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="webinar_cta"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied. Input into your content!', 'webinar-ignition'); ?></span>
	</p>
</div>