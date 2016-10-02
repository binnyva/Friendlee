<?php
require('../../common.php');

$person_id = i($QUERY, 'person_id');

$data = new Crud("Plugin_Data_Point");
$data->title = "Data Point";
$data->setListingQuery("SELECT id,name,data,value,status FROM Plugin_Data_Point WHERE user_id=$_SESSION[user_id] AND person_id=$person_id");
$data->setListingFields("name", "data", "status");
$data->addField("user_id", "User", 'int', array(), $_SESSION['user_id'], 'hidden');
$data->addField("person_id", "Person", 'int', array(), $person_id, 'hidden');
$data->setFormFields("name", "data", 'user_id', 'person_id');


render(joinPath($config['site_folder'],'plugins/data_point/templates/person.php'), true, true);