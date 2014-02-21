<?php

include('jumpgate.inc');

// note: going to add PATH detection stuff here later, so that this is portable.
//       hard coding the include path is definitely not the way to go

$emails = getEmailsFromPOST($_POST, $db);
$from = "lulzbeacon@gmail.com";
$subject = "Vouchers Due!";
$message = "Hello earthling. Vouchers are due on Monday.";

$count = 0;
foreach($emails as $addr) {

	sendReminder($addr, $from, $subject.$count, $message) ;
	$count += 1;
}

header ('location: admin.php?reminderSent=1&startdate=' . $_POST['startdate'].'&employee='.$_POST['employee']);
?>

