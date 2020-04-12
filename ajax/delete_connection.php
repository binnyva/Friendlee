<?php
require('../common.php');

$connection_id = intval($QUERY['connection_id']);

$affected = $t_connection->remove($connection_id);

if($affected) {
	iframe\App::showAjaxMessage('Connection deleted','success');
} else {
	iframe\App::showAjaxMessage('Could not delete connection','error');
}
