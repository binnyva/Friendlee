<?php
require('/var/www/html/iframe/common.php');

$_SESSION['user_id'] = 1;
$t_level = new DBTable('Level');
$t_activity = new DBTable('Activity');
$t_connection = new DBTable('Connection');
$t_personconnection = new DBTable('PersonConnection');
$t_note = new DBTable('Note');
$t_person = new DBTable('Person');

$points = array(
	'met'		=> 10,
	'phone'		=> 5,
	'message'	=> 3,
	'chat'		=> 2
);

$all_interation_types = array('met','phone','message','chat');