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
  
	$stmt = $conn->query("SELECT * FROM supplier WHERE supplier_code LIKE '%$term%' OR supplier_name LIKE '%$term%'");
  
	$output = array();
	while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
		$output[] = array(
			"supplier_code" => $data['supplier_code'],
			"supplier_name" => $data['supplier_name'],
			"supplier_address" => $data['supplier_address'],
			"supplier_city" => $data['supplier_city'],
		);
	}

	echo json_encode($output);
?>