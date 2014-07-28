<?php

include("header.inc");
include("payroll.inc");


$saveok = $_GET["saveok"];

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


// employee information start
$result = mysql_query("SELECT * FROM `empinfo` WHERE `username` = '" . $username . "'",$db);
$i = 0;
$payrate = mysql_result($result,$i,"payrate");

//EMPLOYEE INFORMATION
echo "<div id='empyInfo'>";
echo "<span id='name'>" . mysql_result($result,$i,"fullname") . "</span><br />";
echo "<span id='empyTitle'>" . mysql_result($result,$i,"descduty") . "</span>";
echo "</div>";


//echo "<table border=0 cellpadding=2 cellspacing=2><tr><td bgcolor=\"#DDDDDD\">";echo "<b>" . $fullname . ", " . $descduty . "</b>";
//echo "</td></tr></table>";

echo "<div id='payPeriod'>";
echo "<table id='payPeriodtable'><tr><td><b>Pay period: </b></td>";
echo "<td><form name='ws' action='view.php' method='get'>";
echo "<select name='startdate' onchange=\"ws.submit()\">";

//action=view.php method=GET
//onchange=\"ws.submit()\"

$right_now = time();
for ($i=20; $i<40; $i++) {
   $week_start = mktime(0,0,0, $ws_month, $ws_day+($i*14), $ws_year);
   $week_end = mktime(23,59,59, $ws_month, $ws_day+($i*14)+13, $ws_year);

   echo "<option value=\"" . date("Y-m-d", mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year)) . "\"";

   $thisweek = false;
   $selectedweek = false;

   if ((date('U', $week_start) <= $right_now) && (date('U', $week_end) >= $right_now))
   {
      $thisweek = true;

      if ($startdate_in == "")
         $selectedweek = true;
   }

   if ($startdate_in != "")
      if ($startdate_in == date("Y-m-d", $week_start))
         $selectedweek = true;

   //if ((date('U', $week_start) =< $right_now) && (date('U', $week_end) >= $right_now))
      //$thisweek = 1;


if ( ( $startdate_in == "" ) && $thisweek ){
   $startdate = mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year);
   $startdate_in = date("Y-m-d", $startdate);
   $thismonth = $ws_month;
   $thisday = $ws_day+($i*14);
   $thisyear = $ws_year;
}

if ($selectedweek)
   echo " selected='selected'";

echo ">" . date("F j, Y", $week_start) . " - " . date("F j, Y", $week_end);

if ($thisweek)
   echo " **";
   echo "</option>";
}

echo "</select></form></td><td>** <i>denotes current week</i></td></tr></table></div>";



// WEEK SELECTOR


// INIT VARIABLES
$year = date("Y", $startdate);
$day = date("d", $startdate);
$month = date("m", $startdate);
$enddate = mktime(12,30,0,$month,$day+13,$year);
$startdate_SQL = date("Y-m-d", $startdate);
$enddate_SQL = date("Y-m-d", $enddate);
// INIT VARIABLES



// ARRAY OF TIMES
$querytext = "SELECT * from `payrollinfo` WHERE `username` = '" . $username . "' AND `date` >= '" . $startdate_SQL . "' AND `date` <= '" . $enddate_SQL . "'";

$result = mysql_query($querytext,$db);
while ($data = mysql_fetch_array ($result)) {

	$daystamp = strtotime($data['date'] . " 00:00:00");

	$starttimes[$daystamp] = strtotime($data['date'] . " " . $data['starttime']);

	$endtimes[$daystamp] = strtotime($data['date'] . " " . $data['endtime']);
/*
	if ($data['starttime'] == "00:00:00") $startstamp = 0;
	else $startstamp = strtotime($data['date'] . " " . $data['starttime']);

	$endstamp = strtotime($data['date'] . " " . $data['endtime']);

	if ($data['endtime'] == "00:00:00") $endstamp = 0;
	//else $starttimes[$daystamp] = $startstamp;

	$starttimes[$daystamp] = $startstamp;

	

	$endtimes[$daystamp] = $endstamp;
*/
}
// ARRAY OF TIMES


for ($i=0; $i<14; $i++) {
	$daystamp = mktime(0,0,0,$month,$day+($i),$year);
	if ( ( $starttimes[$daystamp] == $daystamp ) &&
		( $endtimes[$daystamp] == $daystamp ) ){
		$starttimes[$daystamp] = 0;
		$endtimes[$daystamp] = 0; }
	else if ( $starttimes[$daystamp] == $daystamp ) 
		{ $starttimes[$daystamp] = -1; }
	else if ( $endtimes[$daystamp] == $daystamp ) 
		{ $endtimes[$daystamp] = -1; }
}


//container
echo "\n <div id=\"backButton\"><a href='index.php'><img src='images/back.png' alt='back' /></a></div><div id='container'> \n";

//DEPT OR DIVISION
//ImageString($id, 4, 656, 96, "NBCS", $black);

echo "\n <div id='timeGrid'> \n";
echo "<table id='timeGridTable'><tr>";

//WEEK ONE
$week1total = 0;

echo "<td class='weeks' valign='bottom'>Date</td>";
for ($i=0; $i<7; $i++){
	$datetemp = date("D", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	$datetemp2 = date("n / d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	echo "<td class='weeks' align='center' valign='bottom' width='11%'><font size='1'>" . $datetemp2 . "</font><br />" . $datetemp . "</td>";
}
echo "<td rowspan='3' class='time' valign='bottom' align='center'>Week<br />One<br />Total</td>";
echo "</tr><tr>";

echo "<td class='weeks'>Start</td>";
for ($i=0; $i<7; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($stemp == 0) $stemp_s = "";
	else if ($stemp == -1) $stemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $stemp_s = date("g:i a", $stemp);
	echo "<td class='time' align='center'>" . $stemp_s . "</td>";
}
echo "</tr><tr>";

echo "<td class='weeks'>End</td>";
for ($i=0; $i<7; $i++){
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($etemp == 0) $etemp_s = "";
	else if ($etemp == -1) $etemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $etemp_s = date("g:i a", $etemp);
	echo "<td class='time' align='center'>" . $etemp_s . "</td>";
}
echo "</tr><tr>";

echo "<td class='weeks'>Total</td>";
for ($i=0; $i<7; $i++){

	//         
	//         [ This code is so elegant! WOW! ]
	//   > <  /
	//    O  
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];

	if ( ($etemp != -1) && ($stemp != -1))
		$ttemp = ($etemp - $stemp) / 60 / 60;
	else
		$ttemp = 0;
	if ($ttemp == 0)
		$ttempprint = "";
	if ($ttemp > 5) {
		$ttempprint = $ttemp-0.5;
		$daysover5++;
	}
	else
		$ttempprint = $ttemp; 
	//echo "<td class='total' align='center'><b>" . $ttempprint . "</b></td>";
	echo "<td class='total' align='center'><b>" . $ttempprint . "</b></td>";
	$week1total += $ttemp;
}

$week1total = $week1total-($daysover5*0.5);


echo "<td class='total' align='center'><font size='5'>" . $week1total . "</font></td>";

echo "</tr><tr>";

//WEEK TWO
$week2total = 0;

echo "<td class='weeks' valign='bottom'>Date</td>";
for ($i=7; $i<14; $i++){
	$datetemp = date("D", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	$datetemp2 = date("n / d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	echo "<td class='weeks' align='center' valign='bottom'><font size='1'>" . $datetemp2 . "</font><br />" . $datetemp . "</td>";
}
echo "<td rowspan='3' class='time' valign='bottom' align='center'>Week<br />Two<br />Total</td>";
echo "</tr><tr>";

echo "<td class='weeks'>Start</td>";
for ($i=7; $i<14; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($stemp == 0) $stemp_s = "";
	else if ($stemp == -1) $stemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $stemp_s = date("g:i a", $stemp);
	echo "<td class='time' align='center'>" . $stemp_s . "</td>";
}
echo "</tr><tr>";

echo "<td class='weeks'>End</td>";
for ($i=7; $i<14; $i++){
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($etemp == 0) $etemp_s = "";
	else if ($etemp == -1) $etemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $etemp_s = date("g:i a", $etemp);
	echo "<td class='time' align='center'>" . $etemp_s . "</td>";
}
echo "</tr><tr>";

$daysover5=0;

echo "<td class='weeks'>Total</td>";
for ($i=7; $i<14; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ( ($etemp != -1) && ($stemp != -1)) $ttemp = ($etemp - $stemp) / 60 / 60;
	else $ttemp = 0;
	if ($ttemp == 0)  $ttempprint = "";
	if ($ttemp > 5) {$ttempprint = $ttemp-0.5; $daysover5=$daysover5+1;}
	else $ttempprint = $ttemp; 
	echo "<td class='total' align='center'><b>" . $ttempprint . "</b></td>";
	$week2total += $ttemp;
}
$week2total = $week2total-($daysover5*0.5);

echo "<td class='total' align='center'><b>" . $week2total . "</b></td>";

echo "</tr></table></div> \n";


//TOTAL HOURS
echo "\n <div id='periodTotal'>";
echo "<table id='periodTotalTable'>";
echo "<tr><td align='center'><font size='3'><b>" . date("m-d-Y", mktime($hour,$minute,$second,$month,$day+14,$year)) . "</b></font></td></tr>";
echo "<tr><td align='center'><font size='2'>End Date<br />&nbsp;</font></td></tr>";

echo "<tr><td align='center'><font size='3'><b>" . $payrate . "</b></font></td></tr>";
echo "<tr><td align='center'><font size='2'>Payrate<br />&nbsp;</font></td></tr>";

$grandtotal = $week1total + $week2total;
echo "<tr><td align='center'><font size='3'><b>" . $grandtotal . "</b></font></td></tr>";
echo "<tr><td align='center'><font size='2'>Grand Total Hours<br />&nbsp;</font></td></tr>";

//PAYROLL AMOUNT
$payrollamt = $payrate * ($week1total + $week2total);
echo "<tr><td align='center'><font size='3'><b>" . $payrollamt . "</b></font></td></tr>";
echo "<tr><td align='center'><font size='2'>Payroll amount<br />&nbsp;</font></td></tr>";

echo "</table></div> \n";


// edit hours button
echo "<div id='buttons'>";
echo "<form action='hoursform.php' method='post'>";
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input id='editHrs' type='submit' value=\"Edit Hours\" class='button' /></form>";

// printable button
echo "<form action=\"timesheet.php\" method=\"post\">";
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input id='print' type='submit' value=\"Printable\" class='button' /></form></div> \n";

// end container
echo "<!-- container--> </div> \n";


if ($saveok) echo "<div id='success'>Your hours have been sucessfully recorded.</div>";
else echo "&nbsp;";


//overall table 2

//AUTHORIZED SIGNATURE
//ImageString($id, 4, 640, 274, "Joseph Miklojcik", $black);

//imagepng($id);

include("footer.inc");

?>
