<?php
require('../common.php');

$connection_id = intval($QUERY['connection_id']);

// Reset the points given for this connection.
$connection_details = $t_connection->find($connection_id);
$connection_people = $t_personconnection->find(array('connection_id'=>$connection_id));
foreach ($connection_people as $cp) {
	$t_person->find($cp['person_id']);
	$t_person->field['point'] = $t_person->field['point'] - $points[$connection_details['type']];
	$t_person->save();
}

$sql->remove('PersonConnection', "connection_id=$connection_id");
$affected = $sql->remove('Connection', "id=$connection_id");

if($affected) {
	showAjaxMessage('Connection deleted','success');
} else {
	showAjaxMessage('Could not delete connection','error');
}
