<?php

require_once('config.inc.php');

$netid = $_POST['netid'];
$fullname = $_POST['fullname'];
$type = $_POST['type'];
$title = $_POST['title'];
$wage = $_POST['wage'];
$acct = $_POST['acct'];
$email = $_POST['email'];

$connection = mysql_connect($db_host, $db_user, $db_passwd) or die(mysql_error()) or die( mysql_error() );
mysql_select_db($db_database) or die(mysql_error());

mysql_query("UPDATE empinfo SET type='$type', descduty='$title', payrate='$wage', acctcode='$acct', email='$email', fullname='$fullname' WHERE username='$netid'") or die(mysql_error());

mysql_close($connection);
?>
