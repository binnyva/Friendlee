<?php
function cq_showAddToQueue($person) {
	$queued_people_ids = iapp('db')->getCol("SELECT person_id FROM Plugin_Contact_Queue WHERE user_id=$_SESSION[user_id] AND contacted='0'");
	?>
	<td><input type="button" class="btn btn-success btn-sm contact-queue" 
			data-url="plugins/contact_queue/queue.php?person_id=<?php echo $person['id'] ?>" <?php
			if(in_array($person['id'], $queued_people_ids)) echo 'value="Qed" disabled="disabled"';
			else echo 'value="Q"';
			?> /></td>
	<?php
}
$this->addHook("display_uncontacted_people_row", "cq_showAddToQueue");

function cq_showQueueHeader() {
	print "<th>Queue</th>";
}
$this->addHook("display_uncontacted_people_header", "cq_showQueueHeader");

function cq_insertQueueJsCode() {
	print '<script src="' . iframe\App::$config['app_url'] . 'plugins/contact_queue/script.js"  type="text/javascript"></script>' . "\n";
}
$this->addHook("display_page_end", "cq_insertQueueJsCode");


function cq_changeQueueStatusOnContact($person_id, $type='any') {
	global $sql;

	$queue_id = $sql->getOne("SELECT id FROM Plugin_Contact_Queue 
		WHERE person_id='$person_id' AND user_id='$_SESSION[user_id]' AND contacted='0'");
	if($queue_id) {
		$sql->execQuery("UPDATE Plugin_Contact_Queue SET contacted='1' WHERE id=$queue_id");
	}
}
$this->addHook("action_person_connection_made", "cq_changeQueueStatusOnContact");
