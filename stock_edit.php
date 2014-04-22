<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html>
<head>
  <title>Three Stone Solutions (c) Stock Editor</title>
</head>
<body>
<?php
  if(!check_admin($_SESSION['username'])){
    echo "* You must be an admin to access this page!<br/>";
  }
  else if(!isset($_POST['submit'])){ // if page is not submitted to itself echo the form
    END: // haxs?
?>
<?php
		echo view_stock();
?>
  <center><h3>Stock editor</h3><form method="post" action="<?php echo $PHP_SELF;?>"><table border='1'>
  <tr><td>Location</td><td>Meal</td><td>Stock</td></tr>
  <tr><td><select name="location"> <option value="A01">A01</option><option value="Q01">Q01</option><option value="Lunette">Lunette</option></select></td>
  <td><select name="food">
<?php
	$query = mysql_query("select meal from A01 where snack=0") or die(mysql_error()); # Since all locations share the same meals and snacks, this is fine.
	while($ret=mysql_fetch_array($query)){
		echo "<option value=\"" . $ret['meal'] . "\">" . $ret['meal'] . "</option>";
	}
?>
  </td>
  <td><input name="count" type="number" maxlength="5"></td></tr></table><input type="submit" name="submit" value="Submit"></center>
<?php
  }
  else{
    $count = mysql_real_escape_string(stripslashes($_POST['count']));
  	if(!$count){
  		echo "* A quanity must be given!";
  		goto END;
  	}
    if($count < 0){
      echo "* Stock cannot be negative.";
      goto END;
    }
    $food = mysql_real_escape_string(stripslashes($_POST['food']));
    $loc = mysql_real_escape_string(stripslashes($_POST['location']));
    edit_stock($food, $count, $loc);
    goto END;
  }
?>
</body>
</html>
