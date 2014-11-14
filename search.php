<?php
require('./common.php');
$search_term = '';


if(isset($QUERY['nickname'])) {
	$search_term = trim($QUERY['nickname']);
	$person = $t_person->find(array('nickname'=>$QUERY['nickname'], 'user_id'=>$_SESSION['user_id']));
	goToPerson($person);
	
} elseif(isset($QUERY['search'])) {
	$search_term = trim($QUERY['search']);
	$person = $t_person->find("nickname LIKE '%$QUERY[search]%' OR name LIKE '%$QUERY[search]%'", "user_id=$_SESSION[user_id]");
	goToPerson($person);
}

render();

function goToPerson($person) {
	if(count($person) == 1) {
		header("Location: person.php?person_id=".$person[0]['id']);
		exit;
	}
}