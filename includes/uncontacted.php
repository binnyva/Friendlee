<?php
$all_levels = keyFormat($t_level->get('byid'), array('id','name'));

// This will show all the people in various levels who have been uncontacted for X days or more.
$contact_thresholds = $sql->getById("SELECT element_id,`interval` FROM Frequency 
				WHERE user_id='$_SESSION[user_id]' AND data_type='level' AND type='any'");
if(!$contact_thresholds) // If no Contact Thresholds, get default ones.
	$contact_thresholds = $sql->getById("SELECT element_id,`interval` FROM Frequency 
				WHERE user_id=0 AND data_type='level' AND type='any'");
$last_level_id = $sql->getOne("SELECT MAX(id) FROM Level WHERE user_id=$_SESSION[user_id] OR user_id=0");

// Get all people in all levels except acquaintances.
$people_last_contact = $sql->getById("SELECT P.id,P.nickname,P.name,P.level_id, P.priority, MAX(C.start_on) AS last_contact_on
			FROM Person P 
			INNER JOIN PersonConnection PC ON P.id=PC.person_id 
			INNER JOIN Connection C ON PC.connection_id=C.id 
			WHERE P.level_id!=$last_level_id AND (P.user_id=$_SESSION[user_id] OR P.user_id=0) AND (P.automanaged=0 OR P.priority='high')
				GROUP BY PC.person_id ORDER BY P.level_id");

if(iframe\App::$plugin->isHook("data_uncontacted_people")) {
	$people_last_contact = iframe\App::$plugin->callHook("data_uncontacted_people", array($people_last_contact), true);
}

$uncontacted_people = array();
foreach($people_last_contact as $person) {
	$gap = date_difference($person['last_contact_on'], date('Y-m-d'));

	$level_id = $person['level_id'];
	if($person['priority'] == 'high') $level_id = 1;
	elseif($person['priority'] == 'low') $level_id = 4;

	if(i($contact_thresholds, $level_id) and $gap > $contact_thresholds[$level_id]) { // Contact threshold is more than the users level.
		$pid = $person['id'];
		$person['gap'] = $gap;
		$person['type'] = $sql->getOne("SELECT type FROM Connection C INNER JOIN PersonConnection PC ON PC.connection_id = C.id 
											WHERE PC.person_id = $pid AND C.start_on = '{$person['last_contact_on']}'");


		// This block is specifically for the contact_attempt plugin.
		if(isset($person['contact_attempt']) and $person['contact_attempt']) {
			$gap = date_difference($person['last_attempt'], date('Y-m-d'));
			if($gap < $contact_thresholds[$level_id]) { // Last attempt was within the threshold.
				continue;
			}
		}

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
