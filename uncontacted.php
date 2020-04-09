<?php
require('common.php');

$title = 'Uncontacted People';
iframe\App::$template->setTitle($title);

require('includes/uncontacted.php');

iframe\App::$template->addResource("bower_components/jquery.tablesorter/js/jquery.tablesorter.min.js", "js");
$included = false;
render();
