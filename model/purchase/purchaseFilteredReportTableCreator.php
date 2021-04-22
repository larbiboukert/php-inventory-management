<?php
require_once('../../inc/config/constants.php');
require_once('../../inc/config/db.php');

$uPrice = 0;
$qty = 0;
$totalPrice = 0;

$purchaseFilteredReportSql = 'SELECT * FROM purchase';

if (isset($_POST['startDate']) || isset($_POST['endDate']) || isset($_POST['vendorName'])) {
	$purchaseFilteredReportSql .= ' WHERE '
		. (isset($_POST['startDate']) ? "saleDate > '" . htmlentities($_POST['startDate']) . "'" : '')
		. ((isset($_POST['startDate']) && isset($_POST['endDate'])) ? ' AND ' : '')
		. (isset($_POST['endDate']) ? "saleDate < '" . htmlentities($_POST['endDate']) . "'" : '')
		. (((isset($_POST['startDate']) || isset($_POST['endDate'])) && isset($_POST['vendorName'])) ? ' AND ' : '')
		. (isset($_POST['vendorName']) ? "vendorName = '" . htmlentities($_POST['vendorName']) . "'" : '');
}

$purchaseFilteredReportStatement = $conn->prepare($purchaseFilteredReportSql);
$purchaseFilteredReportStatement->execute();

$output = '<table id="purchaseFilteredReportsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
					<thead>
						<tr>
							<th>Num Achat</th>
							<th>Num Produit</th>
							<th>Date Achat</th>
							<th>Nom Produit</th>
							<th>Fournisseur Name</th>
							<th>Fournisseur ID</th>
							<th>Quantité</th>
							<th>Prix unitaire</th>
							<th>Total Price</th>
						</tr>
					</thead>
					<tbody>';

// Create table rows from the selected data
while ($row = $purchaseFilteredReportStatement->fetch(PDO::FETCH_ASSOC)) {
	$uPrice = $row['unitPrice'];
	$qty = $row['quantity'];
	$totalPrice = $uPrice * $qty;

	$output .= '<tr>' .
		'<td>' . $row['purchaseID'] . '</td>' .
		'<td>' . $row['itemNumber'] . '</td>' .
		'<td>' . $row['purchaseDate'] . '</td>' .
		'<td>' . $row['itemName'] . '</td>' .
		'<td>' . $row['vendorName'] . '</td>' .
		'<td>' . $row['vendorID'] . '</td>' .
		'<td>' . $row['quantity'] . '</td>' .
		'<td>' . $row['unitPrice'] . '</td>' .
		'<td>' . $totalPrice . '</td>' .
		'</tr>';
}

$purchaseFilteredReportStatement->closeCursor();

$output .= '</tbody>
						<tfoot>
							<tr>
								<th>Total</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
					</table>';
echo $output;
