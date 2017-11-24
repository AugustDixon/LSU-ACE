<?php
/*
	Page Generate Script: class/info/instructorEdit.php
	This script generates the Edit Instructor page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Name" text field - 0 to 30 characters
		"Email" text field - 0 to 30 characters
		"Office" text field - 0 to 20 characters
		"Office Hours" text field - 0 to 50 characters
		"Submit Change Request" button - Runs class/info/functions/editInstructor.php
		
	AJAX Functions:
		editInstructor.php
			Inputs:
				'ID'
				'Name'
				'Email'
				'Office'
				'Hours'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1 [Pid]" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Name Constraint Error
					Show Constraints
				Case "4" - Email Constraint Error
					Show Constraints
				Case "5" - Office  Constraint Error
					Show Constraints
				Case "6" - Hours  Constraint Error
					Show Constraints
				Case "7" - "Total" Success
					Redirect to class/info/index.php
				Case "8 [Pid]" - Already Exists
					Redirect to class/bulletin/view.php
					
	Page Connections:
		class/info/index.php
		class/bulletin/view.php
*/

session_start();

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);
		header("Location: ../../index.php", true, 303);
		exit();
	}
}
else{
	header("Location: ../../index.php");
	exit();
}

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "SelectOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	//Send HTTP error code
	exit();
}


if(!isset($_GET['ID'])){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}
$ID = $_GET['ID'];

$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}



$html = "<html>
	<head>
		<title>Edit Instructor Info</title>
	</head>
	<body>
		<h1>Edit Instructor Info</h1>
		<a href=\"index.php?ID=$ID\">Back</a><br><br>
		Name: <input type=\"text\" id=\"Name\"><br>
		Email: <input type=\"text\" id=\"Email\"><br>
		Office: <input type=\"text\" id=\"Office\"><br>
		Hours: <input type=\"text\" id=\"Hours\"><br>
		<input type=\"button\" value=\"Submit Change Request\" onClick=\"edit()\">
	</body>
	<script>
		function edit(){
			var Name = document.getElementById('Name').value;
			var Email = document.getElementById('Email').value;
			var Office = document.getElementById('Office').value;
			var Hours = document.getElementById('Hours').value;
			
			var attributes = 'ID=$ID&Name=' + Name + '&Email=' + Email + '&Office=' + Office + '&Hours' + Hours;
			
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					editResponse(this);
				}
			};
			xhttp.open(\"POST\", \"functions/editInstructor.php\", true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
		
		function editResponse(xhttp){
			
			switch(xhttp.responseText.charAt(0)){
			case \"0\":
				alert(\"Edit Instructor Script Failure\");
				break;
			
			case \"1\":
				var arr = xhttp.responseText.split(\" \");
				window.location = \"../bulletin/view.php?ID=$ID&Pid=\" + arr[1];
				break;
			
			case \"2\":
				window.location = \"../../index.php\";
				break;
			
			case \"3\":
				alert(\"Name must be less than 30 characters\");
				break;
			
			case \"4\":
				alert(\"Email must be less than 30 characters\");
				break;
			
			case \"5\":
				alert(\"Office must be less than 20 characters\");
				break;
			
			case \"6\":
				alert(\"Hours must be less than 50 characters\");
				break;
			
			case \"7\":
				window.location = \"index.php?ID=$ID\";
				break;
			
			case \"8\":
				var arr = xhttp.responseText.split(\" \");
				window.location = \"../bulletin/view.php?ID=$ID&Pid=\" + arr[1];
				break;
			}
		}
	</script>
</html>";

echo $html;

exit();


?>