<?php
require_once(joinPath($config['site_folder'] , 'models/User.php'));
require_once(joinPath($config['site_folder'] , 'models/Person.php'));

$user = new User;

if(strpos($config['PHP_SELF'], '/user/') === false
	&& strpos($config['PHP_SELF'], '/about/') === false) checkUser();

$t_level = new DBTable('Level');
$t_activity = new DBTable('Activity');
$t_connection = new DBTable('Connection');
$t_personconnection = new DBTable('PersonConnection');
$t_note = new DBTable('Note');
$t_person = new Person;

// Activate plugins depending on the current users setting - or global setting.
$activate_plugins = false;
if($sql->getOne("SELECT value FROM Setting WHERE name='activate_plugins' AND (user_id='$_SESSION[user_id]' OR user_id='0')")) $activate_plugins = true;

$all_people = array();
if(isset($_SESSION['user_id'])) {
	$people = keyFormat($t_person->sort('nickname')->find(array('user_id'=>$_SESSION['user_id'])));
	foreach($people as $p) $all_people[] = $p['nickname'];
}

function checkUser() {
	global $config;

	if(!isset($_SESSION['user_id'])) {
		$_SESSION['user_id'] = $config['single_user'];
	}
	
	if((!isset($_SESSION['user_id']) or !$_SESSION['user_id']))
		showMessage("Please login to use this feature", $config['site_url'] . 'user/login.php', "error");
}

function getPoints($person_id, $save=true) {
	global $t_person;
	$data = getPointsDetail($person_id);

	if($save) {
		$t_person->field['point'] = $data['total_score'];
		$t_person->save($person_id);
	}
	return $data['total_score'];
}

function getPointsDetail($person_id)  {
	global $points;
	
	$met_count		= getConnectionCount($person_id, 'met');
	$phone_count	= getConnectionCount($person_id, 'phone');
	$message_count	= getConnectionCount($person_id, 'message');
	$chat_count		= getConnectionCount($person_id, 'chat');
	$email_count	= getConnectionCount($person_id, 'email');
	$other_count	= getConnectionCount($person_id, 'other');

	// The Algoritham. Will change over time.
	$total_score = ($met_count * $points['met']) + ($phone_count * $points['phone']) + ($message_count * $points['message']) + ($chat_count * $points['chat'])  
		+ ($email_count * $points['email'])  + ($other_count * $points['other']) ;
	
	return array('total_score'=>$total_score, 'met_count'=>$met_count, 'phone_count'=>$phone_count, 'message_count'=> $message_count, 'chat_count'=>$chat_count, 'email_count'=>$email_count, 'other_count'=>$other_count);
}

function getConnectionCount($person_id, $type) {
	global $sql;
	$count = $sql->getOne("SELECT COUNT(C.id) FROM Connection C 
		INNER JOIN PersonConnection PC ON C.id=PC.connection_id 
		WHERE PC.person_id=$person_id AND
			C.type='$type' AND
			C.user_id='$_SESSION[user_id]'");
	
	return $count;
}

