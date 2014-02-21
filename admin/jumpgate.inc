<?php

include('../payroll.inc');

// note: going to add PATH detection stuff here later, so that this is portable.
//       hard coding the include path is definitely not the way to go
include('/usr/share/php/Mail.php');

function getEmailsFromPOST() {

	$emails = array();

	// get email addresses
	foreach($_POST as $key=>$value) {
		$queryText = "SELECT email FROM `empinfo` WHERE `username`='".$key."'";
		$result = mysql_query($queryText,$db);
		$row = mysql_fetch_row($result);
		$emails[] = $row[0];
	}

	return $emails;
}

function sendReminder($to, $from, $subject, $message){
	$headers = "From: ".$from;
	mail($to, $subject, $message, $headers);
}

?>

