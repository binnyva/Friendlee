<?php
require('../../common.php');

$person_id = i($_GET, 'person_id');
if(!$person_id) die('{"error":"No person specified"}');

$person = $t_person->find($person_id);

$last_photo_sent_at_point = $person['point'] - ($person['point'] % 25);

//print "$person[point] / $last_photo_sent_at_point";
$sql->insert("Plugin_Quarter_Photo", array(
		'person_id'	=> $person_id,
		'user_id'	=> $_SESSION['user_id'],
		'point_status'	=> $last_photo_sent_at_point,
		'sent_on'	=> 'NOW()'
	));

print '{"success": "Photo sending acknoledged."}';