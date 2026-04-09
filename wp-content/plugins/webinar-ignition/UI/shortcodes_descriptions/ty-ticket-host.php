<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

?>
<div class="wi-code-example-wrap grid-col-start grid-col-end-3 mt-20">

	<?php

	if (! empty($is_list)) { ?>
		<h4><?php esc_html_e('Webinar host name section / inline', 'webinar-ignition'); ?></h4>
	<?php }  ?>

	<div class="wi-grid">


		<p class="code-example">
			<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_ticket_host"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied. Input into your content!', 'webinar-ignition'); ?></span>
		</p>

		<p class="code-example">
			<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_ticket_host_inline"]</span><!--
	--><span class="code-example-copy"><?php esc_html_e('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php esc_html_e('Copied. Input into your content!', 'webinar-ignition'); ?></span>
		</p>
	</div>


	<?php if (! empty($is_list)) { 	?>
		<hr>
	<?php }  ?>
</div>