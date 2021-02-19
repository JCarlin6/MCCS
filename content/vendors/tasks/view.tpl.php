		<?php
		
		$VendorTableID = urldecode($_GET['vendorid']);
		$VendorInformation = $content->VendorPageInfo($VendorTableID);
		
		?>
		
		<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Vendor ID:</u><?php echo " " . $VendorTableID; ?></h1>
						<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="VendorName">Vendor Code:</label>
											<input type="hidden" class="form-control" name="VendorID" value="<?php echo $VendorTableID; ?>">
											<input type="text" required="required" class="form-control" name="VendorName" value="<?php echo $VendorInformation[0]->Vendor_ID; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Name">Name:</label>
											<input type="text" required="required" class="form-control" name="Name" value="<?php echo $VendorInformation[0]->Name; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="PhoneNumber">Phone:</label>
											<input type="text" class="form-control" name="PhoneNumber" value="<?php echo $VendorInformation[0]->Phone; ?>" placeholder="xxx-xxx-xxx">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="FaxNumber">Fax:</label>
											<input type="text" class="form-control" name="FaxNumber" value="<?php echo $VendorInformation[0]->Fax; ?>" placeholder="xxx-xxx-xxx">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Street">Street:</label>
											<input type="text" class="form-control" name="Street" value="<?php echo $VendorInformation[0]->Street; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="City">City, State:</label>
											<input type="text" list="Cities" id="City" class="form-control" name="City" value="<?php echo $VendorInformation[0]->City; if(!empty($VendorInformation[0]->City)){ echo ", ";} echo $VendorInformation[0]->State; ?>">
											<datalist id="Cities">
											<?php
												foreach($content->PopulateCities() AS $CityPopulation){
													//Wait Until Database is transferred to normalize using ID's 
													//echo "<option data-value=\"$CityPopulation->id\">$CityPopulation->City, $CityPopulation->State_Code</option>";
													$CityStateOption = "$CityPopulation->City, $CityPopulation->State_Code";
													$CityState = $VendorInformation[0]->City . ", " . $VendorInformation[0]->State;
													if($CityStateOption == $CityState){
														echo "<option selected=\"selected\" data-value=\"$CityStateOption\">$CityStateOption</option>";
													} else {
														echo "<option data-value=\"$CityStateOption\">$CityStateOption</option>";
													}
													unset($CityStateOption);
												}
											?>
											</datalist>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Country">Country:</label>
											<input type="text" required="required" class="form-control" name="Country" value="<?php echo $VendorInformation[0]->Country; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Zip">Zip Code:</label>
											<input type="text" class="form-control" name="Zip" value="<?php echo $VendorInformation[0]->ZipCode; ?>">
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="Website">Website:</label>
											<input type="text" class="form-control" name="Website" value="<?php echo $VendorInformation[0]->Website; ?>">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="Email">Email:</label>
											<input type="text" class="form-control" name="Email" value="<?php echo $VendorInformation[0]->Email; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Type">Type:</label>
											<select class="form-control" name="NameType">
												<?php 
												if(empty($VendorInformation[0]->NameType)){
													echo "<option></option>";
												} else {
													foreach($content->PopulateVendorTypes() AS $VendorType){
														if($VendorInformation[0]->NameType == $VendorType->NameType){
															echo "<option selected=\"selected\" value=\"$VendorType->id\">$VendorType->NameType</option>";
														} else {
															echo "<option value=\"$VendorType->id\">$VendorType->NameType</option>";
														}
													}
												}
												?>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Facility">Facility Assigned:</label>
											<input type="hidden" class="form-control" name="Facility" value="<?php echo $VendorInformation[0]->Facility_Assigned; ?>">
											<input type="text" disabled="disabled" class="form-control" name="Facility_Disabled" value="<?php echo $VendorInformation[0]->Facility_Assigned; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ActiveStatus">Active:</label>
											<select class="form-control" name="Active">
												<?php 
												if(empty($VendorInformation[0]->Active)){
													echo "<option></option>";
												} else {
													if($VendorInformation[0]->Active == 'Active'){
														echo "<option selected=\"selected\" value=\"1\">Active</option>";
														echo "<option value=\"0\">Inactive</option>";
													} else {
														echo "<option selected=\"selected\" value=\"0\">Inactive</option>";
														echo "<option value=\"1\">Active</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
                                </div>

								</br />
							</div>
							<input type="submit" class="btn btn-success" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="UpdateVendorData" value="Save">
						</form>
						</div>
						