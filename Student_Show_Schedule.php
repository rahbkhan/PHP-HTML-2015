<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_student'])) {

	// common student header for student area
	student_header($_SESSION['valid_student']);

	// retrieve sid from session variable
	$sid = $_SESSION['sid'];
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	echo "
		<h2>Your Courses</h2>
		<br />
	";
	
	// query to get course information
	$sql = "SELECT * FROM courses
			WHERE cid IN (
				SELECT cid FROM enrollments
				WHERE sid = $sid
			)
		   ";
	
	$result = mysqli_query($link, $sql);
	
	// print courses
	if($result) {
		echo '<table border = "1">';
		while($row = mysqli_fetch_assoc($result)) {
			echo '<tr>';
			echo '<td>Course ID: ' . $row["cid"] . '</td>';
			echo '<td>Course Name: ' . $row["c_name"] . '</td>';
			echo '<td>Time: ';
			echo time_convert($row["start_time"]);
			echo ' to ';
			echo time_convert($row["end_time"]) . '</td>';
			echo '<td>Days: ' . $row["c_day"] . '</td>';
			echo '<td>Location: ' . $row["location"] . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	else {
		echo '0 results';
	}
	 
	// close connection
	mysqli_close($link);

	// common student closer for student area
	student_closer();
}
else {
	
	student_not_allowed();
}
?>