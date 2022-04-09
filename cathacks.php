<?php
header("Content-type: html; charset=utf-8");

$menu = "";
if (!empty($_POST)) {	
	$menu = htmlspecialchars($_POST["menu"]);

	include_once 'credentials.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($server, $username, $password, $db);
	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$find_category_query = "SELECT * FROM accesses WHERE category = ?";
	$update_access_query = "UPDATE accesses SET number = number + 1 WHERE category = ?";
	$select_number_query = "SELECT number FROM accesses WHERE category = ?";
	$select_item_query = "SELECT item, description, price FROM menu WHERE category = ?";

	if ($menu != "") {
		
		header("Content-Type: application/json; charset=utf-8");
		$result = $conn->prepare($find_category_query);
		$result->bind_param("s", $menu);
		$result->execute();
		$result->store_result();

		
		
		if ($result->num_rows > 0){

			ob_start();
			$connection = $conn->prepare($update_access_query);
			$connection->bind_param("s", $menu);
			$connection->execute();
			$connection->close();

			$connection = $conn->prepare($select_number_query);
			$connection->bind_param("s", $menu);
			$connection->execute();
			$connection->bind_result($count);


			$msg_rcvd = false;
			$received = '';
			$received .= '{"category": "'.$menu.'",';
			while ($connection->fetch()) {
				$msg_rcvd = true;
			}
			if ($msg_rcvd){
				$received .= '"accesses": "'.$count.'",';

			}

			$connection->close();

			$connection = $conn->prepare($select_item_query);
			$connection->bind_param("s", $menu);
			$connection->execute();
			$connection->bind_result($item, $description, $price);
			$received .= '"details": [';
			// $rows = "";
			

			while($connection->fetch()) {
				$received .= '{"item": "' . $item . '",';
				$received .= '"description": "' . $description . '",';
				$received .= '"price": "' . $price . '"},';
				
			}
			$received = rtrim($received, ',');
			$received .= "]}";
			$received = json_encode($received);
			print $received;
			
			$output = ob_get_clean();
			ob_end_clean();
			echo $output;

			$connection->close();
			} 
		$conn->close();
		exit();
	}
	$result->close();
}	
?>

<!DOCTYPE html>
<html lang = 'en'>

	<head>
		<meta charset="UTF-8">
		<title>Cat Hacks</title>
	</head>

	<body style="font-family:Free Serif">
		<h1>Admin Page</h1>

		<input> 

	</body>


</html>