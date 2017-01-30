<?php
require 'common.php';
require '../includes/classes/API.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$sql->options['error_handling'] = 'die';
$api = new API;

$api->request('/met/{people}', function($people) {
	addConnection('met', $people);
});
$api->request('/message/{people}', function($people) {
	addConnection('message', $people);
});
$api->request('/phone/{people}', function($people) {
	addConnection('phone', $people);
});
$api->request('/chat/{people}', function($people) {
	addConnection('chat', $people);
});

function addConnection($type, $people) {
	global $t_connection;
	$connection_id = $t_connection->parse($type, $people);

	if($connection_id) showSuccess("Connection created($connection_id)");
	else showError("Error creating connection.");
}

$api->request('/connection/edit/{connection_id}/{people}', function ($connection_id, $people) {
	global $t_connection, $QUERY;
	$affected = $t_connection->edit($connection_id, $QUERY, $people);

	if($affected) showSuccess("Connection updated");
	else showError("Error updating connection.");
});

$api->request('/connection/delete/{connection_id}', function ($connection_id) {
	global $t_connection;
	$affected = $t_connection->remove($connection_id);

	if($affected) showSuccess("Connection deleted");
	else showError("Error deleting connection.");
});

$api->request('/day/{date}', function ($date) {
	global $t_connection;
	$day = $t_connection->getDay(date('Y-m-d', strtotime($date)));
	
	showSuccess("Data for '$date'", $day);
});

$api->request('/person/add/{nickname}', function($nickname) {
	global $t_person;
	$person_id = $t_person->add($nickname);

	if($person_id) showSuccess("Person created($person_id)");
	else showError("Error creating person.");
});

$api->request('/person/edit/{person_id}', function($person_id) {
	global $t_person, $QUERY;
	$affected = $t_person->edit($person_id, $QUERY, $people);

	if($affected) showSuccess("Person updated");
	else showError("Error updating person.");
});

$api->request('/person/delete/{person_id}', function($person_id) {
	global $t_person;

	$affected = $t_person->remove($person_id);

	if($affected) showSuccess("Connection deleted");
	else showError("Error deleting connection.");
});

$api->request("/user/login", function () {
	global $QUERY, $user;

	$username = i($QUERY, 'username');
	$password = i($QUERY, 'password');
	if(!$user->login($username, $password)) {
		showError($user->error, array('')); exit;
	}

	$return = array('user' => $user->getDetails());

	showSuccess("Login successful", $return);
});


$api->notFound(function() {
	print "404";
});

$api->handle();


function showSuccess($message, $extra = array()) {
	showSituation('success', $message, $extra);
}

function showError($message, $extra = array()) {
	showSituation('error', $message, $extra);
}

function showSituation($status, $message, $extra) {
	$other_status = ($status == 'success') ? 'error' : 'success';
	$return = array($status => true, $other_status => false);

	if(is_string($message)) {
		$return[$status] = $message;

	} elseif(is_array($message)) {
		$return = array_merge($return, $message);
	} 

	$return = array_merge($return, $extra);

	print json_encode($return);
}
