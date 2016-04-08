<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	// common instructor header for instructor area
	instructor_header($_SESSION['valid_instructor']);
	
	if(isset($_POST['f_name']) &&
	   isset($_POST['l_name']) &&
	   isset($_POST['email']) &&
	   isset($_POST['department']) &&
	   isset($_POST['type'])) {
		   
		// attempt MySQL connection
		$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
		
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		$f_name = mysqli_real_escape_string($link, $_POST['f_name']);
		$l_name = mysqli_real_escape_string($link, $_POST['l_name']);
		$email = mysqli_real_escape_string($link, $_POST['email']);
		$department = mysqli_real_escape_string($link, $_POST['department']);
		$type = mysqli_real_escape_string($link, $_POST['type']);
		
		create_new_account($link, $f_name, $l_name, $email, $department, $type);
		
		// close connection
		mysqli_close($link);
	}
	else {
		
		echo "
			<h2>Create New Account</h2>
			<br />
			
			<div>
				<form action = 'Instructor_Create_New_Account.php' method = 'post'>
					<label>First Name</label>
					<input type = 'text' name = 'f_name'>
					<label>Last Name<label>
					<input type = 'text' name = 'l_name'>
					<label>Email Address<label>
					<input type = 'text' name = 'email'>
					<br /><br /><label>Department</label>
					<select name = 'department'>
						<option value = 'CS' selected>CS</option>
						<option value = 'IT'>IT</option>
						<option value = 'MATH'>MATH</option>
						<option value = 'PHYS'>PHYS</option>
					</select>
					<select name = 'type'>
						<option value = 'stu' selected>student</option>
						<option value = 'ins'>instructor</option>
					</select>
					<input type = 'submit' value = 'Create Account'>
					<input type = 'reset' value = 'Reset Form'>
				</form>
			</div>
			
			<br />
			<p>Note: No field can be blank.</p>
		";
	}
	
	// common instructor closer for instructor area
	instructor_closer();
}
else {
	
	instructor_not_allowed();
}
?>

	