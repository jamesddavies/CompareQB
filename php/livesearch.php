<?php

	require('config.php');

	$search = $_GET['search'];

	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	$query = $db->prepare("SELECT * FROM playerdata WHERE full_name LIKE :name");
	$query->execute([
	    "name" => "%" . $search . "%"
	]);

	$name = [];

	while ($row = $query->fetch()) {
		
			$name[] = ucwords($row['full_name']);
		
	}

//Response text

	foreach ($name as $n){
		echo '<li>' . $n . '</li>';
	}

?>