<!doctype html>
<html>

<head>
<title>WU ID And Password</title>
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
		<li><a href = 'Create_New_Student_Account.html'>New Student Account</a></li>
		<li><a href = 'Create_New_Instructor_Account.html'>New Instructor Account</a></li>
	</ul>
	
	<br /><br />

<?php
require_once('database_access.php');
require_once('common_functions.php');

if(isset($_POST["f_name"]) &&
   isset($_POST["l_name"]) &&
   isset($_POST["email"]) &&
   isset($_POST["user_type"])) {
	
	// attempt MySQL server connection
	$link = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	// escape user inputs for security
	$f_name = mysqli_real_escape_string($link, $_POST["f_name"]);
	$l_name = mysqli_real_escape_string($link, $_POST["l_name"]);
	$email = mysqli_real_escape_string($link, $_POST["email"]);
	$user_type = mysqli_real_escape_string($link, $_POST["user_type"]);
	   
	// attempt to email id and password to student
	email_id_password($link, $f_name, $l_name, $email, $user_type);
	
	echo "
		<h2>An email with your ID and password has been sent.</h2>
		<br />
		
		<ul>
			<li><a href = 'Index.html'>Home</a></li>
		</ul>
	";
	
	// close connection
	mysqli_close($link);
}
else {
	
	echo "
		<h2>No field is allowed to be blank.</h2>
		<br />
		
		<ul>
			<li><a href = 'Index.html'>Home</a></li>
		</ul>
	";
}
?>

</body>

</html>