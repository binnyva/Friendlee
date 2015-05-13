<h1>Cities</h1>
<?php include('_nav.php'); ?>

<input type="button" onclick="saveOrder()" value="Save Configuration" class="btn btn-primary" /><br />

<?php foreach($city as $city_id => $info) { ?>
<div class="city size-<?php 
$size = intval(count($info['people']) / 10); 
if($size >= 5) echo 'big';
elseif($size >= 1) echo 'medium';
else echo 'normal'; ?>" id="city-area-<?php echo $city_id ?>">
<h3><?php echo $info['name'] . " (".count($info['people']).")"; ?> </h3>

<ul id="city-<?php echo $city_id ?>" class="friend-city">
<?php foreach($info['people'] as $person) { ?>
<li id="person_<?php echo $person['id'] ?>" class="ui-state-default"><a href="../../person.php?person_id=<?php echo $person['id'] ?>"><?php echo (empty($person['name']) ? $person['nickname'] : $person['name']) . " ($person[point])" ?></a></li>
<?php } ?>
</ul>
</div>
<?php } ?>

