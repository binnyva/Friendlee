<?php
require('common.php');

$title = 'Uncontacted People';
$template->setTitle($title);

require('includes/uncontacted.php');

$template->addResource("../bower_components/jquery.tablesorter/js/jquery.tablesorter.min.js", "js");
render();
