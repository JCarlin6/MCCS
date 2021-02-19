        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><u>Workorders</u></h1>
          <p class="mb-4"> - Current Workorders</p>
		  	<form enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
			<input type="hidden" class="form-control" id="UID" name="UID" value="<?php echo $_SESSION["UID"]; ?>">
							<div class="container-fluid">
								<div class="row">
                                    <div class="col">
                                        <div class="form-group">
											<label for="AssignmentType">Assignment Type</label>
											<select onchange="ExpandWorkOrderFields();" class="form-control" required="required" id="AssignmentType" name="AssignmentType">
												<option disabled="disabled" selected="selected">**Choose Assignment Type**</option>
												<?php 
													$AssignmentTypeArray = $content->PopulateWOAssignmentType();
													foreach($AssignmentTypeArray AS $AssignmentTypeItem){
														echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label for="Priority">Priority</label>
											<select class="form-control" required="required" id="Priority" name="Priority">
												<option disabled="disabled" selected="selected">**Choose Priority**</option>
												<?php 
													$AssignmentTypeArray = $content->PopulateWOPriority();
													foreach($AssignmentTypeArray AS $AssignmentTypeItem){
														if($AssignmentTypeItem->id != '13'){
															echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->CategoryDetail</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<!--
								<div class="row">
									<div class="col">
										<div class="form-group">
											<select multiple="multiple" required="required" name="asset_selected[]" id="asset_select">
												<?php /*
												$WOAssetsArray = $content->PopulateWOAssets();
												$OptGroupCount = 0;
												foreach($WOAssetsArray AS $WOAssetItem){
													if($WOAssetItem->Class_Name != $LastClassName){
														if($OptGroupCount == 1){
															echo "</optgroup>";
															$OptGroupCount = 0;
														}
														echo "<optgroup label=\"$WOAssetItem->Class_Name\">";
														$LastClassName = $WOAssetItem->Class_Name;
														$OptGroupCount++;
													}
													echo "<option value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
												} */
												?>
												</optgroup>

											</select> 
										</div>
									</div>
                                </div>
								-->
								<div id="DeadSwitch">
									<div class="row">
										<div class="col">
											<label for="Asset">Asset</label><br />
											<select class="Multi-Select" required="required" name="WOasset_selected" style="width: 100%;">
											<?php 
												$WOAssetsArray = $content->PopulateWOAssets();
												foreach($WOAssetsArray AS $WOAssetItem){
													echo "<option value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
												}
											?>
											</select>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="RequestedStartDate">Requested Start Date</label>
												<input type="date" class="form-control" id="RequestedStartDate" name="RequestedStartDate" min="<?php echo date("Y-m-d"); ?>" placeholder="6/16/2020">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="RequestedEndDate">Requested End Date</label>
												<input type="date" class="form-control" id="RequestedEndDate" name="RequestedEndDate" min="<?php echo date("Y-m-d"); ?>" placeholder="7/04/2020">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="WOAssignedGroup">Assigned Group</label>
												<select class="form-control" required="required" id="WOAssignedGroup" name="WOAssignedGroup">
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
												<label for="IssueDescription">Issue</label>
												<textarea class="form-control" id="IssueDescription" name="IssueDescription" placeholder="What is the problem you are experiencing?" rows="3"></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<div class="form-group">
												<label for="WorkInstruction">Work Instruction</label>
												<textarea class="form-control" id="WorkInstruction" name="WorkInstruction" placeholder="What do you want to have done?" rows="3"></textarea>
											</div>
										</div>
									</div>
									<input type="submit" class="btn btn-warning" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="CreateWorkorderData" value="Create Workorder">
								</div>
								<div id="DeadSwitchPM" style="display: none;">
									<div class="row">
										<div class="col">
											<label for="Asset">Asset</label><br />
											<select class="Multi-Select" name="PMasset_selected" style="width: 100%;">
											<?php 
												$WOAssetsArray = $content->PopulateWOAssets();
												foreach($WOAssetsArray AS $WOAssetItem){
													echo "<option value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
												}
											?>
											</select>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="Repetition">Repetition</label>
												<select onchange="DetermineRepetition();" class="form-control" id="RepetitonID" name="Repetition">
													<option value="1">Daily</option>
													<option value="2">Weekly</option>
													<option value="3">Monthly</option>
													<option value="4">Quarterly</option>
													<option value="5">Semi-Annually</option>
													<option value="6">Annually</option>
												</select>
											</div>
										</div>
										<div id="MonthSwitch" style="display: none;">
											<div class="col">
												<div class="form-group">
													<label for="MonthSelected">Start Month</label>
													<select class="form-control" name="MonthSelected">
														<option value="01">January</option>
														<option value="02">February</option>
														<option value="03">March</option>
														<option value="04">April</option>
														<option value="05">May</option>
														<option value="06">June</option>
														<option value="07">July</option>
														<option value="08">August</option>
														<option value="09">September</option>
														<option value="10">October</option>
														<option value="11">November</option>
														<option value="12">December</option>
													</select>
												</div>
											</div>
										</div>
										<div id="MonthSwitchTwo" style="display: none;">
											<div class="col">
												<div class="form-group">
													<label for="DayofMonth">Day of Month</label>
													<input type="number" class="form-control" id="DayofMonth" name="DayofMonth" min="1" max="28" placeholder="xx">
												</div>
											</div>
										</div>
										<div id="YearSwitch" style="display: none;">
											<div class="col">
												<div class="form-group">
													<label for="Year">Start Year</label>
													<input type="number" class="form-control" id="Year" name="Year" placeholder="xxxx">
												</div>
											</div>
										</div>
										<div id="WeekSwitch" style="display: none;">
											<div class="col">
												<div class="form-group">
												<label for="DayofWeek">Day of Week</label>
												<select class="form-control" name="DayofWeek" style="width: 100%;">
													<option value="1">Monday</option>
													<option value="2">Tuesday</option>
													<option value="3">Wednesday</option>
													<option value="4">Thursday</option>
													<option value="5">Friday</option>
													<option value="6">Saturday</option>
													<option value="7">Sunday</option>
												</select>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div id="DaySwitch" style="display: none;">
											<div class="col">
												<div class="form-group">
													<label for="DaysToComplete">Days to Complete</label>
													<input type="number" class="form-control" id="DaysToComplete" name="DaysToComplete" min="1" placeholder="xx">
												</div>
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="AssignmentGroup">Assignment Group</label>
												<select class="form-control" id="AssignedGroup" name="AssignedGroup">
												<?php
													$AssignmentTypeArray = $content->getAssignableGroups();
													foreach($AssignmentTypeArray AS $AssignmentTypeItem){
														echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
													}
												?>
												</select>
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="PhotoRequired">Finished Photo</label>
												<select class="form-control" id="PhotoRequired" name="PhotoRequired">
													<option disabled="disabled" selected="selected">**Finished Photo Required?**</option>
													<option value="1">YES</option>
													<option value="2">NO</option>
												</select>
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
													echo "<input type=\"hidden\" name=\"AssignedUserInput-hidden\" id=\"AssignedUserInput-hidden\">";
												?>
												</datalist>
											</div>
										</div>
									</div>
									
									<input type="button" class="btn btn-success" onclick="AddChecklist()" style="width:100%; position:relative; margin-top:10px; margin-bottom: 20px;" name="AddChecklistItem" value="Add Checklist Item">
									<div id="AddWorklistItem">
									</div>

									<div class="row">
										<div class="col">
											<div class="form-group">
												<label for="PMWorkInstruction">Work Instruction</label>
												<textarea class="form-control" id="PMWorkInstruction" name="PMWorkInstruction" placeholder="What do you want to have done?" rows="3"></textarea>
											</div>
										</div>
									</div>
									<input type="submit" class="btn btn-warning" style="width:50%; position:relative; margin-top:20px; top:50%; left:25%;" name="CreatePMData" value="Request Preventative Maintenance">
								</div>
							</div>
						</form>
						</div>
	