<?php
require('../common.php');

if(empty($QUERY['connection_id'])) die('{"success":false,"error":"No connection ID specidied"}');

$connection_id = $QUERY['connection_id'];
$new_people = array();

if(i($QUERY, 'action') == 'Save') {
	// If there is change in the people list, delete and re-insert.
	$all_people = array();
	if(i($QUERY, 'people') != i($QUERY, 'people_existing')) {
		$all_people = i($QUERY, 'people');
	}
	$t_connection->edit($connection_id, $QUERY, $all_people);
	
	if($new_people) {
		showAjaxMessage('Added new people to the system: ' . implode(', ', $new_people),'success');
	} else {
		showAjaxMessage('Connection updated','success');
	}
	exit;
	
} else {
	$connection = $t_connection->find($connection_id);
	$connection['people'] = $t_personconnection->find("connection_id='$connection_id'");

	$people = keyFormat($t_person->sort('level_id', 'nickname')->find());
	$all_people = array();
	foreach($people as $p) $all_people[$p['id']] = $p['nickname'];

	$names = array();
	foreach ($connection['people'] as $person) { 
		$names[] = $all_people[$person['person_id']]; 
	}
}

$html = new iframe\HTML\HTML;

// $template->options['layout_file'] = 'templates/layout/popup.php';
iapp('template')->render(false, ['use_layout' => false]);