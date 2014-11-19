<?php
require('../../common.php');

$person_id = intval($QUERY['person_id']);
//$direction = intval($QUERY['direction']); // Not used for now.

$sql->insert("Plugin_Contact_Attempt", array(
		'person_id'		=> $person_id,
		'attempt_on'	=> 'NOW()',
		'attempt_type'	=> 'any',
		'status'		=> 'none',
		'action_taken'	=> 'none',
	));

print '{"success": "Attempt recorded", "value": "'.($QUERY['old_value'] + 1) . '", "person_id": "'.$person_id.'"}';
