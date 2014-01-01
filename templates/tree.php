<link rel="stylesheet" href="js/library/jquery-ui/css/jquery-ui.css" />
<h1>People</h1>
<input type="button" onclick="saveOrder()" value="Save Configuration" /><br />

<?php foreach($level as $level_id => $info) { ?>
<div class="level big-list-holders">
<h3><?php echo $info['name'] ?></h3>

<ul id="level-<?php echo $level_id ?>" class="friend-level big-list">
<?php foreach($info['people'] as $person) { ?>
<li id="person_<?php echo $person['id'] ?>" class="ui-state-default"><a href="person.php?person_id=<?php echo $person['id'] ?>"><?php echo (empty($person['name']) ? $person['nickname'] : $person['name']) . " ($person[point])" ?></a></li>
<?php } ?>
</ul>
</div>
<?php } ?>

