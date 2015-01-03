<?php
require('../common.php');

if(empty($QUERY['connection_id'])) die('{"success":false,"error":"No connection ID specidied"}');

$connection_id = $QUERY['connection_id'];
$new_people = array();

if(i($QUERY, 'action') == 'Save') {
	// If there is change in the people list, delete and re-insert.
	if(i($QUERY, 'people') != i($QUERY, 'people_existing')) {
		$all_people = explode(",", i($QUERY, 'people'));

		$ids = newPeopleCheckAndInsert($all_people);

		if($ids) {
			$sql->remove("PersonConnection", "connection_id='$connection_id'");
			foreach($ids as $person_id) {
				$sql->insert("PersonConnection", array(
					'connection_id'	=> $connection_id,
					'person_id'		=> $person_id
				));
			}
		}
	}
	
	$affected_count = $sql->update("Connection", array('intensity'=>$QUERY['intensity'], 'start_on'=>$QUERY['start_on'], 'end_on'=>$QUERY['end_on'], 
										'location'=>$QUERY['location'], 'note'=>$QUERY['note']), "id=$connection_id");
	

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

$html = new HTML;

$template->options['layout_file'] = 'templates/layout/popup.php';
render();