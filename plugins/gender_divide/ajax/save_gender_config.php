<?php
require('../../../common.php');

dump($QUERY);
$config = $QUERY['gender_config'];
$count = 0;
foreach($config as $gender_id => $person_ids) {
	$ids = explode(",", $person_ids);
	
	foreach($ids as $person_id) {
		$sql->update("Person", array('sex'=>$gender_id), "id='$person_id'");
		$count++;
	}
}

if($count) {
	showAjaxMessage('Configuration Saved','success');
} else {
	showAjaxMessage('Could not save configuration.','error');
}
