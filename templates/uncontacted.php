<h2>Uncontacted Friends</h2>

<?php if($uncontacted_people) { ?>
<div id="uncontacted-people">
<ul class='nav nav-tabs'>
<?php
$active = 'active';
foreach ($uncontacted_people as $level_id => $uncontacted_in_level) {
	print "<li";
	if($active) { print " class='$active'"; $active = ''; }
	print "><a data-toggle='tab' href='#uncontacted-level-$level_id'>".$all_levels[$level_id]." <span class='badge'>".count($uncontacted_in_level)."</span></a></li>";
}
?>
</ul>

<div class="tab-content">
<?php
$active = 'in active';
foreach ($uncontacted_people as $level_id => $uncontacted_in_level) {
	print "<div class='tab-pane fade $active' id='uncontacted-level-$level_id'>\n";
	print "<table class='uncontacted-table'>\n";
	if(count($uncontacted_in_level) > 3) print "<thead><tr><th>Name</th><th>Last Contact</th></tr><thead>";
	print "<tbody>";
	foreach ($uncontacted_in_level as $person) {
		$gap_days = $person['gap'];
		if($gap_days > 30)
			$gap_days = floor($gap_days / 30) . ' months, ' . ($gap_days % 30) . ' days';
		else $gap_days .= ' days';

		print "<tr><td><a href='person.php?person_id=$person[id]'>$person[nickname]</a></td>";
		print "<td data='$person[gap]'>".ucfirst($person['type'])." $gap_days ago</td></tr>\n";
	}
	print "</tbody></table></div>";
	if($active) $active = '';
}
?>
</div>
</div>
<?php } ?>