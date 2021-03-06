<?php
require '../common.php';

$person_id = i($QUERY, 'person_id', 382);
$from = i($QUERY, 'from', date('Y-m-d', strtotime('last month')));
$to = i($QUERY, 'to', date('Y-m-d'));
$output = i($QUERY, 'output', 'text');
$connection_type = 'met';

$contacts = $sql->getAll("SELECT DATE(C.start_on) AS start_on, C.type, C.note, C.location
	FROM Connection C
	INNER JOIN PersonConnection PC ON C.id=PC.connection_id
	WHERE C.user_id=$_SESSION[user_id] AND PC.person_id=$person_id AND C.type='$connection_type' AND C.start_on > '$from' AND C.start_on < '$to'
	ORDER BY C.start_on");

$a = 0;
$b = 0;
$data = array();
foreach ($contacts as $cont) {
	if($cont['note'] and preg_match('/(\d+)\:(\d+)/', $cont['note'], $matches)) {
		$a_value = intval($matches[1]);
		$b_value = intval($matches[2]);

		$a += $a_value;
		$b += $b_value;
		$data[] = array('date' => $cont['start_on'], 'a' => $a_value, 'b' => $b_value);
	}
}

if($output == 'json') {
	echo json_encode($data);
	exit;
} elseif($output == 'csv') {
	foreach($data as $d) {
		echo "$d[date],$d[a],$d[b]\n";
	}
	exit;
}

$interval = date_diff(date_create($from), date_create($to));
$gap = $interval->format('%a');
$weeks = $gap / 7;
$months = $gap / 30;

if($contacts) {
	print "<pre>";
	print "A : $a\n";
	print "\tDaily Average: ". round($a / $gap, 2) . "\n";
	print "\tWeekly Average: ". round($a / $weeks, 2) . "\n";
	print "\tMonthly Average: ". round($a / $months, 2) . "\n";
	
	print "B : $b\n";
	print "\tDaily Average: ". round($b / $gap, 2) . "\n";
	print "\tWeekly Average: ". round($b / $weeks, 2) . "\n";
	print "\tMonthly Average: ". round($b / $months, 2) . "\n";

	print "\n";
	print "Timeframe: $gap days\n";
	print "</pre>";	
}