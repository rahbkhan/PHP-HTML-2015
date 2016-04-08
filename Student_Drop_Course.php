<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();
$compare=$_SESSION['sid'];
// check user status
if(isset($_SESSION['valid_student'])) {
	
	// common student header for student area
	student_header($_SESSION['valid_student']);
	
	if(isset($_POST['cid'])) {
		
		// retrieve sid from session variable
		$sid = $_SESSION['sid'];
		
		// Attempt MySQL server connection. Assuming you are running MySQL
		// server with default setting (user 'root' with 'rambo' password)
		$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// escape user inputs for security
		$cid = mysqli_real_escape_string($link, $_POST['cid']);

		// first query to find the cid and associated iid
		$sql = "DELETE FROM enrollments
			    WHERE sid = $sid
				AND cid = $cid
			   ";
		
		$result = mysqli_query($link, $sql);
		
		if($result) {
			echo 'You have been removed from that course.';
			
			student_links($_SESSION['valid_student']);
		}
		else {
			echo 'You were not enrolled in that course.<br />';
			
			student_links($_SESSION['valid_student']);
		}
		
		// close connection
		mysqli_close($link);
	}
	else {
		
		echo "
				<h2>Drop A Course</h2>
				<br />
				
				<div>
					<form action = 'Student_Drop_Course.php' method = 'post'>
						<label>Course ID</label>
						<input type = 'text' name = 'cid'>
						<input type = 'submit' value = 'Drop'>
						<input type = 'reset' value = 'Reset Form'>
					</form>
				</div>
		";
	
	}
	
	// common student closer for student area
	student_closer();
}
else {
	
	student_not_allowed();
}



Echo "<br />"."Classes that you are enrolled in currently";
$con=mysqli_connect("localhost","root","rambo", "team_project");
        if(!$con)
                {die("could not connect " . mysql_error());}
		
        //mysql_select_db('team_project',$con);
		
		
       // $sql="INSERT INTO person(FirstName,LastName,Age)VALUES('$_GET[f_name]','$_GET[l_name]','$_GET[age]')";
       // mysql_query($sql, $con);
		$sql="SELECT * FROM students s JOIN enrollments e ON s.sid=e.sid JOIN courses c on c.cid= e.cid WHERE e.sid= $compare";
        $result= mysqli_query($con,$sql);
		//mysqli_query("SELECT * FROM courses");

echo '<table border = "1">';
		while($row = mysqli_fetch_assoc($result)) {
			echo '<tr>';
			echo '<td>Course ID: ' . $row["cid"] . '</td>';
			echo '<td>Course Name: ' . $row["c_name"]. '</td>';
			echo '<td>Time: ';
			echo time_convert($row["start_time"]);
			echo ' to ';
			echo time_convert($row["end_time"]) . '</td>';
			echo '<td>Days: ' . $row["c_day"] . '</td>';
			echo '<td>Location: ' . $row["location"] . '</td>';
			echo '<td>Semester: ' . $row["semester"] . '</td>';
			echo '</tr>';
		}
		echo '</table>';
?>