<?php
require('../common.php');

$person_id = intval($QUERY['person_id']);

if(!$nick = $sql->getOne("SELECT nickname FROM Person WHERE id=$person_id AND user_id=$_SESSION[user_id]")) {
	showMessage("Person can't deleted - it doesn't belong to current user", 'tree.php', 'error');
	exit;
}

$sql->remove('PersonConnection', "person_id=$person_id");
$affected = $sql->remove('Person', "id=$person_id");

if($affected) {
	showMessage("'$nick' deleted", 'tree.php','success');
} else {
	showMessage('Could not delete the person ' . $nick, 'tree.php','error');
}
