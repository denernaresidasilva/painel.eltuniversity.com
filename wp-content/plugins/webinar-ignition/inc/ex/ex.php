<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$ID   = isset( $_POST['webinarignition_webinar_id'] ) ? absint( $_POST['webinarignition_webinar_id'] ) : 0;
$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
$applang = $webinar_data->webinar_lang;
$type = isset( $_POST['webinarignition_leads_type'] ) ? sanitize_text_field( $_POST['webinarignition_leads_type'] ) : '';
$field_labels = [];

foreach ( $webinar_data->ar_fields_order as $_field ) {
	switch ( $_field ) {
		case 'ar_name':
			$field_name = 'optName';
			$label = $webinar_data->lp_optin_name ?? WebinarignitionManager::webinarignition_ar_field_translated_name('optName', $applang);
			break;

		case 'ar_lname':
			$field_name = 'optLName';
			$label = WebinarignitionManager::webinarignition_ar_field_translated_name('optLName', $applang);
			break;

		case 'ar_email':
			$field_name = 'optEmail';
			$label = $webinar_data->lp_optin_email ?? WebinarignitionManager::webinarignition_ar_field_translated_name('optEmail', $applang);
			break;

		case 'ar_phone':
			$field_name = 'optPhone';
			$label = WebinarignitionManager::webinarignition_ar_field_translated_name('optPhone', $applang);
			break;
		case 'ar_salutation':
			$field_name = 'optSalutation';
			$label = 'Salutation';
			break;
		case 'ar_reason':
			$field_name = 'optReason';
			$label = 'Reason';
			break;
		case 'ar_custom_1':
		case 'ar_custom_2':
		case 'ar_custom_3':
		case 'ar_custom_4':
		case 'ar_custom_5':
		case 'ar_custom_6':
		case 'ar_custom_7':
		case 'ar_custom_15':
		case 'ar_custom_16':
		case 'ar_custom_17':
		case 'ar_custom_18':
			$index = str_replace('ar_custom_', '', $_field);
			$field_name = 'optCustom_' . $index;
			$label_property = 'lp_optin_custom_' . $index;
			$label = $webinar_data->{$label_property} ?? 'Custom Field ' . $index;
			break;
		case 'ar_privacy_policy':
			$field_name = 'optGDPR_PP';
			$label = ! empty( $webinar_data->lp_optin_privacy_policy ) ?
				$webinar_data->lp_optin_privacy_policy :
				__( 'Have read and understood our Privacy Policy', 'webinar-ignition' );
			break;
		case 'ar_terms_and_conditions':
			$field_name = 'optGDPR_TC';
			$label = ! empty( $webinar_data->lp_optin_terms_and_conditions ) ?
				$webinar_data->lp_optin_terms_and_conditions :
				__( 'Accept our Terms & Conditions', 'webinar-ignition' );
			break;

		case 'ar_mailing_list':
			$field_name = 'optGDPR_ML';
			$label = ! empty( $webinar_data->lp_optin_mailing_list ) ?
				$webinar_data->lp_optin_mailing_list :
				__( 'Want to be added to our mailing list', 'webinar-ignition' );
			break;
		default:
		
			break;
	}

	$field_labels[ $field_name ] = trim( wp_strip_all_tags( $label ) );
}

if ( empty( $ID ) || empty( $type ) ) {
	exit;
}

global $wpdb;

if ( 'evergreen_normal' === $type || 'evergreen_hot' === $type || 'evergreen' === $type ) {
	$table_db_name      = $wpdb->prefix . 'webinarignition_leads_evergreen';
	$table_meta_db_name = $wpdb->prefix . 'webinarignition_lead_evergreenmeta';
} else {
	$table_db_name      = $wpdb->prefix . 'webinarignition_leads';
	$table_meta_db_name = $wpdb->prefix . 'webinarignition_leadmeta';
}
// Sanitize input values
$ID = intval($ID); // Ensure $ID is an integer

// Prepare and execute the query
$leads_meta = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT LM.lead_id, LM.meta_value 
         FROM `{$table_meta_db_name}` LM 
         WHERE LM.meta_key = %s",
        "wiRegForm_{$ID}"
    ),
    ARRAY_A
);
$meta_fields = array();
$meta_leads = array();
if ( ! empty( $leads_meta ) ) {
	foreach ( $leads_meta as $lead_meta ) {
		$lead_meta_id = $lead_meta['lead_id'];
		$lead_meta_data = $lead_meta['meta_value'];

		if ( ! empty( $lead_meta_data ) ) {
			$lead_meta_data = maybe_unserialize( $lead_meta_data );
			$lead_meta_data = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta_data );
			if ( is_array( $lead_meta_data ) ) {
				$meta_leads[ $lead_meta_id ] = $lead_meta_data;
				if ( isset( $lead_meta_data['optName'] ) || isset( $lead_meta_data['optEmail'] ) ) {
					foreach ( $lead_meta_data as $field_name => $field ) {
						if (  is_array( $field )  ) {
							$field_label = $field['label'];
							$field_value = $field['value'];
							$meta_fields[ $field_name ] = $field_label;
						}
					}

				} else { // compatibility with old lead data
					foreach ( $lead_meta_data as $field_label => $field_value ) {
						$meta_fields[ $field_label ] = $field_label;

					}
				}
			}
		}
	}//end foreach
}//end if

if (!isset($meta_fields['webinar_timezone'])) {
    $meta_fields['webinar_timezone'] = 'webinar_timezone';
}

// Desired field order
$order = ['full_name', 'optEmail', 'optName', 'optLName', 'optPhone', 'webinar_timezone','optSalutation'];

// Reorder array
$reordered_fields = [];

// First add the desired fields in the defined order
foreach ($order as $key) {
    if (isset($meta_fields[$key])) {
        $reordered_fields[$key] = $meta_fields[$key];
        unset($meta_fields[$key]); // Remove from original to avoid duplication
    }
}

// Append any remaining fields
$meta_fields = array_merge($reordered_fields, $meta_fields);
$query_params = array( $ID );

if ( $type === 'live_hot' || $type === 'evergreen_hot' ) {
	$query_params[] = 'Yes';
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM $table_db_name WHERE app_id = %d AND event=%s', $query_params ) );

}else{
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_db_name WHERE app_id = %d", $query_params ) );
}


$export_filename = sprintf( 'webinarignition-leads-%d-%s', $ID, gmdate( 'Y-m-d_H-i-s', current_time( 'U' ) ) );

// CSV Header:
header( 'Content-type: application/text' );
header( "Content-Disposition: attachment; filename={$export_filename}.csv" );
header( 'Pragma: no-cache' );
header( 'Expires: 0' );

$headers = array(
    __( 'Registration date', 'webinar-ignition' ),
    __( 'Attended', 'webinar-ignition' ),
);
echo esc_html( implode( ',', array_map( 'esc_html', $headers ) ) );

if(empty($results)) {
	if (!isset($field_labels['webinar_timezone'])) {
		$field_labels['webinar_timezone'] = 'webinar_timezone';
	}
	if (!isset($field_labels['full_name'])) {
		$field_labels['full_name'] = 'full_name';
	}
	$reordered_fields = [];
	// First add the desired fields in the defined order
	foreach ($order as $key) {
		if (isset($field_labels[$key])) {
			$reordered_fields[$key] = $field_labels[$key];
			unset($field_labels[$key]); // Remove from original to avoid duplication
		}
	}
	// Append any remaining fields
	$field_labels = array_merge($reordered_fields, $field_labels);
	$show_label = array('optEmail', 'optName', 'optLName', 'optPhone');
	foreach($field_labels as $field_name => $label) {
		if(in_array($field_name, $show_label)) {
			echo ',';
			echo esc_html($label);
		}else{
			echo ',';
			echo esc_html($field_name);
		}
	}
}else{
	if ( ! empty( $meta_fields ) ) {
		echo ', ';
		echo esc_html( implode( ', ', array_map( 'sanitize_text_field', $meta_fields ) ) );
	}
}



echo "\n";

usort($results, function($a, $b) {
	return $b->ID <=> $a->ID;
});
foreach ( $results as $r ) {
	$lead_meta = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $r->ID, 'wiRegForm', 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' );

	if ( ! empty( $lead_meta['meta_value'] ) ) {
		$lead_meta_data = maybe_unserialize( $lead_meta['meta_value'] );
		$lead_meta_data = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta_data );
	}else{
		$lead_meta_data = array();
	}

    echo esc_html( str_replace( ',', ' -', $r->created ) );
	echo ',';
	echo  $r->lead_status  === 'no' || empty($r->lead_status) ? 'No' : 'YES';
	echo ',';
	if(in_array('full_name', array_keys($meta_fields))) {
		echo esc_html($lead_meta_data['full_name']['value'] ?? '');
	} else {
		echo esc_html($r->name);
	}
	echo ',';
	echo esc_html($r->email) ;
	echo ',';
	if(in_array('optName', array_keys($meta_fields))) {
		echo esc_html($lead_meta_data['optName']['value'] ?? '');
	} else {
		echo esc_html($r->name);
	}
	if(in_array('optLName', array_keys($meta_fields))) {
		echo ',';
		echo esc_html($lead_meta_data['optLName']['value'] ?? '');
	}
	if(in_array('optPhone', array_keys($meta_fields))) {
		echo ',';
		echo esc_html($r->phone);
	}
	echo ',';
	echo esc_html( !empty($r->lead_timezone) ? $r->lead_timezone : $r->lead_browser_and_os );
	if(in_array('optSalutation', array_keys($meta_fields))) {
		echo ',';
		echo esc_html($lead_meta_data['optSalutation']['value'] ?? '');
	}
	foreach ( $order as $key ) {
		if ( isset( $lead_meta_data[ $key ] ) ) {
			unset( $lead_meta_data[ $key ] );
		}
	}
	foreach ( $lead_meta_data as $field_name => $field ) {
		if ( is_array( $field ) && isset( $field['value'] ) ) {
			echo ',';
			echo esc_html( $field['value'] );
		} 
	}
	echo "\n";
	$lead_meta_data = array();
}