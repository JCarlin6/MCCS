<!-- Begin Page Content -->
<div class="container-fluid">   
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Withdraw Item(s)</h4>
                </button>
            </div>
            <div id="alert-scans-withdrawnIW" class="alert alert-success" style="text-align:center; display: none;"><strong>Item Withdrawn Succesfully!<strong></div>
            <div id="alert-scansIW" class="alert alert-danger" style="text-align:center; display: none;"><strong>Barcode scan incorrect...</strong></div>
            <div id="alert-scans-quantityIW" class="alert alert-danger" style="text-align:center; display: none;"><strong>Quantity higher than what is expected on-hand...</strong></div>
            <div id="alert-scans-assetIW" class="alert alert-danger" style="text-align:center; display: none;"><strong>Asset needs to be selected...</strong></div>
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
                    <input type="number" id="ScanQuantity" min="0" class="form-control validate">
                </div>
                <br />
                <div class="md-form text-center">
                    <label for="Asset">Selected Asset</label>
                    <select id="IDasset_selected" class="Multi-Select" required="required" <?php if($PermissionCheck > 3){ echo "disabled=\"disabled\"";} ?> onchange="WOAssetUpdate(<?php echo $WorkorderID; ?>);" name="asset_selected" style="width: 100%;">
                        <?php 
                        $WOAssetsArray = $content->PopulateWOAssets();
                        echo "<option selected=\"selected\" disabled=\"disabled\" value=\"0\">Select an Option</option>";
                        foreach($WOAssetsArray AS $WOAssetItem){                            
                                echo "<option value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
                        }
                        ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success" <?php echo "onclick=\"WithdrawItems()\""; ?> style="width:100%;">Withdraw</button>
            </div>
            </div>
        </div>
</div>