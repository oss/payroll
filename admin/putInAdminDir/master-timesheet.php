<html>
<body>
<br><br>


<?php
 
ob_start();
include('admin.php');
ob_end_clean();

foreach($allUsers as $nextUser) {

	if ($nextUser !== '') {
		echo '<img src="timesheet2.php?startdate='.$_POST["startdate"]."&username=".$nextUser.'">';
	}
}

?>

<br>
<br>

</body></html>
