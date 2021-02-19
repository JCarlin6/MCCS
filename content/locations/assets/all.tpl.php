

<!-- Retrieve VendorList -->
<?php $VendorListArray = $content->DepartmentByFacility(1); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><u>Asset Location Control</u></h1>
  <p class="mb-4"> - All</p>
<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
	<div class="row">
		<div class="col">
			<input type="text" required="required" class="form-control" name="Department" placeholder="Department" value="">
		</div>
		<div class="col">
			<input type="text" class="form-control" name="Sub_Department" placeholder="Sub_Department" value="">
		</div>
		<div class="col">
			<input type="submit" class="btn btn-success" style="margin-right: 10px; width:200px;" name="AddAssetLocation" value="Add">
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
			  <th>Department</th>
			  <th>Sub_Department</th>
			  <th>Status</th>
			  <th></th>
			</tr>
		  </thead>
		  <tfoot>
			<tr>
			  <th>Department</th>
			  <th>Sub_Department</th>
			  <th>Status</th>
			  <th></th>
			</tr>
		  </tfoot>
		  <tbody>
		  <?php 
		  $DepartmentCheck = 0;
		  if($VendorListArray != "0"){
		  foreach($VendorListArray AS $VendorListItem){
			if($VendorListItem->Disabled == '1'){
				$VendorStatus = 'Disabled';
			} else {
				$VendorStatus = 'Active';
			}

			if($DepartmentCheck != $VendorListItem->Department_ID){
				echo "<tr>";
					echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Department</a></td>";
					echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\"> - </a></td>";
					echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\"> - </a></td>";
					echo "<td><input type=\"checkbox\" id=\"ALDepartmentSelection\" name=\"ALDepartmentSelection[]\" value=\"$VendorListItem->Department_ID\"></td>";
				echo "</tr>";
			}

			$DepartmentCheck = $VendorListItem->Department_ID;

			echo "<tr>";
			echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Department</a></td>";
			echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Sub_Department</a></td>";
			echo "<td><a href=\"\" style=\"text-decoration:none; color:inherit;\">$VendorStatus</a></td>";
			echo "<td><input type=\"checkbox\" id=\"ALSelection\" name=\"ALSelection[]\" value=\"$VendorListItem->Sub_Department_ID\"></td>";
			echo "</tr>";
		  }}
		  ?>
		  </tbody>
		</table>
	  </div>
	</div>
  </div>
	<div class="row">
		<div class="col">
			<input type="submit" class="btn btn-danger" style="float: right; margin-right: 10px;" name="AssetLocationDelete" value="Delete">
			<input type="submit" class="btn btn-warning" style="float: right; margin-right: 10px;" name="AssetLocationDisable" value="Disable">
			<input type="submit" class="btn btn-success" style="float: right; margin-right: 10px;" name="AssetLocationActivate" value="Activate">
		</div>
	</div>

</form>

</div>
<!-- /.container-fluid -->




