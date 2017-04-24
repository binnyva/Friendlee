<?php
class Person extends DBTable {
	private $sql;

	function __construct() {
		global $sql;
		$this->sql = $sql;

		parent::__construct("Person");
	}

	function demote($person_id) {
		$this->sql->execQuery("UPDATE Person SET level_id=level_id+1 WHERE id=$person_id");
	}

	function add($nickname) {
		$nickname = str_replace(array("'"), array(""), stripslashes($nickname));
		$nickname_exists = $this->find("LOWER(REPLACE(REPLACE(nickname, '\'',''), '\\\\',''))=\"$nickname\" AND user_id='$_SESSION[user_id]'", 'id'); // Check if nickname exists... 
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

	/// Delete a person and all the connections he made.
	function remove($person_id) {
		$this->sql->remove('PersonConnection', "person_id=$person_id");
		$affected = $this->sql->remove('Person', "id=$person_id");
		return $affected;
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

	/// Get the last conacted details for this person with the given contact type.
	function getLastContact($person_id, $type) {
		$connection = $this->sql->getAssoc("SELECT C.* FROM Connection C 
			INNER JOIN PersonConnection PC ON C.id=PC.connection_id 
			WHERE PC.person_id=$person_id AND
				C.type='$type' AND
				C.user_id='$_SESSION[user_id]'
			ORDER BY C.start_on DESC");
		return $connection;
	}

	/// Returns the points of the given person. If the second parameter is true, saves it to DB as well after calculation.
	function getPoints($person_id, $save=true) {
		global $t_person;
		$data = $this->calculatePoints($person_id);

		if($save) {
			$t_person->field['point'] = $data['total_score'];
			$t_person->save($person_id);
		}
		return $data['total_score'];
	}

	/// Calculate the points of the given person and returns it as an array.
	function calculatePoints($person_id)  {
		global $points;
		
		$met_count		= $this->getConnectionCount($person_id, 'met');
		$phone_count	= $this->getConnectionCount($person_id, 'phone');
		$message_count	= $this->getConnectionCount($person_id, 'message');
		$chat_count		= $this->getConnectionCount($person_id, 'chat');
		$email_count	= $this->getConnectionCount($person_id, 'email');
		$other_count	= $this->getConnectionCount($person_id, 'other');

		// The Algoritham. Will change over time.
		$total_score = ($met_count * $points['met']) + ($phone_count * $points['phone']) + ($message_count * $points['message']) 
						+ ($chat_count * $points['chat'])  + ($email_count * $points['email'])  + ($other_count * $points['other']) ;
		
		return array(	'total_score'	=> $total_score,
						'met_count'		=> $met_count,
						'phone_count'	=> $phone_count,
						'message_count'	=> $message_count,
						'chat_count'	=> $chat_count,
						'email_count'	=> $email_count,
						'other_count'	=> $other_count);
	}

	/// Returs the number of times the given person was contacted with the given contact type
	function getConnectionCount($person_id, $type) {
		$count = $this->sql->getOne("SELECT COUNT(C.id) FROM Connection C 
			INNER JOIN PersonConnection PC ON C.id=PC.connection_id 
			WHERE PC.person_id=$person_id AND
				C.type='$type' AND
				C.user_id='$_SESSION[user_id]'");
		
		return $count;
	}

	function getLog($person_id) {
		$interaction_log = $this->sql->getAll("SELECT C.id,C.start_on,C.type 
			FROM Connection C 
			INNER JOIN PersonConnection PC ON C.id=PC.connection_id 
			WHERE PC.person_id=$person_id 
			ORDER BY C.start_on DESC");
		return $interaction_log;
	}
}