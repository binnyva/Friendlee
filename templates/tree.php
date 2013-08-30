<h1>People</h1>

<?php foreach($level as $info) { ?>
<h3><?php echo $info['name'] ?></h3>

<ul>
<?php foreach($info['people'] as $person) { ?>
<li><a href="person.php?id=<?php echo $person['id'] ?>"><?php echo $person['nickname'] ? $person['nickname'] : $person['name'] ?></a></li>
<?php } ?>
</ul>

<?php } ?>


