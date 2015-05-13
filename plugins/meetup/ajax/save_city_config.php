<?php
require('../../../common.php');

$config = $QUERY['city_config'];
$count = 0;

$all_people = $sql->getById("SELECT id,city_id FROM Person WHERE user_id=$_SESSION[user_id]");

foreach($config as $city_id => $person_ids) {
	$ids = explode(",", $person_ids);
	
	foreach($ids as $person_id) {
		if(i($all_people, $person_id) == $city_id) continue; // No change to the city. Skip this. :OPTIMIZATION:

		$sql->update("Person", array('city_id'=>$city_id), "id='$person_id'");
		$count++;
	}
}

if($count) {
	showAjaxMessage('Configuration Saved - ' . $count . ' people edited.','success');
} else {
	showAjaxMessage('Could not save configuration.','error');
}
