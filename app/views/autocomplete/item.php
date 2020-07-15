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
  
	$stmt = $conn->query("SELECT * FROM item WHERE item_code LIKE '%$term%' OR item_desc LIKE '%$term%'");
  
	$output = array();
	while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
		$output[] = array(
			"item_code" => $data['item_code'],
			"item_part_no" => $data['item_part_no'],
			"item_desc" => $data['item_desc']
		);
	}

	echo json_encode($output);
?>