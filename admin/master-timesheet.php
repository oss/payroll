<html>
<body>
<br><br>

<?php
foreach($_POST as $key=>$value) {
	if ($key != 'startdate') {
		$user = preg_replace('/value="checked"$/', '', $key);
		echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$user.'">';
	}
}
?>

<br><br>
</body>
</html>
