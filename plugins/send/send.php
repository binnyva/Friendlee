<?php
require('../../common.php');
include("Mail.php");
include("Mail/mime.php");

$to_send = $sql->getAll("SELECT S.*,P.email,P.nickname,S.name AS subject FROM Plugin_Send S INNER JOIN Person P ON S.person_id=P.id WHERE S.status='0' AND P.email!=''");

$already_send_to = array();

foreach ($to_send as $send) {
	// Make sure we don't send the same person multiple things on the same run.
	if(in_array($send['person_id'], $already_send_to)) continue;
	$already_send_to[] = $send['person_id'];
	//$send['email'] = 'binnyva@makeadiff.in';
	$file = '';

	$subject = $send['subject'];
	$message = "Hey " . short_name($send['nickname']) . ",\n\n";

	if($send['url']) {
		$message .= "Check this out!\n$send[url]" . "\n";
	}
	if($send['text']) {
		$message .= $send['text'] . "\n";
	}

	if($send['file']) {
		$message .= "Check out the attachment... :-)\n";
		$file = str_replace('file:///', '/', $send['file']);
	}
	$message .= "\n--\nBinny V A\nhttp://blog.binnyva.com/\n";

	print "Sending $subject to $send[nickname]($send[email]) ... ";
	@sendEmailWithAttachment($send['email'], $subject, $message, false, false, $file); 
	print "Done.\n";

	print $message;
	$sql->update("Plugin_Send", array('status'=>'1'), "id=$send[id]");
}

function sendEmailWithAttachment($to_email, $subject, $body, $from=false, $login_details=false, $file=array()) {
	global $config;

	$crlf = "\n";

	$mime = new Mail_mime($crlf);
	$mime->setTXTBody($body);
	if($file and file_exists($file)) {
		$mime->addAttachment($file, 'image/jpeg');
 	}
	
	if(!$from) $from = '"Binny V A" <binnyva@gmail.com>';
	if(!$login_details) $login_details = array(
		'host'		=> $config['email_host'],
		'username'	=> $config['email_username'],
		'password'	=> $config['email_password'],
	);
	
	//do not ever try to call these lines in reverse order
	$body = $mime->get();
	$headers = $mime->headers(array(
		'From'    => $from,
		'Subject' => $subject
	));

	$login_details['auth'] = true;
	$smtp = Mail::factory('smtp', $login_details);
	$smtp->send($to_email, $headers, $body);
}

/// Returns just the first name of the person.
function short_name($name) {
	$parts = explode(' ', $name);
	return reset($parts);
}
