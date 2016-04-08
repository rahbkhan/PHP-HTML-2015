<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_student'])) {
	
	// common student header for student area
	student_header($_SESSION['valid_student']);
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	echo "
		<h2>All Courses</h2>
		<br />
	";
	
	//show_all_courses($link);
	 
	// close connection
	mysqli_close($link);
	
	// common student closer for student area
	student_closer();
}
else {
	
	student_not_allowed();
}




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
<form action = 'Student_Show_All_courses.php' method = 'post'>
<select name= 'semester'>
<option value= 'ALL'>ALL</option>
<option value='SUMMER'>Summer</option>
<option value='FALL'>Fall</option>
<option value='SPRING'>Spring</option>
</select>
<input type = 'submit' value = 'Search'>

</form>
