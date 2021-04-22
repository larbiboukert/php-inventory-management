<?php
require_once('../../inc/config/constants.php');
require_once('../../inc/config/db.php');

$uPrice = 0;
$qty = 0;
$totalPrice = 0;

$saleFilteredReportSql = 'SELECT * FROM sale';

if (isset($_POST['startDate']) || isset($_POST['endDate']) || isset($_POST['customerName'])) {
	$saleFilteredReportSql .= ' WHERE '
		. (isset($_POST['startDate']) ? "saleDate > '" . htmlentities($_POST['startDate']) . "'" : '')
		. ((isset($_POST['startDate']) && isset($_POST['endDate'])) ? ' AND ' : '')
		. (isset($_POST['endDate']) ? "saleDate < '" . htmlentities($_POST['endDate']) . "'" : '')
		. (((isset($_POST['startDate']) || isset($_POST['endDate'])) && isset($_POST['customerName'])) ? ' AND ' : '')
		. (isset($_POST['customerName']) ? "customerName = '" . htmlentities($_POST['customerName']) . "'" : '');
}

$saleFilteredReportStatement = $conn->prepare($saleFilteredReportSql);
$saleFilteredReportStatement->execute();

$output = '<table id="saleFilteredReportsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
					<thead>
						<tr>
							<th>Num Vente</th>
							<th>Num Produit</th>
							<th>Num Client</th>
							<th>Nom Client</th>
							<th>Nom Produit</th>
							<th>Date Vente</th>
							<th>Remise %</th>
							<th>Quantité</th>
							<th>Prix unitaire</th>
							<th>Total Price</th>
						</tr>
					</thead>
					<tbody>';

// Create table rows from the selected data
while ($row = $saleFilteredReportStatement->fetch(PDO::FETCH_ASSOC)) {
	$uPrice = $row['unitPrice'];
	$qty = $row['quantity'];
	$discount = $row['discount'];
	$totalPrice = $uPrice * $qty * ((100 - $discount) / 100);

	$output .= '<tr>' .
		'<td>' . $row['saleID'] . '</td>' .
		'<td>' . $row['itemNumber'] . '</td>' .
		'<td>' . $row['customerID'] . '</td>' .
		'<td>' . $row['customerName'] . '</td>' .
		'<td>' . $row['itemName'] . '</td>' .
		'<td>' . $row['saleDate'] . '</td>' .
		'<td>' . $row['discount'] . '</td>' .
		'<td>' . $row['quantity'] . '</td>' .
		'<td>' . $row['unitPrice'] . '</td>' .
		'<td>' . $totalPrice . '</td>' .
		'</tr>';
}

$saleFilteredReportStatement->closeCursor();

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
								<th></th>
							</tr>
						</tfoot>
					</table>';
echo $output;
