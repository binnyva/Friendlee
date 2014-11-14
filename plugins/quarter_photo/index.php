<?php
require('../../common.php');

$every_x_points = 25;
$sex = i($QUERY, 'sex','f');
$city_id = i($QUERY, 'city_id','0');
$where = array('user_id'=>$_SESSION['user_id'], 'sex'=>$sex);
if($city_id) $where['city_id'] = $city_id;

$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id] ORDER BY name");
$all_cities[0] = 'All';

$people = $t_person->sort('nickname')->find($where);
$sent_photos = $sql->getById("SELECT DISTINCT person_id, MAX(point_status) FROM Plugin_Quarter_Photo 
			WHERE user_id=$_SESSION[user_id] GROUP BY person_id");

$template->addResource('js/index.js','js',true);

$html = new HTML;
render(joinPath($config['site_folder'],'plugins/quarter_photo/templates/index.php'), true, true);
