<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! session_id() ) {
	session_start();
}

// Application Configurations
$app_id     = trim( $webinar_data->fb_id );
$app_secret = trim( $webinar_data->fb_secret );
$site_url   = get_permalink();

if ( ! empty( $app_id ) && ! empty( $app_secret ) ) {

	$fb = new Facebook\Facebook(
		array(
			'app_id'                => $app_id,
			'app_secret'            => $app_secret,
			'default_graph_version' => 'v2.5',
		)
	);

	$helper      = $fb->getRedirectLoginHelper();
	$accessToken = $helper->getAccessToken();

	if ( isset( $accessToken ) ) {

		$fb->setDefaultAccessToken( $accessToken );
		try {
			$response = $fb->get( '/me?fields=id,name,email' );
			$userNode = $response->getGraphUser();
		} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
			exit;
		} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
			exit;
		}

		$user_info          = array();
		$user_info['name']  = $userNode->getName();
		$user_info['email'] = $userNode->getEmail();
	} else {
		$permissions = array( 'email' );
		$loginUrl    = $helper->getLoginUrl( $site_url, $permissions );
	}//end if
}//end if
