<?php
include("../common.php");
$html = new HTML;

$current_action = 'register';

if(isset($QUERY['username'])) {
	if(i($QUERY,'action') == 'Register') {
		if($user->register($QUERY['username'], $QUERY['password'], $QUERY['name'], $QUERY['email'])) {
			showMessage("Welcome to $config[site_title], $QUERY[name]!", "index.php");
		}
	}
}

render();
