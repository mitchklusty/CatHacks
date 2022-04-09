<?php
header("Content-type: html; charset=utf-8");

if (!empty($_POST)) {	
	$fname = htmlspecialchars($_POST["fname"]);
	$lname = htmlspecialchars($_POST["lname"]);
	$phone = htmlspecialchars($_POST["phone"]);

	include_once 'credentials.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($server, $username, $password, $db);
	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$add_employee = "UPDATE employees SET fname =  WHERE category = ?";

	$connection = $conn->prepare($add_employee);
	$connection->execute();
	$connection->close();
	
}	
?>