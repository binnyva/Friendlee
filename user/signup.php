<?php
include("../common.php");
$html = new \iframe\HTML\HTML;

$current_action = 'register';

if(isset($QUERY['username'])) {
	if(i($QUERY,'action') == 'Register') {
		if($user->register($QUERY['username'], $QUERY['password'], $QUERY['name'], $QUERY['email'])) {
			showMessage("Welcome to $config[site_title], $QUERY[name]!", "index.php");
		}
	}
}

include_once '../includes/google_config.php';
$auth_url = $gClient->createAuthUrl();
$login_button = '<a href="'.filter_var($auth_url, FILTER_SANITIZE_URL).'">'
				. '<img src="' . $config['app_url'] . 'assets/images/google/login.png" width="300" alt="Login Using Google" /></a>';

render();
