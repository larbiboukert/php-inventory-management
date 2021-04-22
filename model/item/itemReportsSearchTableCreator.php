<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$itemDetailsSearchSql = 'SELECT * FROM item';
	$itemDetailsSearchStatement = $conn->prepare($itemDetailsSearchSql);
	$itemDetailsSearchStatement->execute();

	$output = '<table id="itemReportsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Num Produit</th>
						<th>Num Produit</th>
						<th>Nom Produit</th>
						<th>Remise %</th>
						<th>Stock</th>
						<th>Prix unitaire</th>
						<th>Etat</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>';
	
	// Create table rows from the selected data
	while($row = $itemDetailsSearchStatement->fetch(PDO::FETCH_ASSOC)){
		$output .= '<tr>' .
						'<td>' . $row['productID'] . '</td>' .
						'<td>' . $row['itemNumber'] . '</td>' .
						//'<td>' . $row['itemName'] . '</td>' .
						'<td><a href="#" class="itemDetailsHover" data-toggle="popover" id="' . $row['productID'] . '">' . $row['itemName'] . '</a></td>' .
						'<td>' . $row['discount'] . '</td>' .
						'<td>' . $row['stock'] . '</td>' .
						'<td>' . $row['unitPrice'] . '</td>' .
						'<td>' . $row['status'] . '</td>' .
						'<td>' . $row['description'] . '</td>' .
					'</tr>';
	}
	
	$itemDetailsSearchStatement->closeCursor();
	
	$output .= '</tbody>
					<tfoot>
						<tr>
							<th>Num Produit</th>
							<th>Num Produit</th>
							<th>Nom Produit</th>
							<th>Remise %</th>
							<th>Stock</th>
							<th>Prix unitaire</th>
							<th>Etat</th>
							<th>Description</th>
						</tr>
					</tfoot>
				</table>';
	echo $output;
?>