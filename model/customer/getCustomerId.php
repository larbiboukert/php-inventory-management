<?php
require_once('../../inc/config/constants.php');
require_once('../../inc/config/db.php');

// Execute the script if the POST request is submitted
if (isset($_POST['customerName'])) {
	$customerName = htmlentities($_POST['customerName']);

	$customerDetailsSql = "SELECT * FROM customer WHERE fullName = '$customerName'";
	$customerDetailsStatement = $conn->prepare($customerDetailsSql);
	$customerDetailsStatement->execute();

	$row = $customerDetailsStatement->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row['customerID']);
	$customerDetailsStatement->closeCursor();
}
