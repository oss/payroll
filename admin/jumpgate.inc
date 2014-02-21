<?php

include('../payroll.inc');

// note: going to add PATH detection stuff here later, so that this is portable.
//       hard coding the include path is definitely not the way to go
include('/usr/share/php/Mail.php');

function getEmailsFromPOST($request, $db) {

	$emails = array();
	// get email addresses
	foreach($request as $key=>$value) {
		$result = mysql_query("select email from empinfo where username='".$key."'",$db);
		if (!$result)
			echo 'error';
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

