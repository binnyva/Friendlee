<?php
require '../common.php';
include_once '../includes/vendor/google/gpConfig.php';

if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	//Get user profile data from google
	$user_profile = $google_oauthV2->userinfo->get();
    $user_data = $user->oAuthCheckUser($user_profile);
	
    if(empty($user_data)){
        showMessage("Error logging in...", "user/login.php", "error");
    } else {
        showMessage("Welcome back, $_SESSION[user_name]", "index.php", "success");
    }

} else {
   showMessage("Error logging in...", "user/login.php", "error");
}
