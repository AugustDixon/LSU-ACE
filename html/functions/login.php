<?php
/*
	Function Script: functions/login.php
	This script checks a login attempt from the index.php login page. This script should only be called using
	HTTPS.
	
	Script Inputs:
	'username'
		No constraints
	'password'
		No constraints
	
	Script Output codes:
	0 = Script Failure
	1 = Successful Login
	2 = Unsuccessful Login
*/

//Start/Load session
/*
	Every PHP script needs to begin with this line. This will Recieve the session cookie from the user's browser
	and load the session, or create a new session if the user did not send a session cookie. Only once the session
	has been started are we able to use the $_SESSION superglobal. The $_SESSION superglobal should contain 
	'username' and 'idle' members, which contain the username and time of last login in seconds iff the user
	is currently logged in to an account. The index.php which generates the login page will not run if the user is
	already logged in so there is no need to check for that here.
*/
session_start();

//Connect to the MySQL server
/*
	The username and database you use with the mysqli instantiation should change depending on what database you are
	accessing and what function you require. Multiple mysqli variables can be instantiated if need be. The if statement
	sends an error back to the user if a connection to the database could not be established.
*/
$mysqli = new mysqli("localhost", "LoginSelect", "system", "Logins");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

//Load arguments from HTTPS request
/*
	Very simple, the post HTTP request form can send named arguments and they just have to be retrieved.
*/
$username = $_POST['username'];
$password = $_POST['password'];

//Check arguments meet constraints
/*
	No need to check argument constraints as being outside of the constraints will give a negative result 
	from the SQL query anyways.
*/

//Check arguments for SQL injections
/*
	Since the mysqli login only allows for select queries, we only need to be careful for select injections. The only
	thing a malicious user can gain from select injections is information, but no information is being sent back to
	the user so there is no need to protect from it.
*/

//Run SQL query on the database
$res = $mysqli->query("SELECT Sid FROM Logins WHERE Sid = '$username' AND Password = '$password';");

/*
	Check for a match in the result
	
	If there is a match, log the user in and send a success code. The HTML page should then redirect to the user's
		profile page.
	If there is no match, return a code signifiying that.
*/
if($res->num_rows == 1){
	$_SESSION['username'] = $username;
	$_SESSION['idle'] = time();
	echo "1";
	exit();
}
else{
	echo "2";
	exit();
}
?>