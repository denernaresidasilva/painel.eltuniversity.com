<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $assets
 * @var $paid_check
 * @var $loginUrl
 * @var $user - Who is user
 */
?>

<?php

/**
 * The Below code is fully copied from auto-register.php
 * no changes made in functionality only improve the UI of the box that took verification code from the user
 *
 * If the login attribute contains false or something else
 * I will prefer the functionality of previous developers
 */
$email = '';
$user_full_name = '';
$user_first_name = '';
$user_last_name = '';
$user_salutation = '';
$user_reason = '';
$webinar_user_email = '';
$applang = $webinar_data->webinar_lang;
global $webinarignition_shortcode_params;


// Only get the required values from INPUT_GET
if ( isset( $_GET['login'] )&& isset( $_GET['e'] ) && wp_validate_boolean( $_GET['login'] ) ) { // phpcs:ignore
    $email = trim( sanitize_text_field( $_GET['e'] ) );
}

$webinar_user_email = ( isset( $_GET['email'] ) && ! empty( $_GET['email'] ) ) ? trim( sanitize_text_field( $_GET['email'] ) ) : '';
$user_last_name = ( isset( $_GET['ln'] ) && ! empty( $_GET['ln'] ) ) ? trim( sanitize_text_field( $_GET['ln'] ) ) : '';
$user_salutation = ( isset( $_GET['sal'] ) && ! empty( $_GET['sal'] ) ) ? trim( sanitize_text_field( $_GET['sal'] ) ) : '';
$user_reason = ( isset( $_GET['r'] ) && ! empty( $_GET['r'] ) ) ? trim( sanitize_text_field( $_GET['r'] ) ) : '';
$user_full_name = ( isset( $_GET['first'] ) && ! empty( $_GET['first'] ) ) ? trim( sanitize_text_field( $_GET['first'] ) ) : '';
$user_full_name = is_email( $webinar_user_email ) ? $user_full_name : base64_decode( $user_full_name );
$webinar_user_email = is_email( $webinar_user_email ) ? $webinar_user_email : base64_decode( $webinar_user_email );
$user_full_name = $user_full_name != '' && isset($user_full_name) ? $user_full_name : (isset($_GET['n']) ? $_GET['n'] : '');


$order_id = WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) && 
            WebinarignitionManager::webinarignition_get_paid_webinar_type( $webinar_data ) === 'woocommerce' && 
            WebinarignitionManager::webinarignition_url_has_valid_wc_order_id();
$disable_reg_fields = false;
if ( $order_id ) {
    $disable_reg_fields = true;
	$paid_check = true;
    $user = WebinarignitionManager::webinarignition_get_user_from_wc_order_id();
} elseif ( is_user_logged_in() ) {
    $user = wp_get_current_user();
}


/*
 * This check is important; otherwise, due to the plugin structure, 
 * sometimes when we visit wp-admin it tries to check these functions,
 * and if they’re not found, it throws an error.
 */

$wi_shortcode_background_color = ''; // Define empty variable to prevent undefined variable errors
$wi_shortcode_contrast_color   = ''; // Define empty variable to prevent undefined variable errors

if ( function_exists( 'wi_get_shortcode_background_color_style' ) ) {
    $wi_shortcode_background_color = wi_get_shortcode_background_color_style( $webinarignition_shortcode_params, $webinar_data->id );
}

if ( function_exists( 'wi_get_shortcode_contrast_color_style' ) ) {
    $wi_shortcode_contrast_color = wi_get_shortcode_contrast_color_style( $webinarignition_shortcode_params, $webinar_data->id );
    $wi_shortcode_contrast_color_code_only = wi_get_shortcode_contrast_color_style( $webinarignition_shortcode_params, $webinar_data->id, true );
	
}

$wi_use_fse_colors = get_option('webinarignition_use_grid_custom_color', 0);


?>

<div 
	class="optinFormArea optin-form-area <?php echo ( $wi_use_fse_colors == 1 ) ? 'wi-ignore-fsc' : ''; ?>"  
	<?php
		// Build inline style dynamically
		$inline_style = '';

		// Hide if paid_check is 'no'
		if ( isset( $paid_check ) && $paid_check === 'no' ) {
			$inline_style .= 'display:none;';
		}

		// Append background color if available
		if ( ! empty( $wi_shortcode_background_color ) ) {
			$inline_style .= $wi_shortcode_background_color;
		}

		// Output final style attribute if style exists
		if ( ! empty( $inline_style ) ) {
			echo 'style="' . esc_attr( $inline_style ) . '"';
		}
	?>
>

<?php
if ( ! empty( $user ) ) {
    $user_full_name  = $user->display_name;
    $user_first_name = $user->first_name;
    $user_last_name  = $user->last_name;
    if ( empty( $webinar_user_email ) ) {
        $webinar_user_email = $user->user_email;
    }
}
$user_first_name = $user_first_name != '' ? $user_first_name : $user_full_name;


//applicable for logged in users
if( is_user_logged_in() && current_user_can('activate_plugins') ) {
			

	// Manually format date and time
	$todays_date = gmdate('mdY'); // Format: mmddyyyy
	$todays_time = current_time('Hi');  // Format: 24-hour time without colon (e.g., 0000)

	// Get and sanitize the website URL
	$website_url = str_replace('http://', '', get_site_url()); // Remove http
	$website_url = str_replace('https://', '', $website_url);  // Remove https

	// Create a unique webinar user email using the sanitized time, date, and URL
	$webinar_user_email = "{$todays_time}-{$todays_date}@{$website_url}";
	$user_first_name = "{$todays_time}-{$todays_date}";
	$user_full_name = "{$todays_time}-{$todays_date}";
} else {
	$wi_new_regisration_tab = 'webhook_data_registration_url';
}

$WPreadOnlyMethod = 'wp_readonly';
if ( ! function_exists( $WPreadOnlyMethod ) ) {
    $WPreadOnlyMethod = 'readonly';
}
if ( isset( $_GET['n'] ) && ! empty( $_GET['n'] ) ) {
    $user_full_name = trim( sanitize_text_field( $_GET['n'] ) );
} elseif ( isset( $_GET['first'] ) && ! empty( $_GET['first'] ) ) {
    $user_full_name = trim( sanitize_text_field( $_GET['first'] ) );
}
?>
<p 
	class="wi-required-text"
	<?php 
		// Apply inline contrast color if available
		if ( ! empty( $wi_shortcode_contrast_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
		}
	?>
>
	<?php esc_html_e( 'Please fill the required fields *', 'webinar-ignition' ); ?>
</p>


<?php
$webinar_user_email = ( isset( $_GET['e'] ) && ! empty( $_GET['e'] ) ) ? trim( sanitize_text_field( $_GET['e'] ) ) : $webinar_user_email;
if ( ! empty( $webinar_data->ar_fields_order ) && is_array( $webinar_data->ar_fields_order ) ) {
    $alreadyAddedFields = array();
    $wi_showingGDPRHeading = false;
	$webinarignition_enable_honeypot_field        = absint(get_option('webinarignition_enable_honeypot_field', 1));
	$webinarignition_enable_honeypot_field        = $webinarignition_enable_honeypot_field == 1 ? true : false;
	if ( $webinarignition_enable_honeypot_field ) {
		?>
		<span style="display:none;">
			<input type="text" id="full_name_pot" class="full_name_pot" name="full_name" value="" autocomplete="off" >
		</span>
		<?php
	} 
	if(!property_exists($webinar_data, 'lp_schedule_type') || ($webinar_data->lp_schedule_type === 'fixed')) {
		?>
		<div class="wiFormGroup wiFormGroup-lg">
			<label
			<?php if ( !empty( $wi_shortcode_contrast_color ) ) : ?>
				style="<?php echo esc_attr( $wi_shortcode_contrast_color ); ?>"
			<?php endif; ?>
			 for="webinar_timezone"
			class="tz-reg-label wi-label">Timezone</label>
			<select name="webinar_timezone" id="webinar_timezone" class="wi_webinar_timezone inputField inputFieldDash elem webinar_timezone_a" required>
				<?php
				$webinar_tz_string = isset($webinar_data->webinar_timezone) ? $webinar_data->webinar_timezone : $webinar_data->auto_timezone_fixed;
				// Get the timezone from WordPress general settings
				$timezone_string = get_option('timezone_string');
				$gmt_offset = get_option('gmt_offset');

				// Check if the timezone is a named timezone or a UTC offset
				if (!empty($timezone_string)) {
					// If it's a named timezone, select it in the dropdown
					echo wp_kses(
						webinarignition_create_tz_select_list($webinar_tz_string, get_user_locale()),
						array(
							'option' => array(
								'value' => array(),
								'selected' => array(),
							),
							'optgroup' => array(
								'label' => array(),
							)
						)
					);
				} else {
					// If it's a UTC offset, display the dropdown without selection
					echo wp_kses(
						webinarignition_create_tz_select_list('', get_user_locale()),
						array(
							'option' => array(
								'value' => array(),
								'selected' => array(),
							),
							'optgroup' => array(
								'label' => array(),
							)
						)
					);
				}
				?>
			</select>
		</div>
		<?php
	}
	if(class_exists('NextendSocialLogin')) {
		$webinarignition_registration_shortcode		  	= get_option( 'webinarignition_registration_shortcode', '[nextend_social_login]' );
	}else{
		$webinarignition_registration_shortcode		  	= get_option( 'webinarignition_registration_shortcode', '' );
	}
	if(isset($webinarignition_registration_shortcode) || $webinarignition_registration_shortcode != '') {
		$social_login = do_shortcode( $webinarignition_registration_shortcode );
		echo wp_kses_post($social_login);
	}

	// $rowOpen = true; // tracks if we're inside a row multi col
?>
// <div class="wi-reg-row wi-flex wi-gap-4 wi-mb-3">
<?php
    foreach ( $webinar_data->ar_fields_order as $_field ) {
        if ( in_array( $_field, $alreadyAddedFields ) ) {
            continue;
        }
        $alreadyAddedFields[] = $_field;
		




		// new added code for multicolumn
		
		// 💡 Check if this field should start a new row
		// $isNewRow = isset($webinar_data->ar_new_row_fields) 
		// 			&& is_array($webinar_data->ar_new_row_fields)
		// 			&& in_array($_field, $webinar_data->ar_new_row_fields, true);

		// If we need a new row, close the previous one (if open)
		// if ($isNewRow) {
		// 	if ($rowOpen) {
		// 		echo '</div>'; // close previous row
		// 		$rowOpen = false;
		// 	}
		// 	echo '<div class="wi-reg-row wi-flex wi-gap-4 wi-mb-3">'; // open new row
		// 	$rowOpen = true;
		// }
	// 
		


        switch ( $_field ) {
            case 'ar_name':
                $required = ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_name', $webinar_data->ar_required_fields ) ) ? true : false;

                if ( ! in_array( 'ar_lname', $webinar_data->ar_fields_order ) && false ) {
                    ?>
                    <div class="wiFormGroup wiFormGroup-lg">
						<label class="wi-label" for="optName">
							<?php esc_html_e( 'Full Name', 'webinar-ignition' ); ?>
							<?php if ( $required ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>
						
						<input 
							type="text" 
							class="wi-mb-2 wi_optin_name_a radius fieldRadius wiRegForm wiFormControl <?php echo $required ? ' required' : ''; ?>" 
							id="optName"
							name="optName"
							placeholder="<?php
								webinarignition_display( $webinar_data->lp_optin_name, __( 'Enter your full name', 'webinar-ignition' ) );
								echo $required ? '*' : '';
							?>"
							value="<?php echo esc_html( $user_full_name ); ?>" 
							autocomplete="off"
							<?php if ( $required ) : ?>
								required aria-required="true" 
							<?php endif; ?>
							
						>
						
						<span id="fullNameError" class="wi-error-message" aria-live="assertive" role="alert">
							<?php esc_html_e( 'Full name required', 'webinar-ignition' ); ?>
						</span>
					</div>

                    <?php
                } else {
                    ?>
                    <div class="wiFormGroup wiFormGroup-lg">
					<label 
						class="wi-label" 
						for="optName"
						<?php 
							// Apply inline contrast color if available
							if ( ! empty( $wi_shortcode_contrast_color ) ) {
								echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
							}
						?>
					>
						<?php esc_html_e( 'First Name', 'webinar-ignition' ); ?>
						<?php if ( $required ) : ?>
							<span aria-hidden="true">*</span>
						<?php endif; ?>
					</label>

						<input 
							type="text" 
							class="radius testclass fieldRadius wiRegForm optNamer wiFormControl <?php echo $required ? 'required' : ''; ?>" 
							id="optName" 
							name="optName" 
							placeholder="<?php
								webinarignition_display( $webinar_data->lp_optin_name, __( 'Enter your first name', 'webinar-ignition' ) );
							?>"
							value="<?php echo esc_html( $user_first_name ); ?>" 
							autocomplete="off"
							<?php if ( $required ) : ?>
								required aria-required="true"
							<?php endif; ?>
						
						>

						<span id="firstNameError" class="wi-error-message" role="alert" >
							<?php esc_html_e( 'First name required', 'webinar-ignition' ); ?>
						</span>
					</div>

                    <?php
                }
                break;
				case 'ar_lname':
					$required = ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_lname', $webinar_data->ar_required_fields, true ) ) ? true : false;
					?>
					<div class="wiFormGroup wiFormGroup-lg">
						<label 
								class="wi-label" 
								for="optLName"
								<?php 
									// Apply inline contrast color if available
									if ( ! empty( $wi_shortcode_contrast_color ) ) {
										echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
									}
								?>
							>
								<?php 
									echo esc_html(
										WebinarignitionManager::webinarignition_ar_field_translated_name(
											'optLName',
											esc_html( $applang )
										)
									);
								?>

								<?php if ( $required ) : ?>
									<span aria-hidden="true">*</span>
								<?php endif; ?>
							</label>


						<input 
							type="text" 
							<?php $WPreadOnlyMethod( $disable_reg_fields, true, true ); ?> 
							class="radius fieldRadius wiRegForm optNamer wiFormControl <?php echo $required ? 'required' : ''; ?>" 
							id="optLName"
							name="optLName"
							placeholder="<?php
								webinarignition_display( $webinar_data->lp_optin_lname, WebinarignitionManager::webinarignition_ar_field_translated_name('optLName', $applang) );
							?>"
							value="<?php echo esc_html( $user_last_name ); ?>" 
							autocomplete="family-name"
							<?php if ( $required ) : ?>
								required aria-required="true" 
							<?php endif; ?>
							
						>

						<span id="lastNameError" class="wi-error-message" role="alert" aria-live="assertive" style="display:none;">
							<?php esc_html_e( 'Last name required', 'webinar-ignition' ); ?>
						</span>
					</div>
					<?php
					break;

			case 'ar_salutation':
				$required = ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_salutation', $webinar_data->ar_required_fields, true ) ) ? true : false;
				?>
				<div class="wiFormGroup wiFormGroup-lg">
					<label 
						class="wi-label" 
						for="optSalutation"
						<?php 
							if ( ! empty( $wi_shortcode_contrast_color ) ) {
								echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
							}
						?>
					>
						<?php // webinarignition_display( $webinar_data->lp_optin_salutation, 'Salutation'); ?>
						<?php esc_html_e( 'Title', 'webinar-ignition' ); ?>
						<?php if ( $required ) : ?>
							<span aria-hidden="true">*</span>
						<?php endif; ?>
					</label>


					<input 
						type="text" 
						<?php $WPreadOnlyMethod( $disable_reg_fields, true, true ); ?> 
						class="radius fieldRadius wiRegForm wiFormControl <?php echo $required ? 'required' : ''; ?>" 
						id="optSalutation"
						name="optSalutation"
						placeholder="<?php
							webinarignition_display( $webinar_data->lp_optin_salutation, 'Salutation');
							
						?>"
						value="<?php echo esc_html( $user_salutation ?? '' ); ?>" 
						autocomplete="honorific-prefix"
						<?php if ( $required ) : ?>
							required aria-required="true"
						<?php endif; ?>
						
					>

					<span id="salutationError" class="wi-error-message" role="alert" aria-live="assertive" style="display:none;">
						<?php esc_html_e( 'Salutation required', 'webinar-ignition' ); ?>
					</span>
				</div>

				<?php
				break;
			case 'ar_reason':
				$required = ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_reason', $webinar_data->ar_required_fields, true ) ) ? true : false;
				?>
				<div class="wiFormGroup wiFormGroup-lg">
					<label 
						class="wi-label" 
						for="optReason"
						<?php 
							if ( ! empty( $wi_shortcode_contrast_color ) ) {
								echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
							}
						?>
					>
						<?php // webinarignition_display( $webinar_data->lp_optin_reason, 'Reason'); ?>
						<?php esc_html_e( 'Participation Reason', 'webinar-ignition' ); ?>
						<?php if ( $required ) : ?>
							<span aria-hidden="true">*</span>
						<?php endif; ?>
					</label>


					<input 
						type="text" 
						<?php $WPreadOnlyMethod( $disable_reg_fields, true, true ); ?> 
						class="radius fieldRadius wiRegForm wiFormControl <?php echo $required ? 'required' : ''; ?>" 
						id="optReason"
						name="optReason"
						placeholder="<?php
							webinarignition_display( $webinar_data->lp_optin_reason, 'Reason');
							
						?>"
						value="<?php echo esc_html( $user_reason ?? '' ); ?>" 
						autocomplete="honorific-prefix"
						<?php if ( $required ) : ?>
							required aria-required="true"
						<?php endif; ?>
						
					>

					<span id="reasonError" class="wi-error-message" role="alert" aria-live="assertive" style="display:none;">
						<?php esc_html_e( 'Reason required', 'webinar-ignition' ); ?>
					</span>
				</div>

				<?php
				break;
            case 'ar_email':
                // Checking if the current shortcode is autofill registration block.
                // Checking if the email is readonly.
                global $webinarignition_shortcode_params;

                /**
                 * The email readonly should check from url instead of from webinar data.
                 */
                $readonly_email = ( isset( $_GET['readonly'] ) && ! empty( $_GET['readonly'] ) ) ? 
				filter_var( trim( sanitize_text_field( $_GET['readonly'] ) ), 
				FILTER_VALIDATE_BOOLEAN ) : 
				false;

                // URL parameter ko check karen
				$readonly_email = false; // Default value
				if ( isset( $_GET['readonly'] ) && ! empty( $_GET['readonly'] ) ) {
					$readonly_email = filter_var( trim( sanitize_text_field( $_GET['readonly'] ) ), FILTER_VALIDATE_BOOLEAN );
					
				} elseif ( ! empty( $webinarignition_shortcode_params[ $webinar_data->id ] ) && 
						! empty( $webinarignition_shortcode_params[ $webinar_data->id ]['block'] ) && 
						isset( $webinarignition_shortcode_params[ $webinar_data->id ]['readonly'] ) ) {
					// Agar URL me value nahi hai, to shortcode ki value use karen
					$readonly_email = wp_validate_boolean( $webinarignition_shortcode_params[ $webinar_data->id ]['readonly'] );
				}
				
                ?>
                <div class="wiFormGroup wiFormGroup-lg">
					<label 
						class="wi-label" 
						for="optEmail"
						<?php 
							// Apply inline contrast color if available
							if ( ! empty( $wi_shortcode_contrast_color ) ) {
								echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
							}
						?>
					>
						<?php 
							echo esc_html(
								WebinarignitionManager::webinarignition_ar_field_translated_name(
									'optEmail',
									esc_html( $applang )
								)
							); 
						?>
						<span aria-hidden="true">*</span>
					</label>


					<input 
						type="email" 
						class="radius fieldRadius wiRegForm wiFormControl" 
						id="optEmail"
						name="optEmail"
						placeholder="<?php
							webinarignition_display(
								$webinar_data->lp_optin_email,
								WebinarignitionManager::webinarignition_ar_field_translated_name('optEmail', $applang)
							);
						?>"
						value="<?php echo esc_html( $webinar_user_email ); ?>" 
						autocomplete="email"
						required aria-required="true"
						
						<?php echo $readonly_email || $disable_reg_fields ? 'readonly' : ''; ?>
					>

					<span id="wiEmailError" class="wi-error-message" role="alert" aria-live="assertive" style="display:none;">
						<?php esc_html_e('Email required', 'webinar-ignition'); ?>
					</span>
				</div>


                <?php
                break;
            case 'ar_phone':
                ?>
                <div class="wiFormGroup wiFormGroup-lg">
					<label 
						class="wi-label" 
						for="optPhone"
						<?php 
							if ( ! empty( $wi_shortcode_contrast_color ) ) {
								echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
							}
						?>
					>
						<?php esc_html_e( 'Phone', 'webinar-ignition' ); ?>

						<?php
						//  webinarignition_display( 
						// 	$webinar_data->lp_optin_phone, 
						// 	WebinarignitionManager::webinarignition_ar_field_translated_name('optPhone', $applang) 
						// );
						?>

						<?php if ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_phone', $webinar_data->ar_required_fields, true ) ) : ?>
							<span aria-hidden="true">*</span>
						<?php endif; ?>
					</label>


					<input 
						type="tel" 
						class="radius fieldRadius wiRegForm wi_phone_number wiFormControl <?php echo ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_phone', $webinar_data->ar_required_fields, true ) ) ? ' required' : ''; ?>" 
						id="optPhone"
						name="optPhone"
						placeholder="<?php
							webinarignition_display( 
								$webinar_data->lp_optin_phone, 
								WebinarignitionManager::webinarignition_ar_field_translated_name('optPhone', $applang) 
							);
							// echo ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_phone', $webinar_data->ar_required_fields, true ) ) ? '*' : '';
						?>"
						value="<?php echo esc_html( $user_phone ?? '' ); ?>"
						autocomplete="tel"
						<?php if ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_phone', $webinar_data->ar_required_fields, true ) ) : ?>
							required aria-required="true"
						<?php endif; ?>
						
						pattern="^\+?[0-9\s\-\(\)]{7,}$"
					>

					<span id="phoneError" class="wi-error-message" role="alert" aria-live="assertive" style="display:none;">
						<?php esc_html_e( 'Phone number required', 'webinar-ignition' ); ?>
					</span>
				</div>

                <?php
                break;

				case 'ar_custom_1':
					
					?> 
					
					<div class="wiFormGroup wiFormGroup-lg">
						<label 
							class="wi-label" 
							for="optCustom_1"
							<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>
							<?php echo esc_html( $webinar_data->lp_optin_custom_1 ); ?>
							<?php if ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_custom_1', $webinar_data->ar_required_fields, true ) ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>


						<input 
							type="text" 
							class="radius fieldRadius wiRegForm wi_optCustom_1 wiFormControl <?php echo ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_1', $webinar_data->ar_required_fields, true) ) ? 'required' : ''; ?>" 
							id="optCustom_1"
							name="optCustom_1"
							placeholder="<?php
								webinarignition_display($webinar_data->lp_optin_custom_1, '');
							?>"
							<?php if ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_1', $webinar_data->ar_required_fields, true) ) : ?>
								required aria-required="true"
							<?php endif; ?>
							
						>

						<span id="custom1Error" class="wi-error-message" role="alert">
							<?php esc_html_e('Field required', 'webinar-ignition'); ?>
						</span>
					</div>

					<?php
					break;

				case 'ar_custom_2':
					?>
					<div class="wiFormGroup wiFormGroup-lg">
						<label 
							class="wi-label" 
							for="optCustom_2"
							<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>
							<?php echo esc_html( $webinar_data->lp_optin_custom_2 ); ?>
							<?php if ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_custom_2', $webinar_data->ar_required_fields, true ) ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>


						<input 
							type="text" 
							class="radius fieldRadius wiRegForm wi_optCustom_2 wiFormControl <?php echo ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_2', $webinar_data->ar_required_fields, true) ) ? 'required' : ''; ?>" 
							id="optCustom_2"
							name="optCustom_2" 
							placeholder="<?php
								webinarignition_display($webinar_data->lp_optin_custom_2, '');
							?>"
							<?php if ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_2', $webinar_data->ar_required_fields, true) ) : ?>
								required aria-required="true"
							<?php endif; ?>
							
						>

						<span id="custom2Error" class="wi-error-message" role="alert">
							<?php esc_html_e('Field required', 'webinar-ignition'); ?>
						</span>
					</div>

					<?php
					break;

				case 'ar_custom_3':
					?>
					<div class="wiFormGroup wiFormGroup-lg">
						<label 
							class="wi-label" 
							for="optCustom_3"
							<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>
							<?php echo esc_html( $webinar_data->lp_optin_custom_3 ); ?>
							<?php if ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_custom_3', $webinar_data->ar_required_fields, true ) ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>


						<input 
							type="text" 
							class="radius fieldRadius wiRegForm wi_optCustom_3 wiFormControl <?php echo ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_3', $webinar_data->ar_required_fields, true) ) ? 'required' : ''; ?>" 
							id="optCustom_3"
							name="optCustom_3"
							placeholder="<?php
								webinarignition_display($webinar_data->lp_optin_custom_3, '');
							?>"
							<?php if ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_3', $webinar_data->ar_required_fields, true) ) : ?>
								required aria-required="true"
							<?php endif; ?>
							
						>

						<span id="custom3Error" class="wi-error-message" role="alert">
							<?php esc_html_e('Field required', 'webinar-ignition'); ?>
						</span>
					</div>

					<?php
					break;

				case 'ar_custom_4':
					?>
					<div class="wiFormGroup wiFormGroup-lg">
						<label 
							class="wi-label" 
							for="optCustom_4"
							<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>
							<?php echo esc_html( $webinar_data->lp_optin_custom_4 ); ?>
							<?php if ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_custom_4', $webinar_data->ar_required_fields, true ) ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>


						<input 
							type="text" 
							class="radius fieldRadius wiRegForm wi_optCustom_4 wiFormControl <?php echo ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_4', $webinar_data->ar_required_fields, true) ) ? 'required' : ''; ?>" 
							id="optCustom_4"
							name="optCustom_4"
							placeholder="<?php webinarignition_display($webinar_data->lp_optin_custom_4, ''); ?>"
							<?php if ( isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_4', $webinar_data->ar_required_fields, true) ) : ?>
								required aria-required="true"
							<?php endif; ?>
							
						>

						<span id="custom4Error" class="wi-error-message" role="alert">
							<?php esc_html_e('Field required', 'webinar-ignition'); ?>
						</span>
					</div>

					<?php
					break;

				case 'ar_custom_5':
				case 'ar_custom_6':
					$index               = str_replace('ar_custom_', '', $_field);
					$options_setting_str = 'lp_optin_custom_' . $index;
					$is_required         = isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array($_field, $webinar_data->ar_required_fields, true);
					?>
					<div class="customFieldDiv wiFormCheckbox wiFormCheckbox-md">
						<input 
							type="checkbox" 
							id="optCustom_<?php echo absint($index); ?>" 
							name="optCustom_<?php echo absint($index); ?>" 
							class="wiRegForm wi_optCustom_<?php echo absint($index); ?><?php echo $is_required ? ' required' : ''; ?>" 
							<?php echo $is_required ? 'required aria-required="true"' : ''; ?> 
							
						>

						<label 
							for="optCustom_<?php echo absint($index); ?>"
							<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>
							<?php echo esc_html($webinar_data->{$options_setting_str}); ?>
							<?php if ($is_required) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>

<br>
						<span id="custom<?php echo absint($index); ?>Error" class="wi-error-message" role="alert">
							<?php esc_html_e('Please check this box to proceed', 'webinar-ignition'); ?>
						</span>
					</div>
					<?php
					break;


				case 'ar_custom_7':
					?>
					<?php 
						// echo "<pre>";
						// print_r($webinar_data);
						// echo "</pre>";
					?>
					<div class="customFieldDiv wiFormGroup wiFormGroup-lg">
						<label 
	for="optCustom_7"
	<?php 
		if ( ! empty( $wi_shortcode_contrast_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
		}
	?>
>
	<?php echo wp_kses_post($webinar_data->lp_optin_custom_7); ?>
	<?php if (isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_7', $webinar_data->ar_required_fields, true)) : ?>
		<span aria-hidden="true">*</span>
	<?php endif; ?>
</label>


						<textarea 
							id="optCustom_7" 
							name="optCustom_7"
							class="wiRegForm wi_optCustom_7 wiFormControl <?php echo (isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_7', $webinar_data->ar_required_fields, true)) ? 'required' : ''; ?>" 
							placeholder="<?php echo esc_attr(wp_strip_all_tags($webinar_data->lp_optin_custom_7)); ?>" 
							rows="4" 
							cols="50"
							<?php echo (isset($webinar_data->ar_required_fields) && is_array($webinar_data->ar_required_fields) && in_array('ar_custom_7', $webinar_data->ar_required_fields, true)) ? 'required aria-required="true"' : ''; ?>
							
							
						></textarea>

						<span id="custom7Error" class="wi-error-message" role="alert">
							<?php esc_html_e('Field required', 'webinar-ignition'); ?>
						</span>
					</div>

					<?php
					break;

				case 'ar_custom_15':
				case 'ar_custom_16':
				case 'ar_custom_17':
				case 'ar_custom_18':
					$index               = str_replace( 'ar_custom_', '', $_field );
					$label_setting       = 'lp_optin_custom_' . $index;
					$options_setting_str = 'lp_optin_custom_select_' . $index;
					$options_setting     = $webinar_data->{$options_setting_str};
					$options_setting2    = $webinar_data->{$options_setting_str};
					$is_required         = isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( $_field, $webinar_data->ar_required_fields, true );

					if ( ! empty( trim( $options_setting ) ) ) {
						$options_array = explode( "\n", $options_setting );
						?>
						<div class="customFieldDiv customFieldSelectDiv wiFormGroup wiFormGroup-lg">
							
							<label 
								for="optCustom_<?php echo absint($index); ?>"
								<?php 
									if ( ! empty( $wi_shortcode_contrast_color ) ) {
										echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
									}
								?>
							>
								<span><?php echo wp_kses_post( $webinar_data->{$label_setting} ); ?></span>
								<?php if ( $is_required ) : ?>
									<span aria-hidden="true">*</span>
								<?php endif; ?>
							</label>




							<select
								id="optCustom_<?php echo absint($index); ?>"
								name="optCustom_<?php echo absint($index); ?>"
								class="wiRegForm wi_optCustom_<?php echo absint($index); ?> wiFormControl<?php echo $is_required ? ' required' : ''; ?>"
								<?php echo $is_required ? 'required aria-required="true"' : ''; ?>
								
							>
								<option value="">
									<?php echo esc_html__('Please select an option', 'webinar-ignition'); ?>
								</option>
								<?php
								foreach ($options_array as $option) {
									$option_array = explode('::', $option);

									if (count($option_array) === 1) {
										$value = trim($option_array[0]);
										$label = trim($option_array[0]);
									} else {
										$value = trim($option_array[0]);
										$label = trim($option_array[1]);
									}
									?>
									<option value="<?php echo esc_attr($value); ?>">
										<?php echo wp_kses_post($label); ?>
									</option>
									<?php
								}
								?>
							</select>

							<span id="custom<?php echo absint($index); ?>Error" class="wi-error-message" role="alert">
								<?php esc_html_e('Field required', 'webinar-ignition'); ?>
							</span>
						</div>

						<?php
					}
					break;

				case 'ar_privacy_policy':
					$is_required = isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( $_field, $webinar_data->ar_required_fields );
					webinarignition_showGDPRHeading( $webinar_data, $wi_shortcode_contrast_color  );
					?>
					<div class="gdprConsentField gdpr-pp wiFormCheckbox wiFormCheckbox-sm">
						<input 
							type="checkbox" 
							name="optGDPR_PP" 
							id="gdpr-pp"
							class="<?php echo $is_required ? 'required' : ''; ?>" 
							<?php echo $is_required ? 'required aria-required="true"' : ''; ?>
							
							style="margin:4px 0 -5px;"
						>
						<label 
							for="gdpr-pp"
							<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'class="wi-shortcode-color" style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>

							<?php 
									$privacy_html = ! empty( $webinar_data->lp_optin_privacy_policy ) 
										? wp_kses_post( $webinar_data->lp_optin_privacy_policy ) 
										: esc_html__( 'I have read and understood the Privacy Policy', 'webinar-ignition' ); 

									// Privacy HTML
									if ( function_exists( 'wi_add_color_style_to_a_tag' ) ) {
										echo wp_kses_post(wi_add_color_style_to_a_tag( $privacy_html, $wi_shortcode_contrast_color_code_only ));
									} else {
										echo wp_kses_post($privacy_html);
									}								?>

							<?php if ( $is_required ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>

						<span id="gdprPPError" class="wi-error-message" role="alert">
							<?php esc_html_e( 'You must agree to the Privacy Policy to proceed', 'webinar-ignition' ); ?>
						</span>
					</div>

					<?php
					break;
				case 'ar_terms_and_conditions':
					$is_required = isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( $_field, $webinar_data->ar_required_fields, true );
					webinarignition_showGDPRHeading( $webinar_data , $wi_shortcode_contrast_color );
					?>
					
					<div class="gdprConsentField gdpr-tc wiFormCheckbox wiFormCheckbox-sm a <?php if ( ! empty( $wi_shortcode_contrast_color ) ) { echo 'wi-shortcode-color'; }?>">


					<?php // echo esc_attr( $checkbox_style ); ?>
						<?php
							// $checkbox_style = 'margin:4px 0 -5px;';
							
							?>

							<input 
								type="checkbox" 
								name="optGDPR_TC" 
								id="gdpr-tc"
								class="<?php echo $is_required ? 'required' : ''; ?>" 
								<?php echo $is_required ? 'required aria-required="true"' : ''; ?>
								style="accent-color: blue !important; appearance: auto !important;"
							>

						
							<label 
								for="gdpr-tc"
								<?php 
									if ( ! empty( $wi_shortcode_contrast_color ) ) {
										echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
									}
								?>
							>


							<?php 
									$terms_html = ! empty( $webinar_data->lp_optin_terms_and_conditions ) 
										? wp_kses_post( $webinar_data->lp_optin_terms_and_conditions ) 
										: esc_html__( 'I accept the Terms & Conditions', 'webinar-ignition' ); 

									if ( function_exists( 'wi_add_color_style_to_a_tag' ) ) {
										echo wp_kses_post(wi_add_color_style_to_a_tag( $terms_html, $wi_shortcode_contrast_color_code_only ));
									} else {
										echo wp_kses_post($terms_html);
									}

								?>

							<?php if ( $is_required ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>

						<span id="gdprTCError" class="wi-error-message" role="alert">
							<?php esc_html_e( 'You must accept the Terms & Conditions to proceed', 'webinar-ignition' ); ?>
						</span>
					</div>

					<?php
					break;
				case 'ar_mailing_list':
					$is_required = isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( $_field, $webinar_data->ar_required_fields, true );
					webinarignition_showGDPRHeading( $webinar_data, $wi_shortcode_contrast_color);
					?>
					

					<div class="gdprConsentField gdpr-ml wiFormCheckbox wiFormCheckbox-sm">
						<input 
							type="checkbox" 
							name="optGDPR_ML" 
							id="gdpr-ml"
							class="<?php echo $is_required ? 'required' : ''; ?>" 
							<?php echo $is_required ? 'required aria-required="true"' : ''; ?>
							
							style="margin:4px 0 -5px;"
						>
						<label 
						for="gdpr-ml"
						<?php 
								if ( ! empty( $wi_shortcode_contrast_color ) ) {
									echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
								}
							?>
						>
							<?php 
							echo ! empty( $webinar_data->lp_optin_mailing_list ) 
								? wp_kses_post( $webinar_data->lp_optin_mailing_list ) 
								: esc_html__( 'I want to be added to your mailing list', 'webinar-ignition' ); 
							?>
							<?php if ( $is_required ) : ?>
								<span aria-hidden="true">*</span>
							<?php endif; ?>
						</label>
						<span id="gdprMLError" class="wi-error-message" role="alert">
							<?php esc_html_e( 'You must accept to join the mailing list to proceed', 'webinar-ignition' ); ?>
						</span>
					</div>



					<?php
					break;

            default:
                break;
        }
    }

    webinarignition_closeGDPRSection();
}

// close the final row if still open
// if ($rowOpen) {
//     echo '</div>';
// }
if ( empty( $webinar_data->lp_optin_button ) || 'color' === trim( $webinar_data->lp_optin_button ) ) {
	switch_to_locale( $webinar_data->webinar_lang );
	unload_textdomain( 'webinar-ignition' );
	load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
	$btn_color = isset($webinar_data->lp_optin_btn_color) && $webinar_data->lp_optin_btn_color !== '' ? $webinar_data->lp_optin_btn_color : '#74BB00';
	$color_array = webinarignition_btn_color($btn_color);
	$hover_color = $color_array['hover_color'];
	$text_color = $color_array['text_color'];
    ?>
	<button href="#" id="optinBTN" class="large button wiButton wiButton-block wiButton-lg addedArrow"
    style="background-color: <?php echo esc_attr($btn_color); ?> !important; color: <?php echo esc_attr($text_color); ?>;"
    onmouseover="this.style.backgroundColor='<?php echo esc_attr($hover_color); ?>'"
    onmouseout="this.style.backgroundColor='<?php echo esc_attr($btn_color); ?>' !important;">
    <span id="optinBTNText">
        <?php echo isset($webinar_data->lp_optin_btn) ? wp_kses_post(webinarignition_display( $webinar_data->lp_optin_btn, esc_html( __( 'Register For The Webinar', 'webinar-ignition' ) ) )) : esc_html( __( 'Register For The Webinar', 'webinar-ignition' ) ); ?>
    </span>
    <span id="optinBTNLoading" style="display: none;">
        <img src="<?php echo esc_url( WEBINARIGNITION_URL . 'inc/lp/images/loading_dots_cropped_small.gif' ); ?>" style="width: auto; height: 20px;"/>
    </span>
</button>

    <?php
} else {
    ?>
    <a href="#" id="optinBTN" class="optinBTN optinBTNimg">
        <img src="<?php echo esc_url( $webinar_data->lp_optin_btn_image ); ?>" width="327" border="0"/>
    </a>
    <?php
}
?>
<div 
	class="spam wiSpamMessage"
	<?php 
		// Apply inline contrast color if available
		if ( ! empty( $wi_shortcode_contrast_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
		}
	?>
>
	<?php 
		echo isset( $webinar_data->lp_optin_spam ) 
			? wp_kses_post( 
				webinarignition_display( 
					$webinar_data->lp_optin_spam, 
					esc_html__( 'Your data is safe with us', 'webinar-ignition' ) 
				) 
			) 
			: esc_html__( '* Your data is safe with us *', 'webinar-ignition' ); 
	?>
</div>

<?php if ( get_option( 'webinarignition_show_footer_branding' ) ) { ?>
    <div 
	<?php 
		// Apply inline contrast color if available
		if ( ! empty( $wi_shortcode_contrast_color ) ) {
			echo 'style="' . esc_attr( $wi_shortcode_contrast_color ) . '"';
		}
	?>
	class="powered_by_text_wrap">
	<a href="<?php echo esc_url( get_option( 'webinarignition_affiliate_link' ) ); ?>"  target="_blank"><b><?php echo esc_html( get_option( 'webinarignition_branding_copy' ) ); ?></b></a> 
</div>
<?php } 
		restore_previous_locale();

?>
</div>
