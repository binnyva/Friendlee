<?php
require("../../common.php");

$city = array();
$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id]");
$all_cities[0] = 'None';
foreach($all_cities as $id=>$name) {
	$city[$id] = array(
		'name'	=> $name,
		'people'=> getPeople($id)
	);
}
ksort($city);

$template->addResource('library/jquery-ui/jquery-ui.min.js','js');
$template->addResource($config['site_url'].'/js/library/jquery-ui/css/jquery-ui.css', 'css', true);
$template->addResource('css/cities.css','css',true);
$template->addResource('js/cities.js','js',true);
render(joinPath($config['site_folder'],'plugins/meetup/templates/cities.php'), true, true);

function getPeople($city_id) {
	global $t_person;
	$people = $t_person->sort('nickname')->find(array('city_id'=>$city_id));
	return $people;
}
