<?php
require('../common.php');

$org_id = 81;
$dup_id = 197;

$sql->execQuery("UPDATE PersonConnection SET person_id=$org_id WHERE person_id=$dup_id");
$sql->execQuery("DELETE FROM Person WHERE id=$dup_id");