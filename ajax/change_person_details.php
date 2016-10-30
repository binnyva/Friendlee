<?php
require('../common.php');

$person_id = intval($QUERY['person_id']);

$fields_to_update = array('name','nickname','email','phone', 'sex','facebook_id','twitter','birthday','city_id','locality','level_id', 'note', 'automanaged', 'autocomplete');

$data = array();
foreach($fields_to_update as $f) $data[$f] = $QUERY[$f];

$t_person->field = $data;
$t_person->save($person_id);

showMessage("Saved details of '".$QUERY['nickname']."'", joinPath($config['site_url'], "person.php?person_id=$person_id"));
