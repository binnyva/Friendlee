<?php
require('../../common.php');
$html = new HTML;
if(i($QUERY, 'action')) {
	$city_id = $QUERY['city_id'];
	
	$people = $t_person->find(array('city_id'=>$city_id));
	render(joinPath($config['site_folder'],'plugins/meetup/templates/show_people.php'), true, true);
	
} else {
	
	render(joinPath($config['site_folder'],'plugins/meetup/templates/index.php'), true, true);
}


