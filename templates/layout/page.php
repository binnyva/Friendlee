<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $title?></title>
<link href="<?php echo $abs?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>images/silk_theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>js/library/calendar/calendar.css" rel="stylesheet" type="text/css" />
<?php echo $css_includes ?>
</head>
<body>
<div id="loading">loading...</div>
<div id="header">
<div id="nav">
<ul>
<li><a class="site with-icon" href="<?php echo $config['site_url'] ?>tree.php">People</a></li>
<li><a class="calendar with-icon" ="<?php echo $config['site_url'] ?>?date=<?php echo date('Y-m-d', strtotime('yesterday')); ?>">Yesterday</a></li>
<li><a class="add with-icon" href="<?php echo $config['site_url'] ?>">Today</a></li>
</ul>
</div>

<h1 id="logo"><a href="<?php echo $abs ?>"><?php echo $title ?></a></h1>

</div>

<div id="content">
<div class="message-area" id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
	if(isset($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
	else print $QUERY['error']; //Its set in the code(validation error or something.
?></div>
<div class="message-area" id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>

<!-- Begin Content -->
<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

include($GLOBALS['template']->template); 

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>
<!-- End Content -->
</div>

<div id="footer"></div>

<script src="<?=$abs?>js/library/jquery.js" type="text/javascript"></script>
<script src="<?=$abs?>js/library/ajaxify.js" type="text/javascript"></script>
<script src="<?php echo $abs?>js/library/calendar/calendar.js" type="text/javascript"></script>
<script src="<?=$abs?>js/application.js" type="text/javascript"></script>
<?=$js_includes?>
</body>
</html>