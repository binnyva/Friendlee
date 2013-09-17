<?php
include('common.php');

$people = new Crud('Person');

$people->setListingFields('name','nickname','email','phone','facebook','level_id','status');
$people->setListingQuery("SELECT * FROM Person ORDER BY nickname");

if(i($QUERY, 'action') == 'add') {
	$_GET['user_id'] = $_SESSION['user_id'];
	$_GET['status'] = 1;
	$_GET['level_id'] = 2;
}

$people->render();
