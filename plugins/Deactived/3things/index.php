<?php
require('../../common.php');

$people = $sql->getById("SELECT person_id AS id,Person.nickname AS name FROM Plugin_3things
		INNER JOIN Person ON person_id = Person.id WHERE Plugin_3things.user_id=$_SESSION[user_id]");

render(joinPath($config['site_folder'],'plugins/3things/templates/index.php'), true, true); 
