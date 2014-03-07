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


$all_levels = $t_level->get('byid');

// This will show all the people in various levels who have been uncontacted for X days or more.
$contact_thresholds = array(
		1 => 7,		// A Week - for close fri
		2 => 30, 	// A Month
		3 => 90		// 3 Months
	);
// Get all people in all levels execpt 4(Aquancences).
$people_last_contact = $sql->getAll("SELECT P.id,P.nickname,P.name,P.level_id, MAX(C.start_on) AS last_contact_on FROM Person P 
			INNER JOIN PersonConnection PC ON P.id=PC.person_id 
			INNER JOIN Connection C ON PC.connection_id=C.id 
			WHERE P.level_id!=4 AND P.user_id=$_SESSION[user_id]
				GROUP BY PC.person_id");

$uncontacted_people = array();
foreach($people_last_contact as $person) {
	$datetime1 = date_create($person['last_contact_on']);
	$datetime2 = date_create(date('Y-m-d'));
	$interval = date_diff($datetime1, $datetime2);
	$gap = $interval->format('%a');
	$level_id = $person['level_id'];
	if($gap > $contact_thresholds[$level_id]) {
		$pid = $person['id'];
		$person['gap'] = $gap;
		$person['type'] = $sql->getOne("SELECT type FROM Connection C INNER JOIN PersonConnection PC ON PC.connection_id = C.id 
											WHERE PC.person_id = $pid AND C.start_on = '{$person['last_contact_on']}'");

		$uncontacted_people[$level_id][$pid] = $person;
	}
}

// Sort people according to contact gap.
foreach($contact_thresholds as $level_id => $threshold) 
	if(!empty($uncontacted_people[$level_id])) 
		usort($uncontacted_people[$level_id], 'compare_gap');


function compare_gap($a, $b) {
	if ($a['gap'] == $b['gap']) return 0;
	return ($a['gap'] > $b['gap']) ? -1 : 1;
}


if($new_people) {
	$QUERY['success'] = 'Added new people to the system: ' . implode(', ', $new_people);
}

$template->addResource($config['site_url'].'bower_components/jquery-ui/ui/minified/jquery-ui.min.js','js');
$template->addResource($config['site_url'].'bower_components/jquery-ui/ui/minified/jquery.ui.autocomplete.min.js','js');
$template->addResource($config['site_url'].'bower_components/jquery-ui/themes/base/minified/jquery-ui.min.css','css');
$template->addResource($config['site_url'].'bower_components/jquery-ui/themes/base/minified/jquery.ui.autocomplete.min.css','css');
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
