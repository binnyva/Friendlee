<!DOCTYPE HTML>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title?></title>
<link href="<?php echo $abs?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>images/silk_theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>js/library/calendar/calendar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $abs?>bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<?php echo $css_includes ?>
</head>
<body>
<div id="loading">loading...</div>

<div id="header" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<div id="nav" class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	    <span class="sr-only">Toggle navigation</span>
	    <span class="icon-bar"></span>
	    <span class="icon-bar"></span>
	    <span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="<?php echo $abs ?>"><?php echo $title ?></a>
	</div>
	<?php if(!empty($_SESSION['user_id'])) { ?>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav pull-right">
		<li><a class="site with-icon" href="<?php echo $config['site_url'] ?>tree.php">People</a></li>
		<li><a class="calendar with-icon" href="<?php echo $config['site_url'] ?>?date=<?php echo date('Y-m-d', strtotime('yesterday')); ?>">Yesterday</a></li>
		<li><a class="add with-icon" href="<?php echo $config['site_url'] ?>">Today</a></li>
		<li><a class="logout with-icon" href="<?php echo $config['site_url'] ?>user/logout.php">Logout</a></li>
		</ul>
	</div>
	<?php } ?>
</div>
</div>

<div id="content" class="container">
<div class="message-area" id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
	if(!empty($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
	else print $QUERY['error']; //Its set in the code(validation error or something).
?></div>
<div class="message-area" id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>


<div id="popup-area-holder" class="panel panel-primary popup-holder">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<div class="panel-heading" id="popup-title"></div>

<div id="popup-area" class="panel-body"></div>
</div>

<!-- Begin Content -->
<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

include($GLOBALS['template']->template); 

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>
<!-- End Content -->
</div>

<div id="footer"></div>

<script src="<?=$abs?>bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
<script src="<?=$abs?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?=$abs?>js/library/ajaxify.js" type="text/javascript"></script>
<script src="<?php echo $abs?>js/library/calendar/calendar.js" type="text/javascript"></script>
<script src="<?=$abs?>js/application.js" type="text/javascript"></script>
<?php echo $js_includes ?>
</body>
</html>