<?php
require('../common.php');

$connection_id = intval($QUERY['connection_id']);

$sql->remove('PersonConnection', "connection_id=$connection_id");
$affected = $sql->remove('Connection', "id=$connection_id");

if($affected) {
	showAjaxMessage('Connection deleted','success');
} else {
	showAjaxMessage('Could not delete connection','error');
}
