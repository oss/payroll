<?php


echo "testing";
include('../payroll.inc');

$queryText = "SELECT * FROM `payrollinfo` WHERE `iscomplete`=0 AND `date`='".$_POST['startdate']."'";

while($row = mysql_fetch_assoc($result)) {
	$user = $row['username'];
	echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$user.'">';
}

}



?>

