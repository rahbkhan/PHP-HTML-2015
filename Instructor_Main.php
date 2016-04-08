<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// test for iid with password
if(isset($_POST['iid']) && isset($_POST['password'])) {
	
	// attempt MySQL server connection
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	 
	// escape user inputs for security
	$iid = mysqli_real_escape_string($link, $_POST['iid']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	 
	// attempt insert query execution
	$sql = "SELECT * FROM instructors
		   ";
	
	// run query
	$result = mysqli_query($link, $sql);
	
	$allowed = 0;
	
	while($row = mysqli_fetch_assoc($result)) {
		if($iid == $row["iid"] && $password == $row["password"]) {
			$allowed = 1;
			$f_name = $row["f_name"];
			$l_name = $row["l_name"];
			break;
		}
	}
	
	// empty result
	if($allowed == 1) {
		
		// if user is valid, create session variables
		$_SESSION['valid_instructor'] = $f_name . ' ' . $l_name;
										
		$_SESSION['iid'] = $iid;
	}
	 
	// close connection
	mysqli_close($link);
}

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	// common instructor header for instructor area
	instructor_header($_SESSION['valid_instructor']);
	
	echo "
		<h2>Welcome!</h2>
		<br />
	";
	
	// common instructor closer for instructor area
	instructor_closer();
}
else {
	echo "
		<!doctype html>
		<html>
		
		<head>
		<title>WU Instructor Area</title>
		<link href = 'styles/main.css'
			  rel = 'stylesheet'
			  type = 'text/css'
		/>
		</head>
		
		<body>
			<h1>Wimplestiltsen University</h1>
			
			<ul>
				<li><a href = 'Index.html'>Home</a></li>
				<li><a href = 'Student_Main.php'>Student Area</a></li>
				<li><a href = 'Instructor_Main.php'>Instructor Area</a></li>
				<li><a href = 'Show_Courses.php'>View Courses Offerred</a></li>
			</ul>
			
			<br /><br />
	";
	
	if(isset($iid)) {
		echo "
			<h2>Invalid Instructor Login</h2>
			<br />
		";
	}
	else {
		echo "
			<h2>Instructor Login</h2>
			<br />
		";
		
	}
	
	echo "
			<div>
				<form action = 'Instructor_Main.php' method = 'post'>
					<label>Instructor ID</label>
					<input type = 'text' name = 'iid'>
					<label>Password</label>
					<input type = 'password' name = 'password'>
					<input type = 'submit' value = 'Login'>
					<input type = 'reset' value = 'Reset Form'>
				</form>
			</div>
			
		<br /><br />
		
		<ul>
			<li><a href = 'Forgotten_ID_Password.php'>Forget ID or Password?</a></li>
		</ul>
		</body>
		
		</html>
	";
}
?>