<?php
require('../../common.php');

$person_id = intval($QUERY['person_id']);

// Increase the person's level by one.
$t_person->demote($person_id);

// Update Action in the Contact_Attepmt Table
$attempt_id = $sql->getOne("SELECT id FROM Plugin_Contact_Attempt WHERE person_id='$person_id' ORDER BY attempt_on DESC LIMIT 0,1");
$sql->execQuery("UPDATE Plugin_Contact_Attempt SET status='unresponsive',action_taken='demote' WHERE id=$attempt_id");

print '{"success": "Person Demoted"}';
