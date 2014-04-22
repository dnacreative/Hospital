<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html>
<head>
  <title>Three Stone Solutions (c) Control Panel</title>
</head>
<body>
<?php
  if(!check_admin($_SESSION['username'])){
    echo "* You must be an admin to access this page!<br/>";
  }
  else if(!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
    END: // haxs?
?>
<form name="form1" method="post" action="<?php echo $PHP_SELF;?>">
<b>Add meal:</b><input name="add_meal" type="text" maxlength="50"> Snack: <input type="checkbox" name="snack" value="no" /><input type="submit" name="submit" value="Add">
</form>
<form name="form2" method="post" action="<?php echo $PHP_SELF;?>">
<b>Delete meal: </b><select name="del_meal">
<?php
	$query = mysql_query("select meal from A01") or die(mysql_error()); # Since all locations share the same meals and snacks, this is fine.
	while($ret=mysql_fetch_array($query)){
		echo "<option value=\"" . $ret['meal'] . "\">" . $ret['meal'] . "</option>";
	}
?>
</select> <input type="submit" name="submit" value="Meal Delete"></form>
<form name="form3" method="post" action="<?php echo $PHP_SELF;?>">
<b>Delete Patient </b><select name="patient"> 
<?php
	 	$query = mysql_query("select * from patients");
		while($row=mysql_fetch_array($query)){
			echo "<option value=\"".$row['fname']." ".$row['lname']."\">".$row['fname']." ".$row['lname'] . "</option>";
		}
?>
</select> <input type="submit" name="submit" value="Patient Delete"></form>
<form name="form4" method="post" action="<?php echo $PHP_SELF;?>">
<b>Delete User </b><select name="name">
<?
	$query = mysql_query("select * from members");
	while($row=mysql_fetch_array($query)){
		echo "<option value=\"" . $row['username'] . "\">" . $row['username'] . "</option>";
	}
?>
</select> <input type="submit" name="submit" value="User Delete"></form>
<?php
	}
	else{
		if($_POST['submit'] == "Add"){
			$meal = trim($_POST['add_meal']);
			if(!$meal) return "* A meal name must be given!";
			echo add_new_meal($meal, 0, $_POST['snack']?1:0);
		}
		else if($_POST['submit'] == "Patient Delete"){
			$name = explode(" ", mysql_real_escape_string(stripslashes($_POST['patient'])));
			echo del_patient($name[0], $name[1]);
		}
		else if($_POST['submit'] == "User Delete"){
			echo del_user(mysql_real_escape_string(stripslashes($_POST['name'])));
		}
		else if($_POST['submit'] == "Meal Delete"){
			echo del_meal($_POST['del_meal']);
		}
		goto END;
	}
?>
</body></html>

