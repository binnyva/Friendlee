<?php
require('../common.php');

$org_person_id = intval($_REQUEST['org_id']);
$dup_person_id = intval($_REQUEST['dup_id']);

if(!$org_person_id or !$dup_person_id) {
	echo "Invalid arguments - Remove_Duplicate.php?org_id=ORIGINAL_PRESONS_ID&dup_id=DUPLICATE_ENTRY_ID";
	exit;
}

$org = $t_person->find($org_person_id);
$dup = $t_person->find($dup_person_id);

if(i($_REQUEST,'sure') == 'true') {
	$sql->execQuery("UPDATE PersonConnection SET person_id=$org_person_id WHERE person_id=$dup_person_id");
	$sql->execQuery("DELETE FROM Person WHERE id=$dup_person_id");

	print "Changed all references of $dup[nickname] to $org[nickname] - and deleted $dup[nickname]";
} else {
	print "Change all references of $dup[nickname] to $org[nickname] - and delete $dup[nickname]? Are you <a href='" .getLink('Remove_Duplicate.php', array('sure'=>'true'), true). "'>sure</a>?";
}


