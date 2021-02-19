

		<!-- Retrieve VendorList -->
		<?php $VendorListArray = $content->DepartmentByFacilityActive(1); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><u>Asset Location Control</u></h1>
  <p class="mb-4"> - Active</p>

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
			</tr>
		  </thead>
		  <tfoot>
			<tr>
			  <th>Department</th>
			  <th>Sub_Department</th>
			  <th>Status</th>
			</tr>
		  </tfoot>
		  <tbody>
		  <?php 
		  foreach($VendorListArray AS $VendorListItem){
			if($VendorListItem->Active == '1'){
				$VendorStatus = 'Active';
			} else {
				$VendorStatus = 'Inactive';
			}
			echo "<tr>";
				echo "<td><a href=\"vendors.php?do=vendors&action=view&vendorid=$VendorListItem->id \" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Department</a></td>";
				echo "<td><a href=\"vendors.php?do=vendors&action=view&vendorid=$VendorListItem->id \" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Sub_Department</a></td>";
				echo "<td><a href=\"vendors.php?do=vendors&action=view&vendorid=$VendorListItem->id \" style=\"text-decoration:none; color:inherit;\">test</a></td>";
			echo "</tr>";
		  }
		  ?>
		  </tbody>
		</table>
	  </div>
	</div>
  </div>

</div>
<!-- /.container-fluid -->




