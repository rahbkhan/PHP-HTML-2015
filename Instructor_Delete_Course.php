<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	// common instructor header for instructor area
	instructor_header($_SESSION['valid_instructor']);
	
	if(isset($_POST['cid'])) {
		
		// Attempt MySQL server connection. Assuming you are running MySQL
		// server with default setting (user 'root' with 'rambo' password)
		$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// retrieve iid from session variable
		$iid = $_SESSION['iid'];
		
		// escape user inputs for security
		$cid = mysqli_real_escape_string($link, $_POST['cid']);
		
		// query to get associated course information
		$sql_a = "SELECT * FROM courses
				  WHERE cid = $cid
			     ";
		
		$result_a = mysqli_query($link, $sql_a);
		
		if(mysqli_num_rows($result_a) == 0) {
			echo 'That course does not currently exist.';
		}
		else {
			$row_a = mysqli_fetch_assoc($result_a);
			
			// determine if iid is associated with that course
			if($iid == $row_a["iid"]) {
				// query to delete course from courses
				$sql_b = "DELETE FROM courses
						  WHERE cid = $cid
						 ";
				
				// run query
				$result_b = mysqli_query($link, $sql_b);
				
				// query to delete course from enrollments
				$sql_c = "DELETE FROM enrollments
						  WHERE cid = $cid
						 ";
				
				// run query
				$result_c = mysqli_query($link, $sql_c);
				
				if($result_b && $result_c) {
					echo 'The course with the ID of ' . $cid . ' has been deleted.<br /><br />';
				}
				else {
					echo 'deletion error';
				}
			}
			else {
				echo 'You are not authorized to delete this course. You are not
					  the instructor associated with it.';
			}
		}
		
	}
	else {
		
		echo "
			<h2>Delete Course</h2>
			<br />
			
			<div class = 'container'>
				<form action = 'Instructor_Delete_Course.php' method = 'post'>
					<label>Course ID</label>
					<input type = 'text' name = 'cid'>
					<input type = 'submit' value = 'Delete Course'>
					<input type = 'reset' value = 'Reset Form'>
				</form>
			</div>
		";
	}
	
	// common instructor closer for instructor area
	instructor_closer();
}
else {
	
	instructor_not_allowed();
}
?>