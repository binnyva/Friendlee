<?php
require('../common.php');

$connection_id = intval($QUERY['connection_id']);

$affected = $t_connection->remove($connection_id);

if($affected) {
	showAjaxMessage('Connection deleted','success');
} else {
	showAjaxMessage('Could not delete connection','error');
}
