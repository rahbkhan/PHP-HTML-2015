<?php
/***************************************************************************************

	schedule functions

***************************************************************************************/

// show all courses
function show_all_courses($link) {
	
	// attempt insert query execution
	$sql = "SELECT *
			FROM courses";
	
	// run query
	$result = mysqli_query($link, $sql);
	
	// empty result
	if($result) {
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
	else {
		echo '0 results';
	}
}

// day conflict test function
function day_conflict($semester_a, $semester_b, $c_day_a, $c_day_b) {
	
	$shared_day = 0;
					
	// do any of the days overlap?
	if($semester_a == $semester_b) {
		if($c_day_a == $c_day_b) {
			$shared_day = 1;
		}
		if($c_day_a == 'MTWR' && $c_day_b == 'MW') {
			$shared_day = 1;
		}
		if($c_day_a == 'MTWR' && $c_day_b == 'TR') {
			$shared_day = 1;
		}
	}
	
	return $shared_day;
}

// time conflict test function
function time_conflict($start_time_a, $end_time_a, $start_time_b, $end_time_b) {
	// time conflict variable to be returned
	$tc = 0;
	
	// is the new course start_time before an existing start_time?
	if($start_time_a < $start_time_b) {
		// if so, the end time must also be before that existing start_time
		if($end_time_a >= $start_time_b) {
			$tc = 1;
		}
	}
	// is the new course start_time after or on an existing start_time?
	else {
		// if so, it must be after the existing end_time
		if($start_time_a <= $end_time_b) {
			$tc = 1;
		}
	}
	
	return $tc;
}

// convert floats to integers of 3 or 4 digits for class times in database
function time_convert($mil_time) {
	
	$civ_time = $mil_time;
	if($civ_time >= 1300) {
		$civ_time -= 1200;
	}
	
	$time_array = str_split($civ_time);
	$time_digits = strlen($civ_time);
	
	for($i = 0; $i < $time_digits; ++$i) {
		echo $time_array[$i];
		if($i == $time_digits - 3) {
			echo ':';
		}
	}
	
	if($mil_time < 1200) {
		echo 'am';
	}
	else {
		echo 'pm';
	}
}

/***************************************************************************************

	student area functions

***************************************************************************************/

// student header
function student_header($val_stu) {
	
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
			<h1>WU Student Area for " . $val_stu . "</h1>
			<ul>
				<li><a href = 'Student_Show_All_Courses.php'>View All Courses</a></li>
				<li><a href = 'Student_Show_Schedule.php'>View Your Schedule</a></li>
				<li><a href = 'Student_Add_Course.php'>Enroll in a Course</a></li>
				<li><a href = 'Student_Drop_Course.php'>Drop a Course</a></li>
				<li><a href = 'Student_Modify_Account.php'>Account Information</a></li>
				<li><a href = 'Student_Logout.php'>Log Out</a></li>
			</ul>
		
		<br /><br />
	";
}

// student closer
function student_closer() {
	
	echo "
		</body>
		
		</html>
	";
}

// student not allowed
function student_not_allowed() {
	echo "
		<!doctype html>
		<html>
		
		<head>
		<title>Invalid</title>
		<link href = 'styles/main.css'
			  rel = 'stylesheet'
			  type = 'text/css'
		/>
		</head>
		
		<body>
			<h1>You must be logged in as a student to view this area!</h1>
			<ul>
				<li><a href = 'Student_Main.php'>Main Student Page</a></li>
			</ul>
		</body>
		
		</html>
	";
}


/***************************************************************************************

	instructor area functions

***************************************************************************************/

// instructor header
function instructor_header($val_ins) {
	
	echo "
		<!doctype html>
		<html>
		
		<head>
		<title>WU Instructor Area</title>
		<link href = 'styles/main.css'
			  rel = 'stylesheet'
			  type = 'text/css'
		/>
		</head>

		<body>
			<h1>WU Instructor Area for " . $val_ins . "</h1>
			<ul>
				<li><a href = 'Instructor_Show_All_Courses.php'>View All Courses</a></li>
				<li><a href = 'Instructor_Show_Schedule.php'>View Your Schedule</a></li>
				<li><a href = 'Instructor_Create_Course.php'>Create a Course</a></li>
				<li><a href = 'Instructor_Delete_Course.php'>Delete a Course</a></li>
				<li><a href = 'Instructor_Create_New_Account.php'>Create New Account</a></li>
				<li><a href = 'Instructor_Modify_Account.php'>Account Information</a></li>
				<li><a href = 'Instructor_Logout.php'>Log Out</a></li>
			</ul>
		
		<br /><br />
	";
}

// instructor closer
function instructor_closer() {
	
	echo "
		</body>
		
		</html>
	";
}

// instructor not allowed
function instructor_not_allowed() {
	echo "
		<!doctype html>
		<html>
		
		<head>
		<title>Invalid</title>
		<link href = 'styles/main.css'
			  rel = 'stylesheet'
			  type = 'text/css'
		/>
		</head>
		
		<body>
			<h1>You must be logged in as an instructor to view this area!</h1>
			<ul>
				<li><a href = 'Instructor_Main.php'>Main Instructor Page</a></li>
			</ul>
		</body>
		
		</html>
	";
}


/***************************************************************************************

	account creation function

***************************************************************************************/

// account creation header
function create_new_account($link, $f_name, $l_name, $email, $department, $type) {
	
	if($type == 'stu') {
		$id = 100000;
		$max_id = 199999;
		$user = 'student';
	}
	else {
		$id = 300000;
		$max_id = 399999;
		$user = 'instructor';
	}
	
	if($user == 'student') {
		$sql = "SELECT sid FROM students";
	}
	else {
		$sql = "SELECT iid FROM instructors";
	}
	
	$result = mysqli_query($link, $sql);
	
	$used_id_array = array();
	if($user == 'student') {
		
		while($new_row = mysqli_fetch_assoc($result)) {
			$used_id_array[] = $new_row['sid'];
		}
	}
	else {
		
		while($new_row = mysqli_fetch_assoc($result)) {
			$used_id_array[] = $new_row['iid'];
		}
	}
	
	
	$max = count($used_id_array);
	for($i = 0; $i < $max; ++$i) {
		if($id == $used_id_array[$i]) {
			$id++;
		}
		else {
			break;
		}
	}
	
	if($id > $max_id) {
		echo "
			<p>
			There are currently too many  " . $user . "s to add another " . $user . ".
			</p>
		";
	}
	else if(strlen($f_name) > 1 &&
			strlen($l_name) > 1 &&
			strlen($email) > 1) {
	
		
		if($user == 'student') {
			
			$sql = "SELECT f_name, l_name, email
					FROM students
					WHERE f_name = '$f_name'
					AND l_name = '$l_name'
					AND email = '$email'
				   ";
		}
		else {
			
			$sql = "SELECT f_name, l_name, email
					FROM instructors
					WHERE f_name = '$f_name'
					AND l_name = '$l_name'
					AND email = '$email'
				   ";
		}
				
		$result = mysqli_query($link, $sql);
	
		// if result is not empty
		if(mysqli_num_rows($result) > 0) {
			
			echo "
				<p>
				That name and email already exist for another " . $user . ".
				</p>
			";
		}
		else {
			
			// use a function to generate new password for instructor
			$password = 'pass';
			
			// third query and result
			if($user == 'student') {
				
				$sql = "INSERT INTO students VALUES (
						$id, '$password',
						'$f_name', '$l_name',
						'$email', '$department'
					)
				   ";
			}
			else {
				
				$sql = "INSERT INTO instructors VALUES (
						$id, '$password',
						'$f_name', '$l_name',
						'$email', '$department'
					)
				   ";
			}
			
			$result = mysqli_query($link, $sql);
			
			// attempt insert query
			if($result) {
				
				echo $f_name . ' ' . $l_name . ' has been added.<br />';
				echo 'New ID: ' . $id . '<br />';
				echo 'Associated Password: ' . $password . '<br />';
			}
			else {
				echo "account creation error";
			}
		}
	}
	else {
		echo "
			<p>
			Names and emails must be greater than one character.
			</p>
		";
	}
}

/***************************************************************************************

	forgotten id or password function

***************************************************************************************/

function email_id_password($link, $f_name, $l_name, $email, $user_type) {
	
	if($user_type == 'instructor') {
		$sql = "SELECT * FROM instructors
				WHERE f_name = '$f_name'
				AND l_name = '$l_name'
				AND email = '$email'
			   ";
	}
	else {
		$sql = "SELECT * FROM students
				WHERE f_name = '$f_name'
				AND l_name = '$l_name'
				AND email = '$email'
			   ";
	}
	
	// run query
	$result = mysqli_query($link, $sql);
	
	// empty result
	if(mysqli_num_rows($result) > 0) {
		
		$row = mysqli_fetch_assoc($result);
		
		$about = "User ID and Password";
		
		if($user_type == 'instructor') {
			
			$message = "Your current ID: " . $row["iid"] . "\n" .
					   "Current Password: " . $row["password"];
		}
		else {
			
			$message = "Student ID: " . $row["sid"] . "\n" .
					   "Current Password: " . $row["password"];
		}
				   
		$headers = "From: webmaster@wimplestiltsen.link \n" .
				   "Reply-To: webmaster@wimplestiltsen.link \n" .
				   "X-Mailer: PHP/" .
				   phpversion();
				   
		mail($email, $about, $message, $headers);
			 
		echo "An email has been sent to " . $email .
			 "with the associated id and password as requested";
	}
	else {
		
		echo "That name and email combination was not found in our database.";
	}
}

/***************************************************************************************

	current information function

***************************************************************************************/

function current_information($link, $id) {
	
	if($id >= 300000 && $id <= 399999) {
		$sql = "SELECT * FROM instructors
				WHERE iid = $id
			   ";
	}
	else {
		$sql = "SELECT * FROM students
				WHERE sid = $id
			   ";
	}
	
	return mysqli_query($link, $sql);
}
?>