<?php
$PMID = urldecode($_GET['pmid']);
$UID = $_SESSION["UID"];
$RecentWorkorders = $content->PopulateRecentPMWorkorders($PMID);
$Checklist = $content->PopulatePMChecklist($PMID);
$Information = $content->PopulatePMInformation($PMID);
$Priority = $Information[0]->Priority;
$Repetition = $Information[0]->Repetition;
$Asset = $Information[0]->Asset;
$AssignmentGroup = $Information[0]->UserGroup;
$DateSubmitted = $Information[0]->DateSubmitted;
$DateEdited = $Information[0]->DateLastEdited;
$Submitter = $Information[0]->FirstName . ' ' . $Information[0]->LastName;
$Instruction = $Information[0]->WorkDescription;
$WorkOrderCreation = $Information[0]->NextRunDate;
$Year = substr($WorkOrderCreation, 0, 4);
$Month = substr($WorkOrderCreation, 5, 2);
$Day = substr($WorkOrderCreation, 8, 2);
$DaysToComplete = $Information[0]->DaysToComplete;
$AuthorizationStep = $Information[0]->AuthorizationStep;
$PermissionCheckArray = $content->UserPermissionCheck($UID);
$PermissionCheck = $PermissionCheckArray[0]->Level;
//Determine if Day / Week / Month +
if($Repetition == 1){
    $ContentControl = "Day";
    $ContentFlow = "Daily";
} elseif($Repetition == 2) {
    $ContentControl = "Week";
    $ContentFlow = "Week";
} elseif($Repetition >= 3) {
    $ContentControl = "Month";
    if($Repetition == 3){
        $ContentFlow = "Month";
    } elseif($Repetition == 4) {
        $ContentFlow = "Quarterly";
    } elseif($Repetition == 5) {
        $ContentFlow = "Semi-Annually";
    } elseif($Repetition == 6) {
        $ContentFlow = "Annually";
    }
}
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <form novalidate enctype="multipart/form-data" action="/ajax/controller.php?" method="post">
            <h1 class="h3 mb-2 text-gray-800"><u>Preventative Maintenance Order:</u> <?php echo $PMID; ?> <div style="display:inline;
            <?php 
            if(empty($Information[0]->Deleted)){
                if($AuthorizationStep > '1'){ 
                    echo "color:orange;"; 
                } else { 
                    echo "color:green;"; 
                }
            } else {
                echo "color:red;"; 
            }
            ?>"><?php 
            if(empty($Information[0]->Deleted)){
                if($AuthorizationStep == '1'){ 
                    echo "- Approved"; 
                } elseif($AuthorizationStep == '2') { 
                    echo "- Pending Approval (1)"; 
                } else { 
                    echo "- Pending Approval (2)"; 
                }
            } else {
                echo "- Deleted"; 
            }
            
            ?></div><?php 
            if($AuthorizationStep != '1' OR $PermissionCheck < 3 ){ 
            ?><input type="submit" class="btn btn-warning" style="position:relative; float: right; margin-right: 25px;" onclick="RemoveAssignedUserIFNULL();" name="UpdatePMData" value="Update"><?php } ?></h1>
              <input type="hidden" class="form-control" id="PMID" name="PMID" value="<?php echo $PMID; ?>">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="Priority">Priority</label>
											<select disabled="disabled" class="form-control" required="required" id="Priority" name="Priority">
												<?php 
													$AssignmentTypeArray = $content->PopulateWOPriority();
													foreach($AssignmentTypeArray AS $AssignmentTypeItem){
                                                        $PriorityDesc = $AssignmentTypeItem->CategoryDetail;
                                                        if($Priority == $PriorityDesc){
                                                            echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$PriorityDesc</option>";
                                                        } else { 
                                                            echo "<option value=\"$AssignmentTypeItem->id\">$PriorityDesc</option>";
                                                        }
													}
												?>
											</select>
										</div>
									</div>
                                    <div class="col">
                                        <label for="Asset">Asset</label><br />
                                        <select <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> class="Multi-Select" required="required" name="asset_selected[]" style="width: 100%;">
                                        <?php 
                                            $WOAssetsArray = $content->PopulateWOAssets();
                                            foreach($WOAssetsArray AS $WOAssetItem){
                                                if($WOAssetItem->id == $Asset){
                                                    echo "<option selected=\"selected\" value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";    
                                                } else {
                                                    echo "<option value=\"$WOAssetItem->id\">$WOAssetItem->Facility_Name : $WOAssetItem->Department_Name : $WOAssetItem->Sub_Department_Name : $WOAssetItem->Description</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <?php if($AuthorizationStep == '1' AND $PermissionCheck < 3){
                                        echo "<div class=\"col\">";
                                            echo "<label for=\"Closure\">Status</label><br />";
                                            echo "<select class=\"form-control\" required=\"required\" id=\"Closure\" name=\"Closure\">";
                                                if(!empty($Information[0]->Enabled)){
                                                    echo "<option selected=\"selected\" value=\"1\">Open</option>";
                                                    echo "<option value=\"2\">Closed</option>";
                                                } else {
                                                    echo "<option value=\"1\">Open</option>";
                                                    echo "<option selected=\"selected\" value=\"2\">Closed</option>";
                                                }
                                            echo "</select>";
                                        echo "</div>";
                                    }
                                    ?>
								</div>
								
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Submitted">Date Submitted</label>
                                            <input type="text" disabled="disabled" class="form-control" id="Submitted" name="Submitted" value="<?php echo $DateSubmitted; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Edited">Date Edited</label>
                                            <input type="text" disabled="disabled" class="form-control" id="Edited" name="Edited" value="<?php echo $DateEdited; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Workordercreation">Expected WorkOrder Creation</label>
                                            <input type="text" disabled="disabled" class="form-control" id="Workordercreation" name="Workordercreation" value="<?php echo $WorkOrderCreation; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Submitter">Submitter</label>
                                            <input type="text" disabled="disabled" class="form-control" id="Submitter" name="Submitter" value="<?php echo $Submitter; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
											<label for="AssignedUserInput">Assigned User</label>
											<input type="text" list="AssignedUserList" id="AssignedUserInput" class="form-control" placeholder="**Select User**" name="AssignedUserInput" value="<?php if(!empty($Information[0]->Assignee)){echo $Information[0]->Assigned_Fname . " " . $Information[0]->Assigned_Lname;}?>">
											<datalist id="AssignedUserList">
											<?php
												foreach($content->getUserListWithSelf() AS $UserListItem){
                                                    echo "<option data-value=\"$UserListItem->UID\">$UserListItem->FirstName $UserListItem->LastName</option>";
												}
											?>
											</datalist>
											<?php
											if(is_null($Information[0]->Assignee)){
												echo "<input type=\"hidden\" name=\"AssignedUserInput-hidden\" id=\"AssignedUserInput-hidden\">";
											} else {
												$UserID = $Information[0]->Assignee;
												echo "<input type=\"hidden\" name=\"AssignedUserInput-hidden\" id=\"AssignedUserInput-hidden\" value=\"$UserID\">";
											}
											?>
										</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="AssignmentGroup">Assignment Group</label>
                                            <select <?php if($AuthorizationStep == '1' AND  $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> class="form-control" required="required" id="AssignedGroup" name="AssignedGroup">
                                            <?php
                                                $AssignmentTypeArray = $content->getAssignableGroups();
                                                foreach($AssignmentTypeArray AS $AssignmentTypeItem){
                                                    if($AssignmentGroup == $AssignmentTypeItem->Description){
                                                        echo "<option selected=\"selected\" value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
                                                    } else {
                                                        echo "<option value=\"$AssignmentTypeItem->id\">$AssignmentTypeItem->Description</option>";
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="Repetition">Repetition</label>
                                            <select <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> onchange="DetermineRepetition();" class="form-control" id="RepetitonID" name="Repetition">
                                                <option <?php if($ContentFlow == "Daily"){echo "selected=\"selected\"";} ?> value="1">Daily</option>
                                                <option <?php if($ContentFlow == "Week"){echo "selected=\"selected\"";} ?> value="2">Weekly</option>
                                                <option <?php if($ContentFlow == "Month"){echo "selected=\"selected\"";} ?> value="3">Monthly</option>
                                                <option <?php if($ContentFlow == "Quarterly"){echo "selected=\"selected\"";} ?> value="4">Quarterly</option>
                                                <option <?php if($ContentFlow == "Semi-Annually"){echo "selected=\"selected\"";} ?> value="5">Semi-Annually</option>
                                                <option <?php if($ContentFlow == "Annually"){echo "selected=\"selected\"";} ?> value="6">Annually</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="MonthSwitch" <?php if($ContentControl == "Month"){echo "style=\"display: block;\"";}else{echo "style=\"display: none;\"";} ?>>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="MonthSelected">Start Month</label>
                                                <select <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> class="form-control" name="MonthSelected">
                                                    <option <?php if($Month == "01"){echo "selected=\"selected\"";} ?> value="01">January</option>
                                                    <option <?php if($Month == "02"){echo "selected=\"selected\"";} ?> value="02">February</option>
                                                    <option <?php if($Month == "03"){echo "selected=\"selected\"";} ?> value="03">March</option>
                                                    <option <?php if($Month == "04"){echo "selected=\"selected\"";} ?> value="04">April</option>
                                                    <option <?php if($Month == "05"){echo "selected=\"selected\"";} ?> value="05">May</option>
                                                    <option <?php if($Month == "06"){echo "selected=\"selected\"";} ?> value="06">June</option>
                                                    <option <?php if($Month == "07"){echo "selected=\"selected\"";} ?> value="07">July</option>
                                                    <option <?php if($Month == "08"){echo "selected=\"selected\"";} ?> value="08">August</option>
                                                    <option <?php if($Month == "09"){echo "selected=\"selected\"";} ?> value="09">September</option>
                                                    <option <?php if($Month == "10"){echo "selected=\"selected\"";} ?> value="10">October</option>
                                                    <option <?php if($Month == "11"){echo "selected=\"selected\"";} ?> value="11">November</option>
                                                    <option <?php if($Month == "12"){echo "selected=\"selected\"";} ?> value="12">December</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="MonthSwitchTwo" <?php if($ContentControl == "Month"){echo "style=\"display: block;\"";}else{echo "style=\"display: none;\"";} ?>>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="DayofMonth">Day of Month</label>
                                                <input <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> type="number" class="form-control" id="DayofMonth" name="DayofMonth" min="1" max="28" value="<?php echo $Day; ?>"placeholder="xx">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="YearSwitch" <?php if($ContentControl == "Month"){echo "style=\"display: block;\"";}else{echo "style=\"display: none;\"";} ?>>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="Year">Start Year</label>
                                                <input <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> type="number" class="form-control" id="Year" name="Year" value="<?php echo $Year; ?>" placeholder="xxxx">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="WeekSwitch" <?php if($ContentControl == "Week"){echo "style=\"display: block;\"";}else{echo "style=\"display: none;\"";} ?>>
                                        <div class="col">
                                            <div class="form-group">
                                            <label for="DayofWeek">Day of Week</label>
                                            <select <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> class="form-control" name="DayofWeek" style="width: 100%;">
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
                                    <div id="DaySwitch" <?php if(($ContentControl == "Week") OR ($ContentControl == "Month")){echo "style=\"display: block;\"";}else{echo "style=\"display: none;\"";} ?>>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="DaysToComplete">Days to Complete</label>
                                                <input <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> type="number" class="form-control" id="DaysToComplete" name="DaysToComplete" min="1" placeholder="xx" value="<?php echo $DaysToComplete; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if($AuthorizationStep != '1'){ ?>
                                <input type="button" class="btn btn-success" onclick="AddChecklist()" style="width:100%; position:relative; margin-top:10px; margin-bottom: 20px;" name="AddChecklistItem" value="Add Checklist Item">
                                <?php } ?>
                                <div id="AddWorklistItem">
                                <?php
                                $Counter = 0;
                                if((!empty($Checklist)) AND ($AuthorizationStep == '1' AND $PermissionCheck > 2)){
                                    echo "<div class=\"row\">";
                                        echo "<div class=\"col-1\" style=\"margin-bottom: 15px;\">";
                                            echo "#";
                                        echo "</div>";    
                                        echo "<div class=\"col\" style=\"margin-bottom: 15px;\">";
                                            echo "Checklist Description";
                                        echo "</div>";    
                                    echo "</div>";
                                }
                                foreach($Checklist AS $ChecklistItem){
                                    $Counter++;
                                    echo "<div id=\"ChecklistItem[$Counter]\">";
                                        echo "<div class=\"row\">";
                                            if($AuthorizationStep == '1'){
                                                echo "<div class=\"col-1\" style=\"margin-bottom: 15px;\">";
                                                    echo "<input type=\"text\" disabled=\"disabled\" class=\"form-control\" value=\"$Counter\">";
                                                echo " </div>";
                                            }
                                            if($AuthorizationStep == '1'){
                                                echo "<div class=\"col\" style=\"margin-bottom: 15px;\">";
                                                    echo "<input type=\"text\" class=\"form-control\" disabled=\"disabled\" name=\"workchecklist[]\" value=\"$ChecklistItem->ChecklistDescription\" placeholder=\"Add work instruction...\"> ";
                                                echo "</div>";
                                            } else {
                                                echo "<div class=\"col\" style=\"margin-bottom: 15px;\">";
                                                    echo "<input type=\"text\" class=\"form-control\" required=\"required\" name=\"workchecklist[]\" value=\"$ChecklistItem->ChecklistDescription\" placeholder=\"Add work instruction...\"> ";
                                                echo "</div>";
                                            }
                                            if($AuthorizationStep != '1'){
                                                echo "<div class=\"col-2\" style=\"margin-bottom: 15px;\"> ";
                                                    echo "<button type=\"button\" id=\"ChecklistBtn[$Counter]\" class=\"btn btn-danger\" style=\"width:100%;\" onclick=\"DeleteChecklistItem('$Counter');\" name=\"DeleteChecklistItems\">Delete</button> ";
                                                echo " </div>";
                                            }
                                        echo "</div>";
                                    echo "</div>";
                                }
                                ?>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="WorkInstruction">Work Instruction</label>
                                            <textarea <?php if($AuthorizationStep == '1' AND $PermissionCheck > 2){echo "disabled=\"disabled\"";}?> class="form-control" id="WorkInstruction" name="WorkInstruction" placeholder="What do you want to have done?" rows="3"><?php echo $Instruction; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php if((($AuthorizationStep == '3') AND ($PermissionCheck <= '2')) OR (($AuthorizationStep == '2') AND ($PermissionCheck <= '1'))){ ?>
                                <div class="row">
                                    <div class="col">
                                        <input type="submit" class="btn btn-danger" style="width:100%; position:relative;" name="PMDeny" value="Deny">
                                    </div>
                                    <div class="col">
                                        <input type="submit" class="btn btn-success" style="width:100%; position:relative;" name="PMApprove" value="Approve">
                                    </div>
                                </div>
                                <?php } ?>
							</div>
						</form>
						</div>
	