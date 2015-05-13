<?php

$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id] ORDER BY name");
$all_cities[0] = 'All';
$trips = $sql->getAll("SELECT * FROM Plugin_Meetup_Trip WHERE user_id=$_SESSION[user_id] $where ORDER BY start_on DESC");
$html = new HTML;
$template->addResource("../bower_components/jquery.tablesorter/js/jquery.tablesorter.min.js", "js");
//render(joinPath($config['site_folder'],'plugins/meetup/templates/trips.php'), true, true);
?>
<h3>Show City</h3>

<form action="" method="get">
<label for="city_id">Filter by City: </label>
<?php $html->buildDropDownArray($all_cities,"city_id",$city_id); ?>
<input type="submit" name="action" value="Go" />
</form>

<table class="table table-hover people">
<thead><tr><th>Name</th><th>Start</th><th>End</th></tr></thead>
<?php
foreach ($trips as $t) {

	echo "<tr><td>{$all_cities[$t['city_id']]}</td>";
	echo "<td>".date($config['date_format_php'], strtotime($t['start_on']))."</td>";
	echo "<td>".date($config['date_format_php'], strtotime($t['end_on']))."</td>";
	echo "</tr>";
}
?>
</table>

