<?php
include("../common.php");


if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'Login') {
	if($user->login($QUERY['username'], $QUERY['password'], $QUERY['remember'])) {
		//Successful login.
		iframe\App::showMessage("Welcome back, $_SESSION[user_name]", "index.php", "success");
	}
} else {
	include_once '../includes/google_config.php';

	$auth_url = $gClient->createAuthUrl();
	$login_button = '<a href="'.filter_var($auth_url, FILTER_SANITIZE_URL).'">'
					. '<img src="' . $config['app_url'] . 'assets/images/google/login.png" width="300" alt="Login Using Google" /></a>';
}

render();
