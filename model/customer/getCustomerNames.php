<?php
	$customerNamesSql = 'SELECT * FROM customer';
	$customerNamesStatement = $conn->prepare($customerNamesSql);
	$customerNamesStatement->execute();
	
	if($customerNamesStatement->rowCount() > 0) {
		while($row = $customerNamesStatement->fetch(PDO::FETCH_ASSOC)) {
			echo '<option value="' .$row['fullName'] . '">' . $row['fullName'] . '</option>';
		}
	}
	$customerNamesStatement->closeCursor();
?>