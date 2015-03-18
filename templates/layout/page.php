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
<link href="<?php echo $abs?>bower_components/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo $abs?>bower_components/jquery-ui/themes/base/minified/jquery.ui.autocomplete.min.css" rel="stylesheet">

<?php 
echo $css_includes;
$i_plugin->callHook("display_page_head");
?>
<script type="text/javascript">
var people = <?php echo json_encode($all_people); ?>;
</script>
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
	  <a class="navbar-brand" href="<?php echo $abs ?>"><?php echo $config['site_title'] ?></a>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav pull-right">
	<?php if(!empty($_SESSION['user_id'])) { ?>
		<li><form action="<?php echo $config['site_url']; ?>search.php" method="post" id="search-area" class="input-group input-group-sm">
<input type="text" name="search" id="search" placeholder="Search..." value="<?php if(isset($search_term)) echo $search_term ?>" class="form-control" />
<span class="input-group-btn"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button></span>
</form></li>
		<li><a class="site with-icon" href="<?php echo $config['site_url'] ?>tree.php">People</a></li>
		<li><a class="calendar with-icon" href="<?php echo $config['site_url'] ?>?date=<?php echo date('Y-m-d', strtotime('yesterday')); ?>">Yesterday</a></li>
		<li><a class="add with-icon" href="<?php echo $config['site_url'] ?>">Today</a></li>
		<?php if(!isset($config['single_user']) or !$config['single_user']) { ?>
		<li><a class="logout with-icon" href="<?php echo $config['site_url'] ?>user/logout.php">Logout</a></li>
	<?php }
		} else { ?>
		<li><a class="info with-icon" href="<?php echo $config['site_url'] ?>about/">About</a></li>
	<?php } ?>
		</ul>
	</div>

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

<?php 
$i_plugin->callHook("display_content_top");
?>

<!-- Begin Content -->
<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

include($GLOBALS['template']->template); 

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>

<?php 
$i_plugin->callHook("display_content_end");
?>

<!-- End Content -->
</div>

<div id="footer"></div>

<script src="<?php echo $config['site_url']; ?>bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $config['site_url']; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $config['site_url']; ?>js/library/ajaxify.js" type="text/javascript"></script>
<script src="<?php echo $config['site_url']; ?>js/library/calendar/calendar.js" type="text/javascript"></script>
<script src="<?php echo $config['site_url']; ?>js/application.js" type="text/javascript"></script>
<script src="<?php echo $config['site_url']; ?>bower_components/jquery-ui/ui/minified/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $config['site_url']; ?>bower_components/jquery-ui/ui/minified/jquery.ui.autocomplete.min.js" type="text/javascript"></script>

<?php 
echo $js_includes;
$i_plugin->callHook("display_page_end");
?>
</body>
</html>