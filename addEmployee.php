<?php
header("Content-type: html; charset=utf-8");

if (!empty($_POST)) {	
	$fname = htmlspecialchars($_POST["fname"]);
	$lname = htmlspecialchars($_POST["lname"]);
	$phone = htmlspecialchars($_POST["phone"]);
	$hour = htmlspecialchars($_POST["hour"]);
	$min = htmlspecialchars($_POST["min"]);

	include_once 'credentials.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($server, $username, $password, $db);
	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$add_employee = "INSERT INTO employees ".$fname." ".$lname." ".$phone." ".$hour." ".$min ;

	$connection = $conn->prepare($add_employee);
	$connection->execute();
	$connection->close();
	
}	
?>
