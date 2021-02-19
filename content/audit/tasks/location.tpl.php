<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><u>Audit Inventory Location</u></h1>
  <p class="mb-4"> (List contains previous audits) </p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
	<div class="card-body">
	  <div class="table-responsive">
		<table class="table table-bordered" id="getAuditLocationList" width="100%" cellspacing="0">
		  <thead>
			<tr>
				<th>Audit ID</th>
				<th>Location</th>
				<th>Inspector</th>
				<th>Modified</th>
        <th>Status</th>
			</tr>
		  </thead>
		  <tfoot>
			<tr>
        <th>Audit ID</th>
				<th>Location</th>
				<th>Inspector</th>
				<th>Modified</th>
        <th>Status</th>
			</tr>
		  </tfoot>
		</table>
	  </div>
	</div>
  </div>

<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="InventoryLocationInput"><b>Inventory Location: </b></label>
                <input type="text" list="InventoryLocationList" id="InventoryLocationInput" class="form-control" name="InventoryLocationInput" value="">
                <datalist id="InventoryLocationList">
                <?php
                    foreach($content->AuditLocationList() AS $LocationPopulation){
                        $ListItem = "$LocationPopulation->Room █ $LocationPopulation->Aisle █ $LocationPopulation->Column █ $LocationPopulation->Shelf";
                        echo "<option data-value=\"$LocationPopulation->Room_ID,$LocationPopulation->Aisle_ID,$LocationPopulation->Column_ID,$LocationPopulation->Shelf_ID\">$ListItem</option>";
                        unset($ListItem);
                    }
                ?>
                </datalist>
                <input type="hidden" name="InventoryLocationInput-hidden" id="InventoryLocationInput-hidden">
            </div>
        </div>
        <div class="col">
            <input type="submit" class="btn btn-success" style="width:100%; position:relative; margin-top:31px;" name="StartAudit" value="Start Audit">
        </div>
    </div>
</form>
</div>
<!-- /.container-fluid -->