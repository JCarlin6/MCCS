<?php
		
		$PartTableID = urldecode($_GET['partid']);
		$PartInformation = $content->InventoryPageInfo($PartTableID);
		
		?>
		
		<!-- Begin Page Content -->
        <div class="container-fluid">
		<div class="row">
			<div class="col">
				<input type="submit" onclick="window.location=`http://10.162.0.40/inventory.php?do=inventory&action=history&partid=<?php echo $PartTableID; ?>`" class="btn btn-info" style="width:100%; margin: auto; margin-bottom: 20px;" value="History">
			</div>
		</div>

          <!-- Page Heading -->
		  <h1 class="h3 mb-2 text-gray-800" style="margin-left: 20px;"><u>Part ID:</u><?php echo " " . $PartTableID; ?>
		  <u style="float: right; color: green; margin-right: 25px;">On-Hand: <?php foreach($content->InventoryLocationbyID($PartTableID) AS $InventoryItemLocation){ $OHTotalItem = $InventoryItemLocation->QuantityOnHand; $TotalOH = $TotalOH + $OHTotalItem;  } echo $TotalOH; ?></u> <u style="float: right; color: orange; margin-right: 25px;">On-Order: <?php echo $PartInformation[0]->OnOrder; ?></u></h1>
						<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="InventoryDescription">Part Name:</label>
											<input type="hidden" class="form-control" name="InventoryID" value="<?php echo $PartTableID; ?>">
											<input type="text" required="required" class="form-control" name="InventoryDescription" value="<?php echo $PartInformation[0]->Description; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="PartTypeInput">Part Type:</label>
											<?php //echo $PartInformation[0]->Type; ?>
											<input type="text" list="PartTypeList" id="PartTypeInput" class="form-control" name="PartTypeInput" value="<?php echo $PartInformation[0]->Type; ?>">
											<datalist id="PartTypeList">
											<?php
												foreach($content->InventoryPartTypes() AS $LocationPopulation){
													$ListItem = "$LocationPopulation->Type - $LocationPopulation->Description";
													echo "<option data-value=\"$LocationPopulation->id\">$ListItem</option>";
													unset($ListItem);
												}
											?>
											</datalist>
											<input type="hidden" name="PartTypeInput-hidden" id="PartTypeInput-hidden" <?php $TypeID = $PartInformation[0]->TypeID; if(!empty($PartInformation[0]->Type)){ echo "value=\"$TypeID\""; } ?>>
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="InventoryClass">Part Class:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"InventoryClass\">";
											if(empty($PartInformation[0]->UnitofMeasure)){
												echo "<option disabled=\"disabled\" selected=\"selected\">Select an inventory class...</option>";
											}
											foreach($content->getPartClasses() AS $MeasureItem){
												if($PartInformation[0]->UnitofMeasure == $MeasureItem->id){
													echo "<option selected=\"selected\" value='$MeasureItem->id'>$MeasureItem->Class | $MeasureItem->Description</option>";
												} else {
													echo "<option value='$MeasureItem->id'>$MeasureItem->Class | $MeasureItem->Description</option>";
												}
											}
											echo "</select>";
											?>
											<?php //echo $PartInformation[0]->PartClass; ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="ModelNo">Unit of Measure:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"UnitofMeasure\">";
											if(empty($PartInformation[0]->UnitofMeasure)){
												echo "<option disabled=\"disabled\" selected=\"selected\">Select a Unit of Measure...</option>";
											}
											foreach($content->getUOM() AS $MeasureItem){
												if($PartInformation[0]->UnitofMeasure == $MeasureItem->id){
													echo "<option selected=\"selected\" value='$MeasureItem->id'>$MeasureItem->Type</option>";
												} else {
													echo "<option value='$MeasureItem->id'>$MeasureItem->Type</option>";
												}
											}
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="UnitofWeight">Unit of Weight:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"UnitofWeight\">";
											if(empty($PartInformation[0]->UnitofWeight)){
												echo "<option disabled=\"disabled\" selected=\"selected\">Select a Unit of Weight...</option>";
											}
											foreach($content->getUOW() AS $WeightItem){
												if($PartInformation[0]->UnitofWeight == $WeightItem->id){
													echo "<option selected=\"selected\" value='$WeightItem->id'>$WeightItem->Type</option>";
												} else {
													echo "<option value='$WeightItem->id'>$WeightItem->Type</option>";
												}
											}
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Weight">Weight:</label>
											<input type="text" class="form-control" name="Weight" value="<?php echo $PartInformation[0]->Weight; ?>" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Min">Min:</label>
											<input type="text" class="form-control" name="Min" value="<?php echo $PartInformation[0]->Min; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Max">Max:</label>
											<input type="text" class="form-control" name="Max" value="<?php echo $PartInformation[0]->Max; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ROM">Re-Order Method:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"ROM\">";
											if(empty($PartInformation[0]->ReorderMethod)){
												echo "<option disabled=\"disabled\" selected=\"selected\">Select a Reorder Method...</option>";
											}
											foreach($content->getROM() AS $ReorderItem){
												if($PartInformation[0]->ReorderMethod == $ReorderItem->id){
													echo "<option selected=\"selected\" value='$ReorderItem->id'>$ReorderItem->Type</option>";
												} else {
													echo "<option value='$ReorderItem->id'>$ReorderItem->Type</option>";
												}
											}
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ReorderPoint">Re-Order Point:</label>
											<input type="text" class="form-control" name="ReorderPoint" value="<?php echo $PartInformation[0]->ReorderPoint; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ReorderQuantity">Re-Order Quantity:</label>
											<input type="text" class="form-control" name="ReorderQuantity" value="<?php echo $PartInformation[0]->ReorderQuantity; ?>">
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="PODescription">PO Description:</label>
											<input type="text" class="form-control" name="PODescription" value="<?php echo $PartInformation[0]->PODescription; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ManufacturerName">Manufacturer Name:</label>
											<input type="text" class="form-control" name="ManufacturerName" value="<?php echo $PartInformation[0]->ManufacturerName; ?>">

										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ManufacturerID">Manufacturer ID:</label>
											<input type="text" class="form-control" name="ManufacturerID" value="<?php echo $PartInformation[0]->MFRID; ?>">

										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ManufacturerPartID">Manufacturer Part ID:</label>
											<input type="text" class="form-control" name="ManufacturerPartID" value="<?php echo $PartInformation[0]->MFRPartID; ?>">

										</div>
									</div>
								</div>

								<div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="BalanceAccount">Balance Account:</label>
											<input type="text" class="form-control" name="BalanceAccount" value="<?php echo $PartInformation[0]->BalanceAccount; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="CreatedBy">Created By:</label>
											<input type="text" disabled="disabled" class="form-control" name="CreatedBy" value="<?php if(empty($PartInformation[0]->CreatedBy)){ echo "Imported"; } else { echo $PartInformation[0]->FirstName . " " . $PartInformation[0]->LastName; } ?>">
										</div>
									</div>

									<div class="col">
                                        <div class="form-group">
											<label for="Taxable">Taxable:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"Taxable\">";
											if($PartInformation[0]->Taxable == '1'){
												echo "<option selected=\"selected\" value='1'>Yes</option>";
												echo "<option value='2'>No</option>";
											} elseif($PartInformation[0]->Taxable == '2') {
												echo "<option selected=\"selected\" value='2'>No</option>";
												echo "<option value='1'>Yes</option>";
											} else {
												echo "<option disabled=\"disabled\" selected=\"selected\">Nothing Selected</option>";
												echo "<option value='1'>Yes</option>";
												echo "<option value='2'>No</option>";
											}
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Active">Active:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"Active\">";
											if($PartInformation[0]->Active == '1'){
												echo "<option selected=\"selected\" value='1'>Active</option>";
												echo "<option value='2'>inActive</option>";
											} elseif($PartInformation[0]->Active == '2') {
												echo "<option selected=\"selected\" value='2'>inActive</option>";
												echo "<option value='1'>Active</option>";
											} else {
												echo "<option disabled=\"disabled\" selected=\"selected\">Nothing Selected</option>";
												echo "<option value='1'>Active</option>";
												echo "<option value='2'>inActive</option>";
											}
											echo "</select>";
											?>
										</div>
									</div>
								</div>
								<hr class="half-rule"/>
								<h1 class="h3 mb-2 text-gray-800"><u>Assigned Vendors</u></h1>
								<div class="row">
									<div class="col-2">
										<div class="form-group">
											<label for="VendorIDInput">Vendor ID:</label>
											<?php //echo $PartInformation[0]->VendorID; ?>
											<input type="text" list="VendorIDList" id="VendorIDInput" class="form-control" name="VendorIDInput">
											<datalist id="VendorIDList">
											<?php
												foreach($content->getVendorID(1) AS $VendorItem){
													$ListItem = "$VendorItem->Vendor_ID|$VendorItem->Name";
													echo "<option data-value=\"$VendorItem->id\">$ListItem</option>";
													unset($ListItem);
												}
											?>
											</datalist>
											<input type="hidden" name="VendorIDInput-hidden" id="VendorIDInput-hidden">
										</div>
									</div>
									<div class="col-2">
										<div class="form-group">
											<label for="VendorPartID">Vendor Part ID:</label>
											<input type="text" class="form-control" id="VendorPartIDInput" name="VendorPartID" value="">
										</div>
									</div>
									<div class="col-2">
										<div class="form-group">
											<label for="SubmitNewVendor"> &nbsp;</label>
											<button type="button" class="btn btn-success" <?php echo "onclick=\"AddInventoryVendor('$PartTableID');\""; ?> style="width:100%;" name="AddInventoryVendors" >Add</button>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
										</div>
									</div>
								</div>
								<div id="VendorListID">
								<?php 
								foreach($content->InventoryVendorList($PartTableID) AS $InventoryItemLocation){
									echo "<div id=\"VendorID[$InventoryItemLocation->id]\">";
										echo "<div class=\"row\">";
											echo "<div class=\"col-2\" style=\"margin-bottom: 15px;\">";
												echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$InventoryItemLocation->Vendor_ID\">";
											echo "</div>";
											echo "<div class=\"col-2\" style=\"margin-bottom: 15px;\">";
												echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$InventoryItemLocation->VendorPartID\">";
											echo "</div>";
											echo "<div class=\"col-2\" style=\"margin-bottom: 15px;\">";
											echo "<button type=\"button\" class=\"btn btn-danger\" style=\"width:100%;\" onclick=\"DeleteInventoryVendor('$InventoryItemLocation->id', '$PartTableID');\" name=\"DeleteInventoryVendors\">Delete</button>";
											echo "</div>";
											//Push out
											echo "<div class=\"col-6\">";
											echo "</div>";
										echo "</div>";
									echo "</div>";
								}
								?>
								</div>
								<hr class="half-rule"/>
								<h1 class="h3 mb-2 text-gray-800"><u>Assigned Locations</u></h1>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Room">Room Assigned:</label>
											<select name="Room" id="Room-list" class="form-control" onChange="getAisle(this.value);">
												<option value="">Select Room</option>
												<option value="29">AFTERFIRE</option>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Aisle">Aisle Assigned:</label>
											<select name="aisle" id="aisle-list" class="form-control" onChange="getColumn(this.value);">
    											<option value="">Select Aisle</option>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Column">Column Assigned:</label>
											<select name="column" id="column-list" class="form-control" onChange="getShelf(this.value);">
												<option value="">Select Column</option>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Shelf">Shelf Assigned:</label>
											<select name="shelf" id="shelf-list" class="form-control">
												<option value="">Select Shelf</option>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Quantity">TOTAL O/H QTY:</label>
											<input type="text" class="form-control" name="QuantityOHInsert" value="">
										</div>
									</div>
									<div class="col-1">
										<div class="form-group text-center">
											<label for="DefaultAction">Default:</label>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group">
										</div>
									</div>
								</div>
								<?php
								foreach($content->InventoryLocationbyID($PartTableID) AS $InventoryItemLocation){
									echo "<div class=\"row\">";
										echo "<div class=\"col\">";
											echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$InventoryItemLocation->Room_Name\">";
										echo "</div>";

										echo "<div class=\"col\">";
											echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$InventoryItemLocation->Aisle_Name\">";
										echo "</div>";

										echo "<div class=\"col\">";
											echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$InventoryItemLocation->Column_Name\">";
										echo "</div>";

										echo "<div class=\"col\">";
											echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$InventoryItemLocation->Shelf_Name\">";
										echo "</div>";

										echo "<div class=\"col\">";
											echo "<input type=\"text\" class=\"form-control\" name=\"OHQty[$InventoryItemLocation->id]\" value=\"$InventoryItemLocation->QuantityOnHand\">";
										echo "</div>";

										echo "<div class=\"col-1 text-center\">";
											if(!empty($InventoryItemLocation->Default)){
												echo "<input type=\"radio\" checked=\"checked\" id=\"DefaultAction\" name=\"DefaultAction\" value=\"$InventoryItemLocation->id\">";
											} else {
												echo "<input type=\"radio\" id=\"DefaultAction\" name=\"DefaultAction\" value=\"$InventoryItemLocation->id\">";
											}
										echo "</div>";

										echo "<div class=\"col-1\">";
											echo "<button type=\"submit\" class=\"btn btn-danger\" style=\"width:100%;\" name=\"DeleteInventoryLocation\" value=\"$InventoryItemLocation->id\">Delete</button>";
										echo "</div>";
									echo "</div>";
									echo "<br />";
								}
								?>
								<hr class="half-rule"/>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Notes">Notes:</label>
											<input type="text" id="Notes" class="form-control" name="Notes" value="<?php echo $PartInformation[0]->Notes; ?>">
										</div>
									</div>
								</div>
								</br />
							</div>

							<hr class="half-rule"/>

							<input type="submit" class="btn btn-success" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="UpdateInventoryData" value="Save">
						</form>
						</div>
						