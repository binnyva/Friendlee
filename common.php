<?php
require('/mnt/x/Data/www/iframe2/iframe-skeleton/common.php');
require('/mnt/x/Data/www/iframe2/iframe-skeleton/includes/backward-compatible.php');

$points = array(
	'met'		=> 10,
	'phone'		=> 5,
	'message'	=> 3,
	'chat'		=> 2,
	'email'		=> 1,
	'other'		=> 1
);

$all_interation_types = array_keys($points);
