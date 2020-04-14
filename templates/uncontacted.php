<?php if(!isset($included)) $included = true;
if(!$included) { ?><h2>Uncontacted Friends</h2><?php } ?>

<?php if($uncontacted_people) { ?>
<div id="uncontacted-people">
<ul class='nav nav-tabs' role="tablist">
<?php
$active = 'active';
foreach ($uncontacted_people as $level_id => $uncontacted_in_level) {
	// if($active) { print "$active"; $active = ''; }
	print "<li class='nav-item'><a data-toggle='tab' class='nav-link $active' href='#uncontacted-level-$level_id'>";
	print 	$all_levels[$level_id]." <span class='badge badge-dark'>".count($uncontacted_in_level)."</span></a></li>";

	$active = '';
}
?>
</ul>

<div class="tab-content">
<?php
$active = 'active show';
foreach ($uncontacted_people as $level_id => $uncontacted_in_level) {
	print "<div class='tab-pane fade $active' id='uncontacted-level-$level_id' role='tabpanel'>\n";
	print "<table class='table table-sm uncontacted-table'>\n";
	if(count($uncontacted_in_level) > 3) {
		print "<thead><tr><th>Name</th><th class='d-none d-md-table-cell'>Last Contact</th>";
		iframe\App::$plugin->callHook("display_uncontacted_people_header");
		print "</tr></thead>";
	}
	print "<tbody>";
	foreach ($uncontacted_in_level as $person) {
		$gap_days = $person['gap'];
		if($gap_days > 365) {
			$gap_days = "more than a year";
		} elseif($gap_days > 30) {
			$gap_months = floor($gap_days / 30) . ' months';
			if($gap_days % 30) $gap_months .= ', ' . (string) ($gap_days % 30) . ' days';

			$gap_days = $gap_months;
		}
		else $gap_days .= ' days';

		print "<tr><td><a href='person.php?person_id=$person[id]'>$person[nickname]</a></td>";
		print "<td data='$person[gap]' class='d-none d-md-table-cell'>".ucfirst($person['type'])." $gap_days ago</td>";
		iframe\App::$plugin->callHook("display_uncontacted_people_row", array($person));

		print "</tr>\n";
	}
	print "</tbody></table></div>";
	if($active) $active = '';
}
?>
</div>
</div>
<?php }