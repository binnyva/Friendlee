<?php
require('common.php');

$date = i($QUERY,'date', date('Y-m-d'));
$new_people = array();

$people = keyFormat($t_person->sort('level_id', 'nickname')->find());
$all_people = array();
foreach($people as $p) $all_people[] = $p['nickname'];

if(!empty($QUERY['action'])) {
	$met_connection_id = getPeople('met');
	$chat_connection_id = getPeople('chat');
	$phone_connection_id = getPeople('phone');
	$sms_connection_id = getPeople('message');
}


if($new_people) {
	$QUERY['success'] = 'Added new people to the system: ' . implode(', ', $new_people);
}

$template->addResource('library/jquery-ui/jquery-ui.min.js','js');
$template->addResource('library/jquery-ui/jquery.ui.autocomplete.min.js','js');
render();


function getPeople($type) {
	global $QUERY, $sql, $t_person, $people, $new_people;
	
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
		
		foreach($all_people as $nickname_org) {
			$nickname_org = trim($nickname_org);
			$nickname = strtolower($nickname_org);
			if(!$nickname) continue;
			
			$person_id = $t_person->findOne("LOWER(nickname)='$nickname'", 'id');
			if(!$person_id) {
				// If the person is not there in the DB, add him.
				$person_id = $t_person->set(array(
						'nickname'	=> stripslashes($nickname_org),
						'status'	=> 1,
						'level_id'	=> 3, // Friend
						'user_id'	=> $_SESSION['user_id'],
					))->save();
				$people[$person_id]['nickname'] = $nickname_org;
				$new_people[] = $nickname_org;
			}
			$ids[] = $person_id;
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
