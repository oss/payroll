<?php

include('../payroll.inc');
include('jumpgate.inc');

// note: going to add PATH detection stuff here later, so that this is portable.
//       hard coding the include path is definitely not the way to go

$emails = getEmailsFromPOST();
$to = "jcc@rutgers.edu";
$subject = "Vouchers Due!";
$message = "Hello earthling. Vouchers are due on Monday.";

$count = 0;
foreach($emails as $addr) {
	sendReminder($addr, $to, $subject.$count, $message) ;
	$count += 1;
}



?>

