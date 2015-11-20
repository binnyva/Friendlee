<?php
class Person extends DBTable {
	function __construct() {
		parent::__construct("Person");
	}

	function demote($person_id) {
		global $sql;

		$sql->execQuery("UPDATE Person SET level_id=level_id+1 WHERE id=$person_id");
	}

	function add($nickname) {
		// A very rough gender detection.	
		$first_name = @reset(explode(" ", $nickname));
		$last_letter = substr($first_name, -1);
		$sex = 'm';

		if(in_array($last_letter, array('a','e','i','o','u'))) $sex = 'f'; // If the last letter of the first name is a vowel, make the preson female. Not 100% accurate - but good enough. Will have exceptions like Cathy, etc.

		$person_data = array(
				'nickname'	=> stripslashes($nickname),
				'status'	=> 1,
				'level_id'	=> 5, // Set them as level 5 by default - no contact.
				'sex'		=> $sex,
				'user_id'	=> $_SESSION['user_id'],
			);

		// The person is not there in the DB, add him.
		$person_id = $this->set($person_data)->save();
		$person_data['id'] = $person_id;

		return $person_data;
	}

}