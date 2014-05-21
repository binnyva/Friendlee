<?php
$last_person_id = 0;
foreach($sent_photos as $history) {
	if($history['person_id'] != $last_person_id) {
		if($last_person_id) print "</ul>\n\n"; // Close the list opened for the last person. Do it only if its not the first person.
		$last_person_id = $history['person_id'];

		print "<h4><a href='$config[site_url]/person.php?person_id=$last_person_id'>$history[nickname]</a> <span class='badge'>$history[point]</span></h4>\n";
		print "<ul>\n";
	}

	print "<li>$history[point_status] - " . date($config['time_format_php'], strtotime($history['sent_on'])) . "</li>\n";

}
print "</ul>";