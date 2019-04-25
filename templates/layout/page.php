<!DOCTYPE HTML>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $title?></title>
<link href="<?php echo $abs?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>images/silk_theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>js/library/calendar/calendar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $abs?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
<!-- <link href="<?php echo $abs?>bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet"> -->
<link href="<?php echo $abs?>bower_components/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo $abs?>bower_components/jquery-ui/themes/base/minified/jquery.ui.autocomplete.min.css" rel="stylesheet">
<link href="<?php echo $abs?>bower_components/components-font-awesome/css/fontawesome-all.min.css" rel="stylesheet">

<?php 
echo $css_includes;
$i_plugin->callHook("display_page_head");
?>
<script type="text/javascript">
var people = <?php echo json_encode($all_people); ?>;
var people_with_points = <?php echo json_encode($all_people_with_points); ?>;
</script>
</head>
<body>
<div id="loading">loading...</div>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
<div class="container">
  <a class="navbar-brand" href="<?php echo $abs ?>"><?php echo $config['site_title'] ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main" aria-controls="navbar-main" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse my-0" id="navbar-main">
    <ul class="navbar-nav ml-auto">
    	<?php if(!empty($_SESSION['user_id'])) { ?>
		<li class="nav-item mr-3"><form action="<?php echo $config['site_url']; ?>search.php" method="post" id="search-area" class="form-inline my-2 my-md-0 input-group">
			<input type="text" name="search" id="search" placeholder="Search..." value="<?php if(isset($search_term)) echo $search_term ?>" class="form-control" />
			<div class="input-group-append"><button type="submit" class="btn btn-outline-secondary btn-default"><i class="fas fa-search"></i></button></div>
		</form></li>
		<li class="nav-item"><a class="nav-link site with-icon" href="<?php echo $config['site_url'] ?>tree.php">People</a></li>
		<li class="nav-item"><a class="nav-link calendar with-icon" href="<?php echo $config['site_url'] ?>?date=<?php echo date('Y-m-d', strtotime('yesterday')); ?>">Yesterday</a></li>
		<li class="nav-item"><a class="nav-link add with-icon" href="<?php echo $config['site_url'] ?>?date=today">Today</a></li>
		<?php if(!isset($config['single_user']) or !$config['single_user']) { ?>
		<li class="nav-item"><a class="nav-link logout with-icon" href="<?php echo $config['site_url'] ?>user/logout.php">Logout</a></li>
	<?php }
		} else { ?>
		<li class="nav-item"><a class="nav-link info with-icon" href="<?php echo $config['site_url'] ?>about/">About</a></li>
	<?php } ?>
    </ul>
  </div>
</div>
</nav>

<div id="content" class="container">

<div class="message-area" id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
	if(!empty($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
	else print $QUERY['error']; //Its set in the code(validation error or something).
?></div>
<div class="message-area" id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>


<div id="popup-area-holder" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="closePopup()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="popup-area">
	  </div>
    </div>
  </div>
</div>

<?php 
$i_plugin->callHook("display_content_top");
?>

<!-- Begin Content -->
<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

if(isset($crud) and $GLOBALS['template']->template == '') {
	$crud->printAction();
} else {
	include($GLOBALS['template']->template); 
}

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>

<?php 
$i_plugin->callHook("display_content_end");
?>

<!-- End Content -->
</div>

<div id="footer"></div>
<script src="<?php echo $config['site_url']; ?>bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
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