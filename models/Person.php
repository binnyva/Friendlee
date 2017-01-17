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
		$nickname_exists = $this->find("LOWER(REPLACE(REPLACE(nickname, '\'',''), '\\\\',''))='$nickname' AND user_id='$_SESSION[user_id]'", 'id'); // Check if nickname exists... 
		if($nickname_exists) return $nickname_exists[0]; // If so, dont add..

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
		$person_data['new'] = true; // Needed to show a new person added toast. 

		return $person_data;
	}

	/// Goes thru all the nicknames in the array of nicknames given and builds and returns and array with all the IDs of the people. If nickname not found, adds the person.
	function getPeopleIds($all_people) {
		global $people, $new_people;

		$ids = array();

		foreach($all_people as $nickname_org) {
			$nickname_org = trim($nickname_org);
			$nickname = str_replace(array("'", "\\"), '', strtolower($nickname_org));
			if(!$nickname) continue;
			
			$person_data = $this->add($nickname_org);
			$person_id = $person_data['id'];

			$people[$person_id]['nickname'] = $nickname_org; // Add the newly added person to the chached people list
			if(isset($person_data['new'])) $new_people[] = $nickname_org . " (".strtoupper($person_data['sex']).")";

			$ids[] = $person_id;
		}
		return $ids;
	}

}