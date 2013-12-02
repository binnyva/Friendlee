<?php
require('common.php');

$person_id = $QUERY['person_id'];
$person = $t_person->find($person_id);
if(!$person) {
	die("Invalid Person ID Provided");
}
$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id] ORDER BY name");
$all_cities[0] = 'Unknown';
$all_levels = $sql->getById("SELECT id,name FROM Level");

$last_message	= getLastContact($person_id, 'message');
$last_chat		= getLastContact($person_id, 'chat');
$last_phone		= getLastContact($person_id, 'phone');
$last_met		= getLastContact($person_id, 'met');

$last_contact = $last_message;
if($last_chat	and (!$last_contact or @strcmp($last_contact['start_on'], $last_chat['start_on'])	< 0))	$last_contact = $last_chat;
if($last_phone	and (!$last_contact or @strcmp($last_contact['start_on'], $last_phone['start_on'])	< 0))	$last_contact = $last_phone;
if($last_met	and (!$last_contact or @strcmp($last_contact['start_on'], $last_met['start_on'])	< 0))	$last_contact = $last_met;

$data = getPointsDetail($person_id);
extract($data);

$frequency = keyFormat($sql->getAll("SELECT `type`, `interval` FROM Frequency WHERE data_type='level' AND element_id='$person[level_id]' AND user_id=$_SESSION[user_id]"), array('type','interval'));
foreach($all_interation_types as $type) if(!isset($frequency[$type])) $frequency[$type] = 0;

$html = new HTML;
render();


function getLastContact($person_id, $type) {
	global $sql;
	$connection = $sql->getAssoc("SELECT C.* FROM Connection C 
		INNER JOIN PersonConnection PC ON C.id=PC.connection_id 
		WHERE PC.person_id=$person_id AND
			C.type='$type' AND
			C.user_id='$_SESSION[user_id]'
		ORDER BY C.start_on DESC");
		
	//if(!$connection) return array($type=>array('start_on'=>'0000-00-00 00:00:00'));
	return $connection;
}
	