<?php
require '../common.php';

$date = date('l, dS M, Y');
$ymd = date('Y-m-d');

$body = <<<END
<p>Hi%FIRST_NAME%,</p>

<p>Who all did you meet on <strong>$date</strong>?</p>

<p><a href="$config[site_url]index.php?date=$ymd">Enter Data Here</a>.</p> 

<p>--<br />
<a href="$config[site_url]">$config[site_title]</a></p>
END;

$users = $sql->getAll("SELECT id,email,name FROM User WHERE status='1'");
foreach ($users as $user) {
	$replaces = array(
		'%NAME%'	=> ' ' . $user['name'],
		'%FIRST_NAME%'	=> ' ' . short_name($user['name']),
	);

	$body = str_replace(array_keys($replaces), array_values($replaces), $body);

	@email($user['email'], "Who did you meet on $date", $body);
	print "Emailed '$user[name]' at '$user[email]'\n";
}


/// Returns just the first name of the person.
function short_name($name) {
	return @reset(explode(' ', $name));
}