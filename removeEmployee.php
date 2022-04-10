<?php
header("Location: https://cs.uky.edu/~tajo254/cathacks.html");
header("Content-type: html, charset=utf-8, Location: cs.uky.edu/~tajo254/cathacks.html");

$emp = "";
if (!empty($_POST)) {	
	$fname = htmlspecialchars($_POST["fname"]);
	$lname = htmlspecialchars($_POST["lname"]);

	include_once 'credentials.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($server, $username, $password, $db);
	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$remove_employee = "DELETE FROM employees WHERE fname = '".$fname."' AND lname = '".$lname."';";

	$connection = $conn->prepare($remove_employee);
	$connection->execute();
	$connection->close();
	exit();

}	
?>
