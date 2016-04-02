<?php

function ca_injectAttepmtData($data) {
	global $sql;

	// Find how many times the attempt was made after the last succesfull attempt.
	$attempt_data = $sql->getById("SELECT CA.person_id, COUNT(CA.id) AS count 
		FROM Plugin_Contact_Attempt CA 
		INNER JOIN Person P ON P.id=CA.person_id 
		WHERE P.user_id=$_SESSION[user_id] AND CA.status!='replied' AND CA.action_taken!='demote'
			AND CA.attempt_on > IFNULL((SELECT attempt_on FROM Plugin_Contact_Attempt WHERE person_id=CA.person_id AND (status='replied' OR action_taken='demote') ORDER BY attempt_on DESC LIMIT 0,1), '0000-00-00 00:00:00')
		GROUP BY CA.person_id");

	foreach($data as $pid => $info) {
		$attempt_count = i($attempt_data, $pid, 0);
		$data[$pid]['contact_attempt'] = $attempt_count;
	}
	return $data;
}
$this->addHook("data_uncontacted_people", "ca_injectAttepmtData");


function ca_showAttepmtCount($person) {
	$ca_number_of_attempts_before_demote_option = 3;

	?>
	<td><span id="attempts-<?php echo $person['id'] ?>"><?php echo $person['contact_attempt'] ?></span>
	<a class="btn btn-default btn-xs contact-attempt" href="plugins/attempt_contact/change.php?person_id=<?php echo $person['id'] ?>&amp;direction=1&amp;old_value=<?php echo $person['contact_attempt'] ?>">+</a>
	<?php if($person['contact_attempt'] >= $ca_number_of_attempts_before_demote_option) { ?>
	<a class="btn btn-danger btn-xs demote" href="plugins/attempt_contact/demote.php?person_id=<?php echo $person['id'] ?>">Demote</a>
	<?php } ?>
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


function ca_changeAttemptStatusOnContact($person_id, $type='any') {
	global $sql;

	$attempt_id = $sql->getOne("SELECT id FROM Plugin_Contact_Attempt WHERE person_id='$person_id' AND action_taken='none' AND status='none' ORDER BY attempt_on DESC LIMIT 0,1");
	if($attempt_id) {
		$sql->execQuery("UPDATE Plugin_Contact_Attempt SET status='replied',attempt_type='$type' WHERE id=$attempt_id");
	}
}
$this->addHook("action_person_connection_made", "ca_changeAttemptStatusOnContact");