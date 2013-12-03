<?php

include("header.inc");
include("payroll.inc");

// its really weird that we are incuding the header and then putting <html> below, but i dont care

// employee information start
$result = mysql_query("SELECT * FROM `empinfo` WHERE `username` = '" . $username . "'",$db);
$i = 0;
$payrate = mysql_result($result,$i,"payrate");

//EMPLOYEE INFORMATION
echo "<div id='empyInfo'>";
echo "<span id='name'>" . mysql_result($result,$i,"fullname") . "</span><br />";
echo "<span id='empyTitle'>" . mysql_result($result,$i,"descduty") . "</span>";
echo "</div>";


/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/

// Do this if you are coming from view.php

// Grab data from POST and prep
$weekbegins = $_POST["startdate"];
$copyweekbegins = $_POST["copydate"];

$startdatetext = strtotime($weekbegins . " 00:00:00");
$copystartdatetext = strtotime($copyweekbegins . " 00:00:00");


$year = date("Y", $startdatetext);
$day = date("d", $startdatetext);
$month = date("m", $startdatetext);
$copyyear = date("Y", $copystartdatetext);
$copyday = date("d", $copystartdatetext);
$copymonth = date("m", $copystartdatetext);

$enddatetext = mktime(12,30,0,$month,$day+13,$year);
$copyenddatetext = mktime(12,30,0,$copymonth,$copyday+13,$copyyear);


// Check for bad dates, and direct page loads
if (date("w", $startdatetext) != 6) {
	echo "ERROR START DATE NOT A SATURDAY!";
	exit(1);
}

// Prep for query
$startdate = date("Y-m-d", $startdatetext);
$enddate = date("Y-m-d", $enddatetext);
$copystartdate = date("Y-m-d", $copystartdatetext);
$copyenddate = date("Y-m-d", $copyenddatetext);

// if copydate calculate copystartdate/copyenddate for SELECT
$querytext = "SELECT * from `payrollinfo` WHERE `username` = '" . $username . "' AND `date` >= '" . $startdate . "' AND `date` <= '" . $enddate . "'";
$result = mysql_query($querytext,$db) or die(mysql_error());

$numdays = date("d", $enddatetext - $startdatetext);

function my_assert_handler(){
	echo "<div id='assert_error'>Pay period empty, no copy available. </div>";
}

if (mysql_num_rows($result) == 0) {
	while($numdays > 0){
		$inserttext = "INSERT INTO payrollinfo (username, date, starttime, endtime) VALUES ('$username', DATE_ADD('$startdate', INTERVAL $numdays-1 DAY), 0, 0)";
		$insertresult = mysql_query($inserttext,$db) or die(mysql_error());
		$numdays--;
	}
	
	$querytext = "SELECT * from `payrollinfo` WHERE `username` = '" . $username . "' AND `date` >= '" . $startdate . "' AND `date` <= '" . $enddate . "'";
	$result = mysql_query($querytext,$db) or die(mysql_error());
}

// if copydate SELECT * between copydates
if($copyweekbegins) {
	$copyquerytext = "SELECT * from `payrollinfo` WHERE `username` = '" . $username . "' AND `date` >= '" . $copystartdate . "' AND `date` <= '" . $copyenddate . "'";
	$copyresult = mysql_query($copyquerytext,$db) or die(mysql_error());

	if ($copyweekbegins && mysql_num_rows($result)) { 
		// Set up the callback
		assert_options(ASSERT_ACTIVE, 1);
		assert_options(ASSERT_WARNING, 0);
		assert_options(ASSERT_QUIET_EVAL, 1);
		assert_options(ASSERT_CALLBACK, 'my_assert_handler');
		assert(mysql_num_rows($result) == mysql_num_rows($copyresult)); 
		} 

	}

$starttimes[0]=0;
$endtimes[0]=0;
while ($data = mysql_fetch_array ($result)) {
// if copydate $copydata = mysql_fetch_array
	if($copyweekbegins) {$copydata = mysql_fetch_array($copyresult);}
	
	$daystamp = strtotime($data['date'] . " 00:00:00");

// if (copydate) { $startstamp = strtotime($data['date'] . " " . date($copydata['starttime'])); }
	if($copyweekbegins) {
		$startstamp = strtotime($data['date'] . " " . $copydata['starttime']);
		}
	else{ $startstamp = strtotime($data['date'] . " " . $data['starttime']); }
	
// if (copydate) { $endstamp = strtotime($data['date'] . " " . date($copydata['endtime'])); }
	if($copyweekbegins){ 
		$endstamp = strtotime($data['date'] . " " . $copydata['endtime']);
		}
	else{
		$endstamp = strtotime($data['date'] . " " . $data['endtime']);
		}

	
	
	$starttimes[$daystamp] = $startstamp;
	$endtimes[$daystamp] = $endstamp;

}

/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/

//echo "<table border=0 cellpadding=2 cellspacing=2><tr><td bgcolor=\"#DDDDDD\">";echo "<b>" . $fullname . ", " . $descduty . "</b>";
//echo "</td></tr></table>";

echo "<!--<div id='payPeriod'>";
echo "<table id='payPeriodtable'><tr><td><b>Copy Pay period: </b></td>";
echo "<td><form name=\"ws\" action=hoursform.php method=post>";
echo "<input name='startdate' value='" . $weekbegins . "' type='hidden' />";
echo "<select name=copydate onchange=\"ws.submit()\">";

$right_now = time();
for ($i=0; $i<28; $i++) {
   $week_start = mktime(0, 0, 0, $ws_month, $ws_day+($i*14), $ws_year);
   $week_end = mktime(23, 59, 59, $ws_month, $ws_day+($i*14)+13, $ws_year);

   echo "<option value=\"" . date("Y-m-d", mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year)) . "\"";

   $thisweek = false;
   $selectedweek = false;

   if ((date('U', $week_start) <= $right_now) && (date('U', $week_end) >= $right_now))
   {
      $thisweek = true;
 
      if ($copyweekbegins == "")
         $selectedweek = true;
   }

   if ($copyweekbegins != "")
      if ($copyweekbegins == date("Y-m-d", $week_start))
         $selectedweek = true;

if ( ( $startdate_in == "" ) && $thisweek ){
$startdate = mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year);
$startdate_in = date("Y-m-d", $startdate);
$thismonth = $ws_month;
$thisday = $ws_day+($i*14);
$thisyear = $ws_year;
}


if ($selectedweek) {
echo "SELECTED";
}

echo ">" . date("F j, Y", mktime(0,0,0,$ws_month,$ws_day+($i*14),$ws_year)) . " - " . date("F j, Y", mktime(0,0,0,$ws_month,$ws_day+($i*14)+13,$ws_year));

if ($thisweek)
echo " **";


}

echo "</select></form></td><td>** <i>denotes current week</i></td></tr></table></div>-->";

/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/

function timedrop($type, $month, $day, $offset, $year, $start) {

$second = 0;
$minute = 0;
$hour = 8;


//$output = "<select name=\"" . $type  . date("mdY", mktime(12,30,0,$month,$day,$year)) . "\" onChange=\"reCalcHours()\">";
$output = "<select name=\"" . $type  . $offset . "\" onChange=\"reCalcHours()\" class='select'>";

$output .= "<option value=\"0\">--";

for ($hour = 8; $hour <= 24; $hour++) {
	for ($minute = 0; $minute < 60; $minute=$minute+30){
		$realtime = mktime($hour,$minute,$second,$month,$day,$year);
		$add="";
		if($start[mktime(0,0,0,$month,$day,$year)] == $realtime) $add="SELECTED";
		$output .= "<option " . $add . " value=\"" . $realtime . "\">";
		$output .= date("g:i a", mktime($hour,$minute,$second,$month,$day,$year));
	}
}

$output .= "</select>";

return $output;
}

?>

<html>
	<head>
		<script LANGUAGE="JAVASCRIPT">
			function reCalcHours(date) {
			
			var total1 = 0;
			var total2 = 0;
			$("#entry_error").fadeOut();
			
			var START = 0;
			var END = 1;
			var HOURS = 2;
			
			var hours = [[ <?php for ($i = 0; $i<14; $i++) { echo "document.hours.start".$i.", "; } ?> ],
				     [ <?php for ($i = 0; $i<14; $i++) { echo "document.hours.end".$i.", "; } ?> ],
				     [ <?php for ($i = 0; $i<14; $i++) { echo "document.hours.hours".$i.", "; } ?> ]];
						

			for (var i = 0; i < 14; i++) {
			  var diff = hours[END][i].value - hours[START][i].value;
			  var diffHours = diff/60/60;
			  if (diffHours > 5) { diffHours -= .5; }
			  

			  if(hours[START][i].value == 0 && hours[END][i].value != 0) {
			    hours[START][i].options[1].selected = true;
			    i--;
			    /*
			    if (hours[END][i].selectedIndex > 1) {
			      hours[START][i].options[hours[END][i].selectedIndex - 1].selected = true;
			      i--;
			    } else {
			      $("#entry_error").text("Please enter a start time."); $("#entry_error").fadeIn();
			    }
			    */
			    continue;
			  }

			  if(hours[END][i].value != 0 && diff < 0) {
			    $("#entry_error").text("End time must be after start time."); $("#entry_error").fadeIn();
			  } 
			  if (diff > 0) {
			    hours[HOURS][i].value = diffHours;

			    if (i < 7) {
			      total1  += diffHours;
			    } else {
			      total2  += diffHours;
			    }
			  } else {
			    hours[HOURS][i].value = 0;
			  }
			}

			var grandtotal = 0;
			grandtotal = total1 + total2;
			document.hours.hourstotal1.value = total1;
			document.hours.hourstotal2.value = total2;
			document.hours.hourstotal.value = grandtotal;
			}
		</script>
	</head>
	<body>
	<div id="backButton"><a href='view.php'><img src='images/back.png' /></a></div>
		<div id="payPeriodEndDate">
			<b>Pay Period</b>: <?php echo date("m-d-Y", mktime($hour, $minute, $second, $month, $day, $year)); ?> to <?php echo date("m-d-Y", mktime ($hour,$minute,$second,$month,$day+13,$year)) ?>
		</div>
		<div id="entry_error" style="display:none;"></div>
		<form name="hours" action="save.php" method="post">
			<input type=hidden name="startdate" value="<?php echo $_POST["startdate"] ?>">
			<div id="timeSelectorGrid">
				<table id='timeSelectorGridTable'>
					<tr class='weeks'>
						<td>Date</td>
						<?php
							for ($i=0; $i<7; $i++){
								if ( ( mktime ($hour,$minute,$second,$month,$day+$i,$year) < mktime() ) &&
									(mktime () < mktime ($hour,$minute,$second,$month,$day+$i+1,$year) ))
									$istoday = 1;
								echo "<td align=center>";
						
							$datetemp = date("D", mktime ($hour,$minute,$second,$month,$day+$i,$year));
							$datetemp2 = date("n / d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
							echo "<font size=1>" . $datetemp2 . "</font><br>" . $datetemp;
								if ( $istoday ) echo "**";
								echo "</td>";
								$istoday = 0;
							}
						?>
						<td class='time' valign=bottom align=center bgcolor="#FFFFFF">Weekly<br>Total</td>
					</tr>
					<tr>
						<td class='weeks'>Start</td>
						<?php
							for ($i=0; $i<7; $i++){
								echo "<td class='time'>";
								echo timedrop("start", $month, $day+$i, $i, $year, $starttimes);
								echo "</td>";
							}
						?>
					</tr>
					<tr>
						<td class='weeks'>End</td>
						<?php
							for ($i=0; $i<7; $i++){
								echo "<td class='time'>";
								echo timedrop("end", $month, $day+$i, $i, $year, $endtimes);
								echo "</td>";
							}
						?>
					</tr>
					<tr>
						<td class='weeks'>Total</td>
						<?php
							for ($i=0; $i<7; $i++){
								echo "<td align=right class='time'>";
								echo "<input name=\"hours" . $i . "\" size=5 onChange=\"reCalcHours()\"  class='hoursfield'>";
								echo "</td>";
							}
						?>
						<td bgcolor='#FFFFFF' align=center><input name="hourstotal1" value="0" size=5 onChange="reCalcHours()" class='hoursfield'></td>
					</tr>
					<tr class='weeks'>
						<td>Date</td>
						<?php
							for ($i=7; $i<14; $i++){
								if ( ( mktime ($hour,$minute,$second,$month,$day+$i,$year) < mktime() ) &&
									(mktime () < mktime ($hour,$minute,$second,$month,$day+$i+1,$year) ))
									$istoday = 1;
								echo "<td align=center>";
						
							$datetemp = date("D", mktime ($hour,$minute,$second,$month,$day+$i,$year));
							$datetemp2 = date("n / d", mktime ($hour,$minute,$second,$month,$day+$i,$year));
							echo "<font size=1>" . $datetemp2 . "</font><br>" . $datetemp;
								if ( $istoday ) echo "**";
								echo "</td>";
								$istoday = 0;
							}
						?>
						<td class='time' valign=bottom align=center bgcolor="#FFFFFF">Weekly<br>Total</td>
					</tr>
					<tr>
						<td class='weeks'>Start</td>
						<?php
							for ($i=7; $i<14; $i++){
								echo "<td class='time'>";
								echo timedrop("start", $month, $day+$i, $i, $year, $starttimes);
								echo "</td>";
							}
						?>
					</tr>
					<tr>
						<td class='weeks'>End</td>
						<?php
							for ($i=7; $i<14; $i++){
								echo "<td class='time'>";
								echo timedrop("end", $month, $day+$i, $i, $year, $endtimes);
								echo "</td>";
							}
						?>
					</tr>
					<tr>
						<td class='weeks'>Total</td>
						<?php
							for ($i=7; $i<14; $i++){
								echo "<td align=right class='time'>";
								echo "<input name=\"hours" . $i . "\" size=5 onChange=\"reCalcHours()\" class='hoursfield'>";
								echo "</td>";
							}
						?>
						<td bgcolor='#FFFFFF'><input name="hourstotal2" value="0" size=5 onChange="reCalcHours()" class='hoursfield'></td>
					</tr>
					<tr bgcolor="#FFFFFF">
						<td class='time' colspan=9 align=right>
							Grand Total Hours 
							<input name="hourstotal" value="0" size=5 onChange="reCalcHours()" class='hoursfield'>
						</td>
					</tr>
					<tr>
						<td class='time' colspan=9 align=right>
							<input type=submit value="Save" class="goodbutton">
							<input type=reset value="Reset" class="badbutton">
						</td>
					</tr>
				</table>
			</div> <!-- end timeSelectorGrid  -->
		</form>			
	</body>		
	<script LANGUAGE="JAVASCRIPT">
		reCalcHours();
	</script>
<?php
include("footer.inc");
?>
