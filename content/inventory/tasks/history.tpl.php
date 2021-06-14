<?php
    $PartTableID = urldecode($_GET['partid']);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"><u>Inventory ID: <?php echo $PartTableID; ?></u></h1>
<p class="mb-4"> - Part History</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="getInventoryTransactionHistory" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Quantity Adjustment</th>
            <th>Employee</th>
            <th>Comments</th>
            <th>Modified Date</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Quantity Adjustment</th>
            <th>Employee</th>
            <th>Comments</th>
            <th>Modified Date</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

</div>
