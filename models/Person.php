<?php
use iframe\DB\DBTable;

class Person extends DBTable {
	private $sql;

	function __construct() {
		$this->sql = iframe\App::$db;

		parent::__construct("Person");
	}

	function demote($person_id) {
		$this->sql->execQuery("UPDATE Person SET level_id=level_id+1 WHERE id=$person_id");
	}

	function add($nickname) {
		global $all_people_with_points;
		// Check if nickname exists... 

		// For some reson, the Query fails once in a while. No idea why.
		// $nickname_exists = $this->find("LOWER( REGEXP_REPLACE( nickname, '/[^A-Za-z0-9]/', '') )=LOWER( REGEXP_REPLACE( \"$nickname\", '/[^A-Za-z0-9]/', '') )
		// 				 AND user_id='$_SESSION[user_id]'");
		// if($nickname_exists) return $nickname_exists[0]; // If so, dont add..
		foreach($all_people_with_points as $p) {
			if(strtolower(preg_replace("/[^A-Za-z ]/", '', $nickname)) == strtolower(preg_replace("/[^A-Za-z ]/", '', $p['name']))) {
				$p['nickname'] = $p['name'];
				return $p;
			}
		}
		
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

		$count = array(
					'met'		=> 0,
					'phone'		=> 0,
					'message'	=> 0,
					'chat'		=> 0,
					'email'		=> 0,
					'other'		=> 0
				);

		$log = $this->getLog($person_id);
		
		for($i = 0; $i < count($log); $i++) {
			$today = $log[$i];
			if(isset($log[$i+1]) and $today['start_on'] == $log[$i+1]['start_on']) { // There was another connection for this person on the same date
				// Go thru all the enteries for that day, find the highest connection type, and only count points for that.
				$highest_type = $today['type'];
				$highest_index = $i;

				// Go thru each entry after the repeating entry.
				for($j = $i; $j < count($log); $j++) {
					if($today['start_on'] == $log[$j]['start_on']) { // See ifts on the same day.
						// If it is, see if the type value is bigger than current highest.
						if(compareType($log[$j]['type'], $highest_type)) {
							$highest_type = $log[$j]['type'];
							$highest_index = $j;
						}
					} else { // Some other day.
						$i = $j; // make sure this area is not traversed again.
						break; // Get out of the loop
					}
				}
				if($i != $j) $i = count($log); // Edge case. If there are a lot of repetive call for the same person at the end of the log, this makes sure they aren't called.

				$count[$highest_type]++;
			} else {
				$count[$today['type']]++;
			}
		}
		
		// Old logic. Added points even if multiple instance of meeting happened on the same day.
		// $count['met']		= $this->getConnectionCount($person_id, 'met');
		// $count['phone']		= $this->getConnectionCount($person_id, 'phone');
		// $count['message']	= $this->getConnectionCount($person_id, 'message');
		// $count['chat']		= $this->getConnectionCount($person_id, 'chat');
		// $count['email']		= $this->getConnectionCount($person_id, 'email');
		// $count['other']		= $this->getConnectionCount($person_id, 'other');

		// The Algoritham. Will change over time.
		$total_score = ($count['met'] * $points['met']) + ($count['phone'] * $points['phone']) + ($count['message'] * $points['message']) 
						+ ($count['chat'] * $points['chat'])  + ($count['email'] * $points['email'])  + ($count['other'] * $points['other']) ;
		
		return array(	'total_score'	=> $total_score,
						'met_count'		=> $count['met'],
						'phone_count'	=> $count['phone'],
						'message_count'	=> $count['message'],
						'chat_count'	=> $count['chat'],
						'email_count'	=> $count['email'],
						'other_count'	=> $count['other']);
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
		$interaction_log = $this->sql->getAll("SELECT C.id,DATE(C.start_on) AS start_on,C.type 
			FROM Connection C 
			INNER JOIN PersonConnection PC ON C.id=PC.connection_id 
			WHERE PC.person_id=$person_id 
			ORDER BY C.start_on DESC");
		return $interaction_log;
	}
}