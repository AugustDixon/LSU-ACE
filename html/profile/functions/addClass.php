<?php

//Start/Load session
session_start();

//Connect to the MySQL server
$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}
//inputs from add.php
$dept = $_POST['Dept'];
$num = $_POST['Num'];
$sect = $_POST['Sect'];
$daya = $_POST['DayA'];
$stimea = $_POST['STimeA'];
$etimea = $_POST['ETimeA'];
$dayb = $_POST['DayB'];
$stimeb = $_POST['STimeB'];
$etimeb = $_POST['ETimeB'];
$hname = $_POST['HName'];
$hphone = $_POST['HPhone'];
$hlsuid = $_POST['HLSUID'];

//need to find what constraints need to be added.

//need to find what values go in Cid, Title, Classroom, Confirmed.
$sql = "INSERT Class (Cid, Dept, Num, Sect, Title, Classroom, Confirmed, DayA, STimeA, ETimeA, DayB, STimeB, ETimeB)
VALUES ('', '$dept', '$num', '$sect', '', '', '', '$daya', '$stimea', '$etimea', '$dayb', '$stimeb', '$etimeb')";
if ($mysqli->query($sql) === TRUE) {
    echo "1";
} else {
    echo "0";
    exit();
}
$mysqli->close();

?>