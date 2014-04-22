<?php
	session_start();
?>
<html>
<head>
	<title>Three Stone Solutions (c) Meal Planner</title>
</head>
<body>
<?php
	include("overview.php");
	if(!session_is_registered(username)){
?>
	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
		<tr><form name="form1" method="post" action="login.php">
		<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
			<tr><td colspan="3"><center><strong>Member Login </strong></center></td></tr>
			<tr><td width="78">Username</td>
				<td width="6">:</td>
				<td width="294"><input name="username" type="text" id="username" maxlength="25"></td>
			</tr>
			<tr><td>Password</td><td>:</td>
				<td><input name="password" type="password" id="password"></td>
			</tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td>
					<td><input type="submit" name="Submit" value="Login"></td>
			</tr>
		</table></td></form></tr>
	</table>
<?php
	}
	else{
?>
<center><h3>Home</h3>
<p>
<?php
	echo "<h3>Hello " . $_SESSION['username']. "</h3>";
?>
</p>
<a href="add_user.php">[add patient] </a><a href="add_member.php">[add user] </a><a href="patient_view.php">[patient overview]</a><a href="add_meal.php"> [add meal] </a><a href="stock_edit.php">[edit stock] </a><a href="stock_minimum.php">[edit minimum stock]</a> <a href="user_mod.php">[Panel] </a><a href="del_id.php"> [Edit Logs] </a><a href="search_logs.php">[logs] </a><a href="logout.php"> [logout] </a></center><br/>
<?php
		echo view_patients();
		echo view_users();
		echo view_all_logs();
		echo "<center><h3>Weekly Overview</h3><table border='1'><tr><td>AMS</td><td>Lunetten</td></tr><td>";
		echo location_log("A01");
		echo location_log("Q01");
		echo "</td><td>";
		echo location_log("Lunetten");
		echo "</td></tr></table></center>";
	}
?>
</body>
</html>
