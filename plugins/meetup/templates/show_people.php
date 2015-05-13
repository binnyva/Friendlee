<h3>Trip to <?php echo $current_trip['city_name'] ?> from <?php echo date($config['date_format_php'], strtotime($current_trip['start_on'])); ?></h3>

<?php include('_nav.php'); ?>

<form action="" method="POST">
<input class="btn btn-success" type="submit" name="action" value="Trip Done" />
<input type="hidden" name="trip_id" value="<?php echo $current_trip['id'] ?>" />
</form>

<div class="container">
<div class="row">

<div class="col-xs-6">
<h4>Met</h4>
<ul>
<?php foreach($people as $p) { 
	if(in_array($p['id'], $connections)) echo "<li><a href='../../person.php?person_id=$p[id]'>".$p['nickname']."</a></li>\n";
} ?>
</ul>
</div>

<div class="col-xs-6">
<h4>Yet to Meet</h4>
<ul>
<?php foreach($people as $p) { 
	if(!in_array($p['id'], $connections)) echo "<li><a href='../../person.php?person_id=$p[id]'>".$p['nickname']."</a></li>\n";
} ?>
</ul>
</div>

</div>

</div>