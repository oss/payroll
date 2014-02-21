<?php

include('../payroll.inc');
include_once('/usr/share/php/Mail.php');

function getEmailsFromPOST($request, $db) {

	$emails = array();
	foreach($request as $key=>$value) {
		$result = mysql_query("select email from empinfo where username='".$key."'",$db);
		$row = mysql_fetch_row($result);
		$emails[] = $row[0];
	}

	return $emails;
}

function sendReminder($to, $from, $subject, $message){
	$headers = "From: ".$from;
	mail($to, $subject, $message, $headers);
}
function valid_email($email) {
	return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);	
}
?>

