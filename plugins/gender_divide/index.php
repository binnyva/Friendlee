<?php
require("../../common.php");

$genders = array('m'=>'Male','f'=>'Female');
foreach($genders as $id=>$name) {
	$genders[$id] = array(
		'name'	=> $name,
		'people'=> getPeople($id)
	);
}

$template->addResource('library/jquery-ui/jquery-ui.min.js','js');
$template->addResource($config['site_url'].'/js/library/jquery-ui/css/jquery-ui.css', 'css', true);
$template->addResource('js/index.js','js',true);
render(joinPath($config['site_folder'],'plugins/gender_divide/templates/index.php'), true, true);

function getPeople($id) {
	global $t_person;
	$people = $t_person->sort('nickname')->find(array('sex'=>$id));
	return $people;
}
