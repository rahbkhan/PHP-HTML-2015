<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_student'])) {
	
	// common student header for student area
	student_header($_SESSION['valid_student']);
	
	if(isset($_POST['cid'])) {
		
		// retrieve sid from session variable
		$sid = $_SESSION['sid'];
		
		// attempt MySQL server connection
		$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// escape user inputs for security
		$cid = mysqli_real_escape_string($link, $_POST['cid']);

		// query_a to find iid for associated cid
		$sql_a = "SELECT * FROM courses
				  WHERE cid = $cid
		         ";
		
		$result_a = mysqli_query($link, $sql_a);
		
		if($result_a) {
			
			$row_a = mysqli_fetch_assoc($result_a);
			
			// store iid from result_a
			$iid = $row_a["iid"];
			
			// query_b to see if student is in that course
			$sql_b = "SELECT sid FROM enrollments
					  WHERE cid = $cid
			         ";
			         
			$result_b = mysqli_query($link, $sql_b);        
			
			$currently_enrolled = 0;
			
			while($row_b = mysqli_fetch_assoc($result_b)) {
				if($sid == $row_b["sid"]) {
					$currently_enrolled += 1;
				}
			}
			
			if($currently_enrolled > 0) {
				echo 'You are currently enrolled in that course.';
			}
			else {
				// variable to store schedule conflict
				$schedule_conflict = 0;
				
				// query_c for courses student is in
				$sql_c = "SELECT * FROM courses
					      WHERE cid IN (
						     SELECT cid FROM enrollments
							 WHERE sid = $sid
					      )
					     ";
				
				$result_c = mysqli_query($link, $sql_c);
				
				while($row_c = mysqli_fetch_assoc($result_c)) {
					
					// if so, perform time conflict test
					if(day_conflict($row_a["semester"], $row_c["semester"],
									$row_a["c_day"], $row_c["c_day"])) {

						if(time_conflict($row_a["start_time"],
										 $row_a["end_time"],
										 $row_c["start_time"],
										 $row_c["end_time"])) {
							
							$schedule_conflict = 1;
						}
					}
				}
				
				if($schedule_conflict == 0) {
					// query_d for inserting student into enrollments
					$sql_d = "INSERT INTO enrollments VALUES (
								  $sid, $cid, $iid
							  )
							 ";
						 
					$result_d = mysqli_query($link, $sql_d);
					
					if($result_d) {
						echo 'You are now enrolled in that course.';
					}
					else {
						echo 'enrollment error';
					}
				}
				else {
					echo 'A schedule conflict was encountered with that course.';
				}
			}
		}
		else {
			echo 'That course does not exist.';
		}
		
		// close connection
		mysqli_close($link);
	}
	else {
		
		echo "
				<h2>Enroll in a Course</h2>
				<br />
				
				<div>
					<form action = 'Student_Add_Course.php' method = 'post'>
						<label>Course ID</label>
						<input type = 'text' name = 'cid'>
						<input type = 'submit' value = 'Enroll'>
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


echo "<br />"."Courses that you can choose from". "<br />";


$semesterSelect= @$_POST['semester'];
echo $semesterSelect;
if(!isset($semesterSelect))
{
	$con=mysqli_connect("localhost","root","rambo", "team_project");
        if(!$con)
                {die("could not connect " . mysql_error());}
		$sql="SELECT * FROM courses";
        $result= mysqli_query($con,$sql);
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
	
}
else if($semesterSelect == 'FALL')
{
//echo "hi";

$con=mysqli_connect("localhost","root","rambo", "team_project");
        if(!$con)
                {die("could not connect " . mysql_error());}
		$sql="SELECT * FROM courses WHERE semester='FALL' ";
        $result= mysqli_query($con,$sql);
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
}
else if($semesterSelect == 'SUMMER')
{
//echo "hi";

$con=mysqli_connect("localhost","root","rambo", "team_project");
        if(!$con)
                {die("could not connect " . mysql_error());}
		$sql="SELECT * FROM courses WHERE semester='SUMMER' ";
        $result= mysqli_query($con,$sql);
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
}
else if($semesterSelect == 'SPRING')
{
//echo "hi";

$con=mysqli_connect("localhost","root","rambo", "team_project");
        if(!$con)
                {die("could not connect " . mysql_error());}
		$sql="SELECT * FROM courses WHERE semester='SPRING' ";
        $result= mysqli_query($con,$sql);
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
}
else
{
	$con=mysqli_connect("localhost","root","rambo", "team_project");
        if(!$con)
                {die("could not connect " . mysql_error());}
		$sql="SELECT * FROM courses";
        $result= mysqli_query($con,$sql);
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
}
?>


<br />Semester:
<form action = 'Student_Add_Course.php' method = 'post'>
<select name= 'semester'>
<option value= 'ALL'>ALL</option>
<option value='SUMMER'>Summer</option>
<option value='FALL'>Fall</option>
<option value='SPRING'>Spring</option>
</select>
<input type = 'submit' value = 'Search'>

</form>
