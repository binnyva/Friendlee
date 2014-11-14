<?php
function ca_injectAttepmtData($data) {
	global $sql;

	$attempt_data = $sql->getById("SELECT CA.person_id, COUNT(CA.id) AS count FROM Plugin_Contact_Attempt CA INNER JOIN Person P ON P.id=CA.person_id WHERE P.user_id=$_SESSION[user_id] GROUP BY CA.person_id");

	foreach($data as $pid => $info) {
		$data[$pid]['contact_attempt'] = i($attempt_data, $pid, 0);
	}
	
	return $data;
}
$this->addHook("data_uncontacted_people", "ca_injectAttepmtData");


function ca_showAttepmtCount($person) {
	?>
	<td><span id="attempts-<?php echo $person['id'] ?>"><?php echo $person['contact_attempt'] ?></span>
	<a class="btn btn-default btn-xs contact-attempt" href="plugins/attempt_contact/change.php?person_id=<?php echo $person['id'] ?>&amp;direction=1&amp;old_value=<?php echo $person['contact_attempt'] ?>">+</a>
	</td>
	<?php
}
$this->addHook("display_uncontacted_people_row", "ca_showAttepmtCount");

function ca_showAttepmtHeader() {
	print "<th>Attempts</th>";
}
$this->addHook("display_uncontacted_people_header", "ca_showAttepmtHeader");

function ca_showJsCode() {
	global $config;
	print '<script src="' . $config['site_url'] . 'plugins/attempt_contact/script.js"  type="text/javascript"></script>';
}
$this->addHook("display_page_end", "ca_showJsCode");