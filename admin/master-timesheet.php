<html>
<body>
<br><br>


<?php
foreach($_POST as $key=>$value) {
	$datUserTho = preg_replace('/value="checked"$/', '', $key);	
	echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$datUserTho.'">';
}
?>

<br>
<br>

</body></html>
