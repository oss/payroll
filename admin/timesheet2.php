<?php

include("payroll-tshack.inc");

header("Content-type: image/png");
$id = imagecreatefrompng ("images/timesheetclean.png");
$black = ImageColorClosest($id, 0, 0, 0);

$username = $_GET["username"];

//date start
$startdate_in = $_GET["startdate"];
$startdate = strtotime($startdate_in . " 00:00:00");
$year = date("Y", $startdate);
$day = date("d", $startdate);
$month = date("m", $startdate);
$enddate = mktime(12,30,0,$month,$day+13,$year);
$startdate_SQL = date("Y-m-d", $startdate);
$enddate_SQL = date("Y-m-d", $enddate);
//date end

// arrray of time start
$querytext = "SELECT * from `payrollinfo` WHERE `username` = '" . $username . "' AND `date` >= '" . $startdate_SQL . "' AND `date` <= '" . $enddate_SQL . "'";

$result = mysql_query($querytext,$db);
while ($data = mysql_fetch_array ($result)) {

	$daystamp = strtotime($data['date'] . " 00:00:00");
	if ($data['starttime'] == "00:00:00") $startstamp = 0;
	else $startstamp = strtotime($data['date'] . " " . $data['starttime']);
	$endstamp = strtotime($data['date'] . " " . $data['endtime']);
	if ($data['endtime'] == "00:00:00") $endstamp = 0;
	else $starttimes[$daystamp] = $startstamp;
	$endtimes[$daystamp] = $endstamp;
}
// arrray of time end


//START DATE
$temp = date("m-d", mktime($hour,$minute,$second,$month,$day+20,$year));
ImageString($id, 4, 610, 20, $temp, $black);
$temp = date("Y", mktime($hour,$minute,$second,$month,$day+20,$year));
ImageString($id, 4, 610, 32, $temp, $black);


// employee information start
$result = mysql_query("SELECT * FROM empinfo WHERE `username` = '" . $username . "'",$db);
$payrate = mysql_result($result,$i,"payrate");


//EMPLOYEE INFORMATION
ImageString($id, 4, 60, 58, mysql_result($result,$i,"fullname"), $black);
#ImageString($id, 4, 340, 58, mysql_result($result,$i,"social"), $black);
ImageString($id, 4, 550, 58, mysql_result($result,$i,"descduty"), $black);
ImageString($id, 4, 750, 32, mysql_result($result,$i,"type"), $black);
ImageString($id, 4, 710, 135, $payrate, $black);


//DEPT OR DIVISION
ImageString($id, 4, 656, 96, "NBCS", $black);


//WEEK ONE
$week1total = 0;
for ($i=0; $i<7; $i++){
	$datetemp = date("m  d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	ImageString($id, 4, (86 + $i*64), 116, $datetemp, $black);

	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($stemp != 0) $stemp_s = date("h  i", $stemp);
	else $stemp_s = "";
	ImageString($id, 4, (86 + $i*64), 138, $stemp_s, $black);

	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($etemp != 0)  $etemp_s = date("h  i", $etemp);
	else $etemp_s = "";
	ImageString($id, 4, (86 + $i*64), 158, $etemp_s, $black);

	$ttemp = ($etemp - $stemp) / 60 / 60;
	if ($ttemp == 0)  $ttempprint = "";
	if ($ttemp > 5) {$ttempprint = $ttemp-0.5; $daysover5=$daysover5+1;} 
	else $ttempprint = $ttemp; 
	ImageString($id, 4, (96 + $i*64), 178, $ttempprint, $black);
	$week1total += $ttemp;
}
$week1total = $week1total - ($daysover5*0.5);
ImageString($id, 4, 540, 179, $week1total, $black);

//WEEK TWO
$week2total = 0;
$daysover5 = 0;
for ($i=0; $i<7; $i++){
	$datetemp = date("m  d", mktime ($hour,$minute,$second,$month,$day+$i+7,$year));
	ImageString($id, 4, (86 + $i*64), 218, $datetemp, $black);

	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i+7,$year)];
	if ($stemp != 0) $stemp_s = date("h  i", $stemp);
	else $stemp_s = "    ";
	ImageString($id, 4, (84 + $i*64), 240, $stemp_s, $black);

	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i+7,$year)];
	if ($etemp != 0) $etemp_s = date("h  i", $etemp);
	else $etemp_s = "";
	ImageString($id, 4, (84 + $i*64), 260, $etemp_s, $black);

	$ttemp = ($etemp - $stemp) / 60 / 60;
	if ($ttemp == 0)  $ttempprint = "";
	if ($ttemp > 5) {$ttempprint = $ttemp-0.5; $daysover5=$daysover5+1;} 
	else $ttempprint = $ttemp; 
	ImageString($id, 4, (96 + $i*64), 280, $ttempprint, $black);
	$week2total += $ttemp;
}
$week2total= $week2total-($daysover5*0.5);

ImageString($id, 4, 540, 280, $week2total, $black);

//TOTAL HOURS
ImageString($id, 4, 710, 156, $week1total + $week2total, $black);

//PAYROLL AMOUNT
ImageString($id, 4, 710, 182, $payrate * ($week1total + $week2total), $black);

//AUTHORIZED SIGNATURE
//ImageString($id, 4, 640, 274, "Joseph Miklojcik", $black);

imagepng($id);

?>
