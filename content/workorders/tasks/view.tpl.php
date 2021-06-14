<?php 
	$VendorListArray = $content->getInventoryListWithLocation(); 
?>
		<?php 
			
			$WorkorderID = urldecode($_GET['workorderid']);
			$WorkorderMainContent = $content->PopulateWOInformation($WorkorderID);
			$WorkorderAssets = $content->PopulateWOInformationAssets($WorkorderID);
			$WorkorderItems = $content->WorkOrderItemsSelected($WorkorderID);
			$WorkorderChecklist = $content->WorkOrderChecklist($WorkorderID);
			//var_dump($WorkorderAssets);

?>
		
		<!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Workorder:</u> <?php echo $WorkorderID; if(empty($WorkorderMainContent[0]->Authorized)){echo "- <div id=\"ApprovalChange\" style='color: orange; display: inline;'>Not Yet Approved"; if($PermissionCheck < 3){ echo "<input type=\"button\" onclick=\"ApproveWO($WorkorderID);\" class=\"approvalbtn btn btn-success\" style=\"float: right; margin-right: 25px; width:100px;\" name=\"WOApprove\" value=\"Approve\">"; echo "<input type=\"button\" onclick=\"DenyWO($WorkorderID);\" class=\"approvalbtn btn btn-warning\" style=\"float: right; margin-right: 15px; width:100px;\" name=\"WODeny\" value=\"Deny\">";} echo "</div>";}elseif($WorkorderMainContent[0]->Authorized == '1'){echo "- <div id=\"ApprovalChange\" style='color: green; display: inline;'>Approved</div>";} else {echo "- <div id=\"ApprovalChange\" style='color: red; display: inline;'>Denied</div>";} ?></h1>
          <p class="mb-4"> - Current Workorders</p>
						<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Requestor">Requestor</label>
											<input type="text" disabled="disabled" class="form-control" id="Requestor" name="Requestor" placeholder="<?php echo $WorkorderMainContent[0]->FirstName . " " . $WorkorderMainContent[0]->LastName; ?>">
											<input id="workorderids" type="hidden" name="workorderid" value="<?php echo $WorkorderID; ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="AssignedUserInput">Assigned User</label>
											<input type="text" list="AssignedUserList" id="AssignedUserInput" class="form-control" placeholder="**Select User**" name="AssignedUserInput" value="<?php echo $WorkorderMainContent[0]->Assigned_Fname . " " . $WorkorderMainContent[0]->Assigned_Lname; ?>">
											<datalist id="AssignedUserList">
											<?php
												foreach($content->getUserListWithSelf() AS $UserListItem){
														echo "<option data-value=\"$UserListItem->UID\">$UserListItem->FirstName $UserListItem->LastName</option>";
												}
											?>
											</datalist>
											<?php
											if(is_null($WorkorderMainContent[0]->Assigned_ID)){
												echo "<input type=\"hidden\" name=\"AssignedUserInput-hidden\" id=\"AssignedUserInput-hidden\">";
											} else {
												$UserID = $WorkorderMainContent[0]->Assigned_ID;
												echo "<input type=\"hidden\" name=\"AssignedUserInput-hidden\" id=\"AssignedUserInput-hidden\" value=\"$UserID\">";
											}
											?>
										</div>
									</div>
									<div class="col">
                                        <div class="form-group">
											<label for="AssignedGroup">Assigned Group</label>
											<select class="form-control" required="required" id="AssignedGroup" name="AssignedGroup">
											<?php 
											if(is_null($WorkorderMainContent[0]->AssignedGroup)){
												echo "<option selected=\"selected\" disabled=\"disabled\">**Select a Group**</option>";
											}
												$AssignmentTypeArray = $content->getAssignableGroups();
												foreach($AssignmentTypeArray AS $AssignmentTypeItem){
													if($WorkorderMainContent[0]->AssignedGroup == $AssignmentTypeItem->id){
														echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
													} else {
														echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
													}
												}
												unset($AssignmentTypeArray,$AssignmentTypeItem);
											?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
                                    <div class="col">
                                        <div class="form-group">
											<label for="AssignmentType">Assignment Type</label>
											<select class="form-control" required="required" id="AssignmentType" name="AssignmentType">
											<?php 
												$AssignmentTypeArray = $content->PopulateWOAssignmentType();
												foreach($AssignmentTypeArray AS $AssignmentTypeItem){
													if($WorkorderMainContent[0]->AssignmentType == $AssignmentTypeItem->id){
														echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
													} elseif(($AssignmentTypeItem->id != '10') AND ($WorkorderMainContent[0]->AssignmentType != '10')) {
														echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
													}
												}
												unset($AssignmentTypeArray,$AssignmentTypeItem);
											?>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Priority">Priority</label>
											<select class="form-control" required="required" id="Priority" name="Priority">
											<?php 
												$AssignmentTypeArray = $content->PopulateWOPriority();
												foreach($AssignmentTypeArray AS $AssignmentTypeItem){
													if($WorkorderMainContent[0]->Priority != '13'){
														if($WorkorderMainContent[0]->Priority == $AssignmentTypeItem->id){
															echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														} elseif($AssignmentTypeItem->id != '13') {
															echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														}
													} elseif($WorkorderMainContent[0]->Priority == '13'){
														echo "<option selected=\"selected\" value=\"13\">Routine</option>";
														break;
													}
												}
												unset($AssignmentTypeArray,$AssignmentTypeItem);
											?>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Status">Status</label>
											<select class="form-control" required="required" id="Status" name="Status">
											<?php 
												$AssignmentTypeArray = $content->PopulateWOStatus();
												if($WorkorderMainContent[0]->Status == NULL){
													echo "<option disabled=\"disabled\" selected=\"selected\">**Choose Status**</option>";
												}
												foreach($AssignmentTypeArray AS $AssignmentTypeItem){
													if(empty($WorkorderMainContent[0]->Authorized) AND (($PermissionCheck < 3) OR ($WorkorderMainContent[0]->UserID == $_SESSION["UID"]))){
														if($WorkorderMainContent[0]->Status == $AssignmentTypeItem->id){
															echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														} else {
															echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														}
													} else {
														if($WorkorderMainContent[0]->Status == $AssignmentTypeItem->id){
															echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														} elseif($AssignmentTypeItem->id != "3") {
															echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														}
													}
												}
												unset($AssignmentTypeArray,$AssignmentTypeItem);
											?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Asset">Selected Asset</label>
											<select class="Multi-Select" required="required" <?php if($PermissionCheck > 3){ echo "disabled=\"disabled\"";} ?> onchange="WOAssetUpdate(<?php echo $WorkorderID; ?>);" name="asset_selected" style="width: 100%;">
												<?php 
												$WOAssetsArray = $content->PopulateWOAssets();
												foreach($WOAssetsArray AS $WOAssetItem){
													if(in_array($WOAssetItem->id,$WorkorderAssets)){
														echo "<option selected=\"selected\" value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
													} else {
														echo "<option value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
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
											<label for="RequestedStartDate">Requested Start Date</label>
											<input type="text" disabled="disabled" class="form-control" id="RequestedStartDate" name="RequestedStartDate" placeholder="<?php echo date("m/d/Y", strtotime($WorkorderMainContent[0]->RequestedStartDate)); ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="RequestedEndDate">Requested End Date</label>
											<input type="text" <?php 
											if(!empty($WorkorderMainContent[0]->Authorized)){ 
												echo "disabled=\"disabled\""; 
											} else {
												if(($PermissionCheck > 2) OR ($WorkorderMainContent[0]->UserID != $_SESSION["UID"])){
													echo "disabled=\"disabled\""; 
												}
											}
												
												?> class="form-control" id="RequestedEndDate" name="RequestedEndDate" placeholder="<?php echo date("m/d/Y", strtotime($WorkorderMainContent[0]->RequestedEndDate)); ?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="ActualEndDate">Actual End Date</label>
											<input type="date" class="form-control" id="ActualEndDate" <?php if($WorkorderMainContent[0]->Authorized != '1'){ echo "disabled=\"disabled\""; }?> name="ActualEndDate" placeholder="" value="<?php if(!empty($WorkorderMainContent[0]->ActualEndDate)){ echo date("Y-m-d", strtotime($WorkorderMainContent[0]->ActualEndDate)); }?>">
										</div>
									</div>
									<div class="col-1">
										<div class="form-group">
											<label for="HoursTaken">Hours Taken</label>
											<input type="number" step=".01" class="form-control" id="HoursTaken" <?php if($WorkorderMainContent[0]->Authorized != '1'){ echo "disabled=\"disabled\""; }?> name="HoursTaken" placeholder="" value="<?php echo $WorkorderMainContent[0]->HoursTaken; ?>">
										</div>
									</div>
                                </div>
						
								<?php if($WorkorderMainContent[0]->Authorized == '1'){ ?>
						
								<h1 class="h3 mb-2 text-gray-800"><u>Parts Applied:</u></h1>



							<div class="row">
								<div class="col-12">
									<label style="margin-top:10px;" data-error="wrong" data-success="right" for="ScanItemCheck">Item Scan</label>
									<input type="text" id="ScanItemCheck" onchange="ScanSearch();" size="33" maxlength="33" class="form-control validate">
								</div>
							</div>
						
                          <div class="card shadow mb-4" style="margin-top: 20px;">
                            <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="getInventoryListWorkorder" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Inventory ID</th>
                                    <th>Description</th>
									<th>Location</th>
                                    <th>Vendor</th>
                                    <th>On-Hand</th>
                                    <th>On-Order</th>
									<th>Qty</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <th>Inventory ID</th>
                                    <th>Description</th>
									<th>Location</th>
                                    <th>Vendor</th>
                                    <th>On-Hand</th>
                                    <th>On-Order</th>
									<th>Qty</th>
                                    </tr>
                                </tfoot>
                                </table>
                            </div>
                            </div>
						</div>
						<?php } ?>
								<!--
                                <div class="row">
									<div class="col">
                                        <label for="parts_selected">Parts Applied</label>
                                        <select multiple="multiple" name="parts_selected[]" id="part_select">
                                            <optgroup label="Bulk Parts">
                                                <option selected>1-1/2" Screws</option>
                                                <option>1" Bolts</option>
                                                <option>1/4" Nuts</option>
                                                <option>1/8" Screws</option>
                                            </optgroup>
                                            <optgroup label="Purchased Parts">
                                                <option>5500 PLC</option>
                                                <option>LG 2210 Scanner</option>
                                                <option>Weld Horn</option>
                                                <option selected>Screw Cap Assembly</option>
                                                <option>WaterJet Head</option>
                                                <option>Glue Gun</option>
                                                <option>Tensile Sensor</option>
                                            </optgroup>
                                        </select>
                                    </div>
								</div>
								-->
	</br />
	<?php
		if($WorkorderMainContent[0]->RequirePhoto == '1'){
	?>
		<div class="custom-file" id="customFile" lang="en">
			<input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload">
			<label class="custom-file-label" for="fileToUpload">
			File Required...
			</label>
		</div>
		<br />
		<br />
	<?php 
	
		if(!empty($WorkorderMainContent[0]->PhotoLocation)){
			$Photo = $WorkorderMainContent[0]->PhotoLocation;
			echo "<div class=\"text-center\">";
			echo "<img src=\"http://10.162.0.40/archive/workorders/$Photo\" style=\"max-height: 400px;\" class=\"img-fluid\" >";
			echo "</div>";
		}

		

	?>
	<div class="text-center">
	<input type="button" onclick="let a= document.createElement('a');a.target= '_blank';a.download='Workorder_<?php echo $WorkorderID; echo '_'; echo $Photo; ?>';a.href= '<?php echo "http://10.162.0.40/archive/workorders/$Photo"; ?>';a.click();" class="btn btn-success" style="position:relative; margin-top: 10px; " value="Download">
	</div>
	<br />
	<br />
	<?php } ?>
	<?php if($WorkorderMainContent[0]->AssignmentType != '10'){?>
                                <div class="row">
									<div class="col">
                                        <div class="form-group">
                                            <label for="IssueDescription">Issue Description</label>
                                            <textarea class="form-control" disabled="disabled" id="IssueDescription" name="IssueDescription" rows="3"><?php echo $WorkorderMainContent[0]->IssueDescription; ?></textarea>
                                        </div>
                                    </div>
								</div>
	<?php } ?>
	<?php 
		foreach($WorkorderChecklist AS $ChecklistItem){
			$Counter++;
			if($Counter == '1'){
				echo "<div class=\"row\">";
					echo "<div class=\"col-1\" style=\"text-align: center;\">";
						echo "<label for=\"Counter\">ITEM #</label>";
					echo "</div>";
					echo "<div class=\"col-7\">";
						echo "<label for=\"Description\">Description</label>";
					echo "</div>";
					if(empty($ChecklistItem->Signer)){
						echo "<div class=\"col-4\">";
							echo "<label for=\"Signed\"></label>";
						echo "</div>";
					} else {
					echo "<div class=\"col-2\">";
						echo "<label for=\"Signed\">Signed</label>";
					echo "</div>";
					echo "<div class=\"col-2\">";
						echo "<label for=\"Dated\">Dated</label>";
					echo "</div>";
					}
				echo "</div>";
			}
			echo "<div class=\"row\" style=\"margin-bottom: 10px;\">";
				echo "<div class=\"col-1\">";
					echo "<input type=\"text\" disabled=\"disabled\" style=\"text-align: center;\" class=\"form-control\" value=\"$Counter\">";
				echo "</div>";
				echo "<div class=\"col-7\">";
					echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$ChecklistItem->ChecklistDescription\">";
				echo "</div>";
				if(empty($ChecklistItem->Signer)){
					echo "<div class=\"col-4\">";
						echo "<input type=\"submit\" class=\"btn btn-success\" style=\"width:50%; position:relative; left:25%;\" name=\"ChecklistItem[$ChecklistItem->id]\" value=\"Mark Complete\">";
					echo "</div>";
				} else {
					echo "<div class=\"col-2\">";
						echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$ChecklistItem->FirstName $ChecklistItem->LastName\">";
					echo "</div>";
					echo "<div class=\"col-2\">";
						echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$ChecklistItem->Modified\">";
					echo "</div>";
				}
			echo "</div>";
		}
?>
								<div class="row">
									<div class="col">
                                        <div class="form-group">
                                            <label for="WorkInstruction">Work Instruction</label>
                                            <textarea class="form-control" disabled="disabled" id="WorkInstruction" name="WorkInstruction" rows="3"><?php echo $WorkorderMainContent[0]->WorkDescription; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
									<div class="col">
                                        <label for="WorkNotes"><b>Work Notes</b></label>
										<?php 
										foreach($content->PopulateWOAssignedUser($WorkorderID) AS $AssignedItemNote){
										echo "<blockquote class=\"blockquote\">";
                                            echo "<p class=\"mb-0\">$AssignedItemNote->WorkNote</p>";
                                            echo "<footer class=\"blockquote-footer\">$AssignedItemNote->FirstName $AssignedItemNote->LastName : $AssignedItemNote->DateLastEdited</footer>";
										echo "</blockquote>";
										}
										?>
                                    </div>
                                </div>
                                <div class="row">
									<div class="col">
                                        <div class="form-group">
                                            <textarea class="form-control" id="WorkNotes" name="WorkNotes" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
							</div>
							<?php if(($PermissionCheck < 3) OR ($WorkorderMainContent[0]->UserID == $_SESSION["UID"])){ ?>
							<input type="submit" class="btn btn-warning" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="UpdateWorkorderData" value="Update Workorder">
							<?php } ?>
						</form>
						</div>
	