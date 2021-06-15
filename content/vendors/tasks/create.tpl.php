		
		<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Vendor ID:</u></h1>
						<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="VendorName">Vendor Code<b>*</b>:</label>
											<input type="hidden" class="form-control" name="VendorID" value="">
											<input type="text" required="required" class="form-control" name="VendorName" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Name">Name<b>*</b>:</label>
											<input type="text" required="required" class="form-control" name="Name" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="PhoneNumber">Phone:</label>
											<input type="text" class="form-control" name="PhoneNumber" value="" placeholder="xxx-xxx-xxx">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="FaxNumber">Fax:</label>
											<input type="text" class="form-control" name="FaxNumber" value="" placeholder="xxx-xxx-xxx">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Street">Street:</label>
											<input type="text" class="form-control" name="Street" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="City">City, State:</label>
											<input type="text" list="Cities" id="City" class="form-control" name="City" value="">
											<input type="hidden" name="City" id="City-hidden">
											<datalist id="Cities">
											<?php
												foreach($content->PopulateCities() AS $CityPopulation){
													//Wait Until Database is transferred to normalize using ID's 
													//echo "<option data-value=\"$CityPopulation->id\">$CityPopulation->City, $CityPopulation->State_Code</option>";
													echo "<option data-value=\"$CityPopulation->City, $CityPopulation->State_Code\">$CityPopulation->City, $CityPopulation->State_Code</option>";
												}
											?>
											</datalist>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Country">Country:<b>*</b></label>
											<input type="text" required="required" class="form-control" name="Country" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Zip">Zip Code:</label>
											<input type="text" class="form-control" name="Zip" value="">
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="Website">Website:</label>
											<input type="text" class="form-control" name="Website" value="">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="Email">Email:</label>
											<input type="text" class="form-control" name="Email" value="">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Type">Type:<b>*</b></label>
											<select class="form-control" required="required" name="NameType">
												<option></option>
												<?php 
													foreach($content->PopulateVendorTypes() AS $VendorType){
														if($VendorInformation[0]->NameType == $VendorType->NameType){
															echo "<option value=\"$VendorType->id\">$VendorType->NameType</option>";
														} else {
															echo "<option value=\"$VendorType->id\">$VendorType->NameType</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Facility">Facility Assigned:</label>
											<input type="hidden" class="form-control" name="Facility" value="1">
											<input type="text" disabled="disabled" class="form-control" name="Facility_Disabled" value="<?php $Facility = $content->ListFacilities(); echo $Facility[0]->Name; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ActiveStatus">Active:<b>*</b></label>
											<select class="form-control" required="required" name="Active">
												<option></option>
												<?php 
													echo "<option value=\"1\">Active</option>";
													echo "<option value=\"0\">Inactive</option>";
												?>
											</select>
										</div>
									</div>
                                </div>

								</br />
							</div>
							<input type="submit" class="btn btn-warning" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="CreateVendorData" value="Create Vendor">
						</form>
						</div>
						