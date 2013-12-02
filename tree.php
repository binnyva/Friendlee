<?php
require("./common.php");

$level = array();
$all_levels = $t_level->find();
foreach($all_levels as $l) {
	$level[$l['id']] = array(
		'name'	=> $l['name'],
		'people'=> getPeople($l['id'])
	);
}

$template->addResource('library/jquery-ui/jquery-ui.min.js','js');
render();

function getPeople($level_id) {
	global $t_person;
	$people = $t_person->sort('nickname')->find(array('level_id'=>$level_id));
	
	// If the 'recalculate_points' argument is not set, return the data.
	if(empty($_REQUEST['recalculate_points'])) return $people;
	
	// Else, calculate everyones points and return that data.
	return array_map('attachPoints', $people);
}

function attachPoints($person) {
	$person['point'] = getPoints($person['id']);
	
	return $person;
}