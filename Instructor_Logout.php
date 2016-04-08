<?php
	// in regard to session variables
	session_start();
	
	// make copies of session variables
	$old_instructor = $_SESSION['valid_instructor'];
	$old_iid = $_SESSION['iid'];
	
	// unset session variables
	unset($_SESSION['valid_instructor']);
	unset($_SESSION['iid']);
	
	// end session
	session_destroy();

	if(!empty($old_instructor) && !empty($old_iid)) {
		
		echo "
			<!doctype html>
			<html>
		
			<head>
			<title>Instructor Area</title>
			<link href = 'styles/main.css'
				  rel = 'stylesheet'
				  type = 'text/css'
			/>
			</head>

			<body>
				<h1>Have a great day " . $old_instructor . "!</h1>
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
			<title>Instructor Area</title>
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