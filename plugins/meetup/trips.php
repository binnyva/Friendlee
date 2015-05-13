<?php
require('../../common.php');

$city_id = i($QUERY, 'city_id','0');
$where = '';
if($city_id) {
	$where = " AND city_id=$city_id";
}
$all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id] ORDER BY name");
$all_cities[0] = 'All';

$html = new HTML;
$crud = new Crud('Plugin_Meetup_Trip', "Trips");
$crud->code['top'] = '<form action="" method="get">
<label for="city_id">Filter by City: </label>' . $html->buildDropDownArray($all_cities,"city_id",$city_id, array(), false) .
'<input type="submit" name="action" value="Go" />
</form>';

$crud->setListingQuery("SELECT * FROM Plugin_Meetup_Trip WHERE user_id=$_SESSION[user_id] $where ORDER BY start_on DESC");
$crud->setListingFields(array('city_id', 'start_on', 'end_on', 'status'));
$crud->addField('status', 'Trip Status', 'enum', array(), array('projected' => "Future", 'ongoing' => "Ongoing", 'done'=>"Done"), 'select');

$crud->render();
