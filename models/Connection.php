<?php
class Connection extends DBTable {
	private $sql;

	function __construct() {
		global $sql;
		$this->sql = $sql;
		parent::__construct("Connection");
	}

	function add($type, $all_people) {
		global $QUERY, $t_person, $i_plugin, $points;

		$connection_id = $this->sql->insert('Connection', array(
			'type'		=> $type,
			'start_on'	=> $QUERY['date'] . ' 00:00:00',
			'user_id'	=> $_SESSION['user_id']
		));
		
		$ids = $t_person->getPeopleIds($all_people);

		if($ids) {
			foreach($ids as $person_id) {
				$this->sql->insert("PersonConnection", array(
					'connection_id'	=> $connection_id,
					'person_id'		=> $person_id
				));

				// Increment person's points
				$t_person->find($person_id);
				$t_person->field['point'] = $t_person->field['point'] + $points[$type];
				$t_person->save();

				$i_plugin->callHook('action_person_connection_made', array($person_id, $type));
			}
		}

		return $connection_id;
	}

	function edit($connection_id, $data, $all_people) {
		global $t_person;

		if($all_people) {
			$ids = $t_person->getPeopleIds(explode(",", $all_people));
			if($ids) {
				$this->sql->remove("PersonConnection", "connection_id='$connection_id'");
				foreach($ids as $person_id) {
					$this->sql->insert("PersonConnection", array(
						'connection_id'	=> $connection_id,
						'person_id'		=> $person_id
					));
				}
			}
		}

		$affected = $this->sql->update("Connection", array(
						'intensity'	=> $data['intensity'],
						'start_on'	=> $data['start_on'],
						'end_on'	=> $data['end_on'],
						'location'	=> $data['location'],
						'note'		=> $data['note']
					), "id=$connection_id");
		return $affected;

	}

	/// Delete a connection - remove the person connection - and the the connection itself.
	function remove($connection_id) {
		global $t_personconnection, $t_person, $points;

		// Reset the points given for this connection.
		$connection_details = $this->find($connection_id);
		$connection_people = $t_personconnection->find(array('connection_id'=>$connection_id));
		foreach ($connection_people as $cp) {
			$t_person->find($cp['person_id']);
			$t_person->field['point'] = $t_person->field['point'] - $points[$connection_details['type']];
			$t_person->save();
		}

		$this->sql->remove('PersonConnection', "connection_id=$connection_id");
		$affected = $this->sql->remove('Connection', "id=$connection_id");

		return $affected;
	}

	function getDay($date) {
		$connections = $this->sql->getAll("SELECT id,type,intensity,start_on,location,note 
				FROM Connection 
				WHERE user_id=$_SESSION[user_id] AND DATE(start_on)='$date'");
		$data = array();

		foreach ($connections as $con) {
			$people = $this->sql->getById("SELECT P.id, P.nickname 
					FROM Person P 
					INNER JOIN PersonConnection PC ON PC.person_id=P.id
					WHERE PC.connection_id=$con[id]");

			$type = $con['type'];

			if(!isset($data[$type])) $data[$type] = array();
			$index = count($data[$type]);

			$con['people'] = $people;

			$data[$type][$index] = $con;
		}
		return $data;
	}

	function getConnectionsOnDate($date, $type) {
		return $this->sql->getAll("SELECT id FROM Connection 
				WHERE user_id=$_SESSION[user_id] AND DATE(start_on)='$date' AND type='$type'");
	}

	function getPeopleIdsInConnection($connection_id) {
		return $this->sql->getCol("SELECT person_id FROM PersonConnection 
				WHERE connection_id=$connection_id");
	}

	function parse($type, $raw) {
		$connection_id = 0;
		$all_connections = explode(",", $raw);
		foreach($all_connections as $connection_raw) {
			$all_people = explode("+", $connection_raw);
			if(!$all_people) continue;
			
			$connection_id = $this->add($type, $all_people);
		}

		return $connection_id;
	}
}