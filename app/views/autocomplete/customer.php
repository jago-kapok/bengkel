<?php
	try {
		$db_user	= 'root';
		$db_pwd		= '';

		$conn = new PDO("mysql:host=localhost;port=3306;dbname=bengkel", $db_user, $db_pwd);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
  
	$term = $_GET['term'];
  
	$stmt = $conn->query("SELECT * FROM customer WHERE customer_code LIKE '%$term%' OR customer_name LIKE '%$term%'");
  
	$output = array();
	while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
		$output[] = array(
			"customer_code" => $data['customer_code'],
			"customer_name" => $data['customer_name'],
			"customer_address" => $data['customer_address'],
			"customer_city" => $data['customer_city'],
		);
	}

	echo json_encode($output);
?>