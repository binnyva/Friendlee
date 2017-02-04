<?php
//Include Google client library 
include_once dirname(__FILE__) . '/Google_Client.php';
include_once dirname(__FILE__) . '/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '549982652421-thoc3t8t9108dqf2bj0lpugt6cg3topv.apps.googleusercontent.com'; //Google client ID
$clientSecret = '1gEGCTfN1Mp1U2-v_TvshRJn'; //Google client secret
$redirectURL = $config['site_url'] . 'user/oauth_callback.php'; //Callback URL
// $redirectURL = 'http://apps.binnyva.com/friendlee/user/oauth_callback.php';

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Friendlee');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
