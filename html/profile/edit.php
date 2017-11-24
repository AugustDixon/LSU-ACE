<?php
/*
	Page generate script: /profile/edit.php
	This script generates the edit profile page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Back" button - Hyperlinks to /profile/index.php
		"Add Class" button - Hyperlinks to /profile/add.php
		"Change Password" button - Hyperlinks to /profile/password.php
		"First Name" text field - 0 to 20 characters
		"Last Name" text field - 0 to 20 characters
		"LSUID" text field - 0 to 20 characters
		"Phone Number" text field - 0 to 20 characters
		"Edit Profile" button - Runs AJAX /profile/functions/editProfile.php
	
	AJAX Functions:
		editProfile.php
			Inputs
				'FirstName'
				'LastName'
				'LSUID'
				'Phone'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to /profile/index.php
				Case "2" - Idle timer expiration
					Redirect to /index.php
				Case "3" - First Name Constraint Error
					Show message and constraints
				Case "4" - Last Name Constraint Error
					Show message and constraints
				Case "5" - LSUID Constraint Error
					Show message and constraints
				Case "6" - Phone Number Constraint Error
					Show message and constraints
	
	Page Connections:
		/profile/index.php
		/profile/password.php
		/profile/add.php
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

echo "<html>
 	<head> 
   		 <title>Edit Profile</title>
  	</head>
  	<body> 
		<h1>Edit Profile</h1>
			<a href=\"index.php\">Back</a>
                <form name=\"registerForm\">
				LSUID: <input type=\"text\" name=\"LSUID\" id=\"LSUID\"><br>
				First name: <input type=\"text\" name=\"firstName\" id=\"firstName\"><br>
				Last name: <input type=\"text\" name=\"lastName\" id=\"lastName\"><br>
				Phone number: <input type=\"text\" name=\"phoneNumber\" id=\"phoneNumber\"><br>
				
				<input type=\"button\" value=\"Edit profile\" onClick=\"loadDoc('functions/editProfile.php', myFunction)\">
			</form>	
            <a href=\"add.php\">Add Class</a>
            <a href=\"password.php\">Change Password</a>
                
                
  	</body>
	
	</body>
	
	<script>
	function loadDoc(url, cFunction) 
	{
		var LSUID = document.getElementById('LSUID').value;
		var firstName = document.getElementById('firstName').value;
		var lastName = document.getElementById('lastName').value;
		var phoneNumber = document.getElementById('phoneNumber').value;
		var attributes = 'LSUID=' + LSUID + '&FirstName=' + firstName + '&LastName=' + lastName + '&Phone=' + phoneNumber;
	
		var xhttp;
		xhttp=new XMLHttpRequest();
		xhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				cFunction(this);
			}
		};
		xhttp.open(\"POST\", url, true);
		xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
		xhttp.send(attributes);
	}
	
	function myFunction(xhttp) 
	{
		switch(xhttp.responseText)
		{
		case \"0\": 
			alert(\"Edit Profile Script Failure\");
			break;
		
		case \"1\": 
			window.location = \"index.php\";
			break;
		
		case \"2\": 
			window.location = \"../index.php\";
			break;
        
        case \"3\":
			alert(\"First Name must be less than 20 characters\");
            break;
        
        case \"4\":
			alert(\"Last Name must be less than 20 characters\");
            break;
        
        case \"5\":
            alert(\"LSUID must be less than 20 characters\");
            break;
        
        case \"6\":
            alert(\"Phone Number must be less than 20 characters\");
            break;
		}
	}
	</script>
</html>";

exit();

?>