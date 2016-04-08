<?php
require_once('database_access.php');
require_once('common_functions.php');

// in regard to session variables
session_start();

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	// common instructor header for instructor area
	instructor_header($_SESSION['valid_instructor']);
	
	if(isset($_POST['subject']) && isset($_POST['course_number_1']) &&
	   isset($_POST['course_number_2']) && isset($_POST['course_number_3']) &&
	   isset($_POST['course_number_4']) && isset($_POST['hour_time_start']) &&
	   isset($_POST['quarter_time_start']) && isset($_POST['am_pm_start']) &&
	   isset($_POST['hour_time_end']) && isset($_POST['quarter_time_end']) &&
	   isset($_POST['am_pm_end']) && isset($_POST['c_day']) &&
	   isset($_POST['building']) && isset($_POST['room_number_1']) &&
	   isset($_POST['room_number_2']) && isset($_POST['room_number_3']) &&
	   isset($_POST['semester'])) {
	
		// retrieve iid from session variable
		$iid = $_SESSION['iid'];
		
		// attempt MySQL server connection
		$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// escape user inputs for security
		$subject = mysqli_real_escape_string($link, $_POST['subject']);
		$course_number_1 = mysqli_real_escape_string($link, $_POST['course_number_1']);
		$course_number_2 = mysqli_real_escape_string($link, $_POST['course_number_2']);
		$course_number_3 = mysqli_real_escape_string($link, $_POST['course_number_3']);
		$course_number_4 = mysqli_real_escape_string($link, $_POST['course_number_4']);
		$hour_time_start = mysqli_real_escape_string($link, $_POST['hour_time_start']);
		$quarter_time_start = mysqli_real_escape_string($link, $_POST['quarter_time_start']);
		$am_pm_start = mysqli_real_escape_string($link, $_POST['am_pm_start']);
		$hour_time_end = mysqli_real_escape_string($link, $_POST['hour_time_end']);
		$quarter_time_end = mysqli_real_escape_string($link, $_POST['quarter_time_end']);
		$am_pm_end = mysqli_real_escape_string($link, $_POST['am_pm_end']);
		$c_day = mysqli_real_escape_string($link, $_POST['c_day']);
		$building = mysqli_real_escape_string($link, $_POST['building']);
		$room_number_1 = mysqli_real_escape_string($link, $_POST['room_number_1']);
		$room_number_2 = mysqli_real_escape_string($link, $_POST['room_number_2']);
		$room_number_3 = mysqli_real_escape_string($link, $_POST['room_number_3']);
		$semester = mysqli_real_escape_string($link, $_POST['semester']);
		
		// get c_name, time_start, time_end and location with concatenation
		$c_name = $subject . $course_number_1 . $course_number_2 . $course_number_3 . $course_number_4;
		$start_time = $hour_time_start . $quarter_time_start;
		$end_time = $hour_time_end . $quarter_time_end;
		$location = $building . $room_number_1 . $room_number_2 . $room_number_3;
		
		// military time conversion
		if($_POST['am_pm_start'] == 'pm') {$start_time += 1200;}
		if($_POST['am_pm_end'] == 'pm') {$end_time += 1200;}
		
		// initialize cid
		$cid = 200000;

		// first query to get current cids
		$sql_a = "SELECT cid FROM courses";
		
		$result_a = mysqli_query($link, $sql_a);
		
		// put existing cids in this
		$used_cid_array = array();
		
		while($row_a = mysqli_fetch_assoc($result_a)) {
			$used_cid_array[] = $row_a["cid"];
		}
		
		// generate new cid for new course
		$max = count($used_cid_array);
		for($i = 0; $i < $max; ++$i) {
			if($cid == $used_cid_array[$i]) {
				$cid++;
			}
			else {
				break;
			}
		}
		
		if($cid > 299999) {
			echo "There are currently too many courses to add another.";
		}
		else {
			if(strlen($c_name) > 2 && strlen($c_name) < 12 &&
			   $start_time < $end_time && $end_time - $start_time < 375 &&
			   $start_time >= 0 && $start_time <= 2400 &&
			   $end_time >= 0 && $end_time <= 2400 &&
			   strlen($c_day) > 0 && strlen($c_day) < 8 &&
			   strlen($semester) > 2 && strlen($semester) < 10 &&
			   strlen($location) > 2 && strlen($c_day) < 10) {
				
				// initialize room_conflict variable
				$room_conflict = 0;
				
				// second query to get current cid information
				$sql_b = "SELECT * FROM courses";
		
				$result_b = mysqli_query($link, $sql_b);
				
				// determine if there is a room conflict during that time
				while($row_b = mysqli_fetch_assoc($result_b)) {
					
					// compare rooms
					if($location == $row_b["location"]) {
						
						// if so, perform time conflict test
						if(day_conflict($semester, $row_b["semester"],
										$c_day, $row_b["c_day"])) {

							if(time_conflict($start_time,
											 $end_time,
											 $row_b["start_time"],
											 $row_b["end_time"])) {
								
								$room_conflict = 1;
							}
						}
					}
				}
				
				if($room_conflict > 0) {
					echo 'There is a room conflict with your new course and another.';
				}
				else {
					// initialize schedule_conflict variable
					$schedule_conflict = 0;
				
					// second query to get courses instructor is teaching
					$sql_c = "SELECT * FROM courses
							  WHERE iid = $iid
							 ";
							 
					$result_c = mysqli_query($link, $sql_c);
					
					// determine if instructor is teaching another course at that time
					while($row_c = mysqli_fetch_assoc($result_c)) {

						if(day_conflict($semester, $row_c["semester"],
										$c_day, $row_c["c_day"])) {

							if(time_conflict($start_time,
											 $end_time,
											 $row_c["start_time"],
											 $row_c["end_time"])) {
								
								$schedule_conflict = 1;
							}
						}
					}
					
					if($schedule_conflict > 0) {
						echo 'There is a schedule conflict with your new course and another.';
					}
					else {
						// third query to create course
						$sql_d = "INSERT INTO courses VALUES (
									  $cid, $iid, '$c_name',
									  $start_time, $end_time,
									  '$c_day', '$semester',
									  '$location'
								  )
								 ";
								 
						$result_d = mysqli_query($link, $sql_d);
						
						if($result_d) {
							echo $cid . ' has been created.';
						}
						else {
							echo "enrollment error";
						}
					}
				}
			}
			else {
				echo "There was a problem with your field entries.";
			}
		}
		
		// close connection
		mysqli_close($link);
		
	}
	else {
		
		echo "
			<h2>Create Course</h2>
			<br />
			
			<div>
				<form action = 'Instructor_Create_Course.php' method = 'post'>
					<label>Course Name</label>
					<select name = 'subject'>
						<option value = 'CS' selected>CS</option>
						<option value = 'IT'>IT</option>
						<option value = 'MATH'>MATH</option>
						<option value = 'PHYS'>PHYS</option>
					</select>
					<select name = 'course_number_1'>
						<option value = '1' selected>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
					</select>
					<select name = 'course_number_2'>
						<option value = '0' selected>0</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
						<option value = '8'>8</option>
						<option value = '9'>9</option>
					</select>
					<select name = 'course_number_3'>
						<option value = '0' selected>0</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
						<option value = '8'>8</option>
						<option value = '9'>9</option>
					</select>
					<select name = 'course_number_4'>
						<option value = '0' selected>0</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
						<option value = '8'>8</option>
						<option value = '9'>9</option>
					</select>
					<label>Start Time</label>
					<select name = 'hour_time_start'>
						<option value = '8' selected>8</option>
						<option value = '9'>9</option>
						<option value = '10'>10</option>
						<option value = '11'>11</option>
						<option value = '12'>12</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
					</select>
					<select name = 'quarter_time_start'>
						<option value = '00' selected>00</option>
						<option value = '15'>15</option>
						<option value = '30'>30</option>
						<option value = '45'>45</option>
					</select>
					<select name = 'am_pm_start'>
						<option value = 'am' selected>am</option>
						<option value = 'pm'>pm</option>
					</select>
					<label>End Time</label>
					<select name = 'hour_time_end'>
						<option value = '8' selected>8</option>
						<option value = '9'>9</option>
						<option value = '10'>10</option>
						<option value = '11'>11</option>
						<option value = '12'>12</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
					</select>
					<select name = 'quarter_time_end'>
						<option value = '00' selected>00</option>
						<option value = '15'>15</option>
						<option value = '30'>30</option>
						<option value = '45'>45</option>
					</select>
					<select name = 'am_pm_end'>
						<option value = 'am' selected>am</option>
						<option value = 'pm'>pm</option>
					</select>
					<br /><br />
					<label>Days</label>
					<select name = 'c_day'>
						<option value = 'MW' selected>MW</option>
						<option value = 'TR'>TR</option>
						<option value = 'MTWR'>MTWR</option>
						<option value = 'F'>F</option>
						<option value = 'S'>S</option>
					</select>
					<label>Location</label>
					<select name = 'building'>
						<option value = 'HW' selected>HW</option>
						<option value = 'BH'>BH</option>
						<option value = 'SC'>SC</option>
					</select>
					<select name = 'room_number_1'>
						<option value = '1' selected>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
						<option value = '8'>8</option>
						<option value = '9'>9</option>
					</select>
					<select name = 'room_number_2'>
						<option value = '0' selected>0</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
						<option value = '8'>8</option>
						<option value = '9'>9</option>
					</select>
					<select name = 'room_number_3'>
						<option value = '0' selected>0</option>
						<option value = '1'>1</option>
						<option value = '2'>2</option>
						<option value = '3'>3</option>
						<option value = '4'>4</option>
						<option value = '5'>5</option>
						<option value = '6'>6</option>
						<option value = '7'>7</option>
						<option value = '8'>8</option>
						<option value = '9'>9</option>
					</select>
					<label>Semester</label>
					<select name = 'semester'>
						<option value = 'FALL' selected>Fall</option>
						<option value = 'SPRING'>Spring</option>
						<option value = 'SUMMER'>Summer</option>
					</select>
					<input type = 'submit' value = 'Create Course'>
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