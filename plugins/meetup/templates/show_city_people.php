<h3>Show City</h3>
<?php include('_nav.php'); ?>

<form action="" method="get">
<label for="city_id">Filter by City: </label>
<?php $html->buildDropDownArray($all_cities,"city_id",$city_id); ?>
<input type="submit" name="action" value="Go" />
</form>

<table class="table table-hover people">
<thead><tr><th>Name</th><th>Points</th><th>Send File</th></tr></thead>
<?php
foreach ($people as $p) {

	echo "<tr><td><a href='../../person.php?person_id=$p[id]'>$p[nickname]</a></td>";
	echo "<td>$p[point]</td>";
	echo "<td>";
	echo "<a href='file:///home/binnyva/Others/Photography/People/{$all_cities[$p['city_id']]}/{$p['nickname']}'>Folder</a> / ";
	if($p['facebook_id']) echo "<a href='https://www.facebook.com/messages/{$p['facebook_id']}' class='with-icon email'>FB Message</a>";
	echo "</td>";
	echo "</tr>";
}
?>
</table>

