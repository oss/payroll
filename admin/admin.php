<?php
require_once('../config.inc.php');

include("header.inc");
include("payroll.inc");

$saveok = $_GET["saveok"];
$reminderSent = $_GET['reminderSent'];
$employee = $_GET["employee"];

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
echo "<span id='empyTitle'>" . mysql_result($result,$i,"descduty") . "</b></td>";
echo "</div>";


//User selector

$username = $employee;



echo "<div id='selEmployee'>";
echo "<table id='selEmpTable'><tr><td><b>Employee: </b></td>";
echo "<td><form name=\"selEmp\" action=admin.php method=GET>";
echo "<select id='employeename' name=employee onchange=\"selEmp.submit()\">";

$citizensResult = mysql_query("select username from payrollinfo group by username",$db) or die(mysql_error());

// this is used for generating a list of all users to be fed into the checkbox. nomnomnom.
$allUsers = array();

while ($row = mysql_fetch_array($citizensResult)) {

	// this is for printing the timeshseet for all users
	$allUsers[] = $row["username"];
	
	echo "<option value=\"" . $row["username"] . "\"";
	if($row["username"] == $username) echo "SELECTED";
	echo ">" . $row["username"] . " \n";
}
$slackers = array();

$slackQuery = "SELECT * FROM `payrollinfo` WHERE `iscomplete`=0 AND `date`='".$startdate_SQL."'";
$slackResult = mysql_query($slackQuery, $db);

while ($row = mysql_fetch_assoc($slackResult)) {

	$slackers[] = $row['username'];
}


echo "</select><input name='startdate' value=\"" . $startdate_SQL . "\" type=hidden></form></td></tr></table></div>";
mysql_free_result($citizensResult);


//echo "<table border=0 cellpadding=2 cellspacing=2><tr><td bgcolor=\"#DDDDDD\">";echo "<b>" . $fullname . ", " . $descduty . "</b>";
//echo "</td></tr></table>";

echo "<div id='payPeriod'>";
echo "<table id='payPeriodtable'><tr><td><b>Pay period: </b></td>";
echo "<td><form name=\"ws\" action=admin.php method=GET>";
echo "<select id=\"startdate\" name=\"startdate\" onchange=\"ws.submit()\">";

for ($i=0; $i<28; $i++) {

echo "<option value=\"" . date("Y-m-d", mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year)) . "\"";

$thisweek = 0;

if ( ( mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year) < mktime() ) && ( mktime(0,0,0,$ws_month,$ws_day+($i*14)+13,$ws_year) > mktime() ) ) {
$thisweek = 1;
}


if ( ( $startdate_in == "" ) && $thisweek ){
$startdate = mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year);
$startdate_in = date("Y-m-d", $startdate);
$thismonth = $ws_month;
$thisday = $ws_day+($i*14);
$thisyear = $ws_year;
}


if ( mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year) == $startdate ) {
echo "SELECTED";
}

echo ">" . date("F j, Y", mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year)) . " - " . date("F j, Y", mktime(0,0,0,$ws_month,$ws_day+($i*14)+13,$ws_year));

if ($thisweek)
echo " **";


}

echo "</select><input name='employee' value=\"" . $username . "\" type=hidden></form></td><td>** <i>denotes current week</i></td></tr></table>";

?>
<form action="hoursform.php" method="get" id="form_edit_hours">
	<input type="hidden" name="employee" value="" />
	<input type="hidden" name="startdate" value="" />
	<input type="submit" id="button_edit_hours" value="Edit Hours" />
</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		var form_edit_hours = $("#form_edit_hours");
		form_edit_hours.submit(function(e) {
			$(this).children("[name=employee]").val($("#employeename").val());
			$(this).children("[name=startdate]").val($("#startdate").val());
			return true;
		});

	});
</script>

<?

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


$result = mysql_query("SELECT * FROM `empinfo` WHERE `username` = '" . $username . "'",$db);
$i = 0;
$payrate = mysql_result($result,$i,"payrate");

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
echo "\n <div id=\"backButton\"><a href='index.php'><img src='images/back.png' /></a></div><div id='container'> \n";

//DEPT OR DIVISION
//ImageString($id, 4, 656, 96, "NBCS", $black);

echo "\n <div id='timeGrid'> \n";
echo "<table id='timeGridTable'><tr>";

//WEEK ONE
$week1total = 0;

echo "<td id='weeks' valign=bottom>Date</td>";
for ($i=0; $i<7; $i++){
	$datetemp = date("D", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	$datetemp2 = date("n / d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	echo "<td id='weeks' align=center valign=bottom width=11%><font size=1>" . $datetemp2 . "</font><br>" . $datetemp . "</td>";
}
echo "<td rowspan=3 width=12% id='time' valign=bottom align=center>Week<br>One<br>Total</td>";
echo "</tr><tr>";

echo "<td id='weeks'>Start</td>";
for ($i=0; $i<7; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($stemp == 0) $stemp_s = "";
	else if ($stemp == -1) $stemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $stemp_s = date("g:i a", $stemp);
	echo "<td id='time' align=center><nobr>" . $stemp_s . "</nobr></td>";
}
echo "</tr><tr>";

echo "<td id='weeks'>End</td>";
for ($i=0; $i<7; $i++){
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($etemp == 0) $etemp_s = "";
	else if ($etemp == -1) $etemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $etemp_s = date("g:i a", $etemp);
	echo "<td id='time' align=center><nobr>" . $etemp_s . "</nobr></td>";
}
echo "</tr><tr>";

echo "<td id='weeks'>Total</td>";
for ($i=0; $i<7; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ( ($etemp != -1) && ($stemp != -1)) $ttemp = ($etemp - $stemp) / 60 / 60;
	else $ttemp = 0;
	if ($ttemp == 0)  $ttempprint = "";
	if ($ttemp > 5) {$ttempprint = $ttemp-0.5; $daysover5=$daysover5+1;}
	else $ttempprint = $ttemp; 
	echo "<td id='total' align=center><b>" . $ttempprint . "</b></td>";
	$week1total += $ttemp;
}

$week1total = $week1total-($daysover5*0.5);


echo "<td id='total' align=center><font size='5'>" . $week1total . "</font></td>";

echo "</tr><tr>";

//WEEK TWO
$week2total = 0;

echo "<td id='weeks' valign=bottom>Date</td>";
for ($i=7; $i<14; $i++){
	$datetemp = date("D", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	$datetemp2 = date("n / d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
	echo "<td id='weeks' align=center valign=bottom><font size=1>" . $datetemp2 . "</font><br>" . $datetemp . "</td>";
}
echo "<td rowspan=3 id='time' valign=bottom align=center>Week<br>Two<br>Total</td>";
echo "</tr><tr>";

echo "<td id='weeks'>Start</td>";
for ($i=7; $i<14; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($stemp == 0) $stemp_s = "";
	else if ($stemp == -1) $stemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $stemp_s = date("g:i a", $stemp);
	echo "<td id='time' align=center><nobr>" . $stemp_s . "</nobr></td>";
}
echo "</tr><tr>";

echo "<td id='weeks'>End</td>";
for ($i=7; $i<14; $i++){
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ($etemp == 0) $etemp_s = "";
	else if ($etemp == -1) $etemp_s = "<font color='#FF0000'>? ? ?</font>";
	else $etemp_s = date("g:i a", $etemp);
	echo "<td id='time' align=center><nobr>" . $etemp_s . "</nobr></td>";
}
echo "</tr><tr>";

$daysover5=0;

echo "<td id='weeks'>Total</td>";
for ($i=7; $i<14; $i++){
	$stemp = $starttimes[mktime(0,0,0,$month,$day+$i,$year)];
	$etemp = $endtimes[mktime(0,0,0,$month,$day+$i,$year)];
	if ( ($etemp != -1) && ($stemp != -1)) $ttemp = ($etemp - $stemp) / 60 / 60;
	else $ttemp = 0;
	if ($ttemp == 0)  $ttempprint = "";
	if ($ttemp > 5) {$ttempprint = $ttemp-0.5; $daysover5=$daysover5+1;}
	else $ttempprint = $ttemp; 
	echo "<td id='total' align=center><b>" . $ttempprint . "</b></td>";
	$week2total += $ttemp;
}

$week2total = $week2total-($daysover5*0.5);

echo "<td id='total' align=center><b>" . $week2total . "</b></td>";

echo "</tr></table></div> \n";


//TOTAL HOURS
echo "\n <div id='periodTotal'>";
echo "<table id='periodTotalTable'>";
echo "<tr><td align=center><font size=3><b>" . date("m-d-Y", mktime($hour,$minute,$second,$month,$day+20,$year)) . "</b></font></td></tr>";
echo "<tr><td align=center><font size=2>End Date<br>&nbsp;</font></td></tr>";

echo "<tr><td align=center><font size=3><b>" . $payrate . "</b></font></td></tr>";
echo "<tr><td align=center><font size=2>Payrate<br>&nbsp;</font></td></tr>";

$grandtotal = $week1total + $week2total;
echo "<tr><td align=center><font size=3><b>" . $grandtotal . "</b></font></td></tr>";
echo "<tr><td align=center><font size=2>Grand Total Hours<br>&nbsp;</font></td></tr>";

// FISCAL YEAR TOTAL
$fiscalYearStart = (int)$month < 7 ? (int)$year - 1 : (int) $year;
$querStr = "select (SUM(time_to_sec(endtime)-time_to_sec(starttime)) / 60 / 60) as sumofallhours from payrollinfo where username='";
$querStr = $querStr . $username . "' and date between date('" . $fiscalYearStart . "-07-01') and date('" . ($fiscalYearStart + 1) . "-07-01');";
$row = mysql_fetch_array(mysql_query($querStr));
echo "<tr><td align=center><font size=3><b>" . (float)$row["sumofallhours"] . "</b></font></td></tr>";
echo "<tr><td align=center><font size=2>Hours This Fiscal Year<br>&nbsp;</font></td></tr>";

//PAYROLL AMOUNT
$payrollamt = $payrate * ($week1total + $week2total);
echo "<tr><td align=center><font size=3><b>" . $payrollamt . "</b></font></td></tr>";
echo "<tr><td align=center><font size=2>Payroll amount<br>&nbsp;</font></td></tr>";

echo "</table>\n";

echo "<div style=\"\">";
echo "<form action=\"timesheet.php\" method=\"post\">";
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input type='hidden' name='username' value=\"". $employee . "\" id='username' />";
echo "<input id='print' type='submit' value=\"Printable\" class='button'\"/></form></div> \n";


echo "<form method=\"post\" action=\"complete-timesheet.php\">";
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input type='hidden' name='username' value=\"". $employee . "\" id='username' />";
echo "<input id='printcomplete' type='submit' value=\"Print Completed\" class='button'\"/></form></div>\n";

?>

<script language="Javascript">
function toggle(source, $className) {

	checkboxes = document.getElementsByClassName($className);
	for(var i=0; i < checkboxes.length; ++i)
		checkboxes[i].checked = source.checked;
}

</script>

<?

// dynamically create a checkbox based on available users
echo '<div class="adminCheckboxOuter">';
echo "<form class=\"adminCheckboxInner\" method=\"post\" action=\"master-timesheet.php\">";
echo "<input type='checkbox' onClick='toggle(this, \"checkMe\")'/>Check All<br/>";

foreach($allUsers as $nextUser) {

	if ($nextUser !== "")
		echo '<input class="checkMe" type="checkbox" name="'.$nextUser.'">'.$nextUser.'<br>';
	
}
// Edited by jgr68 on Mon Jun 30 - 13:35
// Addint 'startdate' input to 'startdate=null' bug
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input id='print' type='submit' value=\"Print Checked\" class='button'\"/></form></div> \n";
echo "</form><br>";

// dynamically create a checkbox based on available users
echo '<div class="adminCheckboxOuter">';
echo "<form class=\"adminCheckboxInner\" method=\"post\" action=\"csv-timesheet.php\">";
echo "<input type='checkbox' onClick='toggle(this, \"checkMeCsv\")'/>Check All<br/>";

foreach($allUsers as $nextUser) {

	if ($nextUser !== "")
		echo '<input class="checkMeCsv" type="checkbox" name="'.$nextUser.'">'.$nextUser.'<br>';

}
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input id='print' type='submit' value=\"Make CSV\" class='button'\"/></form></div> \n";
echo "</form><br>";

// creates a checkbox listing employees that have not completed their vouchers. emails them
// reminder upon clicking submit button
echo '<div class="adminCheckboxOuter">';
echo "<form class=\"adminCheckboxInner\" method=\"post\" action=\"jumpgate.php\">";
echo "<h3><center>Incomplete Vouchers</center></h3>";
echo "<input type='checkbox' onClick='toggle(this, \"checkSlack\")'/>Check All<br/>";
foreach($slackers as $s) {

	if ($s !== "")
		echo '<input class="checkSlack" type="checkbox" name="'.$s.'">'.$s.'<br>';
	
}
echo "<input type='hidden' name='startdate' value=\"" . $startdate_in . "\" />";
echo "<input type='hidden' name='employee' value=\"". $employee . "\" id='username' />";
echo "<input id='print' type='submit' value=\"Remind\" class='button'\"/></div> \n";
echo "</form></div>";

echo "<div style=\"\">";
echo '</div>';

// end container
echo "<!-- container--></div>\n";

if ($saveok) echo "<div id='success'>Your hours have been sucessfully recorded.</div>";
if ($reminderSent) echo "<div id='success'>Reminder emails sent.</div>";
else echo "&nbsp;";


//overall table 2

//AUTHORIZED SIGNATURE
//ImageString($id, 4, 640, 274, "Joseph Miklojcik", $black);

//imagepng($id);

// rfranknj feb 2 2010 : ^^ what the hell is this doing here? 

include("footer.inc");

?>

