<?php
require('../../common.php');

$person_id = intval($QUERY['person_id']);

$sql->insert("Plugin_Contact_Queue", array(
		'user_id'	=> $_SESSION['user_id'],
		'person_id'	=> $person_id,
		'added_on'	=> 'NOW()',
		'contacted'	=> '0',
	));

print '{"success": "Queued.", "person_id": "'.$person_id.'"}';
