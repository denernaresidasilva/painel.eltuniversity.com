<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function webinarignitionx_meta_box_add() {
	add_meta_box( 'webinarignitionx-id', __( 'Link To WebinarIgnition', 'webinar-ignition' ), 'webinarignitionx_meta_box_cb', 'page', 'side', 'high' );
}

function webinarignitionx_meta_box_cb() {

	global $post;
	wp_nonce_field( 'webinarignitionx_meta_box_nonce', 'webinarignitionx_box_nonce' );
	$webinar_id = absint( get_post_meta( $post->ID, 'webinarignitionx_meta_box_select', true ) ); // Check if webinar page ?>

	<h4 style=" margin-bottom: 0px; margin-top: 15px;"><?php esc_html_e( 'Select A WebinarIgnition Campaign Page:', 'webinar-ignition' ); ?></h4>
	<span style="font-size: 11px;"><?php esc_html_e( 'This page will be replaced with this campaign page...', 'webinar-ignition' ); ?></span>
	<br>
	<select name="webinarignitionx_meta_box_select" id="webinarignitionx_meta_box_select" style="margin-top: 10px; width: 250px;">

		<option <?php
		if ( '0' === (int) $webinar_id ) {
			echo "selected='selected'";
		}
		?> value="0">NONE </option>


		<?php
		global $wpdb;
		$table_db_name  = $wpdb->prefix . 'webinarignition';
		$templates = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table_db_name ORDER BY ID DESC", 
			),
			ARRAY_A
		);

		foreach ( $templates as $template ) {

			$name           = stripslashes( $template['appname'] );
			$id             = stripslashes( $template['ID'] );
			$selectedBox    = '';
			if ( $webinar_id === $id ) {
				$selectedBox = "selected='selected'";
			}

			printf(
				'<option %s value="%d">%s</option>',
				esc_attr( $selectedBox ), absint( $id ), esc_attr( $name )
			);
		}
		?>

	</select>

	<?php
}

// Save Settings

add_action( 'save_post', 'webinarignitionx_meta_box_save' );

function webinarignitionx_meta_box_save( $post_id ) {

	// Bail if we're doing an auto save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// if our nonce isn't there, or we can't verify it, bail
	if ( ! isset( $_POST['webinarignitionx_box_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['webinarignitionx_box_nonce'] ), 'webinarignitionx_meta_box_nonce' ) ) {
		return;
	}

	// if our current user can't edit this post, bail
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	// now we can actually save the data
	// Make sure your data is set before trying to save it

	if ( isset( $_POST['webinarignitionx_meta_box_select'] ) ) {

		if ( 0 === (int) $_POST['webinarignitionx_meta_box_select'] ) {
			delete_post_meta( $post_id, 'webinarignitionx_meta_box_select' );
		} else {
			$meta_box_value = sanitize_text_field( wp_unslash( $_POST['webinarignitionx_meta_box_select'] ) );
			update_post_meta( $post_id, 'webinarignitionx_meta_box_select', $meta_box_value  );
		}
	}
}