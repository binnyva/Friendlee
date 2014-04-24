<?php
require('../common.php');

$text = str_split(strtolower(i($QUERY,'text','a'))); // p-personal, t-technical, a-call to action


render();

