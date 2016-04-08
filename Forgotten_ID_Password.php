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
	</ul>
	
	<br /><br />
	
	<h2>ID and Password Recovery</h2>
	<br />

	<div>
		<form action = 'Email_ID_Password.php' method = 'post'>
			<label>First Name</label>
			<input type = 'text' name = 'f_name'>
			<label>Last Name</label>
			<input type = 'text' name = 'l_name'>
			<label>Email</label>
			<input type = 'text' name = 'email'>
			<select name = 'user_type'>
				<option value = 'student' selected>student</option>
				<option value = 'instructor'>instructor</option>
			</select>
			<input type = 'submit' value = 'Submit'>
			<input type = 'reset' value = 'Reset Form'>
		</form>
	</div>
</body>

</html>