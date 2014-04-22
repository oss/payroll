<?php

include('jumpgate.inc');

$emails = getEmailsFromPOST($_POST, $db);
$from = "jcc@rutgers.edu";
$subject = "Reminder - Vouchers due.";
$message = "Hello".$_POST['employee'].",\nVouchers are due on Monday. Please remember to complete your timesheet and check the 'complete' box before saving.";

foreach($emails as $addr) {
	if (valid_email($addr))
		sendReminder($addr, $from, $subject.$count, $message);
}

header ('location: admin.php?reminderSent=1&startdate=' . $_POST['startdate'].'&employee='.$_POST['employee']);
?>

