<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
?>
<div id="listapps" class="createWrapper wi-create-wrapper">

	<div id="appHeader" class="dashHeaderListing" style="display: none;">
		<span><i class="icon-edit" style="margin-right: 5px;"></i> <?php 
esc_html_e( 'Create a New LIVE Webinar', 'webinar-ignition' );
?>:</span>
	</div>

	<div id="formArea" class="createWebinar wi-create-webinar">

		<div class="weCreateLeft wi-create-left">









			<div class="weCreateExtraSettings wi-create-extra-settings">


				<div class="weCreateTitle wi-create-title-wrap">

					<div class="weCreateTitleIcon">
						<!-- <i class="icon-arrow-right icon-3x weCreateTitleIconI"></i> -->
						<i class="icon-share-sign icon-3x"></i>
					</div>
					<div class="weCreateTitleCopy">
						<span class="weCreateTitleHeadline"><?php 
esc_html_e( 'Create New Webinar', 'webinar-ignition' );
?></span>
						<span class="weCreateTitleSubHeadline wi-create-title-sub-headline"><?php 
esc_html_e( 'Here you can set up a new webinar...', 'webinar-ignition' );
?></span>
					</div>


					<br clear="both" />


				</div>

				<div class="wi-create-left-form-wrap">



					<!-- <span style="font-weight:normal;"><?php 
esc_html_e( 'Select the webinar type...', 'webinar-ignition' );
?></span> -->
					<div class="wi-input-wrap">
						<div class="createTitleCopy1 flex items-center">
							
						

							<svg fill="currentColor"  height="16px" width="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L384 512C419.3 512 448 483.3 448 448L448 192C448 156.7 419.3 128 384 128L128 128zM496 400L569.5 458.8C573.7 462.2 578.9 464 584.3 464C597.4 464 608 453.4 608 440.3L608 199.7C608 186.6 597.4 176 584.3 176C578.9 176 573.7 177.8 569.5 181.2L496 240L496 400z"/></svg>


							
							<span><?php 
echo esc_attr__( 'Type', 'webinar-ignition' );
?></span>
							
						 </div>
						<select class="inputField inputFieldDash2" name="cloneapp" id="cloneapp" autocomplete="off">
							<optgroup label="<?php 
echo esc_attr__( 'Create New', 'webinar-ignition' );
?>">
								<option value="auto"><?php 
echo esc_html__( 'Evergreen', 'webinar-ignition' );
?></option>
								<option value="new"><?php 
echo esc_html__( 'Live', 'webinar-ignition' );
?></option>
								<option value="import"><?php 
echo esc_html__( 'Import', 'webinar-ignition' );
?></option>
							</optgroup>
							<optgroup label="<?php 
echo esc_attr__( 'copy', 'webinar-ignition' );
?>">
								<?php 
global $wpdb;
$table_db_name = $wpdb->prefix . 'webinarignition';
$templates = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name}" ), ARRAY_A );
foreach ( $templates as $template ) {
    $name = stripslashes( $template['appname'] );
    $id = stripslashes( $template['ID'] );
    $webinar_data_to_clone = WebinarignitionManager::webinarignition_get_webinar_data( $id );
    $webinar_to_clone_type = ( isset( $webinar_data_to_clone->webinar_date ) && $webinar_data_to_clone->webinar_date === 'AUTO' ? 'AUTO' : 'live' );
    echo "<option value='" . esc_attr( $id ) . "'>" . esc_html( $name ) . "</option>";
}
$current_user = wp_get_current_user();
$current_user_first_name = ( $current_user->user_firstname ? $current_user->user_firstname : '' );
$current_user_last_name = ( $current_user->user_lastname ? $current_user->user_lastname : '' );
$current_user_name = ( $current_user->user_firstname && $current_user->user_lastname ? $current_user_first_name . ' ' . $current_user_last_name : $current_user->display_name );
$tzstring = get_option( 'timezone_string' );
$date_formats = array(
    __( 'F j, Y', 'webinar-ignition' ),
    'Y-m-d',
    'm/d/Y',
    'd/m/Y'
);
$default_date_format = $date_formats[0];
$time_formats = array('g:i a', 'g:i A', 'H:i');
$time_format = $time_formats[0];
?>
							</optgroup>
						</select>
						<p class="createTitleCopy2"><?php 
esc_html_e( 'You can create a live webinar, an automated webinar or a copy of a webinar.', 'webinar-ignition' );
?></p>
					</div>


					<!-- <span style="font-weight:normal;"><?php 
// echo esc_html__('Give your new webinar a name / pretty url...','webinar-ignition');
?></span> -->

					<div class="wi-input-wrap">
						<div class="createTitleCopy1 flex gap-1 items-center">

						<svg fill="currentColor"  height="16px" width="16px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160C544 124.7 515.3 96 480 96L160 96zM421.8 203.7L436.2 218.1C451.8 233.7 451.8 259 436.2 274.7L412.4 298.5L341.4 227.5L365.2 203.7C380.8 188.1 406.1 188.1 421.8 203.7zM215.9 353L307.4 261.4L378.4 332.4L286.8 423.9C282.7 428 277.6 430.9 271.9 432.3L211.8 447.3C206.3 448.7 200.6 447.1 196.6 443.1C192.6 439.1 191 433.4 192.4 427.9L207.4 367.8C208.8 362.2 211.7 357 215.8 352.9z"/></svg>

							<span>
								<?php 
echo esc_html__( 'Name', 'webinar-ignition' );
?> * </span>
						</div>
						<input class="inputField inputFieldDash2" placeholder="<?php 
echo esc_attr__( 'Enter webinar name', 'webinar-ignition' );
?>" type="text" name="appname" id="appname" value="">

						<p class="createTitleCopy2"><?php 
echo esc_html__( 'Name will be also used for pretty url. ie:', 'webinar-ignition' );
?> <b><?php 
echo esc_html__( 'http://yoursite.com/webinar-name', 'webinar-ignition' );
?></b></p>
					</div>

					<div class="wi-input-wrap">

						<div id="webinar_language" style="margin-bottom: 16px;">

							<div class="flex justify-between items-center">
								<p class="createTitleCopy1 flex items-center">

									<svg  width="16px" height="16px" fill="currentColor"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M192 64C209.7 64 224 78.3 224 96L224 128L352 128C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192L342.4 192L334 215.1C317.6 260.3 292.9 301.6 261.8 337.1C276 345.9 290.8 353.7 306.2 360.6L356.6 383L418.8 243C423.9 231.4 435.4 224 448 224C460.6 224 472.1 231.4 477.2 243L605.2 531C612.4 547.2 605.1 566.1 589 573.2C572.9 580.3 553.9 573.1 546.8 557L526.8 512L369.3 512L349.3 557C342.1 573.2 323.2 580.4 307.1 573.2C291 566 283.7 547.1 290.9 531L330.7 441.5L280.3 419.1C257.3 408.9 235.3 396.7 214.5 382.7C193.2 399.9 169.9 414.9 145 427.4L110.3 444.6C94.5 452.5 75.3 446.1 67.4 430.3C59.5 414.5 65.9 395.3 81.7 387.4L116.2 370.1C132.5 361.9 148 352.4 162.6 341.8C148.8 329.1 135.8 315.4 123.7 300.9L113.6 288.7C102.3 275.1 104.1 254.9 117.7 243.6C131.3 232.3 151.5 234.1 162.8 247.7L173 259.9C184.5 273.8 197.1 286.7 210.4 298.6C237.9 268.2 259.6 232.5 273.9 193.2L274.4 192L64.1 192C46.3 192 32 177.7 32 160C32 142.3 46.3 128 64 128L160 128L160 96C160 78.3 174.3 64 192 64zM448 334.8L397.7 448L498.3 448L448 334.8z"/></svg>


									<span> <?php 
echo esc_html__( 'Language', 'webinar-ignition' );
?></span>
								</p>

								<div class=" wi-help-icon-wrap relative">


								<svg fill="currentColor" width="16px" height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 461.4 178.6 576 320 576zM288 224C288 206.3 302.3 192 320 192C337.7 192 352 206.3 352 224C352 241.7 337.7 256 320 256C302.3 256 288 241.7 288 224zM280 288L328 288C341.3 288 352 298.7 352 312L352 400L360 400C373.3 400 384 410.7 384 424C384 437.3 373.3 448 360 448L280 448C266.7 448 256 437.3 256 424C256 410.7 266.7 400 280 400L304 400L304 336L280 336C266.7 336 256 325.3 256 312C256 298.7 266.7 288 280 288z"/></svg>


									<div class="wi-help-content absolute">


										<p class="createTitleCopy2"><a title="<?php 
esc_attr_e( 'Want to overwrite language strings?', 'webinar-ignition' );
?>" href="https://webinarignition.tawk.help/article/overwrite-update_webinar-strings-texts" target="_blank"><?php 
esc_html_e( 'Want to overwrite language strings?', 'webinar-ignition' );
?></a></p>



										<p class="createTitleCopy2"><a title="<?php 
esc_attr_e( 'Want to add a language?', 'webinar-ignition' );
?>" href="https://webinarignition.tawk.help/article/add-language-to-webinarignition" target="_blank"><?php 
esc_html_e( 'Want to add a language?', 'webinar-ignition' );
?></a></p>
									</div>
								</div>

							</div>



							<?php 
$site_url = get_site_url();
$statusCheck = new stdClass();
$statusCheck->switch = 'free';
$statusCheck->slug = 'free';
$statusCheck->licensor = '';
$statusCheck->is_free = 1;
$statusCheck->is_premium = '';
$statusCheck->is_dev = '';
$statusCheck->is_registered = '';
$statusCheck->title = 'Free';
$statusCheck->name = '';
$show_all_live_languages = true;
$show_all_eg_languages = true;
?>
							<input type="hidden" id="wi_segl" name="wi_segl" value="<?php 
esc_attr( ( $show_all_eg_languages ? 1 : 0 ) );
?>" disabled />
							<input type="hidden" id="wi_sll" name="wi_sll" value="<?php 
esc_attr( ( $show_all_live_languages ? 1 : 0 ) );
?>" disabled />

							<?php 
require_once ABSPATH . 'wp-admin/includes/translation-install.php';
$translations = wp_get_available_translations();
$available_languages = webinarignition_get_available_languages();
// Remove the prefixes from each element
$available_languages = array_map( function ( $value ) {
    return str_replace( array('webinarignition-', 'webinar-ignition-'), '', $value );
}, $available_languages );
$available_languages = array_merge( array('en_US'), $available_languages );
$translations = array_merge( array(
    'en_US' => array(
        'native_name' => __( 'English', 'webinar-ignition' ),
    ),
), $translations );
$selected_language = get_locale();
?>
							<select class="inputField inputFieldDash2" id="applang" name="applang" autocomplete="off">
								<?php 
$all_wp_languages = array_keys( wp_get_available_translations() );
?>


								<!-- Fully Translated Section -->
								<optgroup label="Fully Translated">
									<?php 
foreach ( $available_languages as $language ) {
    ?>
										<option value="<?php 
    echo esc_attr( $language );
    ?>" <?php 
    selected( $selected_language, $language, true );
    ?>>
											<?php 
    echo ( isset( $translations[$language] ) ? esc_html( $translations[$language]['native_name'] ) : esc_html( $language ) );
    ?>
										</option>
									<?php 
}
?>
								</optgroup>

								<!-- Date & Time Only Section -->
								<optgroup label="Date & Time Only">
									<?php 
foreach ( $all_wp_languages as $language ) {
    if ( !in_array( $language, $available_languages ) ) {
        ?>
											<option value="<?php 
        echo esc_attr( $language );
        ?>" <?php 
        selected( $selected_language, $language, true );
        ?>>
												<?php 
        echo ( isset( $translations[esc_html( $language )] ) ? esc_html( $translations[esc_html( $language )]['native_name'] ) : esc_html( $language ) );
        ?>
											</option>
									<?php 
    }
}
?>
								</optgroup>
							</select>
							<span id="wi_new_webinar_lang_select" class="spinner wi-spinner"></span>
							<p class="createTitleCopy2"><?php 
esc_html_e( 'You can choose your preferred language for internationalization.', 'webinar-ignition' );
?></p>

						</div>




						<div class="wi-input-wrap">
							<div class="createTitleCopy1 flex items-center">

								<svg width="16px" height="16px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M259.1 73.5C262.1 58.7 275.2 48 290.4 48L350.2 48C365.4 48 378.5 58.7 381.5 73.5L396 143.5C410.1 149.5 423.3 157.2 435.3 166.3L503.1 143.8C517.5 139 533.3 145 540.9 158.2L570.8 210C578.4 223.2 575.7 239.8 564.3 249.9L511 297.3C511.9 304.7 512.3 312.3 512.3 320C512.3 327.7 511.8 335.3 511 342.7L564.4 390.2C575.8 400.3 578.4 417 570.9 430.1L541 481.9C533.4 495 517.6 501.1 503.2 496.3L435.4 473.8C423.3 482.9 410.1 490.5 396.1 496.6L381.7 566.5C378.6 581.4 365.5 592 350.4 592L290.6 592C275.4 592 262.3 581.3 259.3 566.5L244.9 496.6C230.8 490.6 217.7 482.9 205.6 473.8L137.5 496.3C123.1 501.1 107.3 495.1 99.7 481.9L69.8 430.1C62.2 416.9 64.9 400.3 76.3 390.2L129.7 342.7C128.8 335.3 128.4 327.7 128.4 320C128.4 312.3 128.9 304.7 129.7 297.3L76.3 249.8C64.9 239.7 62.3 223 69.8 209.9L99.7 158.1C107.3 144.9 123.1 138.9 137.5 143.7L205.3 166.2C217.4 157.1 230.6 149.5 244.6 143.4L259.1 73.5zM320.3 400C364.5 399.8 400.2 363.9 400 319.7C399.8 275.5 363.9 239.8 319.7 240C275.5 240.2 239.8 276.1 240 320.3C240.2 364.5 276.1 400.2 320.3 400z"/></svg>
								<span>
									<?php 
esc_html_e( 'Use webinar language in webinar settings?', 'webinar-ignition' );
?>
								</span>
							</div>
							<select class="inputField inputFieldDash2" id="settings_language" name="settings_language" autocomplete="off" ?>>
								<option value="no"><?php 
esc_html_e( 'No', 'webinar-ignition' );
?></option>
								<option value="yes" <?php 
selected( $selected_language === 'en_US', true, true );
?>><?php 
esc_html_e( 'Yes', 'webinar-ignition' );
?></option>
							</select>
						</div>

						<input type="hidden" name="site_default_language" id="site_default_language" value="<?php 
echo esc_attr( determine_locale() );
?>">





					</div>


					<div class="importArea" style="display:none;">
						<div class="createTitleCopy1 flex items-center">
							<svg width="16px" height="16px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M192 64C156.7 64 128 92.7 128 128L128 368L310.1 368L279.1 337C269.7 327.6 269.7 312.4 279.1 303.1C288.5 293.8 303.7 293.7 313 303.1L385 375.1C394.4 384.5 394.4 399.7 385 409L313 481C303.6 490.4 288.4 490.4 279.1 481C269.8 471.6 269.7 456.4 279.1 447.1L310.1 416.1L128 416.1L128 512.1C128 547.4 156.7 576.1 192 576.1L448 576.1C483.3 576.1 512 547.4 512 512.1L512 234.6C512 217.6 505.3 201.3 493.3 189.3L386.7 82.7C374.7 70.7 358.5 64 341.5 64L192 64zM453.5 240L360 240C346.7 240 336 229.3 336 216L336 122.5L453.5 240z"/></svg>
						<span> <?php 
esc_html_e( 'Import Campaign', 'webinar-ignition' );
?> </span>
						</div>
						<p class="createTitleCopy2"><?php 
esc_html_e( 'Paste in the export code below from another WI campaign...', 'webinar-ignition' );
?></p>
						<textarea id="importcode" style="width:100%; height: 150px;"
							placeholder="<?php 
esc_html_e( 'Add import code here...', 'webinar-ignition' );
?>"></textarea>
					</div>



					<div class="wi-date-and-time-preview-tab-wrap">
						<p class="createTitleCopy1 flex items-center">

						<svg  width="16px" height="16px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M224 64C206.3 64 192 78.3 192 96L192 128L160 128C124.7 128 96 156.7 96 192L96 240L544 240L544 192C544 156.7 515.3 128 480 128L448 128L448 96C448 78.3 433.7 64 416 64C398.3 64 384 78.3 384 96L384 128L256 128L256 96C256 78.3 241.7 64 224 64zM96 288L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 288L96 288z"/></svg>


							<span> <?php 
echo esc_html__( 'Date and Time Format', 'webinar-ignition' );
?></span>
						</p>

						<div class="wi-tabs-wrap">
							<div class="wi_tabs">
								<div class="wi_tab flex justify-between" data-tab="1">

									<div></div>
									<div class="flex flex-col">
										<span class="wi-title">Date Format </span>
										<span class="formatPreview" id="date_format_preview"><?php 
echo esc_html( date_i18n( 'D ' . $default_date_format ) );
?></span>
									</div>

									<svg width="10px" height="10px" viewBox="0 -4.5 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">


										<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<g id="Dribbble-Light-Preview" transform="translate(-180.000000, -6684.000000)" fill="#000000">
												<g id="icons" transform="translate(56.000000, 160.000000)">
													<path d="M144,6525.39 L142.594,6524 L133.987,6532.261 L133.069,6531.38 L133.074,6531.385 L125.427,6524.045 L124,6525.414 C126.113,6527.443 132.014,6533.107 133.987,6535 C135.453,6533.594 134.024,6534.965 144,6525.39" id="arrow_down-[#339]">

													</path>
												</g>
											</g>
										</g>
									</svg>

								</div>

								<div class="wi_tab flex justify-between" data-tab="2">

									<div></div>
									<div class="flex flex-col">

										<span class="wi-title">
											Time Format
										</span>
										<span class="formatPreview" id="time_format_preview"><?php 
echo esc_html( date_i18n( get_option( 'time_format' ) ) );
?></span>

									</div>

									<svg width="10px" height="10px" viewBox="0 -4.5 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

										<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<g id="Dribbble-Light-Preview" transform="translate(-180.000000, -6684.000000)" fill="#000000">
												<g id="icons" transform="translate(56.000000, 160.000000)">
													<path d="M144,6525.39 L142.594,6524 L133.987,6532.261 L133.069,6531.38 L133.074,6531.385 L125.427,6524.045 L124,6525.414 C126.113,6527.443 132.014,6533.107 133.987,6535 C135.453,6533.594 134.024,6534.965 144,6525.39" id="arrow_down-[#339]">

													</path>
												</g>
											</g>
										</g>
									</svg>
								</div>
							</div>

							<div id="wi_tab1" class="wi_tab_content">
								<div class="weDashSection locale_formats date_formats">
									<span class="weDashSectionTitle"><?php 
esc_html_e( 'Date Format', 'webinar-ignition' );
?> * <span class="weDashSectionIcon"><i class="icon-calendar"></i></span> </span>


									<div class="date-radio-wrap">

										<?php 
$date_formats = array_unique( $date_formats );
foreach ( $date_formats as $date_format ) {
    ?>
											<label id='default_date_radio_label'>
												<input type='radio' name="date_format_custom_new" value="<?php 
    echo esc_attr( $date_format );
    ?>" <?php 
    echo ( $date_format === $date_formats[0] ? 'checked' : '' );
    ?> /><span class="date-time-text format-i18n"><?php 
    echo esc_html( date_i18n( $date_format ) );
    ?></span><code><?php 
    echo esc_html( $date_format );
    ?></code>
											</label>

										<?php 
}
?>
									</div>


									<label>
										<span style="display: none;"><input type="radio" checked name="date_format" id="date_format_custom_radio" value="D <?php 
echo esc_attr( $date_formats[0] );
?>" /></span>
										<span class="date-time-text date-time-custom-text"><?php 
esc_html_e( 'Custom:', 'webinar-ignition' );
?></span>
										<input type="text" name="date_format_custom" id="date_format_custom" value="D <?php 
echo esc_attr( $date_formats[0] );
?>" class="ml-5 small-text" autocomplete="off" />
									</label>
									<input type="hidden" id="apptz" value="<?php 
echo esc_attr( wp_timezone_string() );
?>" />
									<div id="wi_show_day_wrap" style="margin-top:12px;">
										<label class="flex">
											<input name="wi_show_day" type="checkbox" checked><span style="margin-left: 15px;"><?php 
esc_html_e( 'Show Day', 'webinar-ignition' );
?></span> (<code id="wi_day_string"><?php 
echo esc_html( date_i18n( 'D' ) );
?></code>)
											<div id="wi_day_string_input" style="width: 160px; display: flex;">
												<label style="text-align: right;">
													<input type="radio" name="day_string" value="D" data-string="<?php 
echo esc_html( date_i18n( 'D' ) );
?>" checked>
													<?php 
esc_html_e( 'Short', 'webinar-ignition' );
?>
												</label>
												<label style="text-align: right;">
													<input type="radio" name="day_string" value="l" data-string="<?php 
echo esc_attr( date_i18n( 'l' ) );
?>">
													<?php 
esc_html_e( 'Long', 'webinar-ignition' );
?>
												</label>
											</div>
										</label>
										<br />
										<br />
									</div>

									<!-- <p> -->
									<!-- <strong class="preview_text"><?php 
// esc_html_e('Preview:', 'webinar-ignition');
?></strong> -->
									<!-- <span class="formatPreview" id="date_format_preview"><?php 
// echo esc_html(date_i18n('D ' . $default_date_format));
?></span> -->
									<!-- </p> -->

								</div>
								<p class="createTitleCopy2"><?php 
echo esc_html__( 'Documentation on date and time formatting', 'webinar-ignition' ) . ' <a href="' . esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' ) . '" target="_blank">' . esc_html__( 'Read more', 'webinar-ignition' ) . '</a>';
?></p>
							</div>

							<div id="wi_tab2" class="wi_tab_content">
								<div class="weDashSection locale_formats time_formats">
									<span class="weDashSectionTitle"><?php 
esc_html_e( 'Time Format', 'webinar-ignition' );
?> * <span class="weDashSectionIcon"><i class="icon-time"></i></span> </span>

									<div class="wi-create-time-format">
										<?php 
$custom = true;
$current_time_format = get_option( 'time_format' );
// Get current WP time format
$time_formats = [
    __( 'g:i a', 'webinar-ignition' ),
    // 12-hour format
    __( 'g:i A', 'webinar-ignition' ),
    // 12-hour format with uppercase AM/PM
    __( 'H:i', 'webinar-ignition' ),
];
$counter = 0;
foreach ( $time_formats as $format ) {
    // Check if this is the first iteration
    $checked = ( $counter === 0 ? 'checked' : '' );
    echo "\t<div><label id='default_time_radio_label'>\n\t\t\t\t\t\t\t\t\t<input type='radio' name='time_format' value='" . esc_html( $format ) . "' " . esc_attr( $checked ) . " />\n\t\t\t\t\t\t\t\t\t<span class='date-time-text format-i18n'>" . esc_html( date_i18n( $format ) ) . "</span>\n\t\t\t\t\t\t\t\t\t<code>" . esc_html( $format ) . "</code>\n\t\t\t\t\t\t\t\t</label></div>\n";
    $counter++;
}
// Custom time format input
echo '<label><input type="radio" name="time_format" id="time_format_custom_radio" value="g:i A" />';
echo ' <span class="date-time-text date-time-custom-text">' . esc_html__( 'Custom:', 'webinar-ignition' ) . '</span><input type="text" name="time_format_custom" id="time_format_custom" value="' . esc_attr( $time_format ) . '" class="ml-5 small-text" /></label>';
// echo '<p><strong class="preview_text">' . esc_html__('Preview:', 'webinar-ignition') . '</strong>';
// echo "\t<p class='date-time-doc'>" . esc_html__('Documentation on date and time formatting', 'webinar-ignition') . ' <a href="' . esc_url('https://wordpress.org/support/article/formatting-date-and-time/') . '" target="_blank">' . esc_html__('Read more', 'webinar-ignition') . '</a>' . "</p>\n";
?>
									</div>
								</div>
								<p class="createTitleCopy2"><?php 
echo esc_html__( 'Documentation on date and time formatting', 'webinar-ignition' ) . ' <a href="' . esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' ) . '" target="_blank">' . esc_html__( 'Read more', 'webinar-ignition' ) . '</a>';
?></p>
							</div>
						</div>

					</div>









				</div>

			</div>



		</div>

		<div class="weCreateRight wi-create-right">

			<div class="weDashRight wi-create-dash-right" style="margin-top: 0px;">

				<div class="weDashDateTitle">
					<!-- <i class="icon-ticket"></i> Webinar Event Info: -->
					<div class="dashWebinarTitleIcon"><i class="icon-ticket icon-3x"></i></div>

					<div class="dashWebinarTitleCopy">
						<h2 style="margin:0px; margin-top: 3px;"><?php 
esc_html_e( 'Webinar Event Info', 'webinar-ignition' );
?></h2>

						<p style="margin:0px; margin-top: 3px;"><?php 
esc_html_e( 'The core settings for your webinar event...', 'webinar-ignition' );
?></p>
					</div>

					<br clear="left">
				</div>

				<div class="weDashDateInner">

					<div class="weDashSection">
						<span class="weDashSectionTitle flex items-center">


						<svg height="16px" width="16px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M96.5 160L96.5 309.5C96.5 326.5 103.2 342.8 115.2 354.8L307.2 546.8C332.2 571.8 372.7 571.8 397.7 546.8L547.2 397.3C572.2 372.3 572.2 331.8 547.2 306.8L355.2 114.8C343.2 102.7 327 96 310 96L160.5 96C125.2 96 96.5 124.7 96.5 160zM208.5 176C226.2 176 240.5 190.3 240.5 208C240.5 225.7 226.2 240 208.5 240C190.8 240 176.5 225.7 176.5 208C176.5 190.3 190.8 176 208.5 176z"/></svg>


							<span><?php 
esc_html_e( 'Title', 'webinar-ignition' );
?> *</span>

						</span>

						<input type="text" class="inputField inputFieldDash elem" name="webinar_desc" id="webinar_desc" value=""
							placeholder="<?php 
esc_attr_e( 'Enter webinar title', 'webinar-ignition' );
?>">
					</div>

					<div class="weDashSection">
						<span class="weDashSectionTitle">


						<svg width="16px" height="16px" fill="currentColor"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256.1 72C322.4 72 376.1 125.7 376.1 192C376.1 258.3 322.4 312 256.1 312C189.8 312 136.1 258.3 136.1 192C136.1 125.7 189.8 72 256.1 72zM226.4 368L285.8 368C292.5 368 299 368.4 305.5 369.1C304.6 374 304.1 379 304.1 384.1L304.1 476.2C304.1 501.7 314.2 526.1 332.2 544.1L364.1 576L77.8 576C61.4 576 48.1 562.7 48.1 546.3C48.1 447.8 127.9 368 226.4 368zM352.1 476.2L352.1 384.1C352.1 366.4 366.4 352.1 384.1 352.1L476.2 352.1C488.9 352.1 501.1 357.2 510.1 366.2L606.1 462.2C624.8 480.9 624.8 511.3 606.1 530.1L530 606.2C511.3 624.9 480.9 624.9 462.1 606.2L366.1 510.2C357.1 501.2 352 489 352 476.3zM456.1 432C456.1 418.7 445.4 408 432.1 408C418.8 408 408.1 418.7 408.1 432C408.1 445.3 418.8 456 432.1 456C445.4 456 456.1 445.3 456.1 432z"/></svg>


							<span> <?php 
esc_html_e( 'Webinar Host', 'webinar-ignition' );
?> * </span>

						</span>

						<input type="text" class="inputField inputFieldDash elem" name="webinar_host" id="webinar_host" value="<?php 
echo esc_attr( $current_user_name );
?>"
							placeholder="<?php 
esc_attr_e( 'The Name Of The Host(s)', 'webinar-ignition' );
?>...">
					</div>




					<div class="weDashSection" id="createToggle1">
						<span class="weDashSectionTitle"><?php 
esc_html_e( 'Event Date', 'webinar-ignition' );
?> * <span class="weDashSectionIcon"><i class="icon-calendar"></i></span>
						</span>
						<!-- <br clear="right"> -->
						<input type="text" class="inputField inputFieldDash elem dp-date" name="webinar_date" id="webinar_date" value="<?php 
echo esc_attr( date_i18n( $default_date_format ) );
?>">
					</div>

					<div class="weDashSection" id="createToggle2">
						<span class="weDashSectionTitle"><?php 
esc_html_e( 'Event Time', 'webinar-ignition' );
?> *
							<span class="weDashSectionIcon"><i class="icon-time"></i></span>
						</span>
						<!-- <br clear="right"> -->
						<input type="text" class="timepicker inputField inputFieldDash elem" name="webinar_start_time" id="webinar_start_time" value="<?php 
echo esc_attr( date_i18n( $time_format ) );
?>" />
					</div>

					<div class="weDashSection" id="createToggle4">
						<span class="weDashSectionTitle"><?php 
esc_html_e( 'Event Duration', 'webinar-ignition' );
?> *
							<span class="weDashSectionIcon"><i class="icon-time"></i></span>
						</span>
						<!-- <br clear="right"> -->
						<input type="number" class="inputField inputFieldDash elem" name="webinar_start_duration" id="webinar_start_duration" value="60" />
					</div>

					<div class="weDashSection" id="createToggle3">
						<span class="weDashSectionTitle"><?php 
esc_html_e( 'Event Timezone', 'webinar-ignition' );
?> *
							<span class="weDashSectionIcon"><i class="icon-globe"></i></span>
						</span>
						<!-- <br clear="right"> -->
						<select name="webinar_timezone" id="webinar_timezone" class="wi_webinar_timezone  inputField inputFieldDash elem" required>
							<?php 
// Get the timezone from WordPress general settings
$timezone_string = get_option( 'timezone_string' );
$gmt_offset = get_option( 'gmt_offset' );
// Check if the timezone is a named timezone or a UTC offset
if ( !empty( $timezone_string ) ) {
    // If it's a named timezone, select it in the dropdown
    echo wp_kses( webinarignition_create_tz_select_list( $timezone_string, get_user_locale() ), array(
        'option'   => array(
            'value'    => array(),
            'selected' => array(),
        ),
        'optgroup' => array(
            'label' => array(),
        ),
    ) );
} else {
    // If it's a UTC offset, display the dropdown without selection
    echo wp_kses( webinarignition_create_tz_select_list( '', get_user_locale() ), array(
        'option'   => array(
            'value'    => array(),
            'selected' => array(),
        ),
        'optgroup' => array(
            'label' => array(),
        ),
    ) );
}
?>
						</select>
						<?php 
// If the timezone is a UTC offset, display a message under the field
if ( empty( $timezone_string ) ) {
    // echo '<p class="description">' . esc_html__('Select timezone. Your current timezone is set to a UTC offset.', 'webinar-ignition') . '</p>';
}
?>
					</div>
					<!-- <a class="wi-btn-create-webinar" id="createnewapp" href="<?php 
//  echo esc_url(admin_url('?page=webinarignition-dashboard&create'));
?>">
						<div class="blue-btn-2create btn wi-btn-create-wb" id="createnewappBTN">

							<i class="icon-plus-sign" style="margin-right: 5px;"></i>
							<?php 
// esc_html_e('Create New Webinar', 'webinar-ignition');
?>

						</div>
					</a> -->

				</div>

			</div>


			<div class="wi-create-new-webinar-btn-wrap" id="wi-create-new-webinar-btn-wrap">
				<a class="wi-btn-create-webinar" id="createnewapp" href="<?php 
echo esc_url( admin_url( '?page=webinarignition-dashboard&create' ) );
?>">
					<div class="blue-btn-2create btn wi-btn-create-wb" id="createnewappBTN">

						<i class="icon-plus-sign" style="margin-right: 2px;"></i>
						<?php 
esc_html_e( 'Create New Webinar', 'webinar-ignition' );
?>

					</div>
				</a>
			</div>
		</div>


	</div>





	<br clear="all" />


	<div class="timezoneRef wi-time-zone-ref">
		<div class="timezoneRefTitle">
			<span class="wi-title"> <?php 
esc_html_e( 'Reference', 'webinar-ignition' );
?></span>
			<span class="wi-time">
				<?php 
esc_html_e( 'Current time:', 'webinar-ignition' );
?> <span class="timezoneRefZ"></span>
			</span>
		</div>
	</div>
</div>


</div>

<br clear="left" />