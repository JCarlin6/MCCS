<?php

$AuditID = $_GET['auditid']; 
$ContentList = $content->AuditInventoryLocation($AuditID);

?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><u>Audit ID: <?php echo $AuditID; ?></u></h1>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col" style="text-align:center;">Inventory ID</th>
            <th scope="col" style="text-align:center;">Expected Quantity</th>
            <th scope="col" style="text-align:center;">Quantity Scanned</th>
            <th scope="col" style="text-align:center;">Inspector</th>
            <th scope="col" style="text-align:center;">Signed Date</th>
            <th scope="col" style="text-align:center;">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        foreach($ContentList AS $ContentItem){
            echo "<tr>";
                echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'>$ContentItem->InventoryID</a></td>";
                if($ContentItem->Status == '2'){
                    echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'>$ContentItem->QuantityOnHand</td>";
                    echo "<td style='text-align:center;'><div id=\"InspectedQty[$ContentItem->InventoryID]\">$ContentItem->InspectedQty</div></a></td>";
                    echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'> - </a></td>";
                    echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'> - </a></td>";
                    echo "<td style='text-align:center; color: orange;'><a href='#' style='text-decoration:none; color:inherit;'><div id=\"AuditStatus[$ContentItem->InventoryID]\">Unverified</div></a></td>";
                } else {
                    echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'>$ContentItem->QuantityOnHand</a></td>";
                    echo "<td style='text-align:center;'><div id=\"InspectedQty[$ContentItem->InventoryID]\">$ContentItem->InspectedQty</div></td>";
                    echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'>$ContentItem->FirstName $ContentItem->LastName</a></td>";
                    echo "<td style='text-align:center;'><a href='#' style='text-decoration:none; color:inherit;'>$ContentItem->Modified</a></td>";
                    echo "<td style='text-align:center; color: green;'><a href='#' style='text-decoration:none; color:inherit;'><div id=\"AuditStatus[$ContentItem->InventoryID]\">Verified</div></a></td>";
                }

            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">

    <div class="md-form text-center">
        <label style="margin-top:10px;" data-error="wrong" data-success="right" for="ScanLocation">Scan Location</label>
        <input type="text" id="ScanLocation" size="33" maxlength="33" class="form-control validate">
    </div>

    <div class="md-form text-center">
        <label style="margin-top:10px;" data-error="wrong" data-success="right" for="ScanItem">Scan Item</label>
        <input type="text" id="ScanItem" size="33" maxlength="33" class="form-control validate">
    </div>

    <br />                        

    <button type="button" class="btn btn-success" <?php echo "onclick=\"AuditLocation($AuditID);\""; ?> style="width:100%;">Submit</button>
                        
    </form>
					

</div>