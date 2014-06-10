<?php
function things_showProfileLink($person) {
	global $config;
	print "<a href='". joinPath($config['site_url'], 'plugins/3things/person.php') . "?person_id=".$person['id']."'>3 Things for $person[nickname]</a>";
}
$this->addHook("profile_end_display", "things_showProfileLink");
