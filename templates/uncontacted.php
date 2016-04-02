<?php if(!isset($included)) $included = true;
if(!$included) { ?><h2>Uncontacted Friends</h2><?php } ?>

<?php if($uncontacted_people) { ?>
<div id="uncontacted-people" <?php if($included) echo 'class="col-md-6"'; ?>>
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
	if(count($uncontacted_in_level) > 3) {
		print "<thead><tr><th>Name</th><th>Last Contact</th>";
		$i_plugin->callHook("display_uncontacted_people_header");
		print "</tr><thead>";
	}
	print "<tbody>";
	foreach ($uncontacted_in_level as $person) {
		$gap_days = $person['gap'];
		if($gap_days > 30) {
			$gap_days = floor($gap_days / 30) . ' months';
			if($gap_days % 30) $gap_days .= ', ' . ($gap_days % 30) . ' days';
		}
		else $gap_days .= ' days';

		print "<tr><td><a href='person.php?person_id=$person[id]'>$person[nickname]</a></td>";
		print "<td data='$person[gap]'>".ucfirst($person['type'])." $gap_days ago</td>";
		$i_plugin->callHook("display_uncontacted_people_row", array($person));

		print "</tr>\n";
	}
	print "</tbody></table></div>";
	if($active) $active = '';
}
?>
</div>
</div>
<?php }