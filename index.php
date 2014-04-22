<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- Open Systems Support -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
        <title>Open Systems Solutions - Payroll</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link type="text/css" href="css/south-street/jquery-ui-1.7.1.custom.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
        <script src="js/jquery.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
		<script src="js/submitform.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function(){
				$('#entry_edit_time').click(function(){
					window.location="view.php";
				});
				// Dialog
				$('#dialog').dialog({
					autoOpen: false,
					width: 800,
					modal: true,
					buttons: {
						"Ok": function() {
							$('#submit').click();
						},
						"Cancel": function() {
							$(this).dialog("close");
						}
					}
				});

				// Dialog Link
				$('#entry_add_emp').click(function(){
					$('#dialog').dialog('open');
					$('#netid').focus();
					return false;
				});
				<?php
	            	include("payroll.inc");
	            	$result = mysql_query("select * from empinfo where username='$username'",$db) or die(mysql_error());
					$row = mysql_fetch_array($result);
					echo "$('#netid').attr('value', \"" . $row["username"] . "\");";
					echo "$('#type').attr('value', \"" . $row["type"] . "\");";
					echo "$('#title').attr('value', \"" . $row["descduty"] . "\");";
					echo "$('#wage').attr('value', \"" . $row["payrate"] . "\");";
					echo "$('#email').attr('value', \"" . $row["email"] . "\");";
				?>
			});
		</script>
		<style type="text/css">
			/*demo page css*/
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
		</style>
	</head>
	<body>
	    <div id="header">
	        <center><img src="images/header.gif" alt="header img" /></center>
	        <div id="logo"></div>
	        <div id="listmenu">
	            <ul>
	                <li><a href="http://webmail.rutgers.edu/">Webmail</a></li>
	                <li><a href="http://mailman.rutgers.edu/">Mailman</a></li>
	                <li><a href="https://rams.rutgers.edu/">RAMS</a></li>
	                <li><a href="#">More...</a>
	                <!-- note the proper way to make nested menus w/xhtml...nested list inside of <li> </li> -->
	                    <ul>
	                        <li><a href="https://rats.rutgers.edu">RATS</a></li>
	                        <li><a href="https://rim.rutgers.edu/jwchat/">RIM</a></li>
	                    </ul>
	                </li>
	            </ul>
	        </div>
	        <div id="link_line">
	            <a href="http://css.rutgers.edu/">CSS Home</a> || <a href="http://www.rutgers.edu/">Rutgers Home</a> || <a href="http://search.rutgers.edu/">Search</a>
	        </div>
	        <div id="menu">Welcome to Rutgers Payroll, provided by Open Systems Solutions</div>
	    </div>
		<div id="main">
			<?php
				include("payroll.inc");

				// employee information start
				$result = mysql_query("SELECT * FROM `empinfo` WHERE `username` = '" . $username . "'",$db);
				$i = 0;
				$payrate = mysql_result($result,$i,"payrate");

				//EMPLOYEE INFORMATION
				echo "<div id='empyInfo'>";
				echo "<span id='name'>" . mysql_result($result,$i,"fullname") . "</span><br />";
				echo "<span id='empyTitle'>" . mysql_result($result,$i,"descduty") . "</span>";
				echo "</div>";
			?>
			<div id="entry_container">
				<div id="entry_add_emp">
	         	  	<table>
						<tr><td>Edit Employee</td></tr>
						<tr><td><img src="images/homer_simpson.png" alt="homer" /></td></tr>
					</table>

	         	   <div style="position: relative; width: 90%; height: 100px; padding:1% 4%; overflow:hidden;" class="fakewindowcontain"></div>
				</div>
				<div id="entry_edit_time">
					<table>
						<tr><td>Edit Time</td></tr>
						<tr><td><img src="images/cal.png" alt="calendar" /></td></tr>
					</table>
	            </div>
            </div>
			<div id="dialog" title="Email Forwarding">
			<h1>New Employee Form</h1>
			<p class="alert"></p>
				<form  action="" method="post" id="sendEmail">
					<table>
						<tr>
							<td><label for="netid" id="netidLabel">Netid</label></td>
						</tr>
						<tr>
							<td><input name="netid" id="netid" value="" type="text" /></td>
						</tr>
						<tr>
							<td><span id="netidInfo"></span></td>
						</tr>
						<tr>
							<td><label for="type" id="typeLabel">Type (number)</label></td>
						</tr>
						<tr>
							<td><input name="type" id="type" value="" type="text" /></td>
						</tr>
						<tr>
							<td><span id="typeInfo"></span></td>
						</tr>
						<tr>
							<td><label for="title" id="titleLabel">Title</label></td>
						</tr>
						<tr>
							<td><input name="title" id="title" value="" type="text" /></td>
						</tr>
						<tr>
							<td><span id="titleInfo"></span></td>
						</tr>
						<tr>
							<td><label for="wage" id="wageLabel">Wage Rate</label></td>
						</tr>
						<tr>
							<td><input name="wage" id="wage" value="" type="text" /></td>
						</tr>
						<tr>
							<td><span id="wageInfo"></span></td>
						</tr>
						<tr>
							<td><label for="email" id="emailLabel">Email Address</label></td>
						</tr>
						<tr>
							<td><input name="email" id="email" value="" type="text" /></td>
						</tr>
						<tr>
							<td><span id="emailInfo"></span></td>
						</tr>
						<tr>
							<td><button type="submit" id="submit" style="display:none;">Save >></button></td>
						</tr>
					</table>
					<span id="loading"></span>
					<input name="submitted" id="submitted" value="true" type="hidden" />
				</form>
			</div>
		</div>
		<div id="footer">
			&copy; 1997-2009 Rutgers, The State University of New Jersey. All rights reserved.<br />
			For questions or comments about this site, contact help@rci.rutgers.edu.
		</div>
	</body>
</html>
