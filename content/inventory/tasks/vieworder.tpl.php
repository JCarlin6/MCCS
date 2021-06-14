<?php 
$OrderID = urldecode($_GET['orderid']);
$ArrayOnOrder = $content->OrderListDetailPending($OrderID); 
//Change to get Inventory with vendor records only
$VendorListArray = $content->getInventoryList(); 
//Determine Status
$Pending = $ArrayOnOrder[0]->Pending;
$Ordered = $ArrayOnOrder[0]->Ordered;
$Denied = $ArrayOnOrder[0]->Denied;
if(!empty($Pending)){
    $Status = "Pending";
} elseif(!empty($Ordered)){
    $Status = "Approved";
    $DisableEntry = "disabled=\"disabled\"";
} elseif(!empty($Denied)){
    $Status = "Denied";
    $DisableEntry = "disabled=\"disabled\"";
}
?>
        
        <!-- Begin Page Content -->
        <div class="container-fluid" onload="ShowViewOrder('856148')">
        <h1 class="h3 mb-2 text-gray-800"><u>Internal Order #: <?php echo $OrderID; ?></u><u style="float:right; color: orange;"><?php echo $Status;?></u></h1>
        <form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
        <input type="text" hidden="hidden" class="form-control" name="InternalOrder" value="<?php echo $OrderID; ?>" >
            <div class="row">
                <div class="col">
                    <label for="Requestor">Requestor:</label>
                    <input type="text" readonly="readonly" class="form-control" name="Requestor" value="<?php echo $ArrayOnOrder[0]->FirstName . " " . $ArrayOnOrder[0]->LastName; ?>" >
                </div>
                <div class="col">
                    <label for="OrderDate">Submitted:</label>
                    <input type="text" readonly="readonly" class="form-control" name="OrderDate" value="<?php echo $ArrayOnOrder[0]->OrderDate; ?>" >
                </div>
                <div class="col">
                    <label for="Vendor">Vendor:</label>
                    <input type="text" readonly="readonly" class="form-control" name="Vendor" value="<?php echo $ArrayOnOrder[0]->Vendor_ID; ?>" >
                </div>
                <div class="col">
                    <label for="Req">REQ #:</label>
                    <input type="text" readonly="readonly" class="form-control" name="Req" value="<?php echo $ArrayOnOrder[0]->ReqNumber; ?>" >
                </div>
                <div class="col">
                    <label for="PurchaseOrder">Purchase Order #:</label>
                    <input type="text" required="required" <?php echo $DisableEntry; ?> <?php echo "onchange=\"UpdatePOHeaderPO($OrderID);\""; ?> class="form-control" name="PONumber" value="<?php echo $ArrayOnOrder[0]->PONumber; ?>" >
                </div>
            </div>
            <h1 style="margin-top: 20px;" class="h4 mb-2 text-gray-800">Part Information:</h1>
            <div class="row" style="margin-top: 15px;">
                <div class="col-2">
                    <label for="InventoryID">Inventory ID:</label>
                </div>
                <div class="col-4">
                    <label for="Description">Description:</label>
                </div>
                <div class="col-2">
                    <label for="QuantityRequested">Quantity:</label>
                </div>
                <div class="col-2">
                    <label for="Taxable">Taxable:</label>
                </div>
                <div class="col-1">
                    <label for="TotalCost">Total Cost:</label>
                </div>
                <div class="col-1">
                    <label for="CPP">Cost Per Part:</label>
                </div>
            </div>
            <div id="PartInformationID">
            <?php
            foreach($ArrayOnOrder AS $Simplify){
                //Turn only InventoryID's into array to check what ones need to be selected if modified
                $Haystack[] = $Simplify->InventoryID;
            }
            foreach($ArrayOnOrder AS $Order){
                unset($Taxed);
                $InventoryID = $Order->InventoryID;
                    echo "<div id=\"InventoryID[$Order->InventoryID]\" class=\"row\" style=\"margin-top: 15px;\">";
                        echo "<div class=\"col-2\">";
                            echo "<input type=\"text\" required=\"required\" $DisableEntry readonly=\"readonly\" class=\"form-control\" name=\"InventoryID\" value=\"$Order->InventoryID\" placeholder=\"Scan Location Code Here...\">";
                        echo "</div>";
                        echo "<div class=\"col-4\">";
                            echo "<input type=\"text\" required=\"required\" $DisableEntry readonly=\"readonly\" class=\"form-control\" name=\"Description\" value=\"$Order->Description\" placeholder=\"Scan Location Code Here...\">";
                        echo "</div>";
                        echo "<div class=\"col-2\">";
                            echo "<input type=\"number\" min=\"1\" required=\"required\" $DisableEntry onchange=\"UpdateOrderPartQty('$InventoryID', '$OrderID');\" class=\"form-control\" name=\"PartQuantity[$InventoryID]\" value=\"$Order->QuantityOrdered\" placeholder=\"xxx\">";
                        echo "</div>";
                        echo "<div class=\"col-2\">";
                            echo "<select type=\"text\" onchange=\"UpdateOrderPartTaxation('$InventoryID', '$OrderID');\" $DisableEntry required=\"required\" class=\"form-control\" name=\"Taxable[$InventoryID]\" placeholder=\"xxx\">";
                            //Need to add search to Javascript to do the same functionality... Currently it will just do detail as opposed to inventory lookup
                            if(($Order->Taxable == "1") OR ($Order->Taxable_Detail == "1")){
                                echo "<option selected=\"selected\" value=\"True\">True</option>";
                                echo "<option value=\"False\">False</option>";
                                $Taxed = "True";
                            } elseif(($Taxed != "True") AND (($Order->Taxable == "2") OR ($Order->Taxable_Detail == "2"))) {
                                echo "<option value=\"True\">True</option>";
                                echo "<option selected=\"selected\" value=\"False\">False</option>";
                            } else {
                                echo "<option disabled=\"disabled\" selected=\"selected\" value=\"\">Required</option>";
                                echo "<option value=\"True\">True</option>";
                                echo "<option value=\"False\">False</option>";
                            }
                            echo "</select>";
                        echo "</div>";
                        echo "<div class=\"col-1\">";
                            echo "<input type=\"number\" step=\".01\" min=\"0\" required=\"required\" $DisableEntry onchange=\"UpdateOrderPartCost('$InventoryID', '$OrderID');\" class=\"form-control\" name=\"PartPrice[$InventoryID]\" value=\"$Order->Cost\" placeholder=\"UKNOWN\">";
                        echo "</div>";
                        echo "<div class=\"col-1\">";
                            echo "<input type=\"text\" disabled=\"disabled\" readonly=\"readonly\" class=\"form-control\" name=\"CPP[$InventoryID]\" value=\"$Order->CostPerPart\" placeholder=\"UKNOWN\">";
                        echo "</div>";
                    echo "</div>";
            }
                ?>
                </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col">
                    <label for="InventoryID">Notes:</label>
                    <textarea id="Notes" class="form-control" <?php echo "onchange=\"UpdateOrderNoteDetail('$OrderID');\"" ?> name="Notes[<?php echo $OrderID; ?>]" value="<?php echo $ArrayOnOrder[0]->Notes; ?>"><?php echo $ArrayOnOrder[0]->Notes; ?></textarea>
                </div>
            </div>
            <?php $UserPermission = $content->UserPermissions();
            if($UserPermission[0]->Level < 4){
                if($Status == "Pending"){
            ?>
                <div class="row" style="margin-top: 20px;">
                    <div class="col">
                        <input type="submit" class="btn btn-danger" style="width:49%; position:relative;" name="OrderDeny" value="Deny">
                        <input type="submit" class="btn btn-success" style="width:49%; float:right; position:relative;" name="OrderApprove" value="Approve">
                    </div>
                </div>
                
                </form>
                <div class="row" style="margin-top: 20px;">
                    <div class="col">
                        <input type="button" class="btn btn-warning" onclick="OrderModify();" style="width:100%; position:relative;" name="OrderModify" value="Add / Remove">
                    </div>
                </div>
                <?php 
                } else {
                    echo "</form>";
                }?>
                <div style="margin-top: 20px; display: none;" id="ShowModifyList">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                <tbody>
                                <?php 
                                foreach($VendorListArray AS $VendorListItem){
                                    $Needle = $VendorListItem->id;
                                    if(in_array($Needle,$Haystack)){
                                        $ItemSelected = True;
                                    }
                                    if($VendorListItem->Active == '1'){
                                        $VendorStatus = 'Active';
                                    } else {
                                        $VendorStatus = 'Inactive';
                                    }
                                    //Create Array for JS send
                                    $JSArray["InventoryID"] = $VendorListItem->id;
                                    $JSArray["Description"] = $VendorListItem->Description;
                                    $JSArray["Vendor_ID"] = $VendorListItem->Vendor_ID;

                                    echo "<tr>";
                                        echo "<td><a href=\"http://10.162.0.40/inventory.php?do=inventory&action=view&partid=$VendorListItem->id\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->id</a></td>";
                                        echo "<td><a href=\"http://10.162.0.40/inventory.php?do=inventory&action=view&partid=$VendorListItem->id\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Description</a></td>";
                                        echo "<td><a href=\"http://10.162.0.40/inventory.php?do=inventory&action=view&partid=$VendorListItem->id\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->Vendor_ID</a></td>";
                                        echo "<td><a href=\"http://10.162.0.40/inventory.php?do=inventory&action=view&partid=$VendorListItem->id\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->QuantityOnHand</a></td>";
                                        echo "<td><a href=\"http://10.162.0.40/inventory.php?do=inventory&action=view&partid=$VendorListItem->id\" style=\"text-decoration:none; color:inherit;\">$VendorListItem->QuantityOnOrder</a></td>";
                                        if($ItemSelected == True){
                                            echo "<td><input type=\"checkbox\" checked=\"checked\" onclick=\"ModifySelectionView('$VendorListItem->id','$VendorListItem->Description','$OrderID');\" id=\"InventorySelection[$VendorListItem->id]\" name=\"InventorySelection\" value=\"$VendorListItem->id\"></td>";
                                        } else {
                                            echo "<td><input type=\"checkbox\" onclick=\"ModifySelectionView('$VendorListItem->id','$VendorListItem->Description','$OrderID');\" id=\"InventorySelection[$VendorListItem->id]\" name=\"InventorySelection\" value=\"$VendorListItem->id\"></td>";    
                                        }
                                    echo "</tr>";
                                    $ItemSelected = False;
                                }
                                ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
            }
            ?>
            <div id="InventoryViewOrder">
                <div class="InventoryViewOrderWrapper">
                </div>
            </div>
        </div>