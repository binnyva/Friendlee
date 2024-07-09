<?php
require('includes/backward-compatible.php');
error_reporting(E_ALL ^ E_DEPRECATED);
require_once 'vendor/autoload.php';

$app = new iframe\App();

$points = array(
	'met'		=> 10,
	'phone'		=> 5,
	'message'	=> 3,
	'chat'		=> 2,
	'email'		=> 1,
	'other'		=> 1
);

$all_interation_types = array_keys($points);
