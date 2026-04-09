<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
// Functions For Form Elements ::
// DISPLAY SINGLE FIELD:
function webinarignition_display_field(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder,
    $type = 'text',
    $attr = array()
) {
    // Output HTML
    $attr_strings = array();
    if ( !empty( $attr ) && is_array( $attr ) ) {
        foreach ( $attr as $attr_name => $attr_value ) {
            $attr_name = esc_attr( $attr_name );
            $attr_value = esc_attr( wp_kses_stripslashes( $attr_value ) );
            $attr_strings[] = "{$attr_name}=\"{$attr_value}\"";
        }
    }
    $attr_string = implode( ' ', $attr_strings );
    ?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<input class="inputField elem" placeholder="<?php 
    echo esc_attr( $placeholder );
    ?>" type="<?php 
    echo esc_attr( $type );
    ?>" name="<?php 
    echo esc_attr( $id );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" value="<?php 
    echo ( !empty( $data ) ? esc_attr( stripcslashes( $data ) ) : '' );
    ?>" <?php 
    echo wp_kses_post( $attr_string );
    ?>>
		</div>
		<br clear="left" >

	</div>

	<?php 
}

function webinarignition_display_number_field(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder,
    $min = '',
    $max = '',
    $step = ''
) {
    // Output HTML
    $min_max_step = '';
    if ( $min !== '' ) {
        $min_max_step .= ' min="' . (int) $min . '"';
    }
    if ( $max !== '' ) {
        $min_max_step .= ' max="' . (int) $max . '"';
    }
    if ( $step !== '' ) {
        $min_max_step .= ' step="' . (int) $step . '"';
    }
    ?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<input class="inputField elem" placeholder="<?php 
    echo esc_attr( $placeholder );
    ?>" type="number" name="<?php 
    echo esc_attr( $id );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" value="<?php 
    echo esc_attr( stripcslashes( $data ) );
    ?>"<?php 
    echo wp_kses_post( $min_max_step );
    ?>>
		</div>
		<br clear="left" >

	</div>

	<?php 
}

function webinarignition_display_min_sec_mask_field(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder,
    $type = 'text'
) {
    // Output HTML
    ?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<input class="inputField elem min_sec_mask_field" placeholder="<?php 
    echo esc_attr( $placeholder );
    ?>" type="<?php 
    echo esc_attr( $type );
    ?>" name="<?php 
    echo esc_attr( $id );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" value="<?php 
    echo esc_attr( stripcslashes( $data ) );
    ?>">
		</div>
		<br clear="left" >

	</div>

	<?php 
}

function webinarignition_display_min_sec_field(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder
) {
    // Output HTML
    $min = '0';
    $sec = '00';
    if ( is_array( $id ) ) {
        $min_id = $id[0];
        $sec_id = $id[1];
    } else {
        $min_id = $id . '_min';
        $sec_id = $id . '_sec';
    }
    $min_sec_array = explode( ':', $data );
    if ( !empty( $min_sec_array[0] ) ) {
        $min = (int) $min_sec_array[0];
    }
    if ( !empty( $min_sec_array[1] ) ) {
        $sec = (int) $min_sec_array[1];
        if ( $sec < 10 ) {
            $sec = '0' . $sec;
        } elseif ( $sec > 60 ) {
            $sec = '60';
        }
    }
    ?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo esc_html( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<div style="width:120px;max-width: 40%;display: inline-block;">
				<input
						class="inputField elem"
						placeholder="<?php 
    echo esc_attr( $placeholder );
    ?>"
						type="number"
						name="<?php 
    echo esc_attr( $min_id );
    ?>"
						id="<?php 
    echo esc_attr( $min_id );
    ?>"
						min="0"
						value="<?php 
    echo esc_attr( $min );
    ?>"
				>
			</div>

			:

			<div style="width:80px;max-width: 40%;display: inline-block;">
				<input
						class="inputField elem"
						placeholder="00"
						type="number"
						name="<?php 
    echo esc_html( $sec_id );
    ?>"
						id="<?php 
    echo esc_attr( $sec_id );
    ?>"
						min="0" max="60"
						value="<?php 
    echo esc_html( $sec );
    ?>"
						onchange="if(parseInt(this.value,10)<10)this.value='0'+this.value;if(parseInt(this.value,10)>60)this.value='60';if(this.value=='')this.value='00';"
				>
			</div>
			<br clear="left" >
		</div>
		<br clear="left" >

	</div>

	<?php 
}

// DISPLAY SINGLE FIELD W/ IMAGE BUTTON
function webinarignition_display_field_image_upd(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder
) {
    // Output HTML
    ?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<div id="<?php 
    echo esc_attr( $id );
    ?>_image_holder" class="input_image_holder">
				<?php 
    if ( !empty( $data ) ) {
        ?>
					<img src="<?php 
        echo esc_attr( $data );
        ?>">
					<?php 
    }
    ?>
			</div>

			<input
					style="float:left; width: 420px; margin-bottom: 10px;"
					placeholder="<?php 
    echo esc_html( $placeholder );
    ?>"
					class="inputField elem"
					type="text"
					name="<?php 
    echo esc_html( $id );
    ?>"
					id="<?php 
    echo esc_attr( $id );
    ?>"
					value="<?php 
    echo esc_attr( stripslashes( $data ) );
    ?>"
			>

			<button id="<?php 
    echo esc_attr( $id );
    ?>_upload_image_btn" class="wi_upload_image_btn grey-btn" type="button">
				<?php 
    esc_html_e( 'Media library', 'webinar-ignition' );
    ?>
			</button>

			<button
					id="<?php 
    echo esc_attr( $id );
    ?>_delete_image_btn"
					class="wi_delete_image_btn grey-btn"
					type="button"
				<?php 
    echo ( empty( $data ) ? ' style="display:none;"' : '' );
    ?>
			>
				<?php 
    esc_html_e( 'Delete Image', 'webinar-ignition' );
    ?>
			</button>
			<br clear="all" >
		</div>
		<br clear="left" >

	</div>
	<?php 
}

function webinarignition_display_field_add_media(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder
) {
    // Output HTML
    ?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo esc_html( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<input
					style="float:left; width: 420px; margin-bottom: 10px;"
					placeholder="<?php 
    echo esc_html( $placeholder );
    ?>"
					class="inputField elem"
					type="text"
					name="<?php 
    echo esc_html( $id );
    ?>"
					id="<?php 
    echo esc_attr( $id );
    ?>"
					value="<?php 
    echo esc_attr( stripcslashes( $data ) );
    ?>"
			>

			<button id="<?php 
    echo esc_attr( $id );
    ?>_upload_media_btn" class="wi_upload_media_btn grey-btn" type="button">
				<?php 
    esc_html_e( 'Media library', 'webinar-ignition' );
    ?>
			</button>

			<button
					id="<?php 
    echo esc_attr( $id );
    ?>_delete_media_btn"
					class="wi_delete_media_btn grey-btn"
					type="button"
				<?php 
    echo ( empty( $data ) ? ' style="display:none;"' : '' );
    ?>
			>
				<?php 
    esc_html_e( 'delete', 'webinar-ignition' );
    ?>
			</button>
			<br clear="all" >
		</div>
		<br clear="left" >

	</div>
	<?php 
}

function webinarignition_display_field_image(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder
) {
    // Output HTML
    ?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo esc_html( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<input style="float:left; width: 420px; " placeholder="<?php 
    echo esc_attr( $placeholder );
    ?>" class="inputField elem" type="text" name="<?php 
    echo esc_attr( $id );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" value="<?php 
    echo esc_attr( stripslashes( $data ) );
    ?>">
			<div style="float:right; margin-top: 10px; margin-bottom:15px;" class='launch_media_lib grey-btn ' photoBox='<?php 
    echo esc_attr( $id );
    ?>' ><?php 
    esc_html_e( 'Upload Image', 'webinar-ignition' );
    ?></div>
			<br clear="all" >
		</div>
		<br clear="left" >

	</div>

	<?php 
}

// DISPLAY TEXTAREA:
function webinarignition_display_textarea(
    $num,
    $data,
    $title,
    $id,
    $help,
    $placeholder
) {
    ?>
	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<textarea name="<?php 
    echo esc_html( $id );
    ?>" placeholder="<?php 
    echo esc_html( $placeholder );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" class="inputTextarea elem"><?php 
    echo ( isset( $data ) ? esc_textarea( $data ) : '' );
    ?></textarea>
		</div>
		<br clear="left" >

	</div>

	<?php 
}

// DISPLAY OPTIONS
function webinarignition_display_option(
    $num,
    $data,
    $title,
    $id,
    $help,
    $options
) {
    // Get options:
    $items = explode( ',', $options );
    $first_option = 'N/A';
    // Output HTML
    ?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy w-400 text-md" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>
		<div class="inputSection" >
			<?php 
    $i = 0;
    // Counter
    $selectedClass = '';
    $selectedClass2 = '';
    foreach ( $items as $item ) {
        $item = explode( '[', $item );
        $item[0] = trim( $item[0] );
        $item[1] = str_replace( ']', '', $item[1] );
        $item1Class = 'option-' . str_replace( ' ', '_', $item[1] );
        if ( $data == '' && $i == '0' ) {
            // Is First Element && Data is null
            $selectedClass = 'optionSelectorSelected';
            $selectedClass2 = 'icon-circle';
            $first_option = $item[1];
        }
        $icon_class = ( strtolower( trim( $data ) ) == strtolower( trim( $item[1] ) ) ? 'icon-circle' : 'icon-circle-blank' );
        $selected_class = ( strtolower( trim( $data ) ) == strtolower( trim( $item[1] ) ) ? 'optionSelectorSelected' : '' );
        $css_class = sprintf(
            'opts-grp-%1$s optionSelector %2$s %3$s %4$s',
            esc_attr( $id ),
            $selected_class,
            $selectedClass,
            $item1Class
        );
        ?>
				<a
					href="#"
					class="<?php 
        echo esc_attr( $css_class );
        ?>"
					data-value="<?php 
        echo esc_attr( $item[1] );
        ?>"
					data-id="<?php 
        echo esc_attr( $id );
        ?>"
				>
					<i class="<?php 
        echo esc_attr( $icon_class );
        ?> iconOpts <?php 
        echo esc_attr( $selectedClass2 );
        ?>"></i>
					<?php 
        echo esc_html( $item[0] );
        ?>
				</a>
				<?php 
        ++$i;
        // add to counter
        $selectedClass = '';
        // Reset Class
        $selectedClass2 = '';
    }
    ?>

			<input type="hidden" name="<?php 
    echo esc_html( $id );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" value="<?php 
    if ( $data == '' ) {
        echo esc_html( trim( $first_option ) );
    } else {
        echo esc_html( trim( $data ) );
    }
    ?>" />

			<?php 
    if ( !empty( $belowOptionsText ) ) {
        ?>
				<?php 
        echo esc_html( $belowOptionsText );
        ?>
			<?php 
    }
    ?>

		</div>
		<br clear="left" >

	</div>

	<?php 
}

function webinarignition_display_live_notification_option(
    $num,
    $sent_status,
    $email_date,
    $email_time,
    $title,
    $id,
    $help,
    $options
) {
    // Get options:
    $items = explode( ',', $options );
    $first_option = 'N/A';
    // Output HTML
    ?>
	<div class="editSection">
		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
		</div>
		<div class="inputSection" >
			<?php 
    if ( $sent_status != 'sent' ) {
        echo sprintf( 
            /* translators: %1$s: email date, %2$s: email time */
            esc_html__( 'The email is queued on %1$s at %2$s', 'webinar-ignition' ),
            esc_html( $email_date ),
            esc_html( $email_time )
         );
        echo '</p>';
    } else {
        echo '<p>';
        echo sprintf( 
            /* translators: %1$s: email date, %2$s: email time */
            esc_html__( 'The email is already sent on %1$s at %2$s', 'webinar-ignition' ),
            esc_html( $email_date ),
            esc_html( $email_time )
         );
        echo '</p>';
    }
    if ( !empty( $belowOptionsText ) ) {
        ?>
				<?php 
        echo esc_html( $belowOptionsText );
        ?>
			<?php 
    }
    ?>

		</div>
		<br clear="left" >
		<input 
			type="hidden" 
			name="<?php 
    echo esc_attr( $id );
    ?>" 
			id="<?php 
    echo esc_attr( $id );
    ?>" 
			value="<?php 
    echo esc_attr( $sent_status );
    ?>" 
		>
	</div>

	<?php 
}

// DISPLAY WP EDITOR:
function webinarignition_display_wpeditor_media(
    $num,
    $data,
    $title,
    $id,
    $help
) {
    // $id = htmlspecialchars(stripcslashes($results->$id));
    $settings = array(
        'wpautop' => false,
        'teeny'   => false,
        'tinymce' => array(
            'height' => '250',
        ),
    );
    // Output HTML
    ?>

	<div class="editSection">

		<div class="inputTitle">
			<div class="inputTitleCopy" ><?php 
    echo esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    echo wp_kses_post( $help );
    ?></div>
		</div>

		<div class="inputSection">
			<?php 
    wp_editor( stripcslashes( $data ), $id, $settings );
    ?>
		</div>
		<br clear="left" >

	</div>

	<?php 
}

function webinarignition_display_wpeditor(
    $num,
    $data,
    $title,
    $id,
    $help
) {
    return webinarignition_display_wpeditor_media(
        $num,
        $data,
        $title,
        $id,
        $help
    );
}

function webinarignition_display_stripe_stuff(
    $num,
    $data,
    $title,
    $id,
    $help
) {
    // $id = htmlspecialchars(stripcslashes($results->$id));
    $settings = array(
        'wpautop'       => false,
        'media_buttons' => false,
        'teeny'         => false,
        'tinymce'       => array(
            'height' => '250',
        ),
    );
    // Output HTML
    ?>

	<div class="editSection">

		<div class="inputTitle" style="display:none;">
			<div class="inputTitleCopy" ><?php 
    esc_html( $title );
    ?></div>
			<div class="inputTitleHelp" ><?php 
    esc_html( $help );
    ?></div>
		</div>

		<div class="inputSection" >
			<h3 style="font-weight: bold;"><?php 
    esc_html_e( 'Stripe specific instructions', 'webinar-ignition' );
    ?></h3>
			<ul>
				<li><b>1. </b><?php 
    esc_html_e( 'Paste your secret key in the Stripe Secret Key field, which you can get from', 'webinar-ignition' );
    ?>
					<a href="https://dashboard.stripe.com/account/apikeys" target="_blank">https://dashboard.stripe.com/account/apikeys</a>
					<br>​<?php 
    esc_html_e( "When testing your integration use the Test Secret Key. You can change to the Live Secret Key when you're done with testing.", 'webinar-ignition' );
    ?>
				</li>
				<br>
				<li><b>2. </b><?php 
    esc_html_e( 'Paste your publishable key in the Publishable Key field, which you can get from', 'webinar-ignition' );
    ?>
					<a href="https://dashboard.stripe.com/account/apikeys" target="_blank">https://dashboard.stripe.com/account/apikeys</a>
					<br><?php 
    esc_html_e( "When testing your integration use the Test​ Publishable Key. You can change to the Live ​Publishable Key when you're done with testing.", 'webinar-ignition' );
    ?>
				</li>
				<br>
				<li><b>3. </b>
					<?php 
    esc_html_e( 'Specify your charge for the webinar in the Charge field. This should be in cents. So, if you would like to charge US$120 for the webinar, then write 12000', 'webinar-ignition' );
    ?>
				</li>
				<br>
				<li><b>4. </b>
					<?php 
    esc_html_e( 'Specify the description for the charge. This is all that is needed. You need not edit the values in the fields below Button Color field.', 'webinar-ignition' );
    ?>
				</li>
				<br>
				<li><b>6. </b>
					<?php 
    esc_html_e( 'To test your integration you may use Stripe’s test credit card:', 'webinar-ignition' );
    ?>
				<li><b><?php 
    esc_html_e( 'Number:', 'webinar-ignition' );
    ?> </b> 4242 4242 4242 4242</li>
				<li><b><?php 
    esc_html_e( 'Expiry:', 'webinar-ignition' );
    ?> </b> 12 / 25</li>
				<li><b>CVC:</b> 123</li>
				</li>
				<br>
			</ul>
			<div style="display:none;">
				<?php 
    $data = $data ?? '';
    wp_editor( stripcslashes( $data ), $id, $settings );
    ?>
				<div style="float:right; margin-top: 10px; margin-bottom:15px;" class='launch_media_lib grey-btn ' photoBox='<?php 
    echo esc_attr( $id );
    ?>' ><?php 
    esc_html_e( 'Insert Image', 'webinar-ignition' );
    ?></div>
			</div>
		</div>
		<br clear="left" >

	</div>

	<?php 
}

// DISPLAY - ACTION FOR CALLBACK:
function webinarignition_display_field_hidden(  $id, $callback  ) {
    // Output HTML
    ?>
	<input class="inputField elem" type="hidden" name="<?php 
    echo esc_html( $id );
    ?>" id="<?php 
    echo esc_attr( $id );
    ?>" value="<?php 
    echo esc_attr( $callback );
    ?>">

	<?php 
}

function webinarignition_display_dev_info_section(  $statusCheck  ) {
    if ( !empty( $statusCheck->is_dev ) ) {
        ?>
		<div class="unlockTitle2">
			<span style="font-size: 14px;font-weight: normal;">
				<?php 
        echo ( !empty( $statusCheck->is_dev ) ? ' (DEV Mode)' : '' );
        ?>
				<?php 
        echo ' (branch: ' . esc_html( WEBINARIGNITION_BRANCH ) . ', v.' . esc_html( WEBINARIGNITION_VERSION ) . ')';
        ?>
			</span>
			<?php 
        ?>
		</div>
		<?php 
    }
}

if ( !function_exists( 'webinarignition_get_available_languages' ) ) {
    function webinarignition_get_available_languages() {
        $webinarignition_languages = get_available_languages( WEBINARIGNITION_PATH . '/languages/' );
        $loco_translate_languages = get_available_languages( WP_CONTENT_DIR . '/languages/loco/plugins/' );
        $system_languages = get_available_languages( WP_CONTENT_DIR . '/languages/plugins/' );
        $all_languages = array_merge( $loco_translate_languages, $system_languages, $webinarignition_languages );
        $available_languages = array();
        for ($i = 0; $i < count( $all_languages ); $i++) {
            if ( strpos( $all_languages[$i], 'webinarignition' ) !== false || strpos( $all_languages[$i], 'webinar-ignition' ) !== false ) {
                $available_languages[] = $all_languages[$i];
            }
        }
        for ($i = 0; $i < count( $available_languages ); $i++) {
            if ( strpos( $available_languages[$i], 'webinarignition-' ) !== false ) {
                $available_languages[$i] = substr( $available_languages[$i], 16 );
            }
            if ( strpos( $available_languages[$i], 'webinar-ignition-' ) !== false ) {
                $available_languages[$i] = substr( $available_languages[$i], 17 );
            }
        }
        return array_unique( $available_languages );
    }

}