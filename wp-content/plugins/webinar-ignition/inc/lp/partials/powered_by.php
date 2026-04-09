<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$footer_copy         = get_option( 'webinarignition_footer_text' );
$footer_copy         = str_replace( '{site_title}', get_bloginfo( 'name' ), $footer_copy );
$footer_copy         = str_replace( '{year}', gmdate( 'Y' ), $footer_copy );
$footer_copy         = str_replace( '{site_description}', get_bloginfo( 'description' ), $footer_copy );
$privacy_policy_link = get_privacy_policy_url();
$privacy_policy      = '<a href="' . $privacy_policy_link . '" target="_blank">' . __( 'Privacy Policy', 'webinar-ignition' ) . '</a>';
$footer_copy         = str_replace( '{privacy_policy}', $privacy_policy, $footer_copy );
$imprint_page        = wi_get_page_by_title( 'Imprint' );
$imprint_page        = empty( $imprint_page ) ? wi_get_page_by_title( 'Impressum' ) : $imprint_page;
$imprint_page_url    = ! empty( $imprint_page ) ? get_permalink( $imprint_page->ID ) : '';
$imprint_page_title  = ! empty( $imprint_page ) && isset($imprint_page->post_title) ? $imprint_page->post_title : '';
$imprint_page_link   = '<a href="' . $imprint_page_url . '" target="_blank">' . $imprint_page_title . '</a>';
$footer_copy         = is_object( $imprint_page ) ? str_replace( '{imprint}', $imprint_page_link, $footer_copy ) : str_replace( '{imprint}', ' ', $footer_copy );
?>
<!-- BOTTOM AREA -->
<div class="bottomArea">
	<div class="wiContainer container">
		
		<div><?php 
		echo ! empty( $footer_copy ) ? wp_kses_post( $footer_copy ) : wp_kses_post( $privacy_policy ) . ' | ' . wp_kses_post( $imprint_page_link ) .' | © Copyright ' .  esc_attr( gmdate( 'Y' ) ) .' '. wp_kses_post( get_bloginfo( 'name' ) );
		?></div>
		
		<?php if ( get_option( 'webinarignition_show_footer_branding' ) ) { ?>
				<div style="margin-top: 15px;text-transform:capitalize;"><a href="<?php echo esc_url( get_option( 'webinarignition_affiliate_link' ) ); ?>"  target="_blank"><b><?php echo esc_html( get_option( 'webinarignition_branding_copy' ) ); ?></b></a> </div>
		<?php } ?>
		
	</div>
</div>
