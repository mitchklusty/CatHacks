<?php
header("Content-type: html; charset=utf-8");

$emp = ""
if (!empty($_POST)) {	
	$fname = htmlspecialchars($_POST["fname"]);
	$lname = htmlspecialchars($_POST["lname"]);

	include_once 'credentials.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($server, $username, $password, $db);
	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$check_employee = "SELECT * FROM employees WHERE fname = ? AND lname = ?";
	$remove_employee = "DELETE FROM employees WHERE fname = ? AND lname = ?";

	header("Content-Type: application/json; charset=utf-8");
	$result = $conn->prepare($check_employee);
	$result->bind_param("s", $menu);
	$result->execute();
	$result->store_result();

	if ($result->num_rows > 0){
		ob_start();
		$connection = $conn->prepare($remove_employee);
		$connection->execute();
		$connection->close();
	}

}	
?>