<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html>
<head>
  <title>Three Stone Solutions (c) Log Viewer</title>
</head>
<body>
<?php
  if(!check_admin($_SESSION['username'])){
    echo "* You must be an admin to access this page!<br/>";
  }
  else if(!isset($_POST['submit'])){
    END:
?>
  	<center><h3>Log viewer</h3>
  	<form method="post" action="<?php echo $PHP_SELF;?>"><table border='1'>
  	<tr><td>Type</td><td>Patient</td><td>Date</td></tr>
  	<tr><td><select name="type"><option value="Add Patient">Add patient</option><option value="Add User">Add user</option><option value="Add Meal">Meals</option><option value="Edit Stock">Change stock</option><option value="Edit Minimum">Minimum stock</option><option value="Delete User">Delete User</option><option value="Delete Patient">Delete Patient</option><option value="date">Date</option></select></td>
	  <td><select name="patient"> 
<?php
	 	$query = mysql_query("select * from patients");
		while($row=mysql_fetch_array($query)){
			echo "<option value=\"".$row['fname']." ".$row['lname']."\">".$row['fname']." ".$row['lname'] . "</option>";
		}
?>
  </select></td><td>
  <?php
  	echo print_calander("cal");
  ?>
  </td></tr>
  </table><input type="submit" name="submit" value="Submit"></center>
<?php
  }
  else{
  	$meal_date = mysql_real_escape_string(stripslashes($_POST['calyear'] . "-" . $_POST['calmonth'] . "-" . $_POST['calday']));
    $type = mysql_real_escape_string(stripslashes($_POST['type']));
	  $name = explode(" ", mysql_real_escape_string(stripslashes($_POST['patient'])));
    echo "<center><h3>$type log</h3><table border='1'><tr><td>Action preformed by</td><td>Location</td><td>Meal</td><td>Patient</td><td>Quanity</td><td>Comment</td><td>Time</td></tr>";
    if($type == 'meal'){
    	if($meal_date){
    		$query = mysql_query("select * from log where first='$name[0]' and last='$name[1]' and action='$type' order by id desc") or die(mysql_error());
    	}
    	else{
	    	$query = mysql_query("select * from log where first='$name[0]' and last='$name[1]' and action='$type' order by id desc") or die(mysql_error());
	    }
    }
    else{
    	$name[0] = $name[1] = "";
    	if($type == 'date'){
    		$query = mysql_query("select * from log where action='$type' and time like '$meal_date%' order by id desc") or die(mysql_error());
    	}
    	else{
	    	$query = mysql_query("select * from log where action='$type' order by id desc") or die(mysql_error());
	    }
    }
    while($ret = mysql_fetch_array($query)){
    	echo "<tr><td>" . $ret['user'] . "</td><td>" . $ret['location'] . "</td><td>" . $ret['meal'] . "</td><td>$name[0] $name[1]</td><td>" . $ret['quanity'] . "</td><td>" . $ret['comment'] . "</td><td>" . $ret['time'] . "</td></tr>";
    }
    echo "</table><br/>";
    goto END;
  }
?>
</body>
</html>

