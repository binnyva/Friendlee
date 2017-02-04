<?php
include("../common.php");

if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'Login') {
	if($user->login($QUERY['username'], $QUERY['password'], $QUERY['remember'])) {
		//Successful login.
		showMessage("Welcome back, $_SESSION[user_name]", "index.php", "success");
	}
} else {
	include_once '../includes/vendor/google/gpConfig.php';

	$auth_url = $gClient->createAuthUrl();
	$login_button = '<a href="'.filter_var($auth_url, FILTER_SANITIZE_URL).'"><img src="' . $config['site_url'] . 'images/google/login.png" width="300" alt="Login Using Google" /></a>';
}
render();
