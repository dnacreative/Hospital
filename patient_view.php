<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
	if(!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
		END: // Hackers will hack :E
?>
<html>
<head><title>Three Stone Solutions (c) Meal Planner</title></head>
<body>
<form name="patient" method="post" action="<?php echo $PHP_SELF;?>">
<center><h3>Patient Meal Planner</h3>
<h4>Patient Chooser</h4>
<table border='1'><tr><td>Name</td><td>
<select name="names">
<?
	$query = mysql_query("select * from patients");
	while($row=mysql_fetch_array($query)){
		echo "<option value=\"" . $row['fname'] . " " . $row['lname'] . "\">" . $row['fname'] . " " . $row['lname'] . "</option>";
	}
?>
</select></td></tr>
<tr><input type="submit" value="submit" name="submit"></tr></table>
</form>
<?
	}
	else{
		$name = $_POST['names'];
		$full = explode(" ", $name);
		$full[0] = stripslashes($full[0]);
		$full[1] = stripslashes($full[1]);
		$full[0] = mysql_real_escape_string($full[0]);
		$full[1] = mysql_real_escape_string($full[1]);
		echo view_history($full[0], $full[1], 1);
		echo view_history($full[0], $full[1], 2);
		goto END;
	}
?>
</body></html>

