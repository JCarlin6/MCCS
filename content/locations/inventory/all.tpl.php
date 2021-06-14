

		<!-- Retrieve VendorList -->
		<?php $VendorListArray = $content->InventoryLocationByFacility(1); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><u>Inventory Location Control</u></h1>
  <p class="mb-4"> - All</p>
<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
  <div class="row">
	<div class="col">
		<input type="text" class="form-control" name="Room" placeholder="Room" value="">
	</div>
	<div class="col">
		<input type="text" class="form-control" name="Aisle" placeholder="Aisle" value="">
	</div>
    <div class="col">
		<input type="text" class="form-control" name="Column" placeholder="Column" value="">
	</div>
	<div class="col">
		<input type="text" class="form-control" name="Shelf" placeholder="Shelf" value="">
	</div>
	<div class="col">
		<input type="submit" class="btn btn-success" style="margin-right: 10px; width:200px;" name="addInventoryLocation" value="Add">
	</div>
</div>
</form>

<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">

<br /><br />
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
	<div class="card-body">
	  <div class="table-responsive">
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		  <thead>
			<tr>
				<th>Room</th>
				<th>Aisle</th>
				<th>Column</th>
				<th>Shelf</th>
				<th></th>
			</tr>
		  </thead>
		  <tfoot>
			<tr>
				<th>Room</th>
				<th>Aisle</th>
				<th>Column</th>
				<th>Shelf</th>
				<th></th>
			</tr>
		  </tfoot>
		  <tbody>
		  <?php 
		  	if (is_array($VendorListArray) || is_object($VendorListArray)){
				foreach($VendorListArray AS $VendorListItem){
					if($VendorListItem->Active == '1'){
						$VendorStatus = 'Active';
					} else {
						$VendorStatus = 'Inactive';
					}
					echo "<tr>";
						echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Room</a></td>";
						echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Aisle</a></td>";
						echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Column</a></td>";
						echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Shelf</a></td>";
						if(!empty($VendorListItem->Room_ID) AND !empty($VendorListItem->Aisle_ID) AND !empty($VendorListItem->Column_ID) AND !empty($VendorListItem->Shelf_ID)){
							echo "<td><input type=\"checkbox\" id=\"ILSSelection\" name=\"ILSSelection[]\" value=\"$VendorListItem->Shelf_ID\"></td>";
						} elseif(!empty($VendorListItem->Room_ID) AND !empty($VendorListItem->Aisle_ID) AND !empty($VendorListItem->Column_ID)){
							echo "<td><input type=\"checkbox\" id=\"ILCSelection\" name=\"ILCSelection[]\" value=\"$VendorListItem->Column_ID\"></td>";
						} elseif(!empty($VendorListItem->Room_ID) AND !empty($VendorListItem->Aisle_ID)){
							echo "<td><input type=\"checkbox\" id=\"ILASelection\" name=\"ILASelection[]\" value=\"$VendorListItem->Aisle_ID\"></td>";
						} elseif(!empty($VendorListItem->Room_ID)){
							echo "<td><input type=\"checkbox\" id=\"ILRSelection\" name=\"ILRSelection[]\" value=\"$VendorListItem->Room_ID\"></td>";
						}
					echo "</tr>";
				}
			}
		  ?>
		  </tbody>
		</table>
	  </div>
	</div>
  </div>
  <div class="row">
		<div class="col">
			<input type="submit" class="btn btn-warning" style="float: left; margin-right: 10px;" name="InventoryLocationLabelPrint" value="Print Label">
			<input type="submit" class="btn btn-danger" style="float: right; margin-right: 10px;" name="InventoryLocationDelete" value="Delete">
			<input type="submit" class="btn btn-warning" style="float: right; margin-right: 10px;" name="InventoryLocationDisable" value="Disable">
			<input type="submit" class="btn btn-success" style="float: right; margin-right: 10px;" name="InventoryLocationActivate" value="Activate">
		</div>
	</div>
	</form>
</div>
<!-- /.container-fluid -->




