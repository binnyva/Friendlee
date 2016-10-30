<?php
require('../../common.php');

$people = $sql->getAll("SELECT person_id,Person.nickname AS name, PDP.name as data_point FROM Plugin_Data_Point PDP
		INNER JOIN Person ON PDP.person_id = Person.id WHERE PDP.user_id=$_SESSION[user_id]");

render(joinPath($config['site_folder'],'plugins/data_point/templates/index.php'), true, true); 
