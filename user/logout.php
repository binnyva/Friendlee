<?php
include("../common.php");
include_once '../includes/vendor/google/gpConfig.php';

$user->logout();

if (isset($_SESSION['token'])) {
	//Unset token and user data from session
	unset($_SESSION['token']);
	unset($_SESSION['userData']);

	//Reset OAuth access token
	$gClient->revokeToken();
}

showMessage("User logged out.", "user/login.php");
