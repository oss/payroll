<?php
require_once('config.inc.php');

$netid = $_POST['netid'];
$ssn = $_POST['ssn'];
$type = $_POST['type'];
$title = $_POST['title'];
$wage = $_POST['wage'];
$email = $_POST['email'];

//Connect to database
$connection = mysql_connect($db_host, $db_user, $db_passwd) or die(mysql_error());
mysql_select_db($db_database) or die(mysql_error());

mysql_query("update empinfo set social='$ssn', type='$type', descduty='$title', payrate='$wage', email='$email' where username='$netid'") or die(mysql_error());

mysql_close($connection);
?>
