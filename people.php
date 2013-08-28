<?php
include('common.php');

$people = new Crud('Person');

$people->setListingFields('name','nickname','email','phone','facebook','level_id','status');
$people->render();
