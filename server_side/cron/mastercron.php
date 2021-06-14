<?php
  define("_VALID_PHP", True);
  session_start();
  $_SESSION['loggedin'] = true;
  require_once("../../init.php");
  ini_set('display_errors', 1);

//Check Daily Reports

//Check Weekly Reports

//Check PM's
    //Check for anything (PM's) in Database > Current Time
    $sql="SELECT * FROM Workorder_PM WHERE NextRunDate < NOW() AND Workorder_PM.`Enabled` = '1'";
    $PMArray = content::$db->fetch_all($sql);
    foreach($PMArray AS $PMGenesis){
        $DaysToComplete = $PMGenesis->DaysToComplete;
        $CurrentRunDate = $PMGenesis->NextRunDate;
        $NextRunDate = RepetitionConversion($PMGenesis);
        $RequestedEndDate = EndDateCalculation($PMGenesis, $CurrentRunDate);

        //date('Y-m-d H:i:s', strtotime($NextRunDate . ' +1 day');
        //Create WO
        $NamedArray["AssignmentType"] = "$PMGenesis->AssignmentType";
        $NamedArray["Priority"] = "$PMGenesis->Priority";
        $NamedArray["RequestedStartDate"] = "$CurrentRunDate";
        $NamedArray["RequestedEndDate"] = "$RequestedEndDate";
        $NamedArray["WorkDescription"] = "$PMGenesis->WorkDescription";
        $NamedArray["Requestor"] = "$PMGenesis->Requestor";
        $NamedArray["AssignedGroup"] = "$PMGenesis->AssignedGroup";
        $NotifyingGroup = "$PMGenesis->AssignedGroup";
        $NamedArray["RequirePhoto"] = "$PMGenesis->RequirePhoto";
        $NamedArray["Status"] = "12";
        $NamedArray["DateLastEdited"] = date('Y-m-d H:i:s');
        $NamedArray["DateSubmitted"] = date('Y-m-d H:i:s');
        $NamedArray["Authorized"] = '1';
        $Assignee = $PMGenesis->Assignee;
        if(!empty($Assignee)){
            $NamedArray["AssignedUser"] = $Assignee;
        }

        $SQLEntry = $content->InsertMultipleFields($NamedArray);
        $WorkorderID = content::$db->insert("Workorder_Main", $SQLEntry);

        $Assets["WorkorderID"] = "$WorkorderID";
        $Assets["AssetDetailID"] = "$PMGenesis->Asset";

        $SQLEntry = $content->InsertMultipleFields($Assets);
        content::$db->insert("Workorder_AssetsSelected", $SQLEntry);

        //Insert PM Checklist
            //Check all checklist items and associate with new workorder
            $sql="SELECT * FROM Workorder_PM_Checklist_Header WHERE PM='$PMGenesis->id'";
            $ChecklistArray = content::$db->fetch_all($sql);

            
            //Add to workorder pm table 
            foreach($ChecklistArray AS $ChecklistItem){
                $SecondArray["Workorder"] = "$WorkorderID";
                $SecondArray["PM"] = "$PMGenesis->id";
                $SecondArray["ChecklistItem"] = $ChecklistItem->id;
                $SQLEntry = $content->InsertMultipleFields($SecondArray);
                content::$db->insert("Workorder_PM_Checklist", $SQLEntry);
                unset($SecondArray);
            }

        //Update PM to new date

            unset($NamedArray, $SQLEntry);

            $NamedArray["PM"] = $PMGenesis->id;
            $NamedArray["WO"] = $WorkorderID;

            $SQLEntry = $content->InsertMultipleFields($NamedArray);
            content::$db->insert("Workorder_PM_Workorder_Hist", $SQLEntry);

            unset($NamedArray, $SQLEntry);

            $sql = "UPDATE MCCS.Workorder_PM SET NextRunDate = '" . $NextRunDate . "' WHERE `id`='" . $PMGenesis->id . "'";
            content::$db->query($sql);
            echo "Success!";

        //Email notifying of created workorder
            if(empty($Assignee)){
                //Notify Levels 3 and 4 *perhaps add to db one day
                if($NotifyingGroup == '6'){
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '4'; //Maintenance Operators
                } elseif($NotifyingGroup == '7') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '5';
                } elseif($NotifyingGroup == '10') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '10';
                } elseif($NotifyingGroup == '11') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '18';
                } elseif($NotifyingGroup == '12') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '11';
                } elseif($NotifyingGroup == '13') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '12';
                } elseif($NotifyingGroup == '14') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '13';
                } elseif($NotifyingGroup == '15') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '14';
                } elseif($NotifyingGroup == '16') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '15';
                } elseif($NotifyingGroup == '19') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '16';
                } elseif($NotifyingGroup == '18') {
                    $Notify[] = '3'; //Crib Attendants
                } elseif($NotifyingGroup == '17') {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '17';
                } else {
                    $Notify[] = '3'; //Crib Attendants
                    $Notify[] = '4'; //Maintenance Operators
                }
                //Iterate through notifying groups and ascertain emails...
                $ToList = $content->MailingSQLIterate($Notify);
            } else {
                $ToList = $content->MailingSQLIterateUser($Assignee);
            }

            $body = " Team, <br /><br /> A WorkOrder has been automatically generated for PM: $PMGenesis->id under WorkOrder: $WorkorderID. <br /><br /> Please visit the referenced WorkOrder by clicking this <a href=\"http://10.162.0.40/default.php?do=workorders&action=view&workorderid=$WorkorderID\">link</a><br /><br />  Thank you. ";
            $body = $content->MailNotice($body);
            $MailSubject = "System WorkOrder Generated";
            $content->InternaltoExternalMailCall($body, $MailSubject, $ToList);
            unset($Assignee, $Notify);
    }

    function EndDateCalculation($PMGenesis, $CurrentRunDate){
        $RepetitionCheck = $PMGenesis->Repetition;
        $DayCheck = $PMGenesis->DaysToComplete;
        if($RepetitionCheck == '1'){
            //Daily
            $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +1 day'));
        } elseif($RepetitionCheck == '2'){
            //Weekly
            if($DayCheck < 8){
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +7 day'));
            } else {
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        } elseif($RepetitionCheck == '3'){
            //Monthly
            if($DayCheck < 28){
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +1 month'));
            } else {
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        } elseif($RepetitionCheck == '4'){
            //Quarterly
            if($DayCheck < 84){
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +3 month'));
            } else {
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        } elseif($RepetitionCheck == '5'){
            //Semi-Annually
            if($DayCheck < 168){
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +6 month'));
            } else {
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        } elseif($RepetitionCheck == '6'){
            //Annually
            if($DayCheck < 336){
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +1 year'));
            } else {
                $RequestedEndDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        }
        return $RequestedEndDate;
    }

    function RepetitionConversion($PMGenesis){
        $RepetitionCheck = $PMGenesis->Repetition;
        $CurrentRunDate = $PMGenesis->NextRunDate;
        $DayCheck = $PMGenesis->DaysToComplete;
        if($RepetitionCheck == '1'){
            //Daily
            $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +1 day'));
        } elseif($RepetitionCheck == '2'){
            //Weekly
            if($DayCheck < 8){
                $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +7 day'));
            } else {
                $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        } elseif($RepetitionCheck == '3'){
            //Monthly
            if($DayCheck < 28){
                $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +1 month'));
            } else {
                $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        } elseif($RepetitionCheck == '4'){
            //Quarterly
            $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +3 month'));
        } elseif($RepetitionCheck == '5'){
            //Semi-Annually
            $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +6 month'));
        } elseif($RepetitionCheck == '6'){
            //Annually
            if($DayCheck < 336){
                $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . ' +1 year'));
            } else {
                $NextRunDate = date('Y-m-d H:i:s', strtotime($CurrentRunDate . " +$DayCheck day"));
            }
        }
        return $NextRunDate;
    }

?>