<html>
<body>
<br><br>


<?php
 
ob_start();
include('admin.php');
ob_end_clean();

foreach($allUsers as $nextUser) {

	foreach($_POST as $key=>$value)
	{
		echo preg_replace("$key";
	}
	if ($nextUser !== '') {
		echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$nextUser.'">';
	}
}

?>

<br>
<br>

</body></html>
