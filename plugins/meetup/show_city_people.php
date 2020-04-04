<?php
require('../../common.php');

$order = i($QUERY, 'order', 'point DESC');

$city_id = i($QUERY, 'city_id','0');
$where = array('user_id'=>$_SESSION['user_id']);
if($city_id) $where['city_id'] = $city_id;

$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id] ORDER BY name");
$all_cities[0] = 'All';

$people = $t_person->sort($order)->find($where);

$html = new iframe\HTML\HTML;

iapp('template')->addResource('js/show_city_people.js','js',true);
iapp('template')->addResource("../bower_components/jquery.tablesorter/js/jquery.tablesorter.min.js", "js");
render(joinPath($config['site_folder'],'plugins/meetup/templates/show_city_people.php'), true, true);
