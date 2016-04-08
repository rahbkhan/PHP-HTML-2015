<!doctype html>
<html>

<head>
	<title>WU Home</title>
	<!-- Comments! -->
	<link href = 'styles/main.css'
		  rel = 'stylesheet'
		  type = 'text/css'
	/>
</head>

<body>
	<h1>Wimplestiltsen University</h1>
	
	<ul>
		<li><a href = 'Index.html'>Home</a></li>
		<li><a href = 'Student_Main.php'>Student Area</a></li>
		<li><a href = 'Instructor_Main.php'>Instructor Area</a></li>
		<li><a href = 'Show_Courses.php'>View Courses Offerred</a></li>
	</ul>
	
	<br /><br />

<?php
require_once('database_access.php');
require_once('common_functions.php');
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with no password)
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	echo "
		<h2>All Courses</h2>
		<br />
	";
	
	show_all_courses($link);
	 
	// close connection
	mysqli_close($link);
?>

</body>
	
</html>