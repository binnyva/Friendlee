<?php
require("./common.php");

$level = array();
$all_levels = $t_level->find();
foreach($all_levels as $l) {
	$level[$l['id']] = array(
		'name'	=> $l['name'],
		'people'=> $t_person->sort('nickname')->find(array('level_id'=>$l['id']))
	);
}

$template->addResource('library/jquery-ui/jquery-ui.min.js','js');

render();