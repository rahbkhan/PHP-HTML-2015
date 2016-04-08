<?php
require_once('database_access.php');

// in regard to session variables
session_start();

// test for sid with password
if(isset($_POST['sid']) && isset($_POST['password'])) {
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	// Escape user inputs for security
	$sid = mysqli_real_escape_string($link, $_POST['sid']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	
	// attempt insert query execution
	$sql = "SELECT * FROM students
		   ";
	
	// run query
	$result = mysqli_query($link, $sql);
	
	$allowed = 0;
	
	while($row = mysqli_fetch_assoc($result)) {
		if($sid == $row["sid"] && $password == $row["password"]) {
			$allowed = 1;
			$f_name = $row["f_name"];
			$l_name = $row["l_name"];
			break;
		}
	}
	
	// empty result
	if($allowed == 1) {
		
		// if user is valid, create session variables
		$_SESSION['valid_student'] = $f_name . ' ' . $l_name;
										
		$_SESSION['sid'] = $sid;
	}
	 
	// close connection
	mysqli_close($link);
}

// check user status
if(isset($_SESSION['valid_student'])) {
	
	// common student header for student area
	student_header($_SESSION['valid_student']);
	
	echo "
		<h2>Welcome!</h2>
		<br />
	";
	
	// common student closer for student area
	student_closer();
}
else {
	echo "
		<!doctype html>
		<html>
	
		<head>
		<title>WU Student Area</title>
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
	
	if(isset($sid)) {
		echo "
			<h2>Invalid Student Login</h2>
			<br />
		";
	}
	else {
		echo "
			<h2>Student Login</h2>
			<br />
		";
		
	}
	
	echo "
			
			<div>
				<form action = 'Student_Main.php' method = 'post'>
					<label>Student ID</label>
					<input type = 'text' name = 'sid'>
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