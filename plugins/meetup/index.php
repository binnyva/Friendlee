<?php
require('../../common.php');
$html = new HTML;
$t_city = new DBTable('City');

$current_trip = $sql->getAssoc("SELECT T.id,T.name,T.city_id,C.name AS city_name,T.status,T.start_on 
		FROM Plugin_Meetup_Trip T INNER JOIN City C ON T.city_id=C.id
		WHERE T.status='ongoing'");

if(i($QUERY, 'action') == "Trip Done") {
	$sql->update("Plugin_Meetup_Trip",array(
			'end_on'	=> 'NOW()',
			'status'	=> 'done'
		), "id=$QUERY[trip_id]");
	$QUERY['action'] == '';
	$current_trip = false;
}

if(!$current_trip) {
	if(i($QUERY, 'action') == 'Find Me People...') {
		$current_trip['city_id'] = $QUERY['city_id'];
		$current_trip['start_on'] = $QUERY['start_on'];
		$current_trip['status'] = 'ongoing';
		$current_trip['city_name'] = $t_city->findOne(array('id'=> $QUERY['city_id']), "name");

		$sql->insert("Plugin_Meetup_Trip",array(
				'start_on'	=> $QUERY['start_on'],
				'city_id'	=> $QUERY['city_id'],
				'status'	=> 'ongoing'
			));
	} else {
		$template->addResource('js/index.js','js',true);
		$template->addResource('js/library/calendar.js','js');
		$template->addResource('js/library/calendar.css','css');
		render(joinPath($config['site_folder'],'plugins/meetup/templates/index.php'), true, true);
		exit;
	}
}

$people = $t_person->sort('level_id','point DESC')->find(array('city_id'=>$current_trip['city_id']));

$connections = $sql->getCol("SELECT DISTINCT person_id FROM Connection C INNER JOIN PersonConnection PC ON C.id=PC.connection_id
	WHERE DATE(C.start_on) >= '$current_trip[start_on]' AND C.type='met'");
render(joinPath($config['site_folder'],'plugins/meetup/templates/show_people.php'), true, true);
