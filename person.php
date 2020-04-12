<?php
require('common.php');

$person_id = i($QUERY, 'person_id');
if(!$person_id) exit;
$person = $t_person->find($person_id);
if(!$person) die("Invalid Person ID Provided");

$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id] ORDER BY name");
$all_cities[0] = 'Unknown';
$all_levels = $sql->getById("SELECT id,name FROM Level");
$all_priorities = ['low' => 'Low', 'normal' => 'Normal', 'high' => 'High'];

$last_message	= $t_person->getLastContact($person_id, 'message');
$last_chat		= $t_person->getLastContact($person_id, 'chat');
$last_phone		= $t_person->getLastContact($person_id, 'phone');
$last_met		= $t_person->getLastContact($person_id, 'met');
$last_email		= $t_person->getLastContact($person_id, 'email');
$last_other		= $t_person->getLastContact($person_id, 'other');

$last_contact = $last_message;
if($last_chat	and (!$last_contact or @strcmp($last_contact['start_on'], $last_chat['start_on'])	< 0))	$last_contact = $last_chat;
if($last_phone	and (!$last_contact or @strcmp($last_contact['start_on'], $last_phone['start_on'])	< 0))	$last_contact = $last_phone;
if($last_met	and (!$last_contact or @strcmp($last_contact['start_on'], $last_met['start_on'])	< 0))	$last_contact = $last_met;
if($last_email	and (!$last_contact or @strcmp($last_contact['start_on'], $last_email['start_on'])	< 0))	$last_contact = $last_email;
if($last_other	and (!$last_contact or @strcmp($last_contact['start_on'], $last_other['start_on'])	< 0))	$last_contact = $last_other;

$data = $t_person->calculatePoints($person_id);
extract($data);

$contact_thresholds = $sql->getById("SELECT element_id,`interval` FROM Frequency WHERE (user_id='$_SESSION[user_id]' OR user_id='0') AND data_type='level' AND type='any'");
$frequency = $contact_thresholds[$person['level_id']];

$interaction_log = $t_person->getLog($person_id);

$html = new iframe\HTML\HTML;
render();
