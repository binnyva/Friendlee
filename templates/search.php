<?php if(!$person) { ?>
<h3>Not Found</h3>

<p>There is no one by the name of '<?php echo $search_term ?>' in our database.</p>

<form action="search_person.php" method="post">
<input type="text" name="search" id="search" placeholder="Search again..." value="<?php echo $search_term ?>" />
<input type="submit" class="btn-primary btn" value="Search" />
</form>

<?php } else { ?>
<h3><?php echo count($person) ?> result(s) for '<?php echo $search_term ?>'</h3>

<ul>
<?php foreach ($person as $p) {
	print "<li><a href='person.php?person_id=$p[id]'>$p[nickname] <span class='badge'>$p[point]</span></a></li>\n";
}
?>
</ul>

<?php } ?>