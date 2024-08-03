<?php
require('../../common.php') ;
use iframe\iframe\Crud;

$crud = new Crud('Plugin_Contact_Queue');
$crud->setListingQuery("SELECT CA.* FROM Plugin_Contact_Queue CA 
                            INNER JOIN Person P ON P.id=CA.person_id 
                            WHERE P.user_id=$_SESSION[user_id] AND CA.contacted='0'
                            ORDER BY CA.added_on");
$crud->setListingFields('person_id', 'added_on', 'contacted');
$crud->addListDataField('person_id', 'Person', 'Person', 'user_id='.$_SESSION['user_id'] . ' ORDER BY nickname', array('fields' => 'id,nickname'));
$crud->addField("contacted", "Contacted", 'varchar', array(), array('0'=>'No', '1'=>'Contacted'), 'select');

render(joinPath($config['app_folder'],'plugins/contact_queue/templates/index.php'), true, true); 
