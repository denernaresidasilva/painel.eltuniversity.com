<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="listapps" class="dashList wi-list-apps">

	<div id="appHeader" class="dashHeaderListing">
		<span><i class="icon-dashboard" style="margin-right: 5px;"></i><?php esc_html_e( 'Manage All Of Your Webinars', 'webinar-ignition' ); ?>:</span>
	</div>


	<div class="wi-webinar-wrap">
		<?php

		// Display Apps:
		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition';
		$query = "SELECT * FROM $table_db_name ORDER BY ID DESC";
		$results = $wpdb->get_results( "SELECT * FROM {$table_db_name} ORDER BY ID DESC", OBJECT );
		foreach ( $results as $results ) {
			// Get Date // Date
			$ID           = $results->ID;
			$results2     = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
			$webinar_icon = isset( $results2->webinar_date ) && 'AUTO' === $results2->webinar_date ? 'refresh' : 'microphone';
			$webinar_url  = add_query_arg(
				array(
					'page' => 'webinarignition-dashboard',
					'id'   => $ID,
				),
				admin_url( 'admin.php' )
			);
			?>
			<div class="wi-webinar editableSectionHeading editableSectionHeadingDASH" webinarID="<?php echo absint( $results->ID ); ?>" editsection="we_edit_webinar_settings" style="margin-right: 0px; margin-left: 0px;">

				<a href="<?php echo esc_url( $webinar_url ); ?>">
					<div class="webinar-icon-title-wrap">
						<span class="editableSectionIcon">
							<i class="icon-<?php echo esc_attr( $webinar_icon ); ?> icon-2x"></i>
						</span>

						<span class="editableSectionTitle editableSectionTitleDash ">
							<span>
								<span class="editableSectionWebinarTitle" title="<?php echo esc_attr( $results->appname ); ?>"><?php echo esc_attr( $results->appname ); ?></span>
								<span class="editableSectionTitleSmall"><strong><?php esc_html_e( 'Created', 'webinar-ignition' ); ?>:</strong> <?php echo esc_html( $results->created ); ?></span>
								<?php
									require_once ABSPATH . 'wp-admin/includes/translation-install.php';
									// Get all available translations
									$translations = wp_get_available_translations();

									// Default language code
									$language_code = 'en_US';

									// Check if $results2 is an object and has the property
									if ( is_object( $results2 ) && isset( $results2->webinar_lang ) ) {
										$language_code = $results2->webinar_lang;
									}
									if ( 'en_US' === $language_code ) {
										$language_name = 'English';
									} else {
										// Otherwise, check if the language code exists in the translations array
										$language_name = isset( $translations[ $language_code ] ) ? $translations[ $language_code ]['native_name'] : $language_code; // Fallback to code if not found
									}

									?>
								<span class="editableSectionTitleSmall"><strong><?php esc_html_e( 'Language', 'webinar-ignition' ); ?>:</strong> <?php echo esc_html( $language_name ); ?></span>
							</span>

							<span class="appedit">
								<?php
								// Get Total Leads
								if ( isset( $results2->webinar_date ) && 'AUTO' === $results2->webinar_date ) {
									$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
									// Sanitize input values
									$ID = intval( $ID ); // Ensure $ID is an integer

									// Prepare and execute the query
									$leads = $wpdb->get_results(
										$wpdb->prepare(
											"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d",
											$ID
										),
										OBJECT
									);
								} else {
									$table_db_name = $wpdb->prefix . 'webinarignition_leads';
									// Sanitize input values
									$ID = intval( $ID ); // Ensure $ID is an integer

									// Prepare and execute the query
									$leads = $wpdb->get_results(
										$wpdb->prepare(
											"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d",
											$ID
										),
										OBJECT
									);
								}//end if

								$totalLeads = count( $leads );
								$totalLeads = number_format( $totalLeads );

								?>
								<?php
								if ( isset( $results2->webinar_date ) && 'AUTO' === $results2->webinar_date ) {
									// Auto Webinar
									?>
									<span class="ctrl" style="margin-right: 6px;">EVERGREEN</span>
									<?php
								} else {
									// Live Webinar								
									?>
									<span class="ctrl" style="margin-right: 6px;"><span style="font-weight:normal;"><?php esc_html_e('Webinar Date', 'webinar-ignition') ?>:</span> <?php echo esc_html(webinarignition_get_localized_date($results2)); ?></span>
									<?php
								}
								?>
								<span class="ctrl" style="margin-right: 6px;"><span style="font-weight:normal;"><?php esc_html_e( 'Total Registrants', 'webinar-ignition' ); ?>:</span> <?php echo esc_html( $totalLeads ); ?></span>
							</span>
						</span>
					</div>

					<span class="editableSectionToggle">
						<?php if ( ! empty( $results2->webinar_status ) && ( 'draft' === $results2->webinar_status ) ) : ?>
							<i class="toggleIcon icon-edit-sign icon-2x" title="<?php esc_html_e( 'Draft', 'webinar-ignition' ); ?>"></i>
						<?php else : ?>
							<i class="toggleIcon icon-edit-sign icon-2x published" title="<?php esc_html_e( 'Published', 'webinar-ignition' ); ?>"></i>
						<?php endif; ?>
					</span>


				</a>
			</div>
			<?php
		}//end foreach
		?>
	</div>

	<div class="appnew">
		<a href="<?php echo esc_url( admin_url( '?page=webinarignition-dashboard&create' ) ); ?>">
			<div class="blue-btn-2 btn newWebinarBTN wi-btn-new-webinar">
					<i class="icon-plus-sign" style="margin-right: 5px;"></i>
					<?php esc_html_e( 'Create a New Webinar', 'webinar-ignition' ); ?>
			</div>
		</a>
		<br clear="right">
	</div>
</div>

<br clear="left">