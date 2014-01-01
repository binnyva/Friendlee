<h1>Leaderboard</h1>

<ol>
<?php
foreach($people as $p) print "<li><a href='person.php?person_id=$p[id]'>$p[point]) $p[nickname]</a></li>\n";
?>
</ol>