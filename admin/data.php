<?php
$netid = $_POST['netid'];
$fullname = $_POST['fullname'];
$ssn = $_POST['ssn'];
$type = $_POST['type'];
$title = $_POST['title'];
$wage = $_POST['wage'];



//Connect to database
$connection = mysql_connect($db_host, $db_user, $db_passwd) or die(mysql_error());
mysql_select_db($db_database) or die(mysql_error());

mysql_query("INSERT INTO empinfo (username, fullname, social, type, descduty, payrate) VALUES('$netid', '$fullname', '$ssn', '$type', '$title', '$wage')") or die(mysql_error());

mysql_close($connection);
?>
