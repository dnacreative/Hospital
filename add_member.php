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
	if(!check_admin($_SESSION['username'])){
		echo "* You must be an admin to access this page!<br/>";
	}
	else if(!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
		END:
?>
	<center><h3>Hello
	<?php
		echo $_SESSION['username'];
	?>
!</h3><br/></center>
	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
		<tr><form name="form1" method="post" action="<?php echo $PHP_SELF;?>">
		<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
			<tr><td colspan="3"><center><strong>Add Member </strong></center></td></tr>
			<tr><td width="78">Username</td><td width="6">:</td>
				<td width="294"><input name="username" type="text" maxlength="25"></td>
			</tr>
		<tr><td width="78">Password</td><td width="6">:</td>
				<td width="294"><input name="password" type="password" maxlength="25"></td>
			</tr>
		<tr><td width="78">Admin</td><td width="6">:</td><td><input type="checkbox" name="admin" value="no" /></td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td>
					<td><input type="submit" name="submit" value="Add"></td>
			</tr>
		</table></td></form></tr>
	</table>
<?php
	}
	else{
		$user = trim($_POST['username']);
		$pass = trim($_POST['password']);
		$pass = md5($pass); // No need to sanatize, it will be a 32 char 0-9 A-F
		echo add_user($_SESSION['username'], $user, $pass, $_POST['admin']?1:0);
		goto END;
	}
?>
</body>
</html>
