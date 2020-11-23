<?php
include("../common.php");
include_once '../includes/google_config.php';

$user->logout();

if (isset($_SESSION['token'])) {
	//Unset token and user data from session
	unset($_SESSION['token']);
	unset($_SESSION['userData']);

	//Reset OAuth access token
	$gClient->revokeToken();
}

iframe\App::showMessage("User logged out.", "user/login.php");
