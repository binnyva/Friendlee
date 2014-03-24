<link rel="stylesheet" href="js/library/jquery-ui/css/jquery-ui.css" />
<h1>People</h1>
<form method="post" action="">
<input type="submit" name="recalculate_points" value="Recalculate Points" class="btn btn-success pull-right" />
</form>
<input type="button" onclick="saveOrder()" value="Save Configuration" class="btn btn-primary" /><br />

<div class="row">
<?php foreach($level as $level_id => $info) { ?>
<div class="col-md-6"><div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title"><?php echo $info['name'] ?> <span class='badge'><?php echo count($info['people']); ?></span></h3></div>
  <div class="panel-body level big-list-holders">
<ul id="level-<?php echo $level_id ?>" class="friend-level big-list">
<?php foreach($info['people'] as $person) { ?>
<li id="person_<?php echo $person['id'] ?>" class="ui-state-default btn btn-default"><a href="person.php?person_id=<?php echo $person['id'] ?>"><?php
	echo (empty($person['name']) ? $person['nickname'] : $person['name']) . " <span class='badge'>$person[point]</span>" ?></a></li>
<?php } ?>
</ul>
</div>
</div>
</div>
<?php } ?>
</div>
