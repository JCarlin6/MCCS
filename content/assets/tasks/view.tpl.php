<?php
		
		$AssetTableID = urldecode($_GET['assetid']);
		$AssetInformation = $content->AssetPageInfo($AssetTableID);
		?>
		
		<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Asset ID:</u><?php echo " " . $AssetTableID; ?></h1>
						<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Name">Asset Name:</label>
											<input type="hidden" class="form-control" name="AssetID" value="<?php echo $AssetTableID; ?>">
											<input type="text" required="required" class="form-control" name="Name" value="<?php echo $AssetInformation[0]->Name; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Description">Description:</label>
											<input type="text" required="required" class="form-control" name="Description" value="<?php echo $AssetInformation[0]->Description; ?>">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="AssetClass">Asset Class:</label>
											<input type="text" class="form-control" name="AssetClass" value="<?php echo $AssetInformation[0]->AssetClass; ?>" placeholder="xxx-xxx-xxx">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="ModelNo">Model Number:</label>
											<input type="text" class="form-control" name="ModelNo" value="<?php echo $AssetInformation[0]->ModelNo; ?>" >
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="SerialNo">Serial Number:</label>
											<input type="text" class="form-control" name="SerialNo" value="<?php echo $AssetInformation[0]->SerialNo; ?>" >
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="AssetNo">Asset Number:</label>
											<input type="text" class="form-control" name="AssetNo" value="<?php echo $AssetInformation[0]->AssetNo; ?>" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Notes">Notes:</label>
											<input type="text" id="Notes" class="form-control" name="Notes" value="<?php echo $AssetInformation[0]->Notes; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Priority">Priority:</label>
											<input type="text" class="form-control" name="Priority" value="<?php echo $AssetInformation[0]->Priority; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Status">Status:</label>
											<select required="required" class="form-control" name="Status">
												<option></option>
												<?php 
													if($AssetInformation[0]->Status == '1'){ 
														echo "<option selected=\"selected\" value=\"1\">UP</option>";
														echo "<option value=\"2\">DOWN</option>";
													} elseif(empty($AssetInformation[0]->Status)) {
														echo "<option></option>";
														echo "<option value=\"1\">UP</option>";
														echo "<option value=\"2\">DOWN</option>";
													} else {
														echo "<option value=\"1\">UP</option>";
														echo "<option selected=\"selected\" value=\"2\">DOWN</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="InService">InService:</label>
											<select required="required" class="form-control" name="InService">
											<?php
												if($AssetInformation[0]->InService == '1'){ 
													echo "<option selected=\"selected\" value=\"1\">Yes</option>";
													echo "<option value=\"2\">No</option>";
												} elseif($AssetInformation[0]->InService == '2') {
													echo "<option value=\"1\">Yes</option>";
													echo "<option selected=\"selected\" value=\"2\">No</option>";
												} else {
													echo "<option></option>";
													echo "<option value=\"1\">Yes</option>";
													echo "<option value=\"2\">No</option>";
												}
											?>
											</select>
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="Vendor">Vendor:</label>
											<input type="text" class="form-control" name="Vendor" value="<?php echo $AssetInformation[0]->Vendor; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="CostCenter">CostCenter:</label>
											<input type="text" class="form-control" name="CostCenter" value="<?php echo $AssetInformation[0]->CostCenter; ?>">

										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Active">Active:</label>
											<select required="required" class="form-control" name="Active">
												<?php 
												if(empty($AssetInformation[0]->Active)){
													echo "<option></option>";
												} else {
													if($AssetInformation[0]->Active == '1'){
														echo "<option selected=\"selected\" value=\"1\">Active</option>";
														echo "<option value=\"2\">Inactive</option>";
													} else {
														echo "<option selected=\"selected\" value=\"2\">Inactive</option>";
														echo "<option value=\"1\">Active</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="EndLocationInput"><b>Location Assignment Search: </b><i>*Save to Apply*</i></label>
											<input type="text" list="EndLocationList" id="EndLocationInput" class="form-control" name="EndLocationInput" autocomplete="off" value="">
											<datalist id="EndLocationList">
											<?php
												foreach($content->DepartmentByFacility($AssetInformation[0]->Facility_ID) AS $LocationPopulation){
													$ListItem = "$LocationPopulation->Department █ $LocationPopulation->Sub_Department";
													echo "<option data-value=\"$LocationPopulation->Department_ID, $LocationPopulation->Sub_Department_ID\">$ListItem</option>";
													unset($ListItem);
												}
											?>
											</datalist>
											<input type="hidden" name="EndLocationInput-hidden" id="EndLocationInput-hidden">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Facility">Facility Assigned:</label>
											<input type="text" disabled="disabled" class="form-control" name="Facility_Disabled" value="<?php echo $AssetInformation[0]->Facility_Assigned; ?>">
											<input type="hidden" class="form-control" name="Facility" value="<?php echo $AssetInformation[0]->Facility_ID; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Department">Department Assigned:</label>
											<input type="text" disabled="disabled" class="form-control" name="Department" value="<?php echo $AssetInformation[0]->Department; ?>">
											<input type="hidden" class="form-control" name="Department" value="<?php echo $AssetInformation[0]->Department_ID; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Sub_Department">Sub-Department Assigned:</label>
											<input type="text" disabled="disabled" class="form-control" name="Sub_Department" value="<?php echo $AssetInformation[0]->Sub_Department; ?>">
											<input type="hidden" class="form-control" name="Sub_Department" value="<?php echo $AssetInformation[0]->Sub_Department_ID; ?>">
										</div>
									</div>
								</div>

								</br />
							</div>
							<hr class="half-rule"/>

							<input type="submit" class="btn btn-success" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="UpdateAssetData" value="Save">
						</form>
						</div>
						