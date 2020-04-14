<?php
require('../common.php');

$person_id = intval($QUERY['person_id']);

if(!$nick = $sql->getOne("SELECT nickname FROM Person WHERE id=$person_id AND user_id=$_SESSION[user_id]")) {
	iframe\App::showMessage("Person can't deleted - it doesn't belong to current user", 'tree.php', 'error');
	exit;
}

$affected = $t_person->remove($person_id);

if($affected) {
	iframe\App::showMessage("'$nick' deleted", 'tree.php','success');
} else {
	iframe\App::showMessage('Could not delete the person ' . $nick, 'tree.php','error');
}
