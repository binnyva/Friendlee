<?php
class Person extends DBTable {
	function __construct() {
		parent::__construct("Person");
	}

	function demote($person_id) {
		global $sql;

		$sql->execQuery("UPDATE Person SET level_id=level_id+1 WHERE id=$person_id");
	}

}