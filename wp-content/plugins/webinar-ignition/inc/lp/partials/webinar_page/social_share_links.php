<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="webinarShare">
	<div class="webinarShareCopy" style="color: <?php echo isset( $webinar_data->webinar_invite_color ) ? esc_html($webinar_data->webinar_invite_color) : '#222'; ?>;">
		<!-- <i class="icon-user"></i> <?php // webinarignition_display( $webinar_data->webinar_invite, __( 'Invite Your Friends To The Webinar:', 'webinar-ignition' ) ); ?> -->
	<!-- </div>
	<div class="webinarShareIcons wi-block--sharing"> -->

		<!-- Facebook Share	Button -->
		<?php // if ( isset($webinar_data->webinar_fb_share) && 'off' !== $webinar_data->webinar_fb_share ) : ?>
    <!-- <div style="position: relative; float: left; min-height: 20px; width: 60px; margin-right: 15px;"> -->
        <?php // if ( isset($webinar_data->webinar_permalink) ) { ?>
            <!-- <div class="fb-like"
                data-href="<?php // echo esc_url( $webinar_data->webinar_permalink ); ?>"
                style="position: absolute; top: -2px; left: 0;"
                data-layout="button_count"
                data-width="60"
                data-show-faces="false">
            </div> -->
		<?php // } ?>
        
    </div>
<?php // endif; ?>


		<!-- Twitter Share Button -->
		<?php // if ( isset($webinar_data->webinar_permalink) && 'off' !== $webinar_data->webinar_tw_share ) : ?>
			<!-- <div style="position: relative; float: left; min-height: 20px; width: 60px; margin-right: 15px;">
				<a
					href="https://twitter.com/share"
					data-url="<?php // echo esc_url( $webinar_data->webinar_permalink ); ?>"
						class="twitter-share-button"
				>Tweet
				</a>
			</div> -->
		<?php // endif ?>

	</div>

</div>
