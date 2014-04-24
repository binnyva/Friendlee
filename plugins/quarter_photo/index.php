<?php
require('../../common.php');

$every_x_points = 25;
$sex = i($QUERY, 'sex','f');

$people = $t_person->sort('nickname')->find(array('user_id'=>$_SESSION['user_id'], 'sex'=>$sex));
$sent_photos = $sql->getById("SELECT DISTINCT person_id, point_status FROM Plugin_Quarter_Photo 
			WHERE user_id=$_SESSION[user_id] ORDER BY sent_on DESC");

$template->addResource('js/index.js','js',true);
render(joinPath($config['site_folder'],'plugins/quarter_photo/templates/index.php'), true, true);
