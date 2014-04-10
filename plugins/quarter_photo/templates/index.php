<?php
foreach ($people as $p) {
	if($p['point'] > $every_x_points) {
		$last_photo_sent_at_point = i($sent_photos, $p['id']);
		if($last_photo_sent_at_point) {
			if($p['point'] - $last_photo_sent_at_point < $every_x_points) { // Person hasn't gotten 25 points after the last photo. Don't show this person.
				continue;
			}
		} else {
			$last_photo_sent_at_point = $p['point'] - $every_x_points - ($p['point'] % 25);
		}

		echo "<li><a href='../../person.php?person_id=$p[id]'>$p[nickname] <span class='badge'>$p[point]</span></a> ";
		if($last_photo_sent_at_point) {
			$photos_to_be_sent = floor(($p['point'] - $last_photo_sent_at_point) / $every_x_points);
			if(!$photos_to_be_sent) $photos_to_be_sent = 1; // This is just BAD. Remove all after we have decent size for the database table.

			for($i=0; $i<$photos_to_be_sent; $i++) echo " <a href='photo_sent.php?person_id=$p[id]' class='icon done ajaxify'>Sent</a>";
		}

		echo "</li>";
	}
}

