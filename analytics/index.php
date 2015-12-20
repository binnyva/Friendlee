<?php
require '../common.php';

$type = i($QUERY, 'type', 'this_week');
$from = i($QUERY, 'from', date('Y-m-d'));
$to = i($QUERY, 'to', date('Y-m-d'));
$person_comparison = i($QUERY, 'person_comparison', false);

if($type == 'month') {
	$month = date('Y-m', strtotime($from));
	$to = date("Y-m-d", strtotime('next month', strtotime($from)));
	$date_logic = " AND DATE_FORMAT(C.start_on,'%Y-%m') = '$month'";
	$interval = '1 month';
	$text = 'Month';

} elseif($type == 'this_week') { // This  happens when the user just chanes on the analytics page. Show the user the scores from last sunday.
	$from = date('Y-m-d', strtotime('last sunday', strtotime($from)));
	$to = date('Y-m-d', strtotime('next sunday', strtotime($from)));

	$date_logic = " AND DATE(C.start_on) BETWEEN '$from' AND '$to'";
	$interval = '1 week';
	$text = 'This Week';
	$type = 'week';

} elseif($type == 'week') {
	$to = date('Y-m-d', strtotime('+7 days', strtotime($from)));

	$date_logic = " AND DATE(C.start_on) BETWEEN '$from' AND '$to'";
	$interval = '1 week';
	$text = 'Week';

} else {
	$date_logic = " AND DATE(C.start_on) BETWEEN '$from' AND '$to'";
	$interval = 'Range';
	$text = 'Range';
}

$person_logic = '';
if($person_comparison) {
	$person_logic = "AND (";
	$all_people = explode(",", $person_comparison);
	foreach ($all_people as $pep) {
		$person_logic .= "PC.person_id=$pep OR ";
	}

	$person_logic .= "1)";
}

$freq = $sql->getAll("SELECT P.id,P.nickname,C.type FROM Person P 
		INNER JOIN PersonConnection PC ON PC.person_id=P.id 
		INNER JOIN Connection C ON PC.connection_id=C.id 
		WHERE C.user_id=$_SESSION[user_id] $date_logic $person_logic");

$people = array();
foreach ($freq as $con) {
	if(!isset($people[$con['id']])) {
		$people[$con['id']] = array(
				'name' => $con['nickname'],
				'count'=> 1,
				'points'=> $points[$con['type']]
			);
	} else {
		$people[$con['id']]['count']++;
		$people[$con['id']]['points'] += $points[$con['type']];
	}
}
uasort($people,function($a, $b) {
	if($a['points'] > $b['points']) return -1;
	else return 1;
});
$top_ten = array_slice($people, 0, 10);

// dump($top_ten);exit;
render();

