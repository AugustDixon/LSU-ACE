<?php
/*
	Function Script: profile/functions/logout.php
	This script logs the user out.
	
	Script Inputs:
		none
	
	Script Output Codes:
	0 - Script Failure
	1 - Success
*/

session_start();

if(isset($_SESSION['username'])){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
}

echo "1";

exit();


?>