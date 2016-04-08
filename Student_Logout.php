<?php
	// in regard to session variables
	session_start();
	
	// make copies of session variables
	$old_student = $_SESSION['valid_student'];
	$old_sid = $_SESSION['sid'];
	
	// unset session variables
	unset($_SESSION['valid_student']);
	unset($_SESSION['sid']);
	
	// end session
	session_destroy();

	if(!empty($old_student) && !empty($old_sid)) {
		
		echo "
			<!doctype html>
			<html>
		
			<head>
			<title>Student Area</title>
			<link href = 'styles/main.css'
				  rel = 'stylesheet'
				  type = 'text/css'
			/>
			</head>

			<body>
				<h1>Have a great day " . $old_student . "!</h1>
				<ul>
					<li><a href = 'Index.html'>Return to Home Page</a></li>
				</ul>
			</body>
			
			</html>
		";
	}
	else {
		
		echo "
			<!doctype html>
			<html>
		
			<head>
			<title>Student Area</title>
			<link href = 'styles/main.css'
				  rel = 'stylesheet'
				  type = 'text/css'
			/>
			</head>

			<body>
				<h1>You were not logged in and therefore, cannot be logged out.</h1>
				<ul>
					<li><a href = 'Index.html'>Return to Home Page</a></li>
				</ul>
			</body>
			
			</html>
		";
	}
?>