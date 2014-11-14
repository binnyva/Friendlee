<?php
require('../../../common.php');

$config = $QUERY['city_config'];
$count = 0;
foreach($config as $city_id => $person_ids) {
	$ids = explode(",", $person_ids);
	
	foreach($ids as $person_id) {
		$sql->update("Person", array('city_id'=>$city_id), "id='$person_id'");
		$count++;
	}
}

if($count) {
	showAjaxMessage('Configuration Saved','success');
} else {
	showAjaxMessage('Could not save configuration.','error');
}
