<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html>
<head>
  <title>Three Stone Solutions (c) Delete User</title>
</head>
<body>
<?php
  if(!check_admin($_SESSION['username'])){
    echo "* You must be an admin to access this page!<br/>";
  }
	if(!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
    END: // haxs?
	echo "<form method=\"post\" action=\"del_id.php\"><center><h3>Logs</h3><table border='1'>";
	echo "<tr><td>Action</td><td>Performed By</td><td>First name</td><td>Last name</td><td>Meal/Admin</td><td>Quanity</td><td>Time</td><td>Meal date</td><td>Comments</td><td>ID</td></tr>";
	$query = mysql_query("select * from log order by id desc") or die(mysql_error());
	while(($row=mysql_fetch_array($query))){
		echo "<tr><td>".$row['action']."</td><td>".$row['user']."</td><td>".$row['first']."</td><td>".$row['last']."</td><td>".$row['meal']."</td><td>".$row['quanity']."</td><td>".$row['time']."</td><td>" .$row['date']."</td><td>".$row['comment']."</td><td><input type=\"submit\" name=\"submit\" value=\"".$row['id']."\"></td></tr>";
	}
	echo "</table></center></form>";
?>
<?php
	}
	else{
		echo $POST['submit'] . "<br/>";
		echo del_id(mysql_real_escape_string(stripslashes($_POST['submit'])));
		goto END;
	}
?>

