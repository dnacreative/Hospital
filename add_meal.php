<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
	if (!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
		END: // Hackers will hack :E
?>

<html>
<head><title>Three Stone Solutions (c) Meal Planner</title></head>
<body>
<form name="patient" method="post" action="<?php echo $PHP_SELF;?>">
<center><h3>Patient Meal Planner</h3>
<h4>Patient Information</h4><table border='1'><tr>
<td>Patient name</td><td><select name="names">
<?
	$query = mysql_query("select * from patients");
	while($row=mysql_fetch_array($query)){
		echo "<option value=\"" . $row['fname'] . " " . $row['lname'] . "\">" . $row['fname'] . " " . $row['lname'] . "</option>";
	}
?>
</select></td></tr>

<tr><td>Meal choice:</td><td>
<select name="food">
<?php
	echo meal_options("A01");
?>
?>
</select></td></tr>
<tr><td>Meal Date</td><td>
<?php
	echo print_calander("cal", 2012);
?>
</td></tr>
<tr><td>
<tr><td>Comments</td><td width="294"><input name="comment" type="text" maxlength="50"></td></tr><tr><td></td><td>
<input type="submit" value="submit" name="submit"></td></tr></table>
</form>
<?
}
else{
	$name = $_POST['names'];
	$full = explode(" ", $name);
	$full[0] = mysql_real_escape_string(stripslashes($full[0]));
	$full[1] = mysql_real_escape_string(stripslashes($full[1]));
	$meal_date = mysql_real_escape_string(stripslashes($_POST['meal_date']));
	$food = stripslashes($_POST['food']);
	$stock = stripslashes($_POST['stock']);
	$comment = mysql_real_escape_string(stripslashes(trim($_POST['comment'])));
	$meal_date = mysql_real_escape_string(stripslashes($_POST['calyear'] . "-" . $_POST['calmonth'] . "-" . $_POST['calday']));
	echo add_meal($full[0], $full[1], $food, $comment , $meal_date);
	goto END;
}
?>
</body></html>

