<?php


echo "testing";
include('../payroll.inc');

$queryText = "SELECT * FROM `payrollinfo` WHERE `iscomplete`=0 AND `date`='".$_POST['startdate']."'";
$result = mysql_query($queryText,$db);

while($row = mysql_fetch_assoc($result)) {
//	echo "isthisworking";
	$user = $row['username'];
	echo $user;
	//echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$user.'">';
}





?>

