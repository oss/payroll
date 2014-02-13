<?php

include("payroll.inc");

$weekbegins = $_POST["startdate"];
$username = $_POST["employee"];

$startdatetext = strtotime($weekbegins . " 00:00:00");

$year = date("Y", $startdatetext);
$day = date("d", $startdatetext);
$month = date("m", $startdatetext);

$startdate = date("mdY", mktime(12,30,0,$month,$day,$year));

$grandtotalhours = 0;

for ($i=0; $i<14; $i++) {

$date = date("D m/d/y", mktime(12,30,0,$month,$day+$i,$year)) ;
$date_SQL = date("Y-m-d", mktime(12,30,0,$month,$day+$i,$year)) ;

//$starttimeval = $_POST["start" . date("mdY", mktime(12,30,0,$month,$day+$i,$year)) ];
$starttimeval = $_POST["start" . $i  ];
if ($starttimeval == 0) {
	$starttime = "00:00:00";
	$starttime_SQL = "00:00:00";
} else {
	$starttime = date("h:i a", $starttimeval);
	$starttime_SQL = date("H:i:s", $starttimeval);
}

//$endtimeval = $_POST["end" . date("mdY", mktime(12,30,0,$month,$day+$i,$year)) ];
$endtimeval = $_POST["end" . $i ];
if ($endtimeval == 0) {
	$endtime = "00:00:00";
	$endtime_SQL = "00:00:00";
} else {
	$endtime = date("h:i a", $endtimeval);
	$endtime_SQL = date("H:i:s", $endtimeval);
}

$querytext="DELETE FROM `payrollinfo` WHERE `username`='" . $username . "' AND `date`='" . $date_SQL . "'";
$result = mysql_query($querytext,$db);

if ( mysql_errno() != 0 ) {
	echo "ERROR:" . mysql_errno() . ": " . mysql_error(). "\n";
	exit(1);
}

$querytext="INSERT INTO `payrollinfo` ( `username` , `date` , `starttime` , `endtime` ) VALUES ('". $username . "', '" . $date_SQL . "', '" . $starttime_SQL . "', '" . $endtime_SQL . "')";
//echo $querytext . "<br>";
$result = mysql_query($querytext,$db);

if ( mysql_errno() != 0 ) {
	echo "ERROR:" .mysql_errno() . ": " . mysql_error(). "\n";
	exit(1);
}

}

header ('location: admin.php?saveok=1&startdate=' . $weekbegins.'&employee='.$username);

?>

