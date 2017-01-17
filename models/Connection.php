<?php
class Connection extends DBTable {
	function __construct() {
		parent::__construct("Connection");
	}

	function add($type, $all_people) {
		global $sql, $QUERY, $t_person, $i_plugin, $points;

		$connection_id = $sql->insert('Connection', array(
			'type'		=> $type,
			'start_on'	=> $QUERY['date'] . ' 00:00:00',
			'user_id'	=> $_SESSION['user_id']
		));
		
		$ids = $t_person->getPeopleIds($all_people);

		if($ids) {
			foreach($ids as $person_id) {
				$sql->insert("PersonConnection", array(
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