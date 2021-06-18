<?php
  /**
   * Controller
   *
   */
  define("_VALID_PHP", true);
  require_once("../init.php");

//session_start();
//if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  /*if (!$user->logged_in) {
      $newURL = "account.php";
      header("Location:/$newURL");
      exit;
  }*/

      $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      /* == Ajax Requests == */
      if (isset($_POST['AddPONumber'])):
        if (intval($_POST['AddPONumber']) == 0 || empty($_POST['AddPONumber'])):
          $content->AddPONumber();
          exit;
        endif;
      endif;

      if (isset($_POST['CheckWOInfo'])):
        if (intval($_POST['CheckWOInfo']) == 0 || empty($_POST['CheckWOInfo'])):
          $content->CheckWOInfo();
          exit;
        endif;
      endif;

      if (isset($_POST['AddPODetail'])):
        if (intval($_POST['AddPODetail']) == 0 || empty($_POST['AddPODetail'])):
          $content->AddPODetail();
          exit;
        endif;
      endif;

      if (isset($_POST['RemovePODetail'])):
        if (intval($_POST['RemovePODetail']) == 0 || empty($_POST['RemovePODetail'])):
          $content->RemovePODetail();
          exit;
        endif;
      endif;

      if (isset($_POST['OrderApprove'])):
        if (intval($_POST['OrderApprove']) == 0 || empty($_POST['OrderApprove'])):
          $content->OrderApprove();
          exit;
        endif;
      endif;

      if (isset($_POST['OrderDeny'])):
        if (intval($_POST['OrderDeny']) == 0 || empty($_POST['OrderDeny'])):
          $content->OrderDeny();
          exit;
        endif;
      endif;

      if (isset($_POST['AddPartType'])):
        if (intval($_POST['AddPartType']) == 0 || empty($_POST['AddPartType'])):
          $content->AddPartType();
          exit;
        endif;
      endif;

      if (isset($_POST['UpdateOrderPartTaxation'])):
        if (intval($_POST['UpdateOrderPartTaxation']) == 0 || empty($_POST['UpdateOrderPartTaxation'])):
          $content->UpdateOrderPartTaxation();
          exit;
        endif;
      endif;
      
      if (isset($_POST['UpdateOrderNoteDetail'])):
        if (intval($_POST['UpdateOrderNoteDetail']) == 0 || empty($_POST['UpdateOrderNoteDetail'])):
          $content->UpdateOrderNoteDetail();
          exit;
        endif;
      endif;

      if (isset($_POST['UpdatePOPartDetail'])):
        if (intval($_POST['UpdatePOPartDetail']) == 0 || empty($_POST['UpdatePOPartDetail'])):
          $content->UpdatePOPartDetail();
          exit;
        endif;
      endif;

      if (isset($_POST['UpdateWOAssetDetail'])):
        if (intval($_POST['UpdateWOAssetDetail']) == 0 || empty($_POST['UpdateWOAssetDetail'])):
          $content->UpdateWOAssetDetail();
          exit;
        endif;
      endif;
      
      if (isset($_POST['UpdateWOPartDetail'])):
        if (intval($_POST['UpdateWOPartDetail']) == 0 || empty($_POST['UpdateWOPartDetail'])):
          $content->UpdateWorkorderPartData();
          exit;
        endif;
      endif;

      if (isset($_POST['AddInventoryVendor'])):
        if (intval($_POST['AddInventoryVendor']) == 0 || empty($_POST['AddInventoryVendor'])):
          $content->AddInventoryVendor();
          exit;
        endif;
      endif;

      if (isset($_POST['DeleteInventoryVendor'])):
        if (intval($_POST['DeleteInventoryVendor']) == 0 || empty($_POST['DeleteInventoryVendor'])):
          $content->DeleteInventoryVendor();
          exit;
        endif;
      endif;

      if (isset($_POST['ApproveWOItem'])):
        if (intval($_POST['ApproveWOItem']) == 0 || empty($_POST['ApproveWOItem'])):
          $content->ApproveWOItem();
          exit;
        endif;
      endif;

      if (isset($_POST['AssignUser'])):
        if (intval($_POST['AssignUser']) == 0 || empty($_POST['AssignUser'])):
          $content->AssignUser();
          exit;
        endif;
      endif;

      if (isset($_POST['DenyWOItem'])):
        if (intval($_POST['DenyWOItem']) == 0 || empty($_POST['DenyWOItem'])):
          $content->DenyWOItem();
          exit;
        endif;
      endif;

      if (isset($_POST['UpdatePOHeaderVendor'])):
        if (intval($_POST['UpdatePOHeaderVendor']) == 0 || empty($_POST['UpdatePOHeaderVendor'])):
          $content->UpdatePOHeaderVendor();
          exit;
        endif;
      endif;

      if (isset($_POST['UpdatePOHeaderReq'])):
        if (intval($_POST['UpdatePOHeaderReq']) == 0 || empty($_POST['UpdatePOHeaderReq'])):
          $content->UpdatePOHeaderReq();
          exit;
        endif;
      endif;

      if (isset($_POST['UpdatePOHeaderPO'])):
        if (intval($_POST['UpdatePOHeaderPO']) == 0 || empty($_POST['UpdatePOHeaderPO'])):
          $content->UpdatePOHeaderPO();
          exit;
        endif;
      endif;
      
      if (isset($_POST['SubmitPurchaseOrder'])):
        if (intval($_POST['SubmitPurchaseOrder']) == 0 || empty($_POST['SubmitPurchaseOrder'])):
          $content->SubmitPurchaseOrder();
          exit;
        endif;
      endif;

    /* == Vendor == */
    if (isset($_POST['UpdateVendorData'])):
      if (intval($_POST['UpdateVendorData']) == 0 || empty($_POST['UpdateVendorData'])):
        $content->UpdateVendorData();
        exit;
      endif;
    endif;
    
    if (isset($_POST['CreateVendorData'])):
      if (intval($_POST['CreateVendorData']) == 0 || empty($_POST['CreateVendorData'])):
        $content->CreateVendorData();
        exit;
      endif;
    endif;
    
    /* == Assets == */
    if (isset($_POST['UpdateAssetData'])):
      if (intval($_POST['UpdateAssetData']) == 0 || empty($_POST['UpdateAssetData'])):
        $content->UpdateAssetData();
        exit;
      endif;
    endif;

    if (isset($_POST['CreateAssetData'])):
      if (intval($_POST['CreateAssetData']) == 0 || empty($_POST['CreateAssetData'])):
        $content->CreateAssetData();
        exit;
      endif;
    endif;

    /* == Workorder == */
    if (isset($_POST['PMDeny'])):
      if (intval($_POST['PMDeny']) == 0 || empty($_POST['PMDeny'])):
        $content->PMDeny();
        exit;
      endif;
    endif;

    if (isset($_POST['PMApprove'])):
      if (intval($_POST['PMApprove']) == 0 || empty($_POST['PMApprove'])):
        $content->PMApprove();
        exit;
      endif;
    endif;

    if (isset($_POST['UpdatePMData'])):
      if (intval($_POST['UpdatePMData']) == 0 || empty($_POST['UpdatePMData'])):
        $content->UpdatePMData();
        exit;
      endif;
    endif;

    if (isset($_POST['CreatePMData'])):
      if (intval($_POST['CreatePMData']) == 0 || empty($_POST['CreatePMData'])):
        $content->CreatePMData();
        exit;
      endif;
    endif;

    if (isset($_POST['CreateWorkorderData'])):
      if (intval($_POST['CreateWorkorderData']) == 0 || empty($_POST['CreateWorkorderData'])):
        $content->CreateWorkorderData();
        exit;
      endif;
    endif;

    if (isset($_POST['UpdateWorkorderData'])):
      if (intval($_POST['UpdateWorkorderData']) == 0 || empty($_POST['UpdateWorkorderData'])):
        $content->UpdateWorkorderData();
        exit;
      endif;
    endif;

    //Asset Location
    if (isset($_POST['AddAssetLocation'])):
      if (intval($_POST['AddAssetLocation']) == 0 || empty($_POST['AddAssetLocation'])):
        $content->AddAssetLocation();
        exit;
      endif;
    endif;

    if (isset($_POST['AssetLocationDisable'])):
      if (intval($_POST['AssetLocationDisable']) == 0 || empty($_POST['AssetLocationDisable'])):
        $content->AssetLocationDisable();
        exit;
      endif;
    endif;

    if (isset($_POST['AssetLocationActivate'])):
      if (intval($_POST['AssetLocationActivate']) == 0 || empty($_POST['AssetLocationActivate'])):
        $content->AssetLocationActivate();
        exit;
      endif;
    endif;

    if (isset($_POST['AssetLocationDelete'])):
      if (intval($_POST['AssetLocationDelete']) == 0 || empty($_POST['AssetLocationDelete'])):
        $content->AssetLocationDelete();
        exit;
      endif;
    endif;

    if (isset($_POST['InventoryLocationDisable'])):
      if (intval($_POST['InventoryLocationDisable']) == 0 || empty($_POST['InventoryLocationDisable'])):
        $content->InventoryLocationDisable();
        exit;
      endif;
    endif;

    if (isset($_POST['InventoryLocationActivate'])):
      if (intval($_POST['InventoryLocationActivate']) == 0 || empty($_POST['InventoryLocationActivate'])):
        $content->InventoryLocationActivate();
        exit;
      endif;
    endif;

    if (isset($_POST['InventoryLocationDelete'])):
      if (intval($_POST['InventoryLocationDelete']) == 0 || empty($_POST['InventoryLocationDelete'])):
        $content->InventoryLocationDelete();
        exit;
      endif;
    endif;

    if (isset($_POST['InventoryLocationLabelPrint'])):
      if (intval($_POST['InventoryLocationLabelPrint']) == 0 || empty($_POST['InventoryLocationLabelPrint'])):
        $content->InventoryLocationLabelPrint();
        exit;
      endif;
    endif;

    if (isset($_POST['addInventoryLocation'])):
      if (intval($_POST['addInventoryLocation']) == 0 || empty($_POST['addInventoryLocation'])):
        $content->addInventoryLocation();
        exit;
      endif;
    endif;

    if (isset($_POST['UpdateInventoryData'])):
      if (intval($_POST['UpdateInventoryData']) == 0 || empty($_POST['UpdateInventoryData'])):
        $content->UpdateInventoryData();
        exit;
      endif;
    endif;

    if (isset($_POST['CreateInventoryData'])):
      if (intval($_POST['CreateInventoryData']) == 0 || empty($_POST['CreateInventoryData'])):
        $content->CreateInventoryData();
        exit;
      endif;
    endif;

    if (isset($_POST['StartAudit'])):
      if (intval($_POST['StartAudit']) == 0 || empty($_POST['StartAudit'])):
        $content->StartAudit();
        exit;
      endif;
    endif;

    if (isset($_POST['ReceiveScanCode'])):
      if (intval($_POST['ReceiveScanCode']) == 0 || empty($_POST['ReceiveScanCode'])):
        $content->ReceiveScanCode();
        exit;
      endif;
    endif;

    if (isset($_POST['ReceiveLabelPrint'])):
      if (intval($_POST['ReceiveLabelPrint']) == 0 || empty($_POST['ReceiveLabelPrint'])):
        $content->ReceiveLabelPrint();
        exit;
      endif;
    endif;

    if (isset($_POST['PartInventoryLabelPrint'])):
      if (intval($_POST['PartInventoryLabelPrint']) == 0 || empty($_POST['PartInventoryLabelPrint'])):
        $content->PartInventoryLabelPrint();
        exit;
      endif;
    endif;

    if (isset($_POST['WithdrawItems'])):
      if (intval($_POST['WithdrawItems']) == 0 || empty($_POST['WithdrawItems'])):
        $content->WithdrawItems();
        exit;
      endif;
    endif;

    if (isset($_POST['ReceivedItems'])):
      if (intval($_POST['ReceivedItems']) == 0 || empty($_POST['ReceivedItems'])):
        $content->ReceivedItems();
        exit;
      endif;
    endif;

    if (isset($_POST['AuditLocation'])):
      if (intval($_POST['AuditLocation']) == 0 || empty($_POST['AuditLocation'])):
        $content->AuditLocation();
        exit;
      endif;
    endif;

    if (isset($_POST['OrderParts'])):
      if (intval($_POST['OrderParts']) == 0 || empty($_POST['OrderParts'])):
        $content->OrderParts();
        exit;
      endif;
    endif;

    if (isset($_POST['AddUser'])):
      if (intval($_POST['AddUser']) == 0 || empty($_POST['AddUser'])):
        $content->AddUser();
        exit;
      endif;
    endif;

    if (isset($_POST['AddRoleGroup'])):
      if (intval($_POST['AddRoleGroup']) == 0 || empty($_POST['AddRoleGroup'])):
        $content->AddRoleGroup();
        exit;
      endif;
    endif;

    if (isset($_POST['AddControlGroup'])):
      if (intval($_POST['AddControlGroup']) == 0 || empty($_POST['AddControlGroup'])):
        $content->AddControlGroup();
        exit;
      endif;
    endif;

    if (isset($_POST['UserRolesDelete'])):
      if (intval($_POST['UserRolesDelete']) == 0 || empty($_POST['UserRolesDelete'])):
        $content->UserRolesDelete();
        exit;
      endif;
    endif;

    if (isset($_POST['UserGroupDeactivate'])):
      if (intval($_POST['UserGroupDeactivate']) == 0 || empty($_POST['UserGroupDeactivate'])):
        $content->UserGroupDeactivate();
        exit;
      endif;
    endif;

    if (isset($_POST['ChecklistItem'])):
        $content->ChecklistItem();
        exit;
    endif;

    if (isset($_POST['DeleteInventoryLocation'])):
        $content->DeleteInventoryLocation();
        exit;
    endif;
  
    $newURL = "default.php?do=workorders&msg=error404";
    header("Location:/$newURL");



?>