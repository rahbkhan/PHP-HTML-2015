<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_student'])) {
	
	// common instructor header for instructor area
	student_header($_SESSION['valid_student']);
	
	// attempt MySQL connection
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	// retrieve iid from session variable
	$sid = $_SESSION['sid'];
	
	// current account information stored in result_a
	$result_a = current_information($link, $sid);
	
	echo "
		<h2>Current Account Information</h2>
		<br />
	";
	
	// show current information to instructor
	if(mysqli_num_rows($result_a) > 0) {
		$row_a = mysqli_fetch_assoc($result_a);
		echo "<table border = '1'>";
		echo "<tr>";
		echo "<td>Student ID: " . $row_a["sid"] . "</td>";
		echo "<td>Password: " . $row_a["password"] . "</td>";
		echo "<td>First Name: " . $row_a["f_name"] . "</td>";
		echo "<td>Last Name: " . $row_a["l_name"] . "</td>";
		echo "<td>Email: " . $row_a["email"] . "</td>";
		echo "<td>Major: " . $row_a["major"] . "</td>";
		echo "</tr>";
		echo "</table>";
		
		if(isset($_POST["password"]) || isset($_POST["f_name"]) ||
		   isset($_POST["l_name"]) || isset($_POST["email"]) ||
		   isset($_POST["major"])) {
			
			// initialize blank variables
			$modifications = array("", "", "", "", "", "");
			
			// array of html field names
			$html_field_array = array("password", "f_name", "l_name", "email", "major");
			
			// fill non-empty fields
			for($i = 0; $i < 5; ++$i) {
				if(isset($html_field_array[$i])) {
					$modifications[$i] =
						mysqli_real_escape_string(
							$link, $_POST[$html_field_array[$i]]
						);
						
					if($modifications[$i] == "") {
						$modifications[$i] =
							$row_a[$html_field_array[$i]];
					}
				}
			}
			
			// sql_b to insert new values into table
			$sql_b = "UPDATE students
					  SET password = '$modifications[0]',
					  f_name = '$modifications[1]',
					  l_name = '$modifications[2]',
					  email = '$modifications[3]',
					  major = '$modifications[4]'
					  WHERE sid = $sid
					 ";
			
			$result_b = mysqli_query($link, $sql_b);
			
			if(!$result_b) {
				die("
					<br />
					<h2>update error</h2>
					<br />
				");
			}
			else {
				echo "
					<br />
					<h2>Your account information has been updated.<h2>
				";
			}
		}
		else {
			
			echo "
				<br />
				<h2>Update Account Information</h2>
				<br />
				
				<div>
					<form action = 'Student_Modify_Account.php' method = 'post'>
						<label>Password</label>
						<input type = 'text' name = 'password'>
						<label>First Name</label>
						<input type = 'text' name = 'f_name'>
						<label>Last Name</label>
						<input type = 'text' name = 'l_name'>
						<label>Email</label>
						<input type = 'text' name = 'email'>
						<select name = 'major'>
							<option value = 'CS'>CS</option>
							<option value = 'IT'>IT</option>
							<option value = 'MATH'>MATH</option>
							<option value = 'PHYS'>PHYS</option>
						</select>
						<input type = 'submit' value = 'Update Information'>
						<input type = 'reset' value = 'Reset Form'>
					</form>
				</div>
			";
		}
	}
	else {
		echo "ERROR";
	}
	
	// close connection
	mysqli_close($link);
	
	// common instructor closer for instructor area
	student_closer();
}
else {
	
	student_not_allowed();
}
?>