<?php
require('../common.php');

$config = $QUERY['level_config'];
$count = 0;
$all_people = $sql->getById("SELECT id,level_id FROM Person WHERE user_id=$_SESSION[user_id]");
foreach($config as $level_id => $person_ids) {
	$ids = explode(",", $person_ids);
	
	foreach($ids as $person_id) {
		if($all_people[$person_id] == $level_id) continue; // No change

		$sql->update("Person", array('level_id'=>$level_id), "id='$person_id'");
		$count++;
	}
}

if($count) {
	iframe\App::showAjaxMessage("Configuration Saved - $count people updated.",'success');
} else {
	iframe\App::showAjaxMessage('Could not save configuration.','error');
}
