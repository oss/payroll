<?php

include('jumpgate.inc');

// note: going to add PATH detection stuff here later, so that this is portable.
//       hard coding the include path is definitely not the way to go

$emails = getEmailsFromPOST($_POST, $db);
$from = "lulzbeacon@gmail.com";
$subject = "Vouchers Due!";
$message = "Hello earthling. Vouchers are due on Monday.";

$success = 1;
foreach($emails as $addr) {
	if (valid_email($addr))
		sendReminder($addr, $from, $subject.$count, $message);
}

header ('location: admin.php?reminderSent=1&startdate=' . $_POST['startdate'].'&employee='.$_POST['employee']);
?>

