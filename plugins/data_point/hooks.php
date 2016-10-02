<?php
function data_point_showData($person) {
	global $config, $sql;

	$data = $sql->getAll("SELECT * FROM Plugin_Data_Point WHERE user_id=$_SESSION[user_id] AND person_id=$person[id] AND status='1'");

	foreach($data as $row) {
		print "<h3>$row[name]</h3>";
		print "<p>" . nl2br($row['data']) . "</p>";
		print "<p><a href='". joinPath($config['site_url'], 'plugins/data_point/person.php') . "?action=edit&id=".$row['id']."' class='with-icon edit'>Edit</a></p>";
	}

	print "<a href='". joinPath($config['site_url'], 'plugins/data_point/person.php') . "?person_id=".$person['id']."' class='with-icon add'>Add Points</a>";
}
$this->addHook("profile_middle_display", "data_point_showData");
