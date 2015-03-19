<?php
require('common.php');

// $all_cities = $sql->getById("SELECT id,name FROM City WHERE user_id='$_SESSION[user_id]' ORDER BY name");
// render();
$crud = new Crud('City');
$crud->setListingQuery("SELECT * FROM City WHERE user_id='$_SESSION[user_id]' ORDER BY name");
$crud->setListingFields("name");
//$crud->render();
render();
