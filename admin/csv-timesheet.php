<?php

  include("payroll-tshack.inc");


  echo("netid,signin,signout,total_break_time,account_code,system_notes\n");

  $startdate_in = $_POST['startdate'];
  $startdate = strtotime($startdate_in . " 00:00:00");
  $year = date("Y", $startdate);
  $day = date("d", $startdate);
  $month = date("m", $startdate);
  $enddate = mktime(12,30,0,$month,$day+13,$year);
  $startdate_SQL = date("Y-m-d", $startdate);
  $enddate_SQL = date("Y-m-d", $enddate);

  header("Content-type: application/CSV");
  header('Content-Disposition: attachment; filename=" ' . $enddate_SQL . '-hours.csv"');

  // foreach($_POST as $
  // $result = mysql_query($querytext,$db);
  foreach($_POST as $username=>$unused) {
    if($username == 'startdate')
      continue;


    $querytext = "SELECT * from `payrollinfo` WHERE `username` = '" . $username . "' AND `date` >= '" . $startdate_SQL . "' AND `date` <= '" . $enddate_SQL . "'";
    $result = mysql_query($querytext, $db);

    $userquerytext = "SELECT acctcode from `empinfo` WHERE `username` = '" . $username . "'";
    $userresult = mysql_query($userquerytext, $db);
    $tmparr = mysql_fetch_array($userresult);
    $acct = $tmparr['acctcode'];

    while ($data = mysql_fetch_array ($result)) {

      $daystamp = strtotime($data['date'] . " 00:00:00");
      if ($data['starttime'] == "00:00:00") $startstamp = 0;
      else $startstamp = strtotime($data['date'] . " " . $data['starttime']);
      $endstamp = strtotime($data['date'] . " " . $data['endtime']);
      if ($data['endtime'] == "00:00:00") $endstamp = 0;
      else $starttimes[$daystamp] = $startstamp;
      $endtimes[$daystamp] = $endstamp;
    }

    for ($i=0; $i<7; $i++){
      $datetemp = date("m/d/y", mktime($hour,$minute,$second,$month,$day+$i,$year));
      $stemp = $starttimes[mktime(0,0,0,$month,$day+$i+7,$year)];
      $stemp_s = date("h:i A", $stemp);
      if ($stemp == 0)
        continue;
      $etemp = $endtimes[mktime(0,0,0,$month,$day+$i+7,$year)];
      if ($etemp == 0)
        continue;
      $etemp_s = date("h:i A", $etemp);

      echo(
        $username . "," .
        $datetemp . " " . $stemp_s . "," .
        $datetemp . " " . $etemp_s . "," .
        "0," .
        $acct . "," .
        "\n"
      );
    }

    unset($endtimes);
    unset($starttimes);
  }
?>