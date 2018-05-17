<?php
require('../../common.php') ;

$crud = new Crud('Plugin_Send');
$crud->addListDataField('person_id', 'Person', 'Person', 'user_id='.$_SESSION['user_id'] . ' ORDER BY nickname', array('fields' => 'id,nickname'));
$crud->addField('user_id', 'User', 'int',array(), $_SESSION['user_id'], 'hidden');
$crud->setListingFields('name', 'url', 'text', 'file', 'person_id', 'status');

render(joinPath($config['site_folder'],'plugins/send/templates/index.php'), true, true); 
