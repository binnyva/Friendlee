<?php
require('./common.php');

$people = $t_person->where(array('user_id'=>$_SESSION['user_id']))->sort('point DESC,nickname')->get();
render();