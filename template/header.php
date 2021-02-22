<?php 

if (!isset($_SESSION['UID'])) {
  session_start();
}

  //session_start();  
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Antolin Howell MCS</title>

  <link href="../css/select2.min.css" rel="stylesheet" />
  <script src="../js/select2.min.js"></script> 
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="css/multi.min.css" />
  <script src="js/multi.min.js"></script>

  <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.3.1/css/buttons.bootstrap4.min.css"/>
<!-- 
  Potentially add charts
  <script type="text/javascript" src="../vendor/chart.js/Chart.min.js"></script> 
-->
</head>
<?php
  if(isset($_GET['msg'])){
    $AlertBar = urldecode($_GET['msg']);
    if ($AlertBar != NULL){
      //Vendor Messages
      if ($AlertBar == 'UserAdded'){ $MSGHeader = 'Success!'; $MSGText = 'User has been successfully added into the maintenance system.'; $ErrorType = "success";}
      if ($AlertBar == 'UpdatedVendor'){ $MSGHeader = 'Success!'; $MSGText = 'Vendor has been successfully updated with the latest information.'; $ErrorType = "success";}
      if ($AlertBar == 'VendorCreated'){ $MSGHeader = 'Success!'; $MSGText = 'Vendor has been successfully created.'; $ErrorType = "success";}
      if ($AlertBar == 'WorkorderSubmitted'){ $MSGHeader = 'Success!'; $MSGText = 'Workorder has been successfully submitted!'; $ErrorType = "success";}
      if ($AlertBar == 'WorkorderCannotCloseWithoutChecklist'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder cannot be closed without checklist having been signed!'; $ErrorType = "danger";}
      if ($AlertBar == 'WorkorderCannotCloseWithoutPhoto'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder cannot be closed without photo attached!'; $ErrorType = "danger";}
      if ($AlertBar == 'WorkorderCannotCloseWithoutEndDate'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder cannot be closed without end date!'; $ErrorType = "danger";}
      if ($AlertBar == 'WorkInstructionMissing'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder Instructions are missing!'; $ErrorType = "danger";}
      if ($AlertBar == 'IssueDescriptionMissing'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder work description is missing!'; $ErrorType = "danger";}
      if ($AlertBar == 'AssetMissing'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder asset missing!'; $ErrorType = "danger";}
      if ($AlertBar == 'AssignedGroupMissing'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder assigned group missing!'; $ErrorType = "danger";}
      if ($AlertBar == 'RequestedEndDateMissing'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder requested end date missing!'; $ErrorType = "danger";}
      if ($AlertBar == 'WorkorderCannotCloseWithoutHours'){ $MSGHeader = 'Failure!'; $MSGText = 'Workorder cannot close with time spent missing!'; $ErrorType = "danger";}
      if ($AlertBar == 'AssetCreated'){ $MSGHeader = 'Success!'; $MSGText = 'Asset has been created!'; $ErrorType = "success";}
      if ($AlertBar == 'UpdatedAsset'){ $MSGHeader = 'Success!'; $MSGText = 'Asset has been updated!'; $ErrorType = "success";}
      if ($AlertBar == 'EmptyFields'){ $MSGHeader = 'Failure!'; $MSGText = 'Fields must be filled in completely!'; $ErrorType = "danger";}
      if ($AlertBar == 'NothingChecked'){ $MSGHeader = 'Failure!'; $MSGText = 'Something needs to be checked!'; $ErrorType = "danger";}
      if ($AlertBar == 'RoleRemoved'){ $MSGHeader = 'Success!'; $MSGText = 'Role has been removed!'; $ErrorType = "success";}
      if ($AlertBar == 'GroupRemoved'){ $MSGHeader = 'Success!'; $MSGText = 'Group has been removed!'; $ErrorType = "success";}
      if ($AlertBar == 'GroupAdded'){ $MSGHeader = 'Success!'; $MSGText = 'Group has been added!'; $ErrorType = "success";}
    }
}
?>