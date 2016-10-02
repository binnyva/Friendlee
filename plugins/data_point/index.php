<?php
require('../../common.php');

$people = $sql->getById("SELECT person_id AS id,Person.nickname AS name FROM Plugin_Data_Point
		INNER JOIN Person ON person_id = Person.id WHERE Plugin_Data_Point.user_id=$_SESSION[user_id]");

render(joinPath($config['site_folder'],'plugins/data_point/templates/index.php'), true, true); 
