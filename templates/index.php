<div id="top-area" class="row">
<div class="col-md-6">
<?php include('templates/uncontacted.php'); ?>
</div>

<div class="col-md-3 links-collection d-none d-md-block">
<ul>
<li><a href="tree.php" class="with-icon site">All Friends</a></li>
<li><a href="uncontacted.php" class="with-icon phone">Uncontacted Friends</a></li>
</ul>
</div>

<?php if($activate_plugins) { ?>
<div class="col-md-3 links-collection d-none d-md-block">
<ul>
<?php
$plugins = ls("*", joinPath($config['site_folder'], 'plugins'));
foreach($plugins as $p) {
	if(file_exists(joinPath($config['site_folder'], 'plugins', $p, 'index.php')))
		print "<li><a href='plugins/$p' class='with-icon plugin'>".format(trim($p,'/'))."</a></li>\n";
}
?>
</ul>
</div>
<?php } ?>
</div>

<form action="" method="get" id="change-day-form"><input type="hidden" name="date" id="date" value="" /></form>

<div class='wrapper text-center'>
<?php 
$date_format = 'd<\s\u\p>S<\/\s\u\p> M';
?>
<div class="btn-group mx-auto" role="group" aria-label="Browse thru Dates">
	<a class="btn btn-secondary previous-day" href="?date=<?php echo date('Y-m-d', strtotime($date) - (60*60*24)); ?>"><i class="fas fa-angle-left"></i><?php echo date($date_format, strtotime($date) - (60*60*24)); ?></a>
	<span class="btn btn-dark curdate"><?php echo date($date_format, strtotime($date)); ?> 
		<span class="d-none d-md-inline"><?php echo date('(l)', strtotime($date)); ?></span>
		<a href="#" id="change-day" class="icon calendar">Change</a></span>
	<?php if(date('Y-m-d', strtotime($date) + (60*60*24)) <= date('Y-m-d')) { ?>
	<a class="btn btn-secondary next-day" href="?date=<?php  echo date('Y-m-d', strtotime($date) + (60*60*24)); ?>"><?php echo date($date_format, strtotime($date) + (60*60*24)); ?> <i class="fas fa-angle-right"></i></a>
	<?php } ?>
</div>
</div>

<form action="" method="post">
<input type="hidden" name="date" value="<?php echo $date ?>" />

<div class="container" id="contact-area">
<div class="row">
<div class="col-md-6"><?php showBox('message'); ?></div>
<div class="col-md-6"><?php showBox('met'); ?> <?php $i_plugin->callHook('main_box_met_show_under'); ?></div>
</div>
<div class="row">
<div class="col-md-6"><?php showBox('phone'); ?></div>
<div class="col-md-6"><?php showBox('other'); ?></div>
</div>
</div>


<br />
<input class="btn btn-primary" type="submit" name="action" value="Save" />
</form>

<?php
function showBox($name, $title='') {
	if(!$title) $title = format($name);
	?>
<fieldset class="big-list-holders">
<legend><?php echo $title; ?></legend>
<input type="text" class="data form-control" name="<?php echo $name ?>" id="<?php echo $name ?>" value="" />

<?php showConnections($name); ?>
</fieldset>
<?php
}

function showConnections($name) {
	global $sql, $people, $date, $t_connection;
	$all_connections = $t_connection->getConnectionsOnDate($date, $name);
	
	if($all_connections) {
		print "<ul class='big-list'>";
		foreach($all_connections as $con) {
			$all_people = array();

			$all_people_connections = $t_connection->getPeopleIdsInConnection($con['id']);

			// Show the count of the number of people in this meet - if its more than 4
			$count = '';
			if(count($all_people_connections) > 4) $count = '('.count($all_people_connections).')';
			
			foreach($all_people_connections as $person_id) {
				$person = $people[$person_id];
				$all_people[] = '<a href="person.php?person_id='.$person_id.'" title="' . $person['nickname'] . '">' . firstName($person['nickname']) . '</a>';
			}
			
			if($all_people)  print "<li class='btn btn-success'>" . implode(', ', $all_people)
									. " <a href='popup/connection_details.php?connection_id=$con[id]' class='popup edit icon'>Details</a> $count</li>";
		}
		print "</ul>";
	}
}
