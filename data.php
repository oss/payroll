<!--
Filename:     data.php
Author:       Rutgers Open System Solutions
Date created: Unknown
Last edited:  Mon Jun 30, 2014
Description:  This file makes establishes a connection to the mysql database
              before updating the empinfo table with values taken the http
              request headers (netid, type, title, wage, email).
-->

<?php
	require_once('config.inc.php');

	// obtain the netid, type, title, wage, ane email columns from the HTTP
	// request headers
	$netid = $_POST['netid'];
	$type = $_POST['type'];
	$title = $_POST['title'];
	$wage = $_POST['wage'];
	$email = $_POST['email'];

	// establish a connection to the mysql database
	$connection = mysql_connect(
					$db_host,
					$db_user,
					$db_passwd
				) or die( mysql_error() );
	mysql_select_db($db_database) or die(mysql_error());

	mysql_query("update empinfo set type='$type', descduty='$title', payrate='$wage', email='$email' where username='$netid'") or die(mysql_error());

	mysql_close($connection);
?>
