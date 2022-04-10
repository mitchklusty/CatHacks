<?php
header("Location: http://http://cs.uky.edu/~tajo254/cathacks.html");
header("Content-type: html; charset=utf-8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!empty($_POST)) {	
	
	$fname = htmlspecialchars($_POST["fname"]);
	$lname = htmlspecialchars($_POST["lname"]);
	$phone = htmlspecialchars($_POST["phone"]);
	$hour = htmlspecialchars($_POST["hour"]);
	$min = htmlspecialchars($_POST["min"]);
	$outhour = htmlspecialchars($_POST["out_hour"]);
	$outmin = htmlspecialchars($_POST["out_min"]);

	include_once 'credentials.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($server, $username, $password, $db);
	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$add_employee = "INSERT INTO employees (fname, lname, phone, hours, minutes, out_hours, out_minutes, is_Clocked) VALUES ('".$fname."', '".$lname."', '".$phone."', ".$hour.", ".$min.", ".$outhour.", ".$outmin.", False);";
	$connection = $conn->prepare($add_employee);
	$connection->execute();
	$connection->close();
	exit;
	
}	
?>
