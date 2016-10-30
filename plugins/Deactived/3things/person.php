<?php
require('../../common.php');

$person = $t_person->find($QUERY['person_id']);
$nickname = $person['nickname'];


if(!empty($QUERY['action'])) {
	if($QUERY['things_id']) {
		if($sql->update("Plugin_3things", array(
				'question'	=> i($QUERY, 'question'),
				'answer_1'	=> i($QUERY['answer'], 0),
				'answer_2'	=> i($QUERY['answer'], 1),
				'answer_3'	=> i($QUERY['answer'], 2),
			), "id=$QUERY[things_id]")) {
			$QUERY['success'] = 'Data update.';
		}
	} else {
		if($sql->insert("Plugin_3things", array(
				'person_id'	=> $QUERY['person_id'],
				'user_id'	=> $_SESSION['user_id'],
				'question'	=> i($QUERY, 'question'),
				'answer_1'	=> i($QUERY['answer'], 0),
				'answer_2'	=> i($QUERY['answer'], 1),
				'answer_3'	=> i($QUERY['answer'], 2),
			))) {
			$QUERY['success'] = 'Data saved to database.';
		}
	}
}
$data = $sql->getAssoc("SELECT id,question, answer_1, answer_2, answer_3 
		FROM Plugin_3things WHERE user_id=$_SESSION[user_id] AND person_id=$QUERY[person_id]");


render(joinPath($config['site_folder'],'plugins/3things/templates/person.php'), true, true);