<form action="" method="post">
<?php foreach($people as $p) { ?>
	<input type="checkbox" name="person_id[]" id="person_<?php echo $p['id'] ?>" value="1" />
	<label for="person_<?php echo $p['id'] ?>"><?php echo $p['nickname'] ?></label><br />
<?php } ?>
</form>
