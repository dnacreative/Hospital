<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html><head></head><title>Three Stone Solutions (c) Weekly Report</title><body>
<?php
  if(!check_admin($_SESSION['username'])){
    echo "* You must be an admin to access this page!<br/>";
  }
		$subject = "bestelling utrecht";
		$mail .= "Hierbij onze bestelling:\n";
		$mail2 = $mail;
		$mail .= "Graag leveren maandag tussen 12.00-15:00\n";
		// Select stock minimums from each location and compare them with their stock.
		// Send a report in order of 10 for every meal.
		$q = mysql_query("select meal from A01") or die(mysql_error());
		while($meal = mysql_fetch_array($q)){
			$order = 0;
		 	$query = mysql_query("select stock from A01 where meal='". $meal['meal'] . "' union select min from minimums where location='A01' and meal='". $meal['meal'] . "'") or die(mysql_error());
		 	$ret = mysql_fetch_array($query);
		 	$ret2 = mysql_fetch_array($query);
		 	while($ret['stock']+$order < $ret2['stock']){
		 		$order += 10;
			}
		 	$ams[$meal['meal']] += $order;
		 	$query= mysql_query("select stock from Q01 where meal='" . $meal['meal'] . "' union select min from minimums where location='Q01' and meal='". $meal['meal'] . "'") or die(mysql_error());
		 	$ret = mysql_fetch_array($query);
		 	$ret2 = mysql_fetch_array($query);
		 	while($ret['stock']+$order < $ret2['stock']){
		 		$order += 10;
			}
			$ams[$meal['meal']] += $order;
			$mail .= ($meal['meal'] . " : " . $ams[$meal['meal']] . "\n");
		}
		$q = mysql_query("select meal from Q01") or die(mysql_error());
		while($meal = mysql_fetch_array($q)){
			$query = mysql_query("select stock from Lunetten where meal='" . $meal['meal'] . "' union select min from minimums where location='Lun' and meal='". $meal['meal'] . "'") or die(mysql_error());
			$ret = mysql_fetch_array($query);
		 	$ret2 = mysql_fetch_array($query);
		 	while($ret['stock']+$order < $ret2['stock']){
		 		$order += 10;
			}
		 	echo $ret['stock']  . " : " . $ret2['stock'] . " : " . $meal['meal'] . "<br/>";
		 	$lun[$meal['meal']] += $order;
		 	$mail2 .= $meal['meal'] . " : " . $lun[$meal['meal']] . "\n";
		}
		$mail .= "Afleveradres: Brennerbaan 130 utrecht\n";
		$mail2 .= "Afleveradres: \n";
		// echo "$mail<br/>$mail2<br/>";
		// (Windows only) When PHP is talking to a SMTP server directly, if a full stop is found on the start of a line, it is removed. To counter-act this, replace these occurrences with a double dot.
		//$text = str_replace("\n.", "\n..", $text);
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$mail = wordwrap($mail, 70);
		$mail2 = wordwrap($mail2, 70);
		$headers = 'From: test@hospital.com' . "\r\n" . 'Reply-To: test@hospital.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		ini_set("sendmail_from", "test@hospital.com");
		$query = mysql_query("select * from reports") or die(mysql_error());
		while($row = mysql_fetch_array($query)){
			echo $row['email'] . "<br/>";
			if(mail($row['email'], "bestelling utrecht", $mail, $headers) && mail($row['email'], "bestelling amsterdam", $mail2, $headers)){ // Send the emails
				echo "Email sent!<br/>";
			}
			else{
				echo "Email couldn't be sent!<br/>";
			}
		}
		# Update the weekly id for the weekly overview.
		$query = mysql_query("select id from logs order by asc limit 1") or die(mysql_error());
		$ret = mysql_fetch_array($query);
		$id = $ret['id'];
		$query = mysql_query("update weekly set last_id='$id'") or die(mysql_error());
?>
</body></html>

