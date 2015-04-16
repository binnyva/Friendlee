<?php
require '../common.php';

$person_id = i($QUERY, 'person_id', 0);
$from = i($QUERY, 'from', date('Y-m-d'));
$to = i($QUERY, 'to', date('Y-m-d'));
$type = i($QUERY, 'type', 'week');
$connection_type = i($QUERY, 'connection_type', 'any');
$more_data_type = i($QUERY, 'more_data_type', 'ratio');
$visualization_type = i($QUERY, 'visualization_type', 'calendar');

if(!$person_id) exit;

$connection_type_logic = '';
if($connection_type != 'any') {
	$connection_type_logic = "AND C.type='$connection_type' ";
}

$person = $sql->getAssoc("SELECT * FROM Person WHERE id=$person_id AND user_id=$_SESSION[user_id]");

$freq = $sql->getAll("SELECT C.type,C.start_on,C.note,C.location FROM Connection C
		INNER JOIN PersonConnection PC ON PC.connection_id=C.id 
		WHERE C.user_id=$_SESSION[user_id] AND PC.person_id=$person_id 
				$connection_type_logic
		ORDER BY C.start_on");


//dump($freq);exit;

$page_title = "Analytics for " . $person['nickname'];
render();
