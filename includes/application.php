<?php
use iframe\DB\DBTable;

// Backward compatibility for iframe 1
$config = iframe\App::$config;

$config['app_title'] = $config['app_name'];
$config['app_home'] = $config['app_url'];
// Lots of site_ config options were moved to app_. Eg site_title is now app_title
foreach($config as $key => $value) {
	if(preg_match("/^app_/", $key)) {
		$site_key = str_replace('app_', "site_", $key);
		$config[$site_key] = $value;
	}
}
$config['mode'] = $config['env'];
$sql = iframe\App::$db;

// iframe\App::$template->css_folder = 'css';
// iframe\App::$template->js_folder = 'js';
// Everything up can be replaced by this...
// setupBackwardCompatibility();

require_once(joinPath($config['app_folder'] , 'models/User.php'));
require_once(joinPath($config['app_folder'] , 'models/Person.php'));
require_once(joinPath($config['app_folder'] , 'models/Connection.php'));

$user = new User;

if(strpos($config['PHP_SELF'], '/user/') === false
	&& strpos($config['PHP_SELF'], '/about/') === false) checkUser();

$t_level = new DBTable('Level');
$t_activity = new DBTable('Activity');
$t_personconnection = new DBTable('PersonConnection');
$t_note = new DBTable('Note');
$t_person = new Person;
$t_connection = new Connection;
$i_plugin = new iframe\iframe\Plugin($config['app_folder'] , 'plugins');

// Activate plugins depending on the current users setting - or global setting.
$activate_plugins = false;
if(isset($_SESSION['user_id']) and $sql->getOne("SELECT value FROM Setting WHERE name='activate_plugins' AND (user_id='$_SESSION[user_id]' OR user_id='0')")) 
	$activate_plugins = true;

$all_people = [];
$all_people_with_points = [];
if(isset($_SESSION['user_id'])) {
	$people = keyFormat($t_person->sort('nickname')->find(array('user_id'=>$_SESSION['user_id'], 'autocomplete'=>'1')));
	
	foreach($people as $p) {
		$all_people[] = $p['nickname'];
		$all_people_with_points[] = array('name' => $p['nickname'], 'point' => intval($p['point']), 'id' => $p['id']);
	}
}

function buildInput($id, $label, $type, $value, $options = []) {
	global $html;
	if($type == 'text' or $type == 'textarea') {
		$options['placeholder'] = $label;
		$label = '';
	}
	$html->buildInput($id, $label, $type, $value, $options);
}


function checkUser() {
	global $config;

	if(!isset($_SESSION['user_id'])) {
		$_SESSION['user_id'] = $config['single_user'];
	}
	
	if((!isset($_SESSION['user_id']) or !$_SESSION['user_id']))
		iframe\App::showMessage("Please login to use this feature", $config['site_url'] . 'user/login.php', "error");
}

function date_difference($a, $b) {
 	$datetime1 = date_create($a);
	$datetime2 = date_create($b);
	$interval = date_diff($datetime1, $datetime2);
	$gap = $interval->format('%a');

	return $gap;
}

function compareType($a, $b) {
	global $points;

	return $points[$a] > $points[$b];
}

function firstName($name) {
	$name_parts = explode(" ", $name);
	return reset($name_parts);
}

function email($to, $subject, $body, $from = '') {
	//return true; //:DEBUG:
	global $config;
	require("Mail.php");
	require("Mail/mime.php");

	if(!$from) $from = "BinnBot <binnbot@gmail.com>";
	
	// SMTP info here!
	$host = $config['email_host'];

	$username = $config['email_username'];
	$password = $config['email_password'];
	
	$headers = array ('From' => $from,
		'To' => $to,
		'Subject' => $subject);
	$smtp = Mail::factory('smtp',
		array ('host' => $host,
			'auth' => true,
			'username' => $username,
			'password' => $password));

	$mime = new Mail_mime("\n");
	$mime->setTXTBody(strip_tags($body));
	$mime->setHTMLBody($body);

	$body = $mime->get();
	$headers = $mime->headers($headers);
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
		echo("<p>" . $mail->getMessage() . "</p>");
		return false;
	}
	
	return true;
}

/// Iframe backward compatibility
/**
 * Shows the final message - redirects to a new page with the message in the URL
 */
function showMessage($message, $url='', $status="success",$extra_data=array(), $use_existing_params=true, $ajax = false) {
	global $config;	
	if($config['server_host'] == 'cli') {
		print $message . "\n";
		if($status == 'error') exit;

	} elseif(isset($_REQUEST['ajax']) or $ajax) {
		//If it is an ajax request, Just print the data
		$success = '';
		$error = '';
		$insert_id = '';

		if($status == 'success') $success = $message;
		if($status == 'error' or $status == 'failure') $error = $message;

		$data = array(
			"success"	=> $success,
			"error"		=> $error
		) + $extra_data;

		print json_encode($data);

	} elseif(isset($_REQUEST['layout']) and $_REQUEST['layout']==='cli') {
		if($status === 'success') print $message . "\n";

	} else {
		if(!$url) {
			global $QUERY;
			$QUERY[$status] = $message;
			return;
		}
	
		if(strpos($url, 'http://') === false) {
			global $config;
			$url = joinPath($config['site_url'], $url);
		}
		
		$goto = str_replace('&amp;', '&', getLink($url, array($status=>$message) + $extra_data, $use_existing_params));
		header("Location:$goto");
	}
	exit;
}
/// Shortcut for showMessage when using ajax.
function showAjaxMessage($message, $type='success') {
	showMessage($message,'',$type,array(),true,true);
}
