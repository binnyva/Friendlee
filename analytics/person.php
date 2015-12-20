<?php
require '../common.php';

$person_id = i($QUERY, 'person_id', 0);
$from = i($QUERY, 'from', date('Y-m-d'));
$to = i($QUERY, 'to', date('Y-m-d'));
$type = i($QUERY, 'type', 'week');
$connection_type = i($QUERY, 'connection_type', 'any');
$more_data_type = i($QUERY, 'more_data_type', 'no_ratio'); // ?person_id=382&type=month&connection_type=met&more_data_type=ratio for the specific info.

if(!$person_id) exit;

$connection_type_logic = '';
if($connection_type != 'any') {
	$connection_type_logic = "AND C.type='$connection_type' ";
}

$person = $sql->getAssoc("SELECT * FROM Person WHERE id=$person_id AND user_id=$_SESSION[user_id]");
$person_name = $person['nickname'];

// Group by week or month
$group_by = '';
if($type == 'week') $group_by = "DATE_FORMAT(C.start_on, '%U')";
elseif($type == 'month') $group_by = "DATE_FORMAT(C.start_on, '%Y-%m')";

///////////////////// Frequency //////////////////////////////
// Funny thing happening here - can't calcualte points within the query easily. Get a concatinted list of all the meet types.
$freq = $sql->getAll("SELECT $group_by AS group_index, COUNT(C.id) AS count, GROUP_CONCAT(C.type) AS types
		FROM Connection C
		INNER JOIN PersonConnection PC ON PC.connection_id=C.id 
		WHERE C.user_id=$_SESSION[user_id] AND PC.person_id=$person_id 
				$connection_type_logic
		GROUP BY group_index
		ORDER BY group_index DESC
		LIMIT 0, 10");

// Put this in a json_encode-able array. Will just print it out in the JS part
$data = array();
foreach ($freq as $key => $value) {
	$types = explode(",", $freq[$key]['types']);

	// Here we take the concatintated types, split them, calculate the points and return the points.
	$current_points = array_reduce($types, function($carry, $type) {
		global $points;
		$carry += $points[$type];

		return $carry;
	}, 0);
	$freq[$key]['points'] = $current_points;

	// Y axis labels
	if($type == 'week')
		$date = date("d M", strtotime(date("Y") . "W" . $value['group_index'])) . '-' . date("d M", strtotime(date("Y") . "W" . ($value['group_index']+1))); 
	else 
		$date = date("M Y", strtotime($value['group_index'] . "-01")); 

	$data[] = array($date, intval($value['count']), $current_points);
}
$data = array_reverse($data);

///////////////////// Streaks and Distribution //////////////////////////////
// Streaks part. Code taken from MyLife::tags/tag.php
$distribution =  $sql->getAll("SELECT DATE(C.start_on) AS start_on, C.type, C.note, C.location
	FROM Connection C
	INNER JOIN PersonConnection PC ON C.id=PC.connection_id
	WHERE C.user_id=$_SESSION[user_id] AND PC.person_id=$person_id $connection_type_logic
	ORDER BY C.start_on");

// Finds the longest streak and the longest gaps.
$longest_streak = 0;
$longest_streak_to = '';
$longest_gap = 0;
$longest_gap_to = '';

$last_date = '';
$current_streak = 1; // Because first day is included in the streak
$i = 0;
foreach ($distribution as $row) {
	$date = $row['start_on'];
	$yesterday = date("Y-m-d", strtotime($date) - (24 * 60 * 60));

	if($yesterday == $last_date) { // Streak goes on
		$current_streak++;

	} else { // Streak break.
		// Find the gap between the break and the last day.
		$datetime1 = date_create(date('Y-m-d', strtotime($last_date) + (24 * 60 * 60)));// One day after the event happened...
		$datetime2 = date_create(date('Y-m-d', strtotime($yesterday)));// to one day before the event happened next.
		$interval = date_diff($datetime1, $datetime2);
		$gap = $interval->format('%a') + 1; // Because the first day is included in the gap.
		if($gap > $longest_gap and $i) { // 'and $i' to make sure that the first day is ignored. 
			$longest_gap = $gap;
			$longest_gap_to = $yesterday; // Gap was till yesterday. Today we had a hit.
		}

		if($current_streak > $longest_streak) {
			$longest_streak = $current_streak;
			$longest_streak_to = $last_date; // Streak record was for the last event streak.
		}
		$current_streak = 1;
	}

	$last_date = $date;
	$i++;
}

$page_title = "Analytics for " . $person['nickname'];
render();

function showFromTo($length, $last_date) {
	$from = strtotime("-" . ($length - 1) . " days", strtotime($last_date));
	$to = strtotime($last_date);
	print "<p>From <a href='../index.php?date=" . date('Y-m-d', $from) . "'>" . date("dS M, Y", $from) . '</a> ';
	print "to <a href='../index.php?date=" . date('Y-m-d', $to) . "'>" . date("dS M, Y", $to). '</a></p>';
}
