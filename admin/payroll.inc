<?php
require_once('../config.inc.php');

//EDIT THIS LINE TO SPECIFY HOST, DATABASE_USERNAME, PASSWORD
$db = mysql_connect($db_host, $db_user, $db_passwd);
//ENTER THE DATABASE NAME ON THIS LINE
mysql_select_db($db_database, $db);

$name = explode("@", $_SERVER['PHP_AUTH_USER']);
$username = $name[0];

$result = mysql_query("SELECT * FROM empinfo WHERE `username` = '" . $username . "'",$db);

$fullname = mysql_result($result,$i,"fullname");
$descduty = mysql_result($result,$i,"descduty");
?>
