<?php
require('common.php');

$date = i($QUERY,'date', date('Y-m-d'));

$people = keyFormat($t_person->sort('level_id', 'nickname')->find());
$all_people = array();
foreach($people as $p) $all_people[] = $p['nickname'];

if(!empty($QUERY['action'])) {
	$met_connection_id = getPeople('met');
	$chat_connection_id = getPeople('chat');
	$phone_connection_id = getPeople('phone');
	$sms_connection_id = getPeople('message');
}


$template->addResource('library/jquery-ui/jquery-ui.min.js','js');
$template->addResource('library/jquery-ui/jquery.ui.autocomplete.min.js','js');
render();


function getPeople($type) {
	global $QUERY, $sql, $t_person;
	
	if(empty($QUERY[$type])) return;
	
	$raw = $QUERY[$type];
	
	$all_connections = explode(",", $raw);
	foreach($all_connections as $connection_raw) {
		$all_people = explode("+", $connection_raw);
		if(!$all_people) continue;
		
		$ids = array();
		
		$connection_id = $sql->insert('Connection', array(
			'type'		=> $type,
			'start_on'	=> $QUERY['date'] . ' 00:00:00',
			'user_id'	=> $_SESSION['user_id']
		));
		
		foreach($all_people as $nickname) {
			$nickname = trim(strtolower($nickname));
			if(!$nickname) continue;
			
			$ids[] = $t_person->findOne("LOWER(nickname)='$nickname'", 'id');
		}
		if($ids) {
			foreach($ids as $person_id) {
				$sql->insert("PersonConnection", array(
					'connection_id'	=> $connection_id,
					'person_id'		=> $person_id
				));
			}
		}
	}
	
	return $connection_id;
}