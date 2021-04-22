<?php
session_start();
// Redirect the user to login page if he is not logged in.
if (!isset($_SESSION['loggedIn'])) {
	header('Location: login.php');
	exit();
}

require_once('inc/config/constants.php');
require_once('inc/config/db.php');
require_once('inc/header.html');
?>

<body>
	<?php
	require 'inc/navigation.php';
	?>
	<!-- Page Content -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-2">
				<h1 class="my-4"></h1>
				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="v-pills-reports-tab" data-toggle="pill" href="#v-pills-reports" role="tab" aria-controls="v-pills-reports" aria-selected="true">= Rapports</a>
					<a class="nav-link" id="v-pills-sale-tab" data-toggle="pill" href="#v-pills-sale" role="tab" aria-controls="v-pills-sale" aria-selected="false">+ Vente</a>
					<a class="nav-link" id="v-pills-purchase-tab" data-toggle="pill" href="#v-pills-purchase" role="tab" aria-controls="v-pills-purchase" aria-selected="false">+ Achat</a>
					<a class="nav-link" id="v-pills-item-tab" data-toggle="pill" href="#v-pills-item" role="tab" aria-controls="v-pills-item" aria-selected="false">+ Produit</a>
					<a class="nav-link" id="v-pills-customer-tab" data-toggle="pill" href="#v-pills-customer" role="tab" aria-controls="v-pills-customer" aria-selected="false">+ Client</a>
					<a class="nav-link" id="v-pills-vendor-tab" data-toggle="pill" href="#v-pills-vendor" role="tab" aria-controls="v-pills-vendor" aria-selected="false">+ Fournisseur</a>
					<!-- <a class="nav-link" id="v-pills-search-tab" data-toggle="pill" href="#v-pills-search" role="tab" aria-controls="v-pills-search" aria-selected="false">Search</a> -->
				</div>
			</div>
			<div class="col-lg-10">
				<div class="tab-content" id="v-pills-tabContent">
					<div class="tab-pane fade" id="v-pills-item" role="tabpanel" aria-labelledby="v-pills-item-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Details Produit</div>
							<div class="card-body">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#itemDetailsTab">Produit</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#itemImageTab">Ajouter Image</a>
									</li>
								</ul>

								<!-- Tab panes for item details and image sections -->
								<div class="tab-content">
									<div id="itemDetailsTab" class="container-fluid tab-pane active">
										<br>
										<!-- Div to show the ajax message from validations/db submission -->
										<div id="itemDetailsMessage"></div>
										<form>
											<div class="form-row">
												<div class="form-group col-md-3" style="display:inline-block">
													<label for="itemDetailsItemNumber">Num Produit<span class="requiredIcon">*</span></label>
													<input type="text" class="form-control" name="itemDetailsItemNumber" id="itemDetailsItemNumber" autocomplete="off">
													<div id="itemDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
												</div>
												<div class="form-group col-md-3">
													<label for="itemDetailsProductID">Num Produit</label>
													<input class="form-control invTooltip" type="number" readonly id="itemDetailsProductID" name="itemDetailsProductID" title="This will be auto-generated when you add a new item">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-6">
													<label for="itemDetailsItemName">Nom Produit<span class="requiredIcon">*</span></label>
													<input type="text" class="form-control" name="itemDetailsItemName" id="itemDetailsItemName" autocomplete="off">
													<div id="itemDetailsItemNameSuggestionsDiv" class="customListDivWidth"></div>
												</div>
												<div class="form-group col-md-2">
													<label for="itemDetailsStatus">Etat</label>
													<select id="itemDetailsStatus" name="itemDetailsStatus" class="form-control chosenSelect">
														<?php include('inc/statusList.html'); ?>
													</select>
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-6" style="display:inline-block">
													<!-- <label for="itemDetailsDescription">Description</label> -->
													<textarea rows="4" class="form-control" placeholder="Description" name="itemDetailsDescription" id="itemDetailsDescription"></textarea>
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-3">
													<label for="itemDetailsDiscount">Remise %</label>
													<input type="text" class="form-control" value="0" name="itemDetailsDiscount" id="itemDetailsDiscount">
												</div>
												<div class="form-group col-md-3">
													<label for="itemDetailsQuantity">Quantité<span class="requiredIcon">*</span></label>
													<input type="number" class="form-control" value="0" name="itemDetailsQuantity" id="itemDetailsQuantity">
												</div>
												<div class="form-group col-md-3">
													<label for="itemDetailsUnitPrice">Prix unitaire<span class="requiredIcon">*</span></label>
													<input type="text" class="form-control" value="0" name="itemDetailsUnitPrice" id="itemDetailsUnitPrice">
												</div>
												<div class="form-group col-md-3">
													<label for="itemDetailsTotalStock">Stock total</label>
													<input type="text" class="form-control" name="itemDetailsTotalStock" id="itemDetailsTotalStock" readonly>
												</div>
												<div class="form-group col-md-3">
													<div id="imageContainer"></div>
												</div>
											</div>
											<button type="button" id="addItem" class="btn btn-success">Ajouter Produit</button>
											<button type="button" id="updateItemDetailsButton" class="btn btn-primary">Mettre à jour</button>
											<button type="button" id="deleteItem" class="btn btn-danger">Supprimer</button>
											<button type="reset" class="btn" id="itemClear">Clear</button>
										</form>
									</div>
									<div id="itemImageTab" class="container-fluid tab-pane fade">
										<br>
										<div id="itemImageMessage"></div>
										<p>Vous pouvez ajouter une image pour un produit particulier en utilisant cette section.</p>
										<p>Veuillez vous assurer que l'élément est déjà ajouté à la base de données avant d'ajouter l'image.</p>
										<br>
										<form name="imageForm" id="imageForm" method="post">
											<div class="form-row">
												<div class="form-group col-md-3" style="display:inline-block">
													<label for="itemImageItemNumber">Num Produit<span class="requiredIcon">*</span></label>
													<input type="text" class="form-control" name="itemImageItemNumber" id="itemImageItemNumber" autocomplete="off">
													<div id="itemImageItemNumberSuggestionsDiv" class="customListDivWidth"></div>
												</div>
												<div class="form-group col-md-4">
													<label for="itemImageItemName">Nom Produit</label>
													<input type="text" class="form-control" name="itemImageItemName" id="itemImageItemName" readonly>
												</div>
											</div>
											<br>
											<div class="form-row">
												<div class="form-group col-md-7">
													<label for="itemImageFile">Select Image ( <span class="blueText">jpg</span>, <span class="blueText">jpeg</span>, <span class="blueText">gif</span>, <span class="blueText">png</span> only )</label>
													<input type="file" class="form-control-file btn btn-dark" id="itemImageFile" name="itemImageFile">
												</div>
											</div>
											<br>
											<button type="button" id="updateImageButton" class="btn btn-primary">Ajouter Image</button>
											<button type="button" id="deleteImageButton" class="btn btn-danger">Supprimer Image</button>
											<button type="reset" class="btn">Clear</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-purchase" role="tabpanel" aria-labelledby="v-pills-purchase-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Details Achat</div>
							<div class="card-body">
								<div id="purchaseDetailsMessage"></div>
								<form>
									<div class="form-row">
										<div class="form-group col-md-2">
											<label for="purchaseDetailsPurchaseID">Num Achat</label>
											<input type="text" class="form-control invTooltip" id="purchaseDetailsPurchaseID" name="purchaseDetailsPurchaseID" title="This will be auto-generated when you add a new record" autocomplete="off">
											<div id="purchaseDetailsPurchaseIDSuggestionsDiv" class="customListDivWidth"></div>
										</div>
										<div class="form-group col-md-3 d-none">
											<label for="purchaseDetailsItemNumber">Num Produit<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="purchaseDetailsItemNumber" name="purchaseDetailsItemNumber" autocomplete="off">
											<div id="purchaseDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
										</div>
										<div class="form-group col-md-3">
											<label for="purchaseDetailsPurchaseDate">Date Achat<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control datepicker" id="purchaseDetailsPurchaseDate" name="purchaseDetailsPurchaseDate" readonly value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label for="purchaseDetailsItemName">Nom Produit<span class="requiredIcon">*</span></label>
											<!-- <input type="text" class="form-control invTooltip" id="purchaseDetailsItemName" name="purchaseDetailsItemName" readonly title="This will be auto-filled when you enter the item number above"> -->
											<select id="purchaseDetailsItemName" name="purchaseDetailsItemName" class="form-control chosenSelect"> -->
												<option value=""></option>
												<?php
												require('model/item/getItemDetails.php');
												?>
											</select>
										</div>
										<div class="form-group col-md-2">
											<label for="purchaseDetailsCurrentStock">Stock actuel</label>
											<input type="text" class="form-control" id="purchaseDetailsCurrentStock" name="purchaseDetailsCurrentStock" readonly>
										</div>
										<div class="form-group col-md-4">
											<label for="purchaseDetailsVendorName">Fournisseur Name<span class="requiredIcon">*</span></label>
											<select id="purchaseDetailsVendorName" name="purchaseDetailsVendorName" class="form-control chosenSelect">
												<option value=""></option>
												<?php
												require('model/vendor/getVendorNames.php');
												?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-2">
											<label for="purchaseDetailsQuantity">Quantité<span class="requiredIcon">*</span></label>
											<input type="number" class="form-control" id="purchaseDetailsQuantity" name="purchaseDetailsQuantity" value="0">
										</div>
										<div class="form-group col-md-2">
											<label for="purchaseDetailsUnitPrice">Prix unitaire<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="purchaseDetailsUnitPrice" name="purchaseDetailsUnitPrice" value="0">

										</div>
										<div class="form-group col-md-2">
											<label for="purchaseDetailsTotal">Total Cost</label>
											<input type="text" class="form-control" id="purchaseDetailsTotal" name="purchaseDetailsTotal" readonly>
										</div>
									</div>
									<button type="button" id="addPurchase" class="btn btn-success">Ajouter Achat</button>
									<button type="button" id="updatePurchaseDetailsButton" class="btn btn-primary">Mettre à jour</button>
									<button type="reset" class="btn">Clear</button>
								</form>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="v-pills-vendor" role="tabpanel" aria-labelledby="v-pills-vendor-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Fournisseur Details</div>
							<div class="card-body">
								<!-- Div to show the ajax message from validations/db submission -->
								<div id="vendorDetailsMessage"></div>
								<form>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="vendorDetailsVendorFullName">Nom<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="vendorDetailsVendorFullName" name="vendorDetailsVendorFullName" placeholder="">
										</div>
										<div class="form-group col-md-2">
											<label for="vendorDetailsStatus">Etat</label>
											<select id="vendorDetailsStatus" name="vendorDetailsStatus" class="form-control chosenSelect">
												<?php include('inc/statusList.html'); ?>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="vendorDetailsVendorID">Fournisseur ID</label>
											<input type="text" class="form-control invTooltip" id="vendorDetailsVendorID" name="vendorDetailsVendorID" title="This will be auto-generated when you add a new vendor" autocomplete="off">
											<div id="vendorDetailsVendorIDSuggestionsDiv" class="customListDivWidth"></div>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<label for="vendorDetailsVendorMobile">Telephone<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control invTooltip" id="vendorDetailsVendorMobile" name="vendorDetailsVendorMobile" title="Do not enter leading 0">
										</div>
										<div class="form-group col-md-3">
											<label for="vendorDetailsVendorPhone2">Telephone2</label>
											<input type="text" class="form-control invTooltip" id="vendorDetailsVendorPhone2" name="vendorDetailsVendorPhone2" title="Do not enter leading 0">
										</div>
										<div class="form-group col-md-6">
											<label for="vendorDetailsVendorEmail">Email</label>
											<input type="email" class="form-control" id="vendorDetailsVendorEmail" name="vendorDetailsVendorEmail">
										</div>
									</div>
									<div class="form-group">
										<label for="vendorDetailsVendorAddress">Adresse<span class="requiredIcon">*</span></label>
										<input type="text" class="form-control" id="vendorDetailsVendorAddress" name="vendorDetailsVendorAddress">
									</div>
									<div class="form-group">
										<label for="vendorDetailsVendorAddress2">Adresse 2</label>
										<input type="text" class="form-control" id="vendorDetailsVendorAddress2" name="vendorDetailsVendorAddress2">
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="vendorDetailsVendorCity">Ville</label>
											<input type="text" class="form-control" id="vendorDetailsVendorCity" name="vendorDetailsVendorCity">
										</div>
										<div class="form-group col-md-4">
											<label for="vendorDetailsVendorDistrict">Quartier</label>
											<select id="vendorDetailsVendorDistrict" name="vendorDetailsVendorDistrict" class="form-control chosenSelect">
												<?php include('inc/districtList.html'); ?>
											</select>
										</div>
									</div>
									<button type="button" id="addVendor" name="addVendor" class="btn btn-success">Ajouter Fournisseur</button>
									<button type="button" id="updateVendorDetailsButton" class="btn btn-primary">Mettre à jour</button>
									<button type="button" id="deleteVendorButton" class="btn btn-danger">Supprimer</button>
									<button type="reset" class="btn">Clear</button>
								</form>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="v-pills-sale" role="tabpanel" aria-labelledby="v-pills-sale-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Details Vente</div>
							<div class="card-body">
								<div id="saleDetailsMessage"></div>
								<form>
									<div class="form-row">
										<div class="form-group col-md-3 d-none">
											<label for="saleDetailsItemNumber">Num Produit<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="saleDetailsItemNumber" name="saleDetailsItemNumber" autocomplete="off">
											<div id="saleDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
										</div>
										<div class="form-group col-md-3 d-none">
											<label for="saleDetailsCustomerID">Num Client<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="saleDetailsCustomerID" name="saleDetailsCustomerID" autocomplete="off">
											<div id="saleDetailsCustomerIDSuggestionsDiv" class="customListDivWidth"></div>
										</div>
										<div class="form-group col-md-2">
											<label for="saleDetailsSaleID">Num Vente</label>
											<input type="text" class="form-control invTooltip" id="saleDetailsSaleID" name="saleDetailsSaleID" title="This will be auto-generated when you add a new record" autocomplete="off">
											<div id="saleDetailsSaleIDSuggestionsDiv" class="customListDivWidth"></div>
										</div>
										<div class="form-group col-md-4">
											<label for="saleDetailsCustomerName">Nom Client<span class="requiredIcon">*</span></label>
											<!-- <input type="text" class="form-control" id="saleDetailsCustomerName" name="saleDetailsCustomerName" readonly> -->
											<select id="saleDetailsCustomerName" name="saleDetailsCustomerName" class="form-control chosenSelect">
												<option value=""></option>
												<?php
												require('model/customer/getCustomerNames.php');
												?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-5">
											<label for="saleDetailsItemName">Nom Produit<span class="requiredIcon">*</span></label>
											<select id="saleDetailsItemName" name="saleDetailsItemName" class="form-control chosenSelect"> -->
												<option value=""></option>
												<?php
												require('model/item/getItemDetails.php');
												?>
											</select>
											<!-- <input type="text" class="form-control invTooltip" id="saleDetailsItemName" name="saleDetailsItemName" readonly title="This will be auto-filled when you enter the item number above"> -->
										</div>
										<div class="form-group col-md-3">
											<label for="saleDetailsSaleDate">Date Vente<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control datepicker" id="saleDetailsSaleDate" value="<?php echo date('Y-m-d'); ?>" name="saleDetailsSaleDate" readonly>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-2">
											<label for="saleDetailsTotalStock">Stock total</label>
											<input type="text" class="form-control" name="saleDetailsTotalStock" id="saleDetailsTotalStock" readonly>
										</div>
										<div class="form-group col-md-2">
											<label for="saleDetailsDiscount">Remise %</label>
											<input type="text" class="form-control" id="saleDetailsDiscount" name="saleDetailsDiscount" value="0">
										</div>
										<div class="form-group col-md-2">
											<label for="saleDetailsQuantity">Quantité<span class="requiredIcon">*</span></label>
											<input type="number" class="form-control" id="saleDetailsQuantity" name="saleDetailsQuantity" value="0">
										</div>
										<div class="form-group col-md-2">
											<label for="saleDetailsUnitPrice">Prix unitaire<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="saleDetailsUnitPrice" name="saleDetailsUnitPrice" value="0">
										</div>
										<div class="form-group col-md-3">
											<label for="saleDetailsTotal">Total</label>
											<input type="text" class="form-control" id="saleDetailsTotal" name="saleDetailsTotal">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<div id="saleDetailsImageContainer"></div>
										</div>
									</div>
									<button type="button" id="addSaleButton" class="btn btn-success">Ajouter Vente</button>
									<button type="button" id="updateSaleDetailsButton" class="btn btn-primary">Mettre à jour</button>
									<button type="reset" id="saleClear" class="btn">Clear</button>
								</form>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-customer" role="tabpanel" aria-labelledby="v-pills-customer-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Details Client</div>
							<div class="card-body">
								<!-- Div to show the ajax message from validations/db submission -->
								<div id="customerDetailsMessage"></div>
								<form>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="customerDetailsCustomerFullName">Nom<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control" id="customerDetailsCustomerFullName" name="customerDetailsCustomerFullName">
										</div>
										<div class="form-group col-md-2">
											<label for="customerDetailsStatus">Etat</label>
											<select id="customerDetailsStatus" name="customerDetailsStatus" class="form-control chosenSelect">
												<?php include('inc/statusList.html'); ?>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="customerDetailsCustomerID">Num Client</label>
											<input type="text" class="form-control invTooltip" id="customerDetailsCustomerID" name="customerDetailsCustomerID" title="This will be auto-generated when you add a new customer" autocomplete="off">
											<div id="customerDetailsCustomerIDSuggestionsDiv" class="customListDivWidth"></div>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<label for="customerDetailsCustomerMobile">Telephone<span class="requiredIcon">*</span></label>
											<input type="text" class="form-control invTooltip" id="customerDetailsCustomerMobile" name="customerDetailsCustomerMobile" title="Do not enter leading 0">
										</div>
										<div class="form-group col-md-3">
											<label for="customerDetailsCustomerPhone2">Telephone2</label>
											<input type="text" class="form-control invTooltip" id="customerDetailsCustomerPhone2" name="customerDetailsCustomerPhone2" title="Do not enter leading 0">
										</div>
										<div class="form-group col-md-6">
											<label for="customerDetailsCustomerEmail">Email</label>
											<input type="email" class="form-control" id="customerDetailsCustomerEmail" name="customerDetailsCustomerEmail">
										</div>
									</div>
									<div class="form-group">
										<label for="customerDetailsCustomerAddress">Adresse<span class="requiredIcon">*</span></label>
										<input type="text" class="form-control" id="customerDetailsCustomerAddress" name="customerDetailsCustomerAddress">
									</div>
									<div class="form-group">
										<label for="customerDetailsCustomerAddress2">Adresse 2</label>
										<input type="text" class="form-control" id="customerDetailsCustomerAddress2" name="customerDetailsCustomerAddress2">
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="customerDetailsCustomerCity">Ville</label>
											<input type="text" class="form-control" id="customerDetailsCustomerCity" name="customerDetailsCustomerCity">
										</div>
										<div class="form-group col-md-4">
											<label for="customerDetailsCustomerDistrict">Quartier</label>
											<select id="customerDetailsCustomerDistrict" name="customerDetailsCustomerDistrict" class="form-control chosenSelect">
												<?php include('inc/districtList.html'); ?>
											</select>
										</div>
									</div>
									<button type="button" id="addCustomer" name="addCustomer" class="btn btn-success">Ajouter Client</button>
									<button type="button" id="updateCustomerDetailsButton" class="btn btn-primary">Mettre à jour</button>
									<button type="button" id="deleteCustomerButton" class="btn btn-danger">Supprimer</button>
									<button type="reset" class="btn">Clear</button>
								</form>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Recherche Inventaire<button id="searchTablesRefresh" name="searchTablesRefresh" class="btn btn-warning float-right btn-sm">Rafraîchir</button></div>
							<div class="card-body">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#itemSearchTab">Produit</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#customerSearchTab">Client</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#saleSearchTab">Vente</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#purchaseSearchTab">Achat</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#vendorSearchTab">Fournisseur</a>
									</li>
								</ul>

								<!-- Tab panes -->
								<div class="tab-content">
									<div id="itemSearchTab" class="container-fluid tab-pane active">
										<br>
										<p>Use the grid below to search all details of items</p>
										<!-- <a href="#" class="itemDetailsHover" data-toggle="popover" id="10">wwwee</a> -->
										<div class="table-responsive" id="itemDetailsTableDiv"></div>
									</div>
									<div id="customerSearchTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to search all details of customers</p>
										<div class="table-responsive" id="customerDetailsTableDiv"></div>
									</div>
									<div id="saleSearchTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to search sale details</p>
										<div class="table-responsive" id="saleDetailsTableDiv"></div>
									</div>
									<div id="purchaseSearchTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to search purchase details</p>
										<div class="table-responsive" id="purchaseDetailsTableDiv"></div>
									</div>
									<div id="vendorSearchTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to search vendor details</p>
										<div class="table-responsive" id="vendorDetailsTableDiv"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade show active" id="v-pills-reports" role="tabpanel" aria-labelledby="v-pills-reports-tab">
						<div class="card card-outline-secondary my-4">
							<div class="card-header">Rapports<button id="reportsTablesRefresh" name="reportsTablesRefresh" class="btn btn-warning float-right btn-sm">Rafraîchir</button></div>
							<div class="card-body">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#saleReportsTab">Ventes</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#purchaseReportsTab">Achats</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#itemReportsTab">Produits</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#customerReportsTab">Clients</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#vendorReportsTab">Fournisseurs</a>
									</li>
								</ul>

								<!-- Tab panes for reports sections -->
								<div class="tab-content">
									<div id="itemReportsTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to get reports for items</p>
										<div class="table-responsive" id="itemReportsTableDiv"></div>
									</div>
									<div id="customerReportsTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to get reports for customers</p>
										<div class="table-responsive" id="customerReportsTableDiv"></div>
									</div>
									<div id="saleReportsTab" class="container-fluid tab-pane active">
										<br>
										<!-- <p>Use the grid below to get reports for sales</p> -->
										<form>
											<div class="form-row">
												<div class="form-group col-md-3">
													<label for="saleReportStartDate">Date Debut</label>
													<input type="text" class="form-control datepicker" id="saleReportStartDate" value="" name="saleReportStartDate" readonly>
												</div>
												<div class="form-group col-md-3">
													<label for="saleReportEndDate">Date Fin</label>
													<input type="text" class="form-control datepicker" id="saleReportEndDate" value="" name="saleReportEndDate" readonly>
												</div>
												<div class="form-group col-md-3">
													<label for="customerNameFilter">Client</label>
													<select id="customerNameFilter" name="customerNameFilter" class="form-control chosenSelect">
														<option value=""></option>
														<?php
														require('model/customer/getCustomerNames.php');
														?>
													</select>
												</div>
											</div>
											<button type="button" id="showSaleReport" class="btn btn-dark">Appliquer</button>
											<button type="reset" id="saleFilterClear" class="btn">Clear</button>
										</form>
										<br><br>
										<div class="table-responsive" id="saleReportsTableDiv"></div>
									</div>
									<div id="purchaseReportsTab" class="container-fluid tab-pane fade">
										<br>
										<!-- <p>Use the grid below to get reports for purchases</p> -->
										<form>
											<div class="form-row">
												<div class="form-group col-md-3">
													<label for="purchaseReportStartDate">Date Debut</label>
													<input type="text" class="form-control datepicker" id="purchaseReportStartDate" value="" name="purchaseReportStartDate" readonly>
												</div>
												<div class="form-group col-md-3">
													<label for="purchaseReportEndDate">Date Fin</label>
													<input type="text" class="form-control datepicker" id="purchaseReportEndDate" value="" name="purchaseReportEndDate" readonly>
												</div>
												<div class="form-group col-md-3">
													<label for="vendorNameFilter">Fournisseur</label>
													<select id="vendorNameFilter" name="vendorNameFilter" class="form-control chosenSelect">
														<option value=""></option>
														<?php
														require('model/vendor/getVendorNames.php');
														?>
													</select>
												</div>
											</div>
											<button type="button" id="showPurchaseReport" class="btn btn-dark">Appliquer</button>
											<button type="reset" id="purchaseFilterClear" class="btn">Clear</button>
										</form>
										<br><br>
										<div class="table-responsive" id="purchaseReportsTableDiv"></div>
									</div>
									<div id="vendorReportsTab" class="container-fluid tab-pane fade">
										<br>
										<p>Use the grid below to get reports for vendors</p>
										<div class="table-responsive" id="vendorReportsTableDiv"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	require 'inc/footer.php';
	?>
</body>

</html>