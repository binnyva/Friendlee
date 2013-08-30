<?php
require('../common.php');

$connection_id = intval($QUERY['connection_id']);

$sql->delete('PersonConnection', array('connection_id'=>$connection_id));
$affected = 1; $sql->delete('Connection', array('id'=>$connection_id));

if($affected) {
	showAjaxMessage('Connection deleted','success');
} else {
	showAjaxMessage('Could not delete connection','error');
}
