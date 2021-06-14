<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><u>Inventory Control</u></h1>
  <p class="mb-4"> - All Parts</p>
  <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
	<div class="card-body">
	  <div class="table-responsive">
		<table class="table table-bordered" id="getInventoryListAll" width="100%" cellspacing="0">
		  <thead>
			<tr>
			  <th>Part ID</th>
			  <th>Description</th>
			  <th>Model #</th>
			  <th>Type</th>
			  <th>Status</th>
			  <th></th>
			</tr>
		  </thead>
		  <tfoot>
			<tr>
			  <th>Part ID</th>
			  <th>Description</th>
			  <th>Model #</th>
			  <th>Type</th>
			  <th>Status</th>
			  <th></th>
			</tr>
		  </tfoot>
		</table>
	  </div>
	</div>
  </div>
  <input type="submit" class="btn btn-warning" style="float: left; margin-right: 10px;" name="PartInventoryLabelPrint" value="Print Label">
</form>
</div>
<!-- /.container-fluid -->




