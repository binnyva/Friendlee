<?php
require('/var/www/html/iframe/common.php');

$_SESSION['user_id'] = 1;
$t_level = new DBTable('Level');
$t_activity = new DBTable('Activity');
$t_connection = new DBTable('Connection');
$t_personconnection = new DBTable('PersonConnection');
$t_note = new DBTable('Note');
$t_person = new DBTable('Person');
