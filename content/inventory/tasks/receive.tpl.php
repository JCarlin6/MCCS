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
    $StatusColor = "color: orange;";
} elseif(!empty($Ordered)){
    $Status = "Approved";
    $StatusColor = "color: green;";
    $DisableEntry = "disabled=\"disabled\"";
} elseif(!empty($Denied)){
    $Status = "Denied";
    $StatusColor = "color: red;";
    $DisableEntry = "disabled=\"disabled\"";
}
foreach($ArrayOnOrder AS $Order){
    if($Order->QuantityRemaining != '0'){
        $ChooseReceiving = True;
    }
}
if($ChooseReceiving != True){
    $Status = "Fulfilled";
    $StatusColor = "color: blue;";
    $DisableEntry = "disabled=\"disabled\"";
}
?>
        
        <!-- Begin Page Content -->
        <div class="container-fluid" onload="ShowViewOrder('856148')">
        <h1 class="h3 mb-2 text-gray-800"><u>Internal Order #: <?php echo $OrderID; ?></u><u style="float:right; <?php echo $StatusColor; ?>"><?php echo $Status;?></u></h1>
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
                    <textarea id="Notes" class="form-control" readonly="readonly" <?php echo "onchange=\"UpdateOrderNoteDetail('$OrderID');\"" ?> name="Notes[<?php echo $OrderID; ?>]" value="<?php echo $ArrayOnOrder[0]->Notes; ?>"><?php echo $ArrayOnOrder[0]->Notes; ?></textarea>
                </div>
            </div>

          <!-- Page Heading -->
            <?php
            if($ChooseReceiving == True){
            echo "<hr class=\"half-rule\"/>";
            echo "<h1 class=\"h3 mb-2 text-gray-800\"><u>Receive Item(s):</u></h1>";
            echo "<div class=\"row\">";
                echo "<div class=\"col\">";
                    echo "<label for=\"InventoryID\">Inventory ID:</label>";
                echo "</div>";
                echo "<div class=\"col\">";
                    echo "<label for=\"Description\">Description:</label>";
                echo "</div>";
                echo "<div class=\"col\">";
                    echo "<label for=\"QuantityRequested\">Quantity Ordered:</label>";
                echo "</div>";
                echo "<div class=\"col\">";
                    echo "<label for=\"QuantityRemaining\">Quantity to Receive:</label>";
                echo "</div>";
                echo "<div class=\"col\">";
                    echo "<label for=\"LabelsToPrint\"># of Labels to Print</label>";
                echo "</div>";
                echo "<div class=\"col\">";
                    echo "<label for=\"ReceiveLabelPrinted\">&nbsp;</label>";
                echo "</div>";
            echo "</div>";
            $PurchaseOrder = $ArrayOnOrder[0]->PONumber;
            $Vendor = $ArrayOnOrder[0]->Vendor_ID;
            foreach($ArrayOnOrder AS $Order){
                    echo "<div class=\"row\">";
                        echo "<div class=\"col\">";
                            echo "<input type=\"text\" readonly=\"readonly\" class=\"form-control\" name=\"InventoryID\" value=\"$Order->InventoryID\" placeholder=\"Scan Location Code Here...\">";
                        echo "</div>";
                        echo "<div class=\"col\">";
                            echo "<input type=\"text\" readonly=\"readonly\" class=\"form-control\" name=\"Description\" value=\"$Order->Description\" placeholder=\"Scan Location Code Here...\">";
                        echo "</div>";
                        echo "<div class=\"col\">";
                            echo "<input type=\"text\" readonly=\"readonly\" class=\"form-control\" name=\"QuantityRequested\" value=\"$Order->QuantityOrdered\" placeholder=\"xxx\">";
                        echo "</div>";
                        echo "<div class=\"col\">";
                            echo "<input type=\"text\" readonly=\"readonly\" class=\"form-control\" id=\"InventoryIDQ[$Order->InventoryID]\" name=\"QuantityRemaining\" value=\"$Order->QuantityRemaining\" placeholder=\"xxx\">";
                        echo "</div>";
                        echo "<div class=\"col\">";
                            echo "<input type=\"number\" min=\"0\" max=\"$Order->QuantityRemaining\" id=\"CheckMaxValue[$Order->InventoryID]\" class=\"form-control\" name=\"LabelsToPrint[$Order->InventoryID]\" value=\"\">";
                        echo "</div>";
                        echo "<div class=\"col\">";
                            echo "<button type=\"button\" class=\"btn btn-warning\" onclick=\"ReceiveLabelPrint('$Order->InventoryID', '$Order->QuantityRemaining', '$PurchaseOrder', '$Vendor');\" style=\"width:100%;\" name=\"ReceiveLabelPrinted\" value=\"\">Print Label</button>";
                        echo "</div>";
                    echo "</div>";
                    echo "<br />";
            }
            echo "<div class=\"row\">";
                echo "<div class=\"col\">";
                    echo "<button type=\"button\" class=\"btn btn-success\" style=\"width:100%;\" data-toggle=\"modal\" data-target=\"#ReceiveForm\" name=\"ReceiveScanCode\" value=\"\">Receive</button>";
                echo "</div>";
            echo "</div>";
            }
                ?>
                <div class="modal fade" id="ReceiveForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Receive Item(s)</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="alert-scans" class="alert alert-success" style="text-align:center; display: none;"><strong>Barcode scan incorrect...</strong></div>
                        <div id="alert-scans-quantity" class="alert alert-success" style="text-align:center; display: none;"><strong>Quantity higher than what is left to receive...</strong></div>
                        <div class="modal-body mx-3">
                            <div class="md-form text-center">
                                <label style="margin-top:10px;" data-error="wrong" data-success="right" for="ScanLocation">Scan Location</label>
                                <input type="text" id="ScanLocation" size="33" maxlength="33" class="form-control validate">
                            </div>

                            <div class="md-form text-center">
                                <label style="margin-top:10px;" data-error="wrong" data-success="right" for="ScanItem">Scan Item</label>
                                <input type="text" id="ScanItem" size="33" maxlength="33" class="form-control validate">
                            </div>

                            <div class="md-form text-center">
                                <label style="margin-top:10px;" data-error="wrong" data-success="right" for="Quantity">Quantity</label>
                                <input type="number" id="ScanQuantity" class="form-control validate">
                            </div>

                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-success" <?php echo "onclick=\"ReceiveItems($OrderID);\""; ?> style="width:100%;">Submit</button>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
