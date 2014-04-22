<?php
$host = 'localhost';
$username = 'test';
$password = 'test1234';
$db_name = "hospital_db";
	
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

function close_sql(){
	mysql_close();
}

function add_email($email){
	$email = mysql_real_escape_string(stripslashes($email));
	$query = mysql_query("insert into reports (email) values('$email')");
}

function add_meal($first, $last, $meal, $comment, $date){
	$user = $_SESSION['username'];
	$query = mysql_query("select * from patients where fname='$first' and lname='$last'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	$loc = $row['location']; // Data in the database is considered safe.
	if(!$loc)	return "* Patient name error!<br/>";
	$q = mysql_query("select * from $loc where meal='$meal'") or die(mysql_error());
	$row2 = mysql_fetch_array($q);
	if($row2['snack'] == 1){ # The meal added is a snack.
			mysql_query("insert into log (action, user, meal, quanity, first, last, time, comment, date) values('Add Meal', '$user','$meal','0', '$first', '$last', now(), '$comment', '$date')") or die(mysql_error()) ;
		return  "* Meal added successfully!<br/>";
	}
	$tmp = $row2['stock'];
	if($tmp - 1 < 0){
		if($loc == "A01" || $loc == "Q01"){
			$loc2 = ($loc=="A01") ? "Q01":"A01";
			$r =  mysql_query("select * from $loc2 where meal='$meal'") or die(mysql_error());
			$row3 = mysql_fetch_array($r);
			$tmp2 = $row3['stock'];
			if($tmp2 + $tmp - 1 < 0) return "* Stock is too low, cannot remove from inventory.<br/>";
			$num = $tmp2+$tmp - 1;
			mysql_query("update $loc set stock='0' where meal='$meal'") or die(mysql_error());
			mysql_query("update $loc2 set stock='$num' where meal='$meal'") or die(mysql_error());
			mysql_query("insert into log (action, user, meal, quanity, first, last, time, comment, location, date) values('Add Meal', '$user','$meal',1, '$first', '$last', now(), '$comment', '$loc', '$date')") or die(mysql_error()) ;
			return "* Meal added successfully!<br/>";
		}
		return "* Stock is too low, cannot remove from inventory.<br/>";
	}
	else{	# The stock can be removed without any problems.
		$num = $tmp - 1;
		$query = mysql_query("update $loc set stock='$num' where meal='$meal'");
		# Add the meal to the log.
		$query = mysql_query("insert into log (action, user, meal, quanity, first, last, time, comment, location, date) values('Add Meal', '$user','$meal', 1, '$first', '$last', now(), '$comment', '$loc', '$date')") or die(mysql_error()) ;
		return "* Meal added successfully!<br/>";
	}
}

function add_new_meal($name, $minimum, $snack){
	$name = mysql_real_escape_string(stripslashes($name));
	$minimum = mysql_real_escape_string(stripslashes($minimum));
	$q = mysql_query("select location from minimums union select location from minimums") or die(mysql_error());
	while($ret = mysql_fetch_array($q)){
		$query = mysql_query("insert into " . $ret['location'] . " (meal, stock, snack) values ('$name', 0, $snack)") or die(mysql_error()); # All meals are added with 0 stock, they can be edited with the stock editor.
		if(!$snack) $query = mysql_query("insert into minimums (location, meal, min) values('" . $ret['location'] . "', '$meal', '$minimum')") or die(mysql_error());
	}
	return "* Meal added successfully!<br/>";
}

function add_patient($user, $first, $last, $dob, $loc){
	$user = mysql_real_escape_string(stripslashes($user));
	$first = mysql_real_escape_string(stripslashes($first));
	$last = mysql_real_escape_string(stripslashes($last));
	$dob = mysql_real_escape_string(stripslashes($dob));
	$loc = mysql_real_escape_string(stripslashes($loc));
	$query = mysql_query("select * from patients where fname='$first' and lname='$last' limit 1");
	if(mysql_num_rows($query) == 0){
		$query = mysql_query("insert into patients (fname, lname, location, dob) values('$first', '$last', '$loc', '$dob')") or die (mysql_error());;
		$query = mysql_query("insert into log (action, user, meal, quanity, first, last, time) values('Add Patient', '$user', 0, 0, '$first', '$last', now())") or die (mysql_error()) ;
		return "* Patient added successfully!<br/>";
	}
	else{
		return "* Patient exists in the records already. Please try adding another patient.<br/>";
	}
}

function add_user($sent, $user, $password, $admin){
	# Add the user to the database. (If there is no username already.)
	# Sanatize the users input. (Just encase someone changes values.)
	$first = mysql_real_escape_string(stripslashes($first));
	$last = mysql_real_escape_string(stripslashes($last));
	$loc = mysql_real_escape_string(stripslashes($loc));
	$dob = mysql_real_escape_string(stripslashes($dob));
	$user = mysql_real_escape_string(stripslashes($user));
	
	$query = mysql_query("select username from members where username='$user' limit 1");
	if(mysql_num_rows($query) == 0){
		$query = mysql_query("insert into members (username, password, admin) values('$user', '$password', '$admin')") or die (mysql_error());
		$query = mysql_query("insert into log (action, user, meal, quanity, first, last, time) values('Add User', '$sent','$admin',0, '$user', 0, now())") or die(mysql_error()) ;
		return "* Member added successfully!<br/>";
	}
	else{
		echo "* Username exists, please try another username.<br/>";
	}
}

function check_admin($username){
	$query = mysql_query("select admin from members where username='$username' limit 1");
	$row = mysql_fetch_array($query);
	return $row['admin'];
}

function del_meal($name){
	$name = mysql_real_escape_string(stripslashes($name));
	$q = mysql_query("select location from minimums union select location from minimums") or die(mysql_error());
	while($ret = mysql_fetch_array($q)){
		$query = mysql_query("delete from " . $ret['location']  ." where meal='$name'") or die(mysql_error());
	}
	$query = mysql_query("delete from minimums where meal='$name'") or die(mysql_error());
	return "* Meal delete Successfully!<br/>";
}

function del_id($id){
	$query = mysql_query("delete from log where id='$id'") or die(mysql_error());
	return "* Delete Successful!<br/>";
}

function del_patient($first, $last){
	$query = mysql_query("delete from patients where fname='$first' and lname='$last' limit 1") or die(mysql_error());
	return "* Patient deleted from the database.<br/>";
}

function del_user($user){
	$user = mysql_real_escape_string(stripslashes($user));
	$query = mysql_query("delete from members where username='$user' limit 1") or die(mysql_error());
	return "* User deleted from the database!<br/>";
}

function edit_stock($meal, $count, $location){
	$query = mysql_query("update $location set stock='$count' where meal='$meal'") or die(mysql_error());
	$username = mysql_real_escape_string(stripslashes($_SESSION['username']));
	$query = mysql_query("insert into log (action, user, meal, quanity, time, location) values('Edit Stock', '$username','$meal','$count', now(), '$location')") or die(mysql_error());
	echo "* Stock successfully modified.</br>";	
}

function edit_minimum_stock($meal, $count, $location){
	$query = mysql_query("update minimums set min='$count' where meal='$meal' and location='$location'") or die(mysql_error());
	$username = mysql_real_escape_string(stripslashes($_SESSION['username']));
	$query = mysql_query("insert into log(action, user, meal, quanity, time, location) values('Edit Minimum', '$username', '$meal', '$count',  now(), '$location')") or die(mysql_error());
	echo "* Stock minimum successfully modified.</br>";	
}

function view_all_logs(){
	echo "<center><h3>Logs</h3><table border='1'>";
	echo "<tr><td>Action</td><td>Performed By</td><td>First name</td><td>Last name</td><td>Meal/Admin</td><td>Quanity</td><td>Time</td><td>Meal date</td><td>Comments</td><td>ID</td></tr>";
	$query = mysql_query("select * from log order by id desc") or die(mysql_error());
	while(($row=mysql_fetch_array($query))){
		echo "<tr><td>".$row['action']."</td><td>".$row['user']."</td><td>".$row['first']."</td><td>".$row['last']."</td><td>".$row['meal']."</td><td>".$row['quanity']."</td><td>".$row['time']."</td><td>" .$row['date']."</td><td>".$row['comment']."</td><td>".$row['id']."</td></tr>";
	}
	echo "</table></center>";
}

function view_history($first, $last, $option){ # See patients information from the week (the last ID)
	echo "<center><h3>$first $last record.</h3><table border='1'>";
	if($option == 1){ # View meal plans.
		$query = mysql_query("select * from log where first='$first' and last='$last'");
		echo "<tr><td>Action</td><td>User</td><td>Meal</td><td>Quanity</td><td>Comments</td><td>Meal Date</td></tr>";
		while($row=mysql_fetch_array($query)){ # Make the patient overview nice
			echo "<tr><td>".$row['action']."</td><td>".$row['user']."</td><td>".$row['meal']."</td><td>".$row['quanity']."</td><td>".$row['comment']."</td><td>".$row['date']."</td></tr>";
		}
	}
	if($option == 2){ # View patient information.
		$query = mysql_query("select * from patients where fname='$first' and lname='$last' limit 1");
		$row=mysql_fetch_array($query);
		echo "<tr><td>Location</td><td>".$row['location']."</td></tr><tr><td>Date of birth</td><td>".$row['dob']."</td></tr>";
	}
	echo "</table></center><br/>";
}

function view_patients(){
	echo "<center><h3>Patient list</h3><table border='1'>";
	echo "<tr><td>First name</td><td>Last name</td><td>DOB</td><td>Location</td></tr>";
	$query = mysql_query("select * from patients");
	while($row=mysql_fetch_array($query)){
		echo "<tr><td>".$row['fname']."</td><td>".$row['lname']."</td><td>".$row['dob']."</td><td>".$row['location']."</td></tr>";
	}
	echo "</table></center>";
}

function view_stock(){
	echo "<center><h3>Current Stock</h3><table><tr><td><b>AMS</b><br/><table border='1'><tr><td>Meal</td><td>A01 Stock</td><td>Q01 Stock</td><td>Combined</td></tr>";
	$query = mysql_query("select * from A01 order by meal asc") or die(mysql_error());
	$q = mysql_query("select * from Q01 order by meal asc") or die(mysql_error());
	while($row = mysql_fetch_array($query)){
		$total=0;
		echo "<tr><td>".$row['meal']."</td><td>".$total=$row['stock']."</td>";
		$r = mysql_fetch_array($q); // As long as both A01 and Q01 have the same stock, this is fine.
		$total+=$r['stock'];
		echo "<td>".$r['stock']."</td><td>".$total."</td></tr>";
	}
	echo "</table></td><td><b>Lunetten</b><br/><table border='1'><tr><td>Meal</td><td>Stock</td></tr>";
	$query = mysql_query("select * from Lunetten order by meal asc") or die(mysql_error());
	while($row = mysql_fetch_array($query)){
		echo "<tr><td>".$row['meal']."</td><td>".$row['stock']."</td></tr>";
	}
	echo "</table></td></tr></table></center>";
}

function view_stock_min(){
	echo "<center><h3>Current Stock Minimums</h3><table><tr><td><b>AMS</b><br/><table border='1'><tr><td>Meal</td><td>A01 Stock Minimum</td><td>Q01 Stock Minimum</td></tr>";
	$query = mysql_query("select * from minimums where location='A01' order by meal asc") or die(mysql_error());
	$q = mysql_query("select * from minimums where location='Q01' order by meal asc") or die(mysql_error());
	while($row = mysql_fetch_array($query)){
		$total=0;
		echo "<tr><td>".$row['meal']."</td><td>".$row['min']."</td>";
		$r = mysql_fetch_array($q); // As long as both A01 and Q01 have the same stock, this is fine.
		echo "<td>".$r['min']."</td></tr>";
	}
	echo "</table></td><td><b>Lunetten</b><br/><table border='1'><tr><td>Meal</td><td>Minimum</td></tr>";
	$query = mysql_query("select * from minimums where location='Lunetten' order by meal asc") or die(mysql_error());
	while($row = mysql_fetch_array($query)){
		echo "<tr><td>".$row['meal']."</td><td>".$row['min']."</td></tr>";
	}
	echo "</table></td></tr></table></center>";
}

function view_users(){
	echo "<center><h3>User list</h3><table border='1'>";
	echo "<tr><td>Username</td><td>Admin</td></tr>";
	$query = mysql_query("select username,admin from members");
	while($row=mysql_fetch_array($query)){
		echo "<tr><td>".$row['username']."</td><td>". ($row['admin']?"Yes":"No") ."</tr>";
	}
	echo "</table></center>";
}

function print_calander($name, $startyear=1990, $endyear=2050){
	$months=array('','January','February','March','April','May','June','July','August', 'September','October','November','December');
	$html="<select name=\"".$name."month\">";
 	for($i=1;$i<=12;$i++){
  	$html.="<option value='$i'>$months[$i]</option>";
 	}
	$html.="</select> ";
	$html.="<select name=\"".$name."day\">";
	for($i=1;$i<=31;$i++){
  	$html.="<option value='$i'>$i</option>";
 	}
 	$html.="</select> ";
	$html.="<select name=\"".$name."year\">";
 	for($i=$startyear;$i<=$endyear;$i++){
  	$html.="<option value='$i'>$i</option>";
 	}
 	$html.="</select> ";
 	return $html;
}

function location_log($location){
	$t = mysql_query("select last_id from weekly") or die(mysql_error());
	$ret = mysql_fetch_array($t);
	$id = $ret['last_id'];
	$tbl = "<table border='1'><tr><td>Patient</td><td>Meals</td></tr>";
	$q = mysql_query("select * from patients where location='$location'") or die(mysql_error());
	while($req = mysql_fetch_array($q)){
		$last = $req['lname'];
		$first = $req['fname'];
		$tbl .= "<tr><td>$first $last</td><td><table border='1'>";
		$q2 = mysql_query("select meal from $location") or die(mysql_error());
		while($r2 = mysql_fetch_array($q2)){
			$meal = $r2['meal'];
			$query = mysql_query("select sum(quanity) as total from log where first='$first' and last='$last' and meal='$meal' and id>=$id");
			$ret = mysql_fetch_array($query);
			if($ret['total'] > 0)$tbl .= "<tr><td>$meal</td><td>" . $ret['total'] . "</td></tr>";
		}
		$tbl .= "</table></td></tr>";
	}
	$tbl .= "</table><br/>";
	return $tbl;
}

function meal_options($location){
	$option_form = "";
	$query = mysql_query("select meal from $location") or die(mysql_error()); # Since all locations share the same meals and snacks, this is fine.
	while($ret=mysql_fetch_array($query)){
		$option_form .= "<option value=\"" . $ret['meal'] . "\">" . $ret['meal'] . "</option>";
	}
	return $option_form;
}


?>
