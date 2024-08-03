<?php
require('common.php');

$date = i($QUERY,'date', 'guess');
if($date == 'guess') {
	if(date('H:i:s') < '15:00:00') $date = date('Y-m-d', strtotime('yesterday'));
	else $date = date('Y-m-d');
} else {
	$date = date('Y-m-d', strtotime($date));
}
$new_people = array();

$title = 'Friendlee : ' . date('dS F, Y', strtotime($date));
iapp('template')->setTitle($title);

if(!empty($QUERY['action'])) {
	getPeople('met');
	getPeople('phone');
	getPeople('message');
	getPeople('other');

	$url = getRefreshUrl();
	if($new_people) {
		$QUERY['success'] = 'Added new people to the system: ' . implode(', ', $new_people);
		$url = getRefreshUrl(['success' => $QUERY['success']]);
	}
	
	header("Location: $url"); // Fix the resubmit on back issue.
	exit;
}

require('includes/uncontacted.php');

iapp('template')->addResource("uncontacted.css", "css");
render();

function getPeople($type) {
	global $QUERY, $sql, $t_person, $people, $new_people, $points, $i_plugin, $t_connection;
	
	if(empty($QUERY[$type])) return;
	
	$raw = $QUERY[$type];
	$connection_id = $t_connection->parse($type, $raw);

	return $connection_id;
}
