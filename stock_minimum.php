<?php
	session_start();
	if(!session_is_registered(username)){
		header("location:index.php");
	}
	include("overview.php");
?>
<html>
<head>
  <title>Three Stone Solutions (c) Stock Minimum Editor</title>
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
	echo view_stock_min();
?>

  <center><h3>Stock minimum editor</h3><form method="post" action="<?php echo $PHP_SELF;?>"><table border='1'>
  <tr><td>Location</td><td>Meal</td><td>Stock</td></tr>
  <tr><td><select name="location"> <option value="A01">A01</option><option value="Q01">Q01</option><option value="Lun">Lunette</option></select></td>
  <td><select name="food">
<?php
	echo meal_options("A01");
?>
  </td><td><input name="count" type="number" maxlength="5"></td></tr></table><input type="submit" name="submit" value="Submit"></center>
<?php
  }
  else{
    $count = mysql_real_escape_string(stripslashes($_POST['count']));
    if(!$count){
    	echo "* A quanity must be given!";
  		goto END;
    }
    if($count < 0){
      echo "* Stock minimum cannot be negative.</br>";
    }
    $food = mysql_real_escape_string(stripslashes($_POST['food']));
    $loc = mysql_real_escape_string(stripslashes($_POST['location']));
    edit_minimum_stock($food, $count, $loc);
    goto END;
  }
?>
</body>
</html>
