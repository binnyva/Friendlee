<div id="top-area" class="row">
<?php include('templates/uncontacted.php'); ?>

<div id="links" class="col-md-6">
<div class="col-md-6">
<ul>
<li><a href="tree.php" class="with-icon site">All Friends</a></li>
<li><a href="uncontacted.php" class="with-icon phone">Uncontacted Friends</a></li>
<?php if($_SESSION['user_id'] == 1) { ?><li><a href="leaderboard.php" class="with-icon info">Friends Leaderboard</a></li><?php } ?>
</ul>
</div>
<div class="col-md-6">
<?php if($activate_plugins) { ?>
<ul>
<?php
$plugins = ls("*", joinPath($config['site_folder'], 'plugins'));
foreach($plugins as $p) {
	if(file_exists(joinPath($config['site_folder'], 'plugins', $p, 'index.php')))
		print "<li><a href='plugins/$p' class='with-icon plugin'>".format(trim($p,'/'))."</a></li>\n";
}
?>
</ul>
<?php } ?>

</div>
</div>
</div>

<form action="" method="get" id="change-day-form"><input type="hidden" name="date" id="date" value="" /></form>

<div class="container">
<ul id="date-changer" class="btn-group btn-group-justified center-block">
<li class="btn btn-default"><a class="previous previous-day with-icon" href="?date=<?php echo date('Y-m-d', strtotime($date) - (60*60*24)); ?>">Previous Day(<?php echo date('dS M', strtotime($date) - (60*60*24)); ?>)</a></li>
<li class="btn btn-default"><span class="curdate"><?php echo date('dS M(l)', strtotime($date)); ?> <a href="#" id="change-day" class="icon calendar">Change</a></span></li>
<?php if(date('Y-m-d', strtotime($date) + (60*60*24)) <= date('Y-m-d')) { ?>
<li class="btn btn-default"><a class="next next-day with-icon" href="?date=<?php 
		echo date('Y-m-d', strtotime($date) + (60*60*24)); ?>">Next Day(<?php echo date('dS M', strtotime($date) + (60*60*24)); ?>)</a></li><?php } ?>
</ul>
</div>

<form action="" method="post">
<input type="hidden" name="date" value="<?php echo $date ?>" />

<div class="container" id="contact-area">
<div class="row">
<div class="col-md-6"><?php showBox('message', 'Whatsapp/Text'); ?></div>
<div class="col-md-6"><?php showBox('met'); ?> <a href="#" id="multi-meet">Multi Meet</a></div>
</div>
<div class="row">
<div class="col-md-6"><?php showBox('chat'); ?></div>
<div class="col-md-6"><?php showBox('phone'); ?></div>
</div>
<div class="row">
<div class="col-md-6"><?php showBox('email'); ?></div>
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
<input type="text" class="data" name="<?php echo $name ?>" id="<?php echo $name ?>" value="" />

<?php showConnections($name); ?>
</fieldset>
<?php
}

function showConnections($name) {
	global $sql, $people, $date;
	$all_connections = $sql->getAll("SELECT id FROM Connection WHERE user_id=$_SESSION[user_id] AND DATE(start_on)='$date' AND type='$name'");
	
	if($all_connections) {
		print "<ul class='big-list'>";
		foreach($all_connections as $con) {
			$all_people = array();

			$all_people_connections = $sql->getAll("SELECT person_id FROM PersonConnection WHERE connection_id=$con[id]");
			foreach($all_people_connections as $pep_con) {
				$person = $people[$pep_con['person_id']];
				$all_people[] = '<a href="person.php?person_id='.$pep_con['person_id'].'">'
					. stripslashes((empty($person['name']) ? $person['nickname'] : $person['name'])) 
					. '</a>';
			}
			
			if($all_people)  print "<li class='btn btn-default'>".implode(', ', $all_people)." <a href='ajax/delete_connection.php?connection_id=$con[id]' "
									. "class='ajaxify ajaxify-remove-parent ajaxify-confirm delete icon' title=\"Delete '". stripslashes(empty($person['name']) ? $person['nickname'] : $person['name']) ."' connection\">Delete</a>"
									. " <a href='popup/connection_details.php?connection_id=$con[id]' class='popup edit icon'>Details</a></li>";
		}
		print "</ul>";
	}
}
