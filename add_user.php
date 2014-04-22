<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html>
<head>
	<title>Three Stone Solutions (c) Meal Planner</title>
</head>
<body>
<?php
	if(!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
		END:
?>
	<center><h3>Hello
	<?php
		echo $_SESSION['username'];
	?>!</h3><br/></center>
	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
		<tr><form name="form1" method="post" action="<?php echo $PHP_SELF;?>">
		<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
			<tr><td colspan="3"><center><strong>Add Patient </strong></center></td></tr>
			<tr><td width="78">First name</td>
				<td width="294"><input name="fname" type="text" maxlength="25"></td>
			</tr>
		<tr><td width="78">Last name</td>
				<td width="294"><input name="lname" type="text" maxlength="25"></td>
			</tr>
			<tr><td>Location</td>
				<td><select name="loc">
				<option value="A01">A01</option>
				<option value="Q01">Q01</option>
				<option value="Lunetten">Lunetten</option>
				</select></td>
			</tr>
			<tr><td>Date of birth</td>
				<td>
<?php
	echo print_calander("cal", 1925, 2012);
?>
</td>
			</tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td>
					<td><input type="submit" name="submit" value="Add"></td>
			</tr>
		</table></td></form></tr>
	</table>
<?php
	}
	else{
		$first = $_POST['fname'];
		$last = $_POST['lname'];
		$loc = $_POST['loc'];
		$dob = mysql_real_escape_string(stripslashes($_POST['calyear'] . "-" . $_POST['calmonth'] . "-" . $_POST['calday']));
		if($first && $last && $loc && $dob){
			echo add_patient($_SESSION['username'], $first, $last, $dob, $loc);
			goto END;
		}
		else{
			echo "* All fields are required!";
			goto END;
		}
	}
?>
</body>
</html>

