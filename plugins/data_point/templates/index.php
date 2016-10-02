<h2>3 Things</h2>

<ul>
<?php foreach($people as $person_id => $name) { ?>
<li><a href="person.php?person_id=<?php echo $person_id; ?>"><?php echo $name ?></a></li>
<?php } ?>
</ul>