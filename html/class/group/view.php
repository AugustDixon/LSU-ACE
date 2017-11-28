<?php
/*
	Page Generate Script:	class/group/view.php
	This script generates the View Group page.
	
	HTTP Inputs:
		'ID'
		'Sid' - LSUID of group leader
		
	Page Features:
		"Back" button - Hyperlinks to class/group/index.php
		"Edit Group" button - Hyperlinks to class/group/edit.php. Only shows if the user is the group leader.
		"Join Group" button - Runs AJAX class/group/functions/joinGroup.php. Only shows if group is open
				and Number of Members is less than Maximum Members.
		The following info is available to anyone:
			Group Name
			Maximum Members
			Number of Members
			Whether or not the group is looking for members
		The following info is only available to group members: For Each Member:
			LSUID
			First Name
			Last Name
			Nickname
			Phone Number
		
	AJAX Functions:
		joinGroup.php
			Inputs
				'ID'
				'Sid' - LSUID of group leader
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Reloads class/group/view.php
				Case "2" - Idle Timeout
					Redirects to index.php
					
	Page Connections:
		class/group/edit.php
		class/group/index.php
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

$Sid = $_GET['Sid'];




$res = $mysqli->query("SELECT Max, Open, Looking, Name FROM SGroup WHERE Cid = '$ID' AND Sid = '$Sid';");
$result = $res->fetch_assoc();
$Max = $result['Max'];
$Open = $result['Open'];
$Looking = $result['Looking'];
$Name = $result['Name'];

$res = $mysqli->query("SELECT * FROM InGroup WHERE Cid = '$ID' AND Gid = '$Sid' AND Sid = '$username';");
$InGroup = ($res->num_rows > 0);

$res = $mysqli->query("SELECT Sid, FirstName, LastName, Phone, LSUID FROM InGroup NATURAL JOIN Student WHERE Cid = '$ID' AND Gid = '$Sid';");
$Num = $res->num_rows;
$Join = ($Num < $Max) && $Open;
$Leader = ($username == $Sid);



$html = "<html>
 	<head> 
   		<title>View Group</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			th, td {
				padding: 5px;
				text-align: left;    
			}
		</style>
  	</head>
  	<body> 
		<h1>View Group</h1>
   		 	<a href=\"index.php?ID=$ID\">Back</a>";
			
if($Leader)
	$html .= "
			<a href=\"edit.php?ID=$ID\">Edit Group</a>";

if($Join && !($InGroup))
	$html .= "
			<input type=\"button\" value=\"Join Group\" onClick=\"loadDoc('functions/joinGroup.php', myFunction)\">";

$html .= "
			<p>
				Group Name: $Name<br>
				Group Leader: $Sid<br>
				Maximum Members: $Max<br>
				Number of Members: $Num<br>";

if($Looking)
	$html .= "
				Currently Looking for Members<br>";

$html .= "
			</p>";

if($InGroup){
	$html .= "<table style=\"width:100%\">
				<tr>
					<th>Username</th>
					<th>Name</th> 
					<th>LSUID</th>
					<th>Phone Number</th>
				</tr>";
	for($i = 0; $i < $res->num_rows; $i++){
		$res->data_seek($i);
		$result = $res->fetch_assoc();
		$User = $result['Sid'];
		$FirstName = $result['FirstName'];
		$LastName = $result['LastName'];
		$PhoneNumber = $result['Phone'];
		$LSUID = $result['LSUID'];
		$html .= "
				<tr> 
					<td>$User</td>
					<td>$FirstName $LastName</td>
					<td>$LSUID</td>
					<td>$PhoneNumber</td>
				</tr>";
	}
	$html .= "
			</table>";
}

$html .= "
	</body>
	<script>
		function loadDoc(url, cFunction) 
		{
			var attributes = \"ID=$ID&Sid=$Sid\";
	
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
				alert(\"Join Group Script Failure\");
				break;
			
			case \"1\": 
				location.reload();
				break;
			
			case \"2\": 
				window.location = \"../../index.php\";
				break;
			}
		}
	</script>";

$html .= "
</html>";

echo $html;


exit();



?>