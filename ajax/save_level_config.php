<?php
require('../common.php');

$config = $QUERY['level_config'];
$count = 0;
foreach($config as $level_id => $person_ids) {
	$ids = explode(",", $person_ids);
	
	foreach($ids as $person_id) {
		$sql->update("Person", array('level_id'=>$level_id), "id='$person_id'");
		$count++;
	}
}

if($count) {
	showAjaxMessage('Configuration Saved','success');
} else {
	showAjaxMessage('Could not save configuration.','error');
}
