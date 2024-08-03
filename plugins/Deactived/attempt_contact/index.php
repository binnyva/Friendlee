<?php
require('../../common.php') ;
use iframe\iframe\Crud;

$crud = new Crud('Plugin_Contact_Attempt');
$crud->setListingQuery("SELECT CA.* FROM Plugin_Contact_Attempt CA INNER JOIN Person P ON P.id=CA.person_id WHERE P.user_id=$_SESSION[user_id] ORDER BY CA.attempt_on DESC");
$crud->addListDataField('person_id', 'Person', 'Person', 'user_id='.$_SESSION['user_id'] . ' ORDER BY nickname', array('fields' => 'id,nickname'));
$crud->addField("status", "Status", 'varchar', array(), array('none'=>'None', 'unresponsive'=>'Unresponsive', 'replied'=>'Replied'), 'select');

render(joinPath($config['app_folder'],'plugins/attempt_contact/templates/index.php'), true, true); 
