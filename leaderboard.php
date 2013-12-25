<?php
require('./common.php');

$people = $t_person->sort('point DESC,nickname')->get();
render();