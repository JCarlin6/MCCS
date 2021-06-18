
		<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Create Asset:</u></h1>
						<form autocomplete="off" enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Name">Asset Name:</label>
											<input type="hidden" class="form-control" name="AssetID" value="<?php echo $AssetTableID; ?>">
											<input type="text" required="required" class="form-control" name="Name" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Description">Description:</label>
											<input type="text" required="required" class="form-control" name="Description" value="">
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="AssetClass">Asset Class:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"AssetClass\">";
												echo "<option disabled=\"disabled\" selected=\"selected\" value=\"\">Select an Asset class...</option>";
											foreach($content->AssetClassList() AS $MeasureItem){
												echo "<option value='$MeasureItem->id'>$MeasureItem->Name</option>";
											}
											echo "</select>";
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="ModelNo">Model Number:</label>
											<input type="text" class="form-control" name="ModelNo" value="" >
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="SerialNo">Serial Number:</label>
											<input type="text" class="form-control" name="SerialNo" value="" >
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="AssetNo">Asset Number:</label>
											<input type="text" class="form-control" name="AssetNo" value="" >
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
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Priority">Priority:</label>
											<?php
											echo "<select required=\"required\" class=\"form-control\" name=\"Priority\">";
												echo "<option disabled=\"disabled\" selected=\"selected\" value=\"\">Select a Priority...</option>";
												echo "<option value='0'>Low</option>";
												echo "<option value='1'>Medium</option>";
												echo "<option value='2'>High</option>";
												echo "<option value='3'>Critical</option>";
											echo "</select>";
											?>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Status">Status:</label>
											<select required="required" class="form-control" name="Status">
												<option disabled="disabled" selected="selected" value="">Select a Status</option>
												<option value="1">UP</option>
												<option value="2">DOWN</option>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="InService">InService:</label>
											<select required="required" class="form-control" name="InService">
												<option disabled="disabled" selected="selected" value="">Select an Option</option>
												<option value="1">Yes</option>
												<option value="2">No</option>
											</select>
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col">
                                        <div class="form-group">
											<label for="Vendor">Vendor:</label>
											<input type="text" class="form-control" name="Vendor" value="">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="CostCenter">CostCenter:</label>
											<input type="text" class="form-control" name="CostCenter" value="">

										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Active">Active:</label>
											<select required="required" class="form-control" name="Active">
												<option disabled="disabled" selected="selected" value="">Select an Option</option>
												<option value="1">Active</option>
												<option value="2">Inactive</option>
											</select>
										</div>
									</div>
								</div>

								</br />
							</div>
							<hr class="half-rule"/>

							<input type="submit" class="btn btn-warning" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="CreateAssetData" value="Create Asset">
						</form>
						</div>
						