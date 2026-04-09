<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
?>

<div class="topArea">
    <div class="bannerTop">
        <?php
            if ( ! empty( $webinar_data->webinar_banner_image ) ) {
                echo "<img src='" . esc_url($webinar_data->webinar_banner_image) . "' />";
            }
        ?>
    </div>
</div>
