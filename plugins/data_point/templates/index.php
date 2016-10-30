<h2>Data Points</h2>

<ul>
<?php foreach($people as $data) { ?>
<li><a href="person.php?person_id=<?php echo $data['person_id']; ?>"><?php echo "$data[name] ($data[data_point])" ?></a></li>
<?php } ?>
</ul>