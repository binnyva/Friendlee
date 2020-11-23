<?php
require '../common.php';
include_once '../includes/google_config.php';

if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $gClient->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($gClient);
    $google_account_info = $google_oauth->userinfo->get();
    $user_data = $user->oAuthCheckUser($google_account_info);

    if(empty($user_data)){
        iframe\App::showMessage("Error logging in...", "user/login.php", "error");
    } else {
        $user->rememberLogin($user_data['id']);
        iframe\App::showMessage("Welcome back, $user_data[name]", "index.php", "success");
    }
} else {
    iframe\App::showMessage("Error logging in...", "user/login.php", "error");
}
