		<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Create Inventory Item:</u></h1>
						<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="InventoryDescription">Part Name:</label>
											<input type="hidden" class="form-control" name="InventoryID" value="">
											<input type="text" required="required" class="form-control" name="InventoryDescription" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="PartTypeInput">Part Type:</label>
											<?php //echo $PartInformation[0]->Type; ?>
											<input type="text" required="required" list="PartTypeList" id="PartTypeInput" class="form-control" name="PartTypeInput" value="">
											<datalist id="PartTypeList">
											<?php
												foreach($content->InventoryPartTypes() AS $LocationPopulation){
													$ListItem = "$LocationPopulation->Type - $LocationPopulation->Description";
													echo "<option data-value=\"$LocationPopulation->id\">$ListItem</option>";
													unset($ListItem);
												}
											?>
											</datalist>
											<input type="hidden" name="PartTypeInput-hidden" id="PartTypeInput-hidden" value="">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="InventoryClass">Part Class:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"InventoryClass\">";
												echo "<option disabled=\"disabled\" selected=\"selected\">Select an inventory class...</option>";
											foreach($content->getPartClasses() AS $MeasureItem){
												echo "<option value='$MeasureItem->id'>$MeasureItem->Class | $MeasureItem->Description</option>";
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
												echo "<option disabled=\"disabled\" selected=\"selected\">Select a Unit of Measure...</option>";
											foreach($content->getUOM() AS $MeasureItem){
													echo "<option value='$MeasureItem->id'>$MeasureItem->Type</option>";
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
												echo "<option disabled=\"disabled\" selected=\"selected\">Select a Unit of Weight...</option>";
											foreach($content->getUOW() AS $WeightItem){
												echo "<option value='$WeightItem->id'>$WeightItem->Type</option>";
											}
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Weight">Weight:</label>
											<input type="text" class="form-control" name="Weight" value="" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Min">Min:</label>
											<input type="text" class="form-control" name="Min" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Max">Max:</label>
											<input type="text" class="form-control" name="Max" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ROM">Re-Order Method:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"ROM\">";
												echo "<option disabled=\"disabled\" selected=\"selected\">Select a Reorder Method...</option>";
											foreach($content->getROM() AS $ReorderItem){
												echo "<option value='$ReorderItem->id'>$ReorderItem->Type</option>";
											}
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ReorderPoint">Re-Order Point:</label>
											<input type="text" class="form-control" name="ReorderPoint" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ReorderQuantity">Re-Order Quantity:</label>
											<input type="text" class="form-control" name="ReorderQuantity" value="">
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="PODescription">PO Description:</label>
											<input type="text" class="form-control" name="PODescription" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ManufacturerName">Manufacturer Name:</label>
											<input type="text" class="form-control" name="ManufacturerName" value="">

										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ManufacturerID">Manufacturer ID:</label>
											<input type="text" class="form-control" name="ManufacturerID" value="">

										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ManufacturerPartID">Manufacturer Part ID:</label>
											<input type="text" class="form-control" name="ManufacturerPartID" value="">

										</div>
									</div>
								</div>

								<div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="BalanceAccount">Balance Account:</label>
											<input type="text" class="form-control" name="BalanceAccount" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="CreatedBy">Created By:</label>
											<input type="text" disabled="disabled" class="form-control" name="CreatedBy" value="<?php $UserName = explode('.',$_SESSION["Username"]); echo $UserName[0] . " " . $UserName[1]?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="VendorIDInput">Vendor ID:</label>
											<?php //echo $PartInformation[0]->VendorID; ?>
											<input type="text" list="VendorIDList" id="VendorIDInput" class="form-control" name="VendorIDInput" value="">
											<datalist id="VendorIDList">
											<?php
												foreach($content->getVendorID(1) AS $VendorItem){
													$ListItem = "$VendorItem->Vendor_ID|$VendorItem->Name";
													echo "<option data-value=\"$VendorItem->id\">$ListItem</option>";
													unset($ListItem);
												}
											?>
											</datalist>
											<input type="hidden" name="VendorIDInput-hidden" id="VendorIDInput-hidden" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="VendorPartID">Vendor Part ID:</label>
											<input type="text" class="form-control" name="VendorPartID" value="">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="Taxable">Taxable:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"Taxable\">";
												echo "<option disabled=\"disabled\" selected=\"selected\">Nothing Selected</option>";
												echo "<option value='1'>Yes</option>";
												echo "<option value='2'>No</option>";
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Active">Active:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"Active\">";
												echo "<option disabled=\"disabled\" selected=\"selected\">Nothing Selected</option>";
												echo "<option value='1'>Active</option>";
												echo "<option value='2'>inActive</option>";
											echo "</select>";
											?>
										</div>
									</div>
								</div>
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
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Notes">Notes:</label>
											<input type="text" id="Notes" class="form-control" name="Notes" value="">
										</div>
									</div>
								</div>
								</br />
							</div>

							<hr class="half-rule"/>

							<input type="submit" class="btn btn-success" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="CreateInventoryData" value="Save">
						</form>
						</div>
						