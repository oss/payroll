<html>
<body>
<br><br>

<?php
include('../payroll.inc');

$queryText = "SELECT * FROM `payrollinfo` WHERE `iscomplete`=1 AND `date`='".$_POST['startdate']."'";

while($row = mysql_fetch_assoc($result)) {
	$user = $row['username'];
	echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$user.'">';
}
?>

<br><br>
</body>
</html>
