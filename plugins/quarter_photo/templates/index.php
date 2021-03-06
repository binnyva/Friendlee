<a href="history.php">See History</a><br />
<h3>Reward Candidates</h3>

<form action="" method="get">
<label for="city_id">Filter by City: </label>
<?php $html->buildDropDownArray($all_cities,"city_id",$city_id); ?>
<input type="submit" name="action" value="Go" />
</form>

<table class="table table-hover">
<thead><tr><th>Name</th><th>Points</th><th>Send File</th><th>History</th><th>Send Photo</th></tr></thead>
<?php
foreach ($people as $p) {
	if($p['point'] > $every_x_points) {
		$last_photo_sent_at_point = i($sent_photos, $p['id']);

		if($last_photo_sent_at_point) {
			if($p['point'] - $last_photo_sent_at_point < $every_x_points) { // Person hasn't gotten 25 points after the last photo. Don't show this person.
				continue;
			}
		} else {
			$last_photo_sent_at_point = $p['point'] - ($p['point'] % 25);
		}

		echo "<tr><td><a href='../../person.php?person_id=$p[id]'>$p[nickname]</a></td>";
		echo "<td>$p[point]</td>";
		echo "<td>";
		echo "<a href='file:///home/binnyva/Others/Photography/People/{$all_cities[$p['city_id']]}/{$p['nickname']}'>Folder</a> / ";
		if($p['facebook_id']) echo "<a href='https://www.facebook.com/messages/{$p['facebook_id']}' class='with-icon email'>FB Message</a>";
		echo "</td>";

		if($last_photo_sent_at_point) {
			$photos_to_be_sent = floor(($p['point'] - $last_photo_sent_at_point) / $every_x_points);
			if(!$photos_to_be_sent) $photos_to_be_sent = 1; // This is just BAD. Remove all after we have decent size for the database table.

			echo "<td><a href='history.php?person_id=$p[id]' class='icon wait'>History</a></td>";
			echo "<td>";
			for($i=0; $i<$photos_to_be_sent; $i++) echo " <a href='photo_sent.php?person_id=$p[id]' class='icon done ajaxify photo-sent'>Sent</a>";
			if($photos_to_be_sent > 3) print " ($photos_to_be_sent)";
			echo "</td>";
		}

		echo "</tr>";
	}
}
?>
</table>

