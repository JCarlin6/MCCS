  <!-- Begin Page Content -->
  <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800"><u>Order Part(s):</u></h1>
        <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
            <div class="card shadow mb-4">
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="getInventoryList" width="100%" cellspacing="0">
                <thead>
                    <tr>
                    <th>Inventory ID</th>
                    <th>Description</th>
                    <th>Vendor</th>
                    <th>On-Hand</th>
                    <th>On-Order</th>
                    <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <th>Inventory ID</th>
                    <th>Description</th>
                    <th>Vendor</th>
                    <th>On-Hand</th>
                    <th>On-Order</th>
                    <th></th>
                    </tr>
                </tfoot>

                </table>
            </div>
            </div>
        </div>
        <div class="container-fluid">
        <br />
        </div>
        <div class="field_wrapper">
        </div>
        <div id="order_field_wrapper">
        </div>
        <datalist id="VendorIDList">
            <?php
                foreach($content->getVendorID(1) AS $VendorItem){
                    $ListItem = "$VendorItem->Vendor_ID|$VendorItem->Name";
                    echo "<option data-value=\"$VendorItem->id\">$ListItem</option>";
                    unset($ListItem);
                }
            ?>
        </datalist>
        <hr class="half-rule"/>
    </form>
    <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
    </div>