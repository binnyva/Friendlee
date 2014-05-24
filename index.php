<?php
require('common.php');

$date = i($QUERY,'date', date('Y-m-d'));
$new_people = array();

$title = 'Friendlee : ' . date('dS F, Y', strtotime($date));
$template->setTitle($title);

if(!empty($QUERY['action'])) {
	$met_connection_id = getPeople('met');
	$chat_connection_id = getPeople('chat');
	$phone_connection_id = getPeople('phone');
	$sms_connection_id = getPeople('message');
}

require('includes/uncontacted.php');

if($new_people) {
	$QUERY['success'] = 'Added new people to the system: ' . implode(', ', $new_people);
}

$template->addResource("../bower_components/jquery.tablesorter/js/jquery.tablesorter.min.js", "js");
render();


function getPeople($type) {
	global $QUERY, $sql, $t_person, $people, $new_people, $points;
	
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
				// A very rough gender detection.	
				$first_name = @reset(explode(" ", $nickname_org));
				$last_letter = substr($first_name, -1);
				$sex = 'm';

				if(in_array($last_letter, array('a','e','i','o','u'))) $sex = 'f';

				// If the person is not there in the DB, add him.
				$person_id = $t_person->set(array(
						'nickname'	=> stripslashes($nickname_org),
						'status'	=> 1,
						'level_id'	=> 3, // Friend
						'sex'		=> $sex,
						'user_id'	=> $_SESSION['user_id'],
					))->save();
				$people[$person_id]['nickname'] = $nickname_org;
				$new_people[] = $nickname_org . " (".strtoupper($sex).")";
			}
			$ids[] = $person_id;
		}
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
			}
		}
	}
	
	return $connection_id;
}
