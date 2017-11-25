<?php
/*
	Page Generate Script: profile/password.php
	This script generates the change password page.
	
	HTTP Inputs:
		none
		
	Page Features:
		"Back" button - Hyperlinks to profile/edit.php
		"Current Password" text field
		"New Password" text field - 5 to 20 characters. Must not match Current Password
		"Confirm Password" text field - Must match New Password.
		"Change Password" button - Runs AJAX profile/functions/changePassword.php
		
	AJAX Functions:
		changePassword.php
			Inputs
				'password'
				'newpass'
				'cpass'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Success
					Redirects to profile/edit.php
				Case "2" - Idle Timeout
					Redirects to index.php
				Case "3" - Incorrect Password
					Show message and clear Current Password field
				Case "4" - New Password Constraint Error
					Show message and clear Current Password field
				Case "5" - New Password is the same
					Show message and clear Current Password field
				Case "6" - Passwords do not match
					Show message and clear Current Password field
					
	Page Connections:
		profile/edit.php
*/

session_start();

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);
		header("Location: ../index.php", true, 303);
		exit();
	}
}
else{
	header("Location: ../index.php");
	exit();
}

$_SESSION['idle'] = time();

$html = "<html>
	<head>
		<title>Change Password</title>
	</head>
	<body>
		<h1>Change Password</h1>
		<a href=\"edit.php\">Back</a><br><br>
		Current Password: <input type=\"password\" id=\"password\"><br><br>
		New Password: <input type=\"text\" id=\"newpass\"><br>
		Confirm Password: <input type=\"text\" id=\"cpass\"><br>
		<input type=\"button\" value=\"Change Password\" onClick=\"changePassword()\">
	</body>
	<script>
		function changePassword() {
			var password = document.getElementById('password').value;
			var newpass = document.getElementById('newpass').value;
			var cpass = document.getElementById('cpass').value;
			var attributes = 'password=' + password + '&newpass=' + newpass + '&cpass=' + cpass;
	
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					changePasswordFinish(this);
				}
			};
			xhttp.open(\"POST\", \"functions/changePassword.php\", true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
	
		function changePasswordFinish(xhttp) {
			switch(xhttp.responseText){
			case \"0\": 
				alert(\"Change Password Script Failure\");
				break;
			
			case \"1\": 
				window.location = \"edit.php\";
				break;
			
			case \"2\": 
				window.location = \"../index.php\";
				break;
			
			case \"3\":
				alert(\"Incorrect Password\");
				break;
			
			case \"4\":
				alert(\"New Password must be 5 to 20 characters\");
				break;
        
			case \"5\":
				alert(\"New Password is the same as Current Password\");
				break;
			
			case \"6\":
				alert(\"Passwords do not match\");
				break;
			}
		}
	</script>
</html>";

echo $html;

exit();


?>