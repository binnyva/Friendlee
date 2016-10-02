<?php
require('common.php');

$date = i($QUERY,'date', 'guess');
if($date == 'guess') {
	if(date('H:i:s') < '15:00:00') $date = date('Y-m-d', strtotime('yesterday'));
	else $date = date('Y-m-d');
} else {
	$date = date('Y-m-d', strtotime($date));
}
$new_people = array();

$title = 'Friendlee : ' . date('dS F, Y', strtotime($date));
$template->setTitle($title);

if(!empty($QUERY['action'])) {
	$met_connection_id = getPeople('met');
	$chat_connection_id = getPeople('chat');
	$phone_connection_id = getPeople('phone');
	$sms_connection_id = getPeople('message');
	$email_connection_id = getPeople('email');
	$other_connection_id = getPeople('other');
}

require('includes/uncontacted.php');

if($new_people) {
	$QUERY['success'] = 'Added new people to the system: ' . implode(', ', $new_people);
}

$template->addResource("../bower_components/jquery.tablesorter/js/jquery.tablesorter.min.js", "js");
$template->addResource("uncontacted.css", "css");
render();


function getPeople($type) {
	global $QUERY, $sql, $t_person, $people, $new_people, $points, $i_plugin;
	
	if(empty($QUERY[$type])) return;
	
	$raw = $QUERY[$type];
	
	$all_connections = explode(",", $raw);
	foreach($all_connections as $connection_raw) {
		$all_people = explode("+", $connection_raw);
		if(!$all_people) continue;
		
		$connection_id = $sql->insert('Connection', array(
			'type'		=> $type,
			'start_on'	=> $QUERY['date'] . ' 00:00:00',
			'user_id'	=> $_SESSION['user_id']
		));
		
		$ids = newPeopleCheckAndInsert($all_people);

		if($ids) {
			foreach($ids as $person_id) {
				$sql->insert("PersonConnection", array(
					'connection_id'	=> $connection_id,
					'person_id'		=> $person_id
				));

				// Increment person's points
				$t_person->find($person_id);
				$t_person->field['point'] = $t_person->field['point'] + $points[$type];
				$t_person->save();

				$i_plugin->callHook('action_person_connection_made', array($person_id, $type));
			}
		}
	}
	
	return $connection_id;
}
