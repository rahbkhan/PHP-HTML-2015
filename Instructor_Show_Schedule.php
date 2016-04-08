<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	// common instructor header for instructor area
	instructor_header($_SESSION['valid_instructor']);

	// retrieve sid from session variable
	$iid = $_SESSION['iid'];
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	echo "
		<h2>Your Courses And Rosters</h2>
		<br />
	";

	// query to get course information
	$sql_a = "SELECT * FROM courses
			  WHERE iid = $iid
			 ";
	
	$result_a = mysqli_query($link, $sql_a);
	
	// print courses
	if(mysqli_num_rows($result_a) > 0) {
		
		while($row_a = mysqli_fetch_assoc($result_a)) {
			echo '<table border = "1">';
			echo '<tr>';
			echo '<td>Course ID: ' . $row_a["cid"] . '</td>';
			echo '<td>Course Name: ' . $row_a["c_name"] . '</td>';
			echo '<td>Time: ';
			echo time_convert($row_a["start_time"]);
			echo ' to ';
			echo time_convert($row_a["end_time"]) . '</td>';
			echo '<td>Days: ' . $row_a["c_day"] . '</td>';
			echo '<td>Location: ' . $row_a["location"] . '</td>';
			echo '<td>Semester: ' . $row_a["semester"] . '</td>';
			echo '</tr>';
			echo '</table>';
			
			$cid_temp = $row_a["cid"];
			
			// query to get student information
			$sql_b = "SELECT * FROM students
					  WHERE sid IN (
						  SELECT sid FROM enrollments
						  WHERE cid = $cid_temp
					  )
					 ";
	
			$result_b = mysqli_query($link, $sql_b);
			
			if(mysqli_num_rows($result_b) > 0) {
				echo '<br /><table border = "1">';
				while($row_b = mysqli_fetch_assoc($result_b)) {
					echo '<tr>';
					echo '<td>Student ID: ' . $row_b["sid"] . '</td>';
					echo '<td>Major: ' . $row_b["major"] . '</td>';
					echo '<td>Name: ' . $row_b["f_name"] . ' ' . $row_b["l_name"] . '</td>';
					echo '<td>Email: ' . $row_b["email"] . '</td>';
					echo '</tr>';
				}
				echo '</table><br /><br />';
			}
			else {
				echo '0 students enrolled';
			}
			
			echo '<br /><br />';
		}
	}
	else {
		echo '0 courses';
	}
	 
	// close connection
	mysqli_close($link);

	// common instructor closer for instructor area
	instructor_closer();
	
}
else {
	
	instructor_not_allowed();
}
?>