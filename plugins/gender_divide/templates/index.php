<h1>Gender Divide</h1>
<input type="button" onclick="saveOrder()" value="Save Configuration" class="btn btn-primary" /><br />

<div class="row">
<?php foreach($genders as $gender_id => $info) { ?>
<div class="col-md-6"><div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title"><?php echo $info['name'] ?> <span class='badge'><?php echo count($info['people']); ?></span></h3></div>
  <div class="panel-body gender size-<?php 
$size = intval(count($info['people']) / 10); 
if($size >= 5) echo 'big';
elseif($size >= 1) echo 'medium';
else echo 'normal'; ?>" id="gender-area-<?php echo $gender_id ?>">

<ul id="gender-<?php echo $gender_id ?>" class="friend-gender big-list">
<?php foreach($info['people'] as $person) { ?>
<li id="person_<?php echo $person['id'] ?>" class="ui-state-default btn btn-default"><a href="person.php?person_id=<?php echo $person['id'] ?>"><?php echo (empty($person['name']) ? $person['nickname'] : $person['name']) . " <span class='badge'>$person[point]</span>" ?></a></li>
<?php } ?>
</ul>
</div>
</div>
</div>
<?php } ?>
</div>
