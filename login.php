<?php
	// ob_start();
	$host = 'localhost';
	$username = 'test';
	$password = 'test1234';
	$db_name = "hospital_db";
	$tbl_name = "members";
	
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");
	
	if(!($username && $password)){
		return "* Must enter a Username and Password!";
	}
	$username=mysql_real_escape_string(stripslashes($_POST['username'])); // POST data from the login script.
	$password=md5($_POST['password']);
	 // $password = mysql_real_escape_string($password); // md5 is a 32 char password, a-f, 0-9, no need to escape it.
	
	$sql="SELECT * FROM $tbl_name WHERE username='$username' and password='$password'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);
	if($count==1){
		// Register username, password and redirect
		session_register("username");
		session_register("password");
		header("location:index.php");
	}
	else{
		return "* Wrong Username or Password";
	}
	// ob_end_flush();
?>

