</div>
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
          <?php
          include 'lib/sitevariables.php';
            echo "<span><u><i>$SiteName</i></u></span>";
            ?>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
  
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">

function OrderModify(){
  var x = document.getElementById("ShowModifyList");
  if (window.getComputedStyle(x).display === "none") {
    document.getElementById("ShowModifyList").style.display = "block";
  } else {
    document.getElementById("ShowModifyList").style.display = "none";
  }
}

function ApproveWO(WorkOrderID){
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'ApproveWOItem',
        ApproveWOItem: 'True',
        WorkOrderID: WorkOrderID
      },
      success: function(data){
        console.log('Workorder Approved');
      }
    });
  var x = document.getElementById("ApprovalChange");
    x.style.color = "green";
  var elements = document.getElementsByClassName('approvalbtn');
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
  var OriginalText = x.innerHTML;
    x.textContent = x.textContent.replace(`${OriginalText}`, `Approved`);
    location.reload();
}

function DenyWO(WorkOrderID){
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'DenyWOItem',
        DenyWOItem: 'True',
        WorkOrderID: WorkOrderID
      },
      success: function(data){
        console.log('Workorder Denied');
      }
    });
  var x = document.getElementById("ApprovalChange");
    x.style.color = "red";
  var elements = document.getElementsByClassName('approvalbtn');
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
  var OriginalText = x.innerHTML;
    x.textContent = x.textContent.replace(`${OriginalText}`, `Denied`);
  var Status = document.getElementById("Status");
  var option = document.createElement("option");
    option.text = "Declined";
    option.value = "3";
    option.selected = true;
    Status.add(option);
    $("select").attr("disabled", "disabled");
    $("input").attr("disabled", "disabled");
    $("textarea").attr("disabled", "disabled");
    document.querySelectorAll(`[name="UpdateWorkorderData"]`).forEach(el => el.remove());
}

function DeleteChecklistItem(Element){
  var i = 0;
  document.getElementById(`ChecklistItem[${Element}]`).remove();
  var divs = document.querySelectorAll("[id^='ChecklistItem']");
  var btns = document.querySelectorAll("[id^='ChecklistBtn']");
  while (i < divs.length) {
    console.log(divs[i].id);
    var Original = divs[i].id;
    var OriginalBtns = btns[i].id;
    i++;
    document.getElementById(`${Original}`).id = `ChecklistItem[${i}]`;
    document.getElementById(`${OriginalBtns}`).setAttribute( "onClick", `DeleteChecklistItem('${i}')` );
    document.getElementById(`${OriginalBtns}`).id = `ChecklistBtn[${i}]`;
  }
}

function AddChecklist(){
  var CountElements = document.querySelectorAll("[id^='ChecklistItem']").length;
  var ElementAdd = CountElements + 1;

  var node = document.getElementById('AddWorklistItem');
    var innerHTML = `\    
      <div id="ChecklistItem[${ElementAdd}]"> \
        <div class="row"> \
          <div class="col" style="margin-bottom: 15px;"> \
            <input type="text" class="form-control" required="required" name="workchecklist[]" value="" placeholder="Add work instruction..."> \
          </div> \
          <div class="col-2" style="margin-bottom: 15px;"> \
          <button type="button" id="ChecklistBtn[${ElementAdd}]" class="btn btn-danger" style="width:100%;" onclick="DeleteChecklistItem('${ElementAdd}');" name="DeleteChecklistItems">Delete</button> \
          </div> \
        </div> \
      </div> \
    `;
    $(node).append(innerHTML);
}

function DetermineRepetition(){
  var AssignmentType = document.getElementById("RepetitonID").value;
  if(AssignmentType == '2'){
    document.getElementById("DaySwitch").style.display = "block";
    document.getElementById("WeekSwitch").style.display = "block";
    document.getElementById("WeekSwitchTwo").style.display = "block";
    document.getElementById("MonthSwitch").style.display = "none";
    document.getElementById("MonthSwitchTwo").style.display = "none";
    document.getElementById("YearSwitch").style.display = "none";
  } else if(AssignmentType == '1') {
    document.getElementById("WeekSwitch").style.display = "none";
    document.getElementById("WeekSwitchTwo").style.display = "none";
    document.getElementById("MonthSwitch").style.display = "none";
    document.getElementById("MonthSwitchTwo").style.display = "none";
    document.getElementById("YearSwitch").style.display = "none";
    document.getElementById("DaySwitch").style.display = "none";
  } else {
    document.getElementById("DaySwitch").style.display = "block";
    document.getElementById("WeekSwitch").style.display = "none";
    document.getElementById("WeekSwitchTwo").style.display = "none";
    document.getElementById("MonthSwitch").style.display = "block";
    document.getElementById("MonthSwitchTwo").style.display = "block";
    document.getElementById("YearSwitch").style.display = "block";
  }
}

function ExpandWorkOrderFields(){
  var AssignmentType = document.getElementById("AssignmentType").value;
  var Priority = document.getElementById("Priority");
  if(AssignmentType == '10'){
    document.getElementById("DeadSwitch").style.display = "none";
    document.getElementById("DeadSwitchPM").style.display = "block";
    var option = document.createElement("option");
    option.text = "Routine";
    option.value = "13";
    Priority.add(option);
    Priority.value = '13';
    Priority.disabled = true;
  } else {
    document.getElementById("DeadSwitch").style.display = "block";
    document.getElementById("DeadSwitchPM").style.display = "none";
    Priority.value = '7';
    Priority.disabled = false;
    console.log(Priority.length);
    if (Priority.length > 4) {
      Priority.remove(Priority.length-1);
    }
  }
}

function DeleteInventoryVendor(VendorID, InventoryID){
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'DeleteInventoryVendor',
        DeleteInventoryVendor: 'True',
        VendorID: VendorID,
        InventoryID: InventoryID
      },
      success: function(data){
        console.log('Vendor Data Deleted');
      }
    });
    document.getElementById(`VendorID[${VendorID}]`).remove();
}

function AddInventoryVendor(InventoryID){
  var VendorID = document.querySelector(`[name="VendorIDInput-hidden"]`).value;
  var VendorIDDisplay = document.querySelector(`[name="VendorIDInput"]`).value;
  var VendorIDDisplaySplit = VendorIDDisplay.split("|", 1);
  var VendorPartID = document.querySelector(`[name="VendorPartID"]`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'AddInventoryVendor',
        AddInventoryVendor: 'True',
        VendorID: VendorID,
        InventoryID: InventoryID,
        VendorPartID: VendorPartID
      },
      success: function(data){
        console.log('Vendor Data Added');
      }
    });
    //document.getElementById("VendorListID").remove();
    var node = document.getElementById('VendorListID');
    var innerHTML = `\    
      <div id="VendorID[${VendorID}]"> \
        <div class="row"> \
          <div class="col-2" style="margin-bottom: 15px;"> \
            <input type="text" disabled="disabled" class="form-control" value="${VendorIDDisplaySplit}"> \
          </div> \
          <div class="col-2" style="margin-bottom: 15px;"> \
            <input type="text" disabled="disabled" class="form-control" value="${VendorPartID}"> \
          </div> \
          <div class="col-2" style="margin-bottom: 15px;"> \
          <button type="button" class="btn btn-danger" style="width:100%;" onclick="DeleteInventoryVendor('${VendorID}', '${InventoryID}');" name="DeleteInventoryVendors">Delete</button> \
          </div> \
        </div> \
      </div> \
    `;
    $(node).append(innerHTML);
    document.getElementById('VendorIDInput').value = '';
    document.getElementById('VendorPartIDInput').value = '';
};

function UpdateOrderPartCost(InventoryID,OrderNumber){
  var PartPrice = document.querySelector(`[name="PartPrice[${InventoryID}]"]`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdatePOPartDetail',
        UpdatePOPartDetail: 'True',
        OrderNumber: OrderNumber,
        InventoryID: InventoryID,
        PartPrice: PartPrice
      },
      success: function(data){
        console.log('Part Qty Updated');
      }
    });

    var CPP = document.querySelector(`[name="CPP[${InventoryID}]"]`);
    if(!!CPP){
      var PartQuantity = document.querySelector(`[name="PartQuantity[${InventoryID}]"]`).value;
      if(!!PartQuantity){
        var PricePerPart = PartPrice / PartQuantity;
        console.log(`Price Per Part: ${PricePerPart}`);
        document.querySelector(`[name="CPP[${InventoryID}]"]`).value = `${PricePerPart.toFixed(3)}`;
      }
    }
};

function UpdateOrderPartTaxation(InventoryID,OrderNumber){
  var Taxable = document.querySelector(`[name="Taxable[${InventoryID}]"]`).value;

  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdateOrderPartTaxation',
        UpdateOrderPartTaxation: 'True',
        OrderNumber: OrderNumber,
        InventoryID: InventoryID,
        Taxable: Taxable
      },
      success: function(data){
        console.log('Taxation Updated');
      }
    });
};


function WOAssetUpdate(WorkorderID){
  var Asset = document.querySelector(`[name="asset_selected"]`).value;
  console.log(Asset);
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdateWOAssetDetail',
        UpdateWOAssetDetail: 'True',
        WorkorderID: WorkorderID,
        Asset: Asset
      },
      success: function(data){
        console.log('Asset Updated');
      }
    });
}

function UpdateWorkorderQty(InventoryID, InventoryLocation, WorkorderID){
  var PartQuantity = document.querySelector(`[name="ItemQty[${InventoryID}${InventoryLocation}]"]`).value;
  var Asset = document.querySelector(`[name="asset_selected"]`).value;
  var MaxValue = document.getElementById(`OnHand[${InventoryID},${InventoryLocation}]`).textContent;
  if(PartQuantity < 0){
    console.log("fail");
    return;
  }
  if(PartQuantity > MaxValue){
    console.log("fail");
    return;
  }
  
  console.log(PartQuantity);
  console.log(MaxValue);
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdateWOPartDetail',
        UpdateWOPartDetail: 'True',
        InventoryLocation: InventoryLocation,
        InventoryID: InventoryID,
        PartQuantity: PartQuantity,
        WorkorderID: WorkorderID,
        Asset: Asset
      },
      success: function(data){
        console.log('Part Qty Updated');
      }
    });

    window.location.reload()

}

function UpdateOrderPartQty(InventoryID,OrderNumber){
  var PartQuantity = document.querySelector(`[name="PartQuantity[${InventoryID}]"]`).value;
  console.log('InventoryID');
  console.log(InventoryID);
  console.log('OrderNumber');
  console.log(OrderNumber);
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdatePOPartDetail',
        UpdatePOPartDetail: 'True',
        OrderNumber: OrderNumber,
        InventoryID: InventoryID,
        PartQuantity: PartQuantity
      },
      success: function(data){
        console.log('Part Qty Updated');
      }
    });

    var CPP = document.querySelector(`[name="CPP[${InventoryID}]"]`);
    if(!!CPP){
      var PartPrice = document.querySelector(`[name="PartPrice[${InventoryID}]"]`).value;
      if(!!PartPrice){
        var PricePerPart = PartPrice/ PartQuantity;
        console.log(`Price Per Part: ${PricePerPart}`);
        document.querySelector(`[name="CPP[${InventoryID}]"]`).value = `${PricePerPart.toFixed(3)}`;
      }
    }
};

function UpdatePOHeaderVendor(OrderNumber){
  var Vendor = document.querySelector(`[name="VendorIDInput"]`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdatePOHeaderVendor',
        UpdatePOHeaderVendor: 'True',
        OrderNumber: OrderNumber,
        Vendor: Vendor
      },
      success: function(data){
        console.log('Header Vendor Updated');
      }
    });
};

function UpdatePOHeaderReq(OrderNumber){
  var Req = document.querySelector(`[name="REQ"]`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdatePOHeaderReq',
        UpdatePOHeaderReq: 'True',
        OrderNumber: OrderNumber,
        Req: Req
      },
      success: function(data){
        console.log('Header Req Updated');
      }
    });
};

function UpdateOrderNoteDetail(OrderNumber){
  var Note = document.querySelector(`[name="Notes[${OrderNumber}]"]`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdateOrderNoteDetail',
        UpdateOrderNoteDetail: 'True',
        OrderNumber: OrderNumber,
        Note: Note
      },
      success: function(data){
        console.log('Header Note Updated');
      },
      failure: function(data){
        console.log('Header Note Updated');
      }
    });
};

function UpdatePOHeaderPO(OrderNumber){
  var PONumber = document.querySelector(`[name="PONumber"]`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'UpdatePOHeaderPO',
        UpdatePOHeaderPO: 'True',
        OrderNumber: OrderNumber,
        PONumber: PONumber
      },
      success: function(data){
        console.log('Header PO Updated');
      },
      failure: function(data){
        console.log('Header PO Updated');
      }
    });
};
function ScanSearch(){
  var ScanItem = document.getElementById(`ScanItemCheck`).value;
  var Gumdrop = ScanItem.substring(0, 2);
    if(Gumdrop == '~P'){
      var chunks = ScanItem.split("|");
      var chunked = chunks[0];
      var FinalChunk = chunked.replace('~P','');
      FinalChunk = Number(FinalChunk);
      document.getElementById(`ScanItemCheck`).value = "";
      var oTable = $('#getInventoryListWorkorder').dataTable();
      oTable.fnFilter(`${FinalChunk}`);
    } else {
      var FinalChunk = ScanItem.replace('~','');
      FinalChunk = Number(FinalChunk);
      document.getElementById(`ScanItemCheck`).value = "";
      var oTable = $('#getInventoryListWorkorder').dataTable();
      oTable.fnFilter(`${FinalChunk}`);
    }
  

  //$('.SearchPane').val(`${FinalChunk}`).change();
  //document.getElementsByClassName('SearchPane')[0].setAttribute("value", "test");
}

function RemoveAssignedUserIFNULL(){
  var AssignedItem = document.getElementById(`AssignedUserInput`).value;
  console.log(AssignedItem);
  if(AssignedItem == ''){
    document.getElementById(`AssignedUserInput`).remove();
  }
}

function AuditLocation(AuditID){
  var ScanLocation = document.getElementById(`ScanLocation`).value;
  var ScanItem = document.getElementById(`ScanItem`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'AuditLocation',
        AuditLocation: 'True',
        ScanLocation: ScanLocation,
        ScanItem: ScanItem,
        AuditID: AuditID
      },
      success: function(data){
        if(data.status == 'success'){
          console.log('Item Added');
          var CSSID = `InspectedQty[${data.InventoryID}]`
          var QuantityScannedID = document.getElementById(CSSID);
          var QuantityScanned = QuantityScannedID.textContent;
          QuantityScanned = parseInt(QuantityScanned);
          var FinalCount = QuantityScanned + 1;
          QuantityScannedID.textContent = QuantityScannedID.textContent.replace(`${QuantityScanned}`, `${FinalCount}`);
        }else if(data.status == 'partcomplete'){
          console.log('Item Complete');
          var CSSID = `InspectedQty[${data.InventoryID}]`
          var CSSAID = `AuditStatus[${data.InventoryID}]`

          var QuantityScannedID = document.getElementById(CSSID);
          var StatusText = document.getElementById(CSSAID);

          var StatusContent = StatusText.textContent;
          var QuantityScanned = QuantityScannedID.textContent;

          StatusText.textContent = StatusText.textContent.replace(`${StatusContent}`, `Verified`);
          StatusText.style.color = "green";

          QuantityScanned = parseInt(QuantityScanned);
          var FinalCount = QuantityScanned + 1;
          QuantityScannedID.textContent = QuantityScannedID.textContent.replace(`${QuantityScanned}`, `${FinalCount}`);
        }else if(data.status == 'error'){
          console.log('Item Not Received');
          console.log(data.count);
          var e = document.getElementById('alert-scans');
          e.style.display = 'block';
        }else if(data.status == 'QuantityError'){
          console.log('Item quantity too high');
          var e = document.getElementById('alert-scans-quantity');
          e.style.display = 'block';
        }else if(data.status == 'problem'){
          console.log('SQL ISSUE');
          console.log(data.text);
        }
      }
  });  
  
  document.getElementById('ScanLocation').value = '';
  document.getElementById('ScanItem').value = '';
}

function WithdrawItems(){
  var ScanLocation = document.getElementById(`ScanLocation`).value;
  var ScanItem = document.getElementById(`ScanItem`).value;
  var Quantity = document.getElementById(`ScanQuantity`).value;
  var Asset = document.getElementById(`IDasset_selected`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'WithdrawItems',
        WithdrawItems: 'True',
        ScanLocation: ScanLocation,
        Quantity: Quantity,
        ScanItem: ScanItem,
        Asset: Asset
      },
      success: function(data){
        if(data.status == 'success'){
          console.log('Item Withdrawn');
          var e = document.getElementById('alert-scans-withdrawnIW');
          e.style.display = 'block';
          setTimeout(function() {
              $('#alert-scans-withdrawnIW').fadeOut('slow');
          }, 15000); // <-- time in milliseconds
        }else if(data.status == 'error'){
          console.log('Item Not Withdrawn');
          var e = document.getElementById('alert-scansIW');
          e.style.display = 'block';
          setTimeout(function() {
              $('#alert-scansIW').fadeOut('slow');
          }, 15000); // <-- time in milliseconds
        }else if(data.status == 'asseterror'){
          console.log('Item Not Withdrawn');
          var e = document.getElementById('alert-scans-assetIW');
          e.style.display = 'block';
          setTimeout(function() {
              $('#alert-scans-assetIW').fadeOut('slow');
          }, 15000); // <-- time in milliseconds
        }else if(data.status == 'QuantityError'){
          console.log('Item quantity too high');
          var e = document.getElementById('alert-scans-quantityIW');
          e.style.display = 'block';
          setTimeout(function() {
              $('#alert-scans-quantityIW').fadeOut('slow');
          }, 15000); // <-- time in milliseconds
        }else if(data.status == 'problem'){
          console.log('SQL ISSUE');
          console.log(data.text);
        }
      }
  });  
  document.getElementById('ScanLocation').value = '';
  document.getElementById('ScanItem').value = '';
  document.getElementById('ScanQuantity').value = '';
};

function ReceiveItems(OrderID){
  var ScanLocation = document.getElementById(`ScanLocation`).value;
  var ScanItem = document.getElementById(`ScanItem`).value;
  var Quantity = document.getElementById(`ScanQuantity`).value;
  $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'ReceivedItems',
        ReceivedItems: 'True',
        ScanLocation: ScanLocation,
        Quantity: Quantity,
        ScanItem: ScanItem,
        OrderID: OrderID
      },
      success: function(data){
        if(data.status == 'success'){
          console.log('Item Received');
          var InventoryIDValue = document.getElementById(`InventoryIDQ[${data.InventoryID}]`).value;
          var FinalValue = InventoryIDValue - data.QuantityScanned;
          document.getElementById(`InventoryIDQ[${data.InventoryID}]`).value = FinalValue;
        }else if(data.status == 'error'){
          console.log('Item Not Received');
          var e = document.getElementById('alert-scans');
          e.style.display = 'block';
        }else if(data.status == 'QuantityError'){
          console.log('Item quantity too high');
          var e = document.getElementById('alert-scans-quantity');
          e.style.display = 'block';
        }else if(data.status == 'problem'){
          console.log('SQL ISSUE');
          console.log(data.text);
        }
      }
  });  
  document.getElementById('ScanLocation').value = '';
  document.getElementById('ScanItem').value = '';
  document.getElementById('ScanQuantity').value = '';
};

function ReceiveLabelPrint(InventoryID, QuantityRequested, PurchaseOrder, Vendor){
  var LabelsToPrint = document.querySelector(`[name="LabelsToPrint[${InventoryID}]"]`).value;
  var Max = document.getElementById(`CheckMaxValue[${InventoryID}]`).max;
  if(LabelsToPrint <= Max){
    $.ajax({
        type: "POST",
        dataType: "json",
        url: 'ajax/controller.php',
        data: {
          name: 'ReceiveLabelPrint',
          ReceiveLabelPrint: 'True',
          QuantityRequested: QuantityRequested,
          InventoryID: InventoryID,
          LabelsToPrint: LabelsToPrint,
          PurchaseOrder: PurchaseOrder,
          Vendor: Vendor
        },
        success: function(data){
          console.log('Print Event');
        },
        failure: function(data){
          console.log('Print Event');
        }
      });
  }
};

function ModifySelectionView(InventoryID,Description,OrderNumber){
  const CheckboxValid = document.getElementById(`InventorySelection[${InventoryID}]`);

  if (CheckboxValid.checked){
    console.log('Add Inventory Item');
    //Add to DB table
    $.ajax({
        type: "POST",
        dataType: "json",
        url: 'ajax/controller.php',
        data: {
          name: 'AddPODetail',
          AddPODetail: 'True',
          OrderNumber: OrderNumber,
          InventoryID: InventoryID
        },
        success: function(data){
          console.log('Part Added');
        }
      });
    //Add HTML with necessary information
    var node = document.getElementById('PartInformationID');
    var innerHTML = `\    
    <div id="InventoryID[${InventoryID}]" class="row" style="margin-top: 15px;"> \
      <div class="col-2"> \
        <input type="text" required="required" readonly="readonly" class="form-control" name="InventoryID" value="${InventoryID}" placeholder="Scan Location Code Here..."> \
      </div> \
      <div class="col-4"> \
        <input type="text" required="required" readonly="readonly" class="form-control" name="Description" value="${Description}" placeholder="Scan Location Code Here..."> \
      </div> \
      <div class="col-2"> \
        <input type="number" required="required" min="1" onchange="UpdateOrderPartQty('${InventoryID}', '${OrderNumber}');" class="form-control" name="PartQuantity[${InventoryID}]" value="" placeholder="xxx"> \
      </div> \
      <div class="col-2"> \
        <select type="text" required="required" onchange="UpdateOrderPartTaxation('${InventoryID}', '${OrderNumber}');" class="form-control" name="Taxable[${InventoryID}]" value="" placeholder="xxx"> \
        <option disabled="disabled" selected="selected">Required</option> \
        <option value="True">True</option> \
        <option value="False">False</option> \
        </select> \
      </div> \
      <div class="col-1"> \
        <input type="number" required="required" step=".01" min="0" onchange="UpdateOrderPartCost('${InventoryID}', '${OrderNumber}');" class="form-control" name="PartPrice[${InventoryID}]" value="" placeholder="UKNOWN"> \
      </div> \
      <div class="col-1"> \
        <input type="text" disabled="disabled" readonly="readonly" class="form-control" name="CPP[${InventoryID}]" value="" placeholder="UKNOWN"> \
      </div> \
    </div> \
    `;
    $(node).append(innerHTML);
  } else {
    console.log('Delete Inventory Item');
    document.getElementById(`InventoryID[${InventoryID}]`).remove();
    $.ajax({
      type: "POST",
      dataType: "json",
      url: 'ajax/controller.php',
      data: {
        name: 'RemovePODetail',
        RemovePODetail: 'True',
        OrderNumber: OrderNumber,
        InventoryID: InventoryID
      },
      success: function(data){
        console.log('Part Removed');
      }
    });
  }
};

function ShowSelection(InventoryID,Description) {  
    var addButton = $('#InventorySelection'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var submitwrapper = $('#order_field_wrapper'); //Input field wrapper
    var x = 1; //Initial field counter is 1
    var OrderNumber;

    var myEle = document.getElementById(`InventoryIDDisplay`);
    if(!myEle){
      //Add Purchase Order Header Information in SQL
      $.ajax({
        type: "POST",
        dataType: "json",
        url: 'ajax/controller.php',
        data: {
          name: 'AddPONumber',
          AddPONumber: 'True'
        },
        success: myCallback
      });

      function myCallback(response) {
        result = response;
        console.log("Inside ajax: "+result["OrderNumber"]);     
        OrderNumber = result["OrderNumber"];

        console.log(OrderNumber);
        //Add parts header for PO
        var fieldHTML = `<div id="InventoryIDDisplay" class="three fields"> \
                    <div class="row"> \
                      <div class="col"> \
                        <label for=\"OrderNumber\">Order Number:</label> \
                        <input type=\"text\" readonly="readonly" class="form-control" name="OrderNumber" value="${OrderNumber}">
                      </div> \
                      <div class="col"> \
                        <label for=\"Vendor\">Vendor:</label> \
                        <input type="text" list="VendorIDList" onchange="UpdatePOHeaderVendor('${OrderNumber}');" required="required" id="VendorIDInput" class="form-control" name="VendorIDInput"> \
                        <input type="hidden" name="VendorIDInput-hidden" id="VendorIDInput-hidden"> \
                      </div> \
                      <div class="col"> \
                        <label for=\"REQ\">REQ #:</label> \
                        <input type=\"text\" onchange="UpdatePOHeaderReq('${OrderNumber}');" required="required" class="form-control" name="REQ" value="">
                      </div> \
                      <div class="col"> \
                        <label for=\"PONumber\">PO #:</label> \
                        <input type=\"text\" class="form-control" onchange="UpdatePOHeaderPO('${OrderNumber}');" name="PONumber" value="">
                      </div> \
                    </div> \
                  </div> \
                  <br /> \
        `; 
        $(wrapper).prepend(fieldHTML); //Add field html

        var fieldButtonHTML = `
        <br /> \
          <div class="row"> \
            <div class="col"> \
              <button type="submit" class="btn btn-warning" style="width:100%;" name="SubmitPurchaseOrder" value="">Order</button> \
            </div> \
          </div> \
        `; 

        $(submitwrapper).append(fieldButtonHTML); //Add field html
        
      //Assuming first post so we can insert the part within the callback method
      $.ajax({
        type: "POST",
        dataType: "json",
        url: 'ajax/controller.php',
        data: {
          name: 'AddPODetail',
          AddPODetail: 'True',
          OrderNumber: OrderNumber,
          InventoryID: InventoryID
        },
        success: function(data){
          console.log('Part Added');
        }
      });

      var fieldHTML = `<div style="margin-top: 10px;" id="InventoryIDDisplay[${InventoryID}]" class="three fields"> \
                    <div class="row"> \
                      <div class="col-2"> \
                        <label for="PartID">PartID:</label> \
                        <input type="text" readonly="readonly" class="form-control" name="PartID" value="${InventoryID}">
                      </div> \
                      <div class="col-4"> \
                        <label for="PartDescription">Part Description:</label> \
                        <input type="text" readonly="readonly" class="form-control" name="PartDescription" value="${Description}" >
                      </div> \
                      <div class="col-3"> \
                        <label for="PartQuantity">Part Quantity:</label> \
                        <input type="number" min="1" onchange="UpdateOrderPartQty('${InventoryID}', '${OrderNumber}');" required="required" class="form-control" name="PartQuantity[${InventoryID}]" value="">
                      </div> \
                      <div class="col-3"> \
                        <label for="PartPrice">Cost:</label> \
                        <input type="number" step=".01" min="0" onchange="UpdateOrderPartCost('${InventoryID}', '${OrderNumber}');" class="form-control" name="PartPrice[${InventoryID}]" value="">
                      </div> \
                    </div> \
                  </div> \
        `; 
        $(wrapper).append(fieldHTML); //Add field html

      };

    } else {
        var OrderNumber = document.querySelector('[name="OrderNumber"]').value;
        var myPartEle = document.getElementById(`InventoryIDDisplay[${InventoryID}]`);
        if(myPartEle){
          //Remove InventoryID from HTML first
          document.getElementById(`InventoryIDDisplay[${InventoryID}]`).remove();

          //Remove from Detail 
          $.ajax({
              type: "POST",
              dataType: "json",
              url: 'ajax/controller.php',
              data: {
                name: 'RemovePODetail',
                RemovePODetail: 'True',
                OrderNumber: OrderNumber,
                InventoryID: InventoryID
              },
              success: function(data){
                console.log('Part Removed');
              }
            });

        } else {
          //Add Purchase Order Detail Information in SQL
          if(OrderNumber){
            $.ajax({
              type: "POST",
              dataType: "json",
              url: 'ajax/controller.php',
              data: {
                name: 'AddPODetail',
                AddPODetail: 'True',
                OrderNumber: OrderNumber,
                InventoryID: InventoryID
              },
              success: function(data){
                console.log('Part Added');
              }
            });

            var fieldHTML = `<div style="margin-top: 10px;" id="InventoryIDDisplay[${InventoryID}]" class="three fields"> \
                      <div class="row"> \
                        <div class="col-2"> \
                          <label for="PartID">PartID:</label> \
                          <input type="text" readonly="readonly" class="form-control" name="PartID" value="${InventoryID}">
                        </div> \
                        <div class="col-4"> \
                          <label for="PartDescription">Part Description:</label> \
                          <input type="text" readonly="readonly" class="form-control" name="PartDescription" value="${Description}" >
                        </div> \
                        <div class="col-3"> \
                          <label for="PartQuantity">Part Quantity:</label> \
                          <input type="number" min="1" onchange="UpdateOrderPartQty('${InventoryID}', '${OrderNumber}');" required="required" class="form-control" name="PartQuantity[${InventoryID}]" value="">
                        </div> \
                        <div class="col-3"> \
                          <label for="PartPrice">Cost:</label> \
                          <input type="number" step=".01" min="0" onchange="UpdateOrderPartCost('${InventoryID}', '${OrderNumber}');" class="form-control" name="PartPrice[${InventoryID}]" value="">
                        </div> \
                      </div> \
                    </div> \
            `; 
            $(wrapper).append(fieldHTML); //Add field html
          } else {
            console.log('Something went wrong');
          }
        }
    }
};
</script>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

<!-- DataTable Default Sort Workorders -->
<script>

$('.custom-file-input').on('change', function() { 
    let fileName = $(this).val().split('\\').pop(); 
    var label_text = $('.custom-file-label').text(); //Get the text
    $('.custom-file-label').text( label_text.replace(`${label_text}`, `${fileName}`) );
});

$(document).ready(function() {
    $('#getPMActive').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMActive.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&action=viewpm&pmid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getPMSuper').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMSuper.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&action=viewpm&pmid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );
<?php
  if(isset($PartTableID)){
?>
  $(document).ready(function() {
    $('#getInventoryTransactionHistory').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryTransactionHistory.php?partid=<?php echo $PartTableID; ?>",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="#">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );
<?php
  }
?>

$(document).ready(function() {
    $('#getPMCrib').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMCrib.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&action=viewpm&pmid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getPMClosed').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMClosed.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[1] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getPMOverdue').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMOverdue.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[1] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getPMOpen').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMOpen.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[1] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

  $(document).ready(function() {
    $('#getWorkordersPending').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersPending.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getWorkordersScheduled').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersScheduled.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getWorkordersClosed').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersClosed.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getWorkordersDeclined').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersDeclined.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('#getWorkordersInprocess').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersInprocess.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

  $(document).ready(function() {
    $('#getWorkordersAll').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersAll.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

  $(document).ready(function() {
    $('#getWorkordersTeam').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersTeam.php?uid=<?php echo $_SESSION["UID"];?>",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

  $(document).ready(function() {
    $('#getWorkordersEmployee').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersEmployee.php?uid=<?php echo $_SESSION["UID"];?>",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

  $(document).ready(function() {
    $('#getWorkordersOpen').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getWorkordersOpen.php",
        "order": [[ 0, "ASC" ]],
        "columnDefs": [{
          "targets": '_all',
          "render": function ( data, type, row, meta ) {
            if(type === 'display'){
              data = '<a style="text-decoration:none; color:inherit;" href="default.php?do=workorders&amp;action=view&amp;workorderid=' + row[0] + '">'+ data +'</a>';
              console.log(data);
            }
            return data;
          }
        }]
      } );
  } );

$(document).ready(function() {
    $('.WorkOrderIDSort').DataTable( {
        "order": [[ 0, "ASC" ]]
    } );
} );

$(document).ready(function() {
    $('#getLocationsInventoryDisabled').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getLocationsInventoryDisabled.php"
      } );
  } );

$(document).ready(function() {
    $('#getLocationsInventoryActive').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getLocationsInventoryActive.php"
      } );
  } );

  $(document).ready(function() {
    var myElem = document.getElementById("getInventoryListWorkorder");
    if(myElem){
      var WorkorderIDs = document.getElementById("workorderids").value;
      $.ajax({
        type: "POST",
        dataType: "json",
        url: 'ajax/controller.php',
        data: {
          name: 'CheckWOInfo',
          CheckWOInfo: 'True',
          WorkorderID: WorkorderIDs
        },
        success: myCallbackWO
      });

      function myCallbackWO(response) {
        result = response;
        console.log("Inside ajax: "+result["Mixed"]);     
        var ItemIDArray = JSON.parse(result["Mixed"]);
        var QuantityUsed = JSON.parse(result["QuantityUsed"]);
        var InventoryLocationID = JSON.parse(result["LocationID"]);
        console.log("Inside ajax: "+ItemIDArray);   

    $('#getInventoryListWorkorder').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryListWorkorder.php",
        "order": [[ 0, "asc" ]],
        'columnDefs': [
        {
         'targets': 2,
         'render': function (data, type, row, meta){
           var LocationText;
            if(row[6] != null){
              LocationText = row[6];
            }
            if(row[7] != null){
              LocationText = LocationText + '-' + row[7];
            }
            if(row[8] != null){
              LocationText = LocationText + '-' + row[8];
            }
            if(row[9] != null){
              LocationText = LocationText + '-' + row[9];
            }
            var inputchar = '<a href="#" style="text-decoration:none; color:inherit;">'+LocationText+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = row[2];
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<div id=\"OnHand[' + row[0] + ',' + row[5] + ']\">' + row[3] + '</div>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = row[4];
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var ans = row[0] + row[5];
            if(ItemIDArray != null){
              var n = ItemIDArray.indexOf(ans);
            }
            var loc = row[5];

            if(n != null){
              var QuantityChosen = QuantityUsed[n];
              var l = InventoryLocationID[n];
            }

            if(l != loc){
                var QuantityChosen = '0';
            }
            var maxcalc = Math.abs(parseFloat(QuantityChosen)) + parseFloat(row[3]);
            var inputchar = '<input style=\"width: 80px;\" type=\"number\" onchange=\"UpdateWorkorderQty(' + row[0] + ',' + row[5] + ',<?php echo urldecode($_GET['workorderid']); ?>);\" class=\"form-control\" min="0" max="' + maxcalc + '" name=\"ItemQty[' + row[0] + row[5] + ']\" value=\"' + Math.abs(QuantityChosen) + '\">';
            return inputchar;
         }
        }],
      } );

      $('.dataTables_filter input').addClass('SearchPane');

    }

  }
    
  } );

  $(document).ready(function() {
    $('#getUpcomingPM').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getUpcomingPM.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        columnDefs: [ {
          targets: 3,
          render: function(data, type, row) {
          if (type === 'display' && data != null) {
            data = data.replace(/<(?:.|\\n)*?>/gm, '');
            if(data.length > 30) {
                return data.substr(0, 30) + '...';
              } else {
                return data;
              }
            } else {
              return data;
            }
          }
      }]
      } );
  } );

  $(document).ready(function() {
    $('#dataTable').DataTable( {
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
      } );
  } );

  $(document).ready(function() {
    $('#getAuditLocationList').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getAuditLocationList.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        'columnDefs': [
        {
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = row[1]+'-'+row[5]+'-'+row[6]+'-'+row[7];
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = row[2]+' '+row[8];
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
           if(row[4] == 1){
            var inputchar = '<a href="#" style="text-decoration:none; color:green;">Verified</a>';
           } else if(row[4] == 2) {
            var inputchar = '<a href="#" style="text-decoration:none; color:orange;">Unverified</a>';
           }
            return inputchar;
         }
        }]
      } );
  } );
</script>

<script>
$(document).ready(function() {
    $('#getUserRoles').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getUserRoles.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        'columnDefs': [
        {
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '  <td><input type="radio" id="UserRoleSelection" name="UserRoleSelection" value="'+row[-1]+'"></td>';
            return inputchar;
         }
        }]
      } );
  } );
</script>

<script>
$(document).ready(function() {
    $('#getControlGroups').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getControlGroups.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        'columnDefs': [
        {
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '  <td><input type="radio" id="UserGroupSelection" name="UserGroupSelection" value="'+row[-1]+'"></td>';
            return inputchar;
         }
        }]
      } );
  } );
</script>

<script>

  $(document).ready(function() {
    $('#getUserList').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getUserList.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      } );
  } );

  $(document).ready(function() {
    $('#getRoleList').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getRoleList.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      } );
  } );

  $(document).ready(function() {
    $('#getQuantitiesUsed').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getQuantitiesUsed.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      } );
  } );
  
  $(document).ready(function() {
    $('#getPMConducted').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPMConducted.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      } );
    
  } );

  $(document).ready(function() {
    $('#getPastDueWO').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPastDueWO.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        'columnDefs': [
        {
         'targets': 6,
         'render': function (data, type, row, meta){
           if(row[6] != null){
            var inputchar = '<a href="" style="text-decoration:none; color:inherit;">'+row[6]+ ' ' +row[8]+'</a>';
           } else {
            var inputchar = '<a href="" style="text-decoration:none; color:inherit;">Unassigned</a>';
           }
            return inputchar;
         }
        }],
      } );
    
  } );

  $(document).ready(function() {
    $('.dataTables_filter input[type="search"]').css(
     {'width':'400px','display':'inline-block'}
  );
    $('#getPastDuePM').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getPastDuePM.php",
        dom: 'Blfrtip',
        buttons: [ 'excel', 'pdf', 'copy' ],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        'columnDefs': [
        {
         'targets': 6,
         'render': function (data, type, row, meta){
           if(row[6] != null){
            var inputchar = '<a href="" style="text-decoration:none; color:inherit;">'+row[6]+ ' ' +row[8]+'</a>';
           } else {
            var inputchar = '<a href="" style="text-decoration:none; color:inherit;">Unassigned</a>';
           }
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getVendorListInActive').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getVendorListInActive.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[4]+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[5]+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getVendorListActive').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getVendorListActive.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[4]+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[5]+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getVendorList').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getVendorList.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[4]+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[5]+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="vendors.php?do=vendors&action=view&vendorid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getAssetListOOO').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getAssetListOOO.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'UP';
            } else {
              RosettaStone = 'DOWN';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[5] == '1'){
              RosettaStone = 'Yes';
            } else {
              RosettaStone = 'No';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getAssetListInActive').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getAssetListInActive.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'UP';
            } else {
              RosettaStone = 'DOWN';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[5] == '1'){
              RosettaStone = 'Yes';
            } else {
              RosettaStone = 'No';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getAssetListActive').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getAssetListActive.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'UP';
            } else {
              RosettaStone = 'DOWN';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[5] == '1'){
              RosettaStone = 'Yes';
            } else {
              RosettaStone = 'No';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );


$(document).ready(function() {
    $('#getAssetList').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getAssetList.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'UP';
            } else {
              RosettaStone = 'DOWN';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[5] == '1'){
              RosettaStone = 'Yes';
            } else {
              RosettaStone = 'No';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 6,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[6] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="assets.php?do=assets&action=view&assetid='+row[-1]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

  $(document).ready(function() {
    $('#getInventoryListLowStock').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryListLowStock.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryListOutOfStock').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryListOutOfStock.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryListIndividual').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryListIndividual.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryListBulk').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryListBulk.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryListAll').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryListAll.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var RosettaStone;
            if(row[4] == '1'){
              RosettaStone = 'Active';
            } else {
              RosettaStone = 'InActive';
            }
            var inputchar = '<a href="inventory.php?do=inventory&action=view&partid='+row[0]+'" style="text-decoration:none; color:inherit;">'+RosettaStone+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '  <td><input type="checkbox" id="LabelINVSelection" name="LabelINVSelection[]" value="'+row[0]+'"></td>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryOrdersFulfilled').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryOrdersFulfilled.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[4]+' '+row[6]+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[5]+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryOrdersPending').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryOrdersPending.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=vieworder&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=vieworder&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=vieworder&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=vieworder&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=vieworder&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[4]+' '+row[6]+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=vieworder&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[5]+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryOrdersReceiving').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryOrdersReceiving.php",
        'columnDefs': [
        {
         'targets': 0,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[0]+'</a>';
            return inputchar;
         }
        },{
         'targets': 1,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[1]+'</a>';
            return inputchar;
         }
        },{
         'targets': 2,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[2]+'</a>';
            return inputchar;
         }
        },{
         'targets': 3,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[3]+'</a>';
            return inputchar;
         }
        },{
         'targets': 4,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[4]+' '+row[6]+'</a>';
            return inputchar;
         }
        },{
         'targets': 5,
         'render': function (data, type, row, meta){
            var inputchar = '<a href="inventory.php?do=inventory&action=receive&orderid='+row[0]+'" style="text-decoration:none; color:inherit;">'+row[5]+'</a>';
            return inputchar;
         }
        }],
      } );
    
  } );

$(document).ready(function() {
    $('#getInventoryList').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server_side/scripts/getInventoryList.php",
        'columnDefs': [{
         'targets': 5,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, row, meta){
            var inputchar = '<input type="checkbox" onclick="ShowSelection(\''+row[0]+'\',\'' +row[1]+ '\');" id="InventorySelection" name="InventorySelection" value=\''+row[0]+'\'>';
            return inputchar;
         }
      }],
    } );
    
} );

$(document).ready(function() {
    $(".btn-danger").click(function(event) {
      $InventoryVendors = document.querySelectorAll(`[name="DeleteInventoryVendors"]`)
      console.log($InventoryVendors);
        if( !confirm('Are you sure that you want to submit the form') ) {
            event.preventDefault();
        }

    });
});

/*
$(document).ready(function() {
var InventoryView = document.getElementById("InventoryViewOrder");
if(typeof(InventoryView) != 'undefined' && InventoryView != null){
  var ViewWrapper = document.querySelector('div.InventoryViewOrderWrapper'); //Input field wrapper
  var fieldButtonHTML = `
          <br /> \
            <div class="row"> \
              <div class="col"> \
                <button type="submit" class="btn btn-warning" style="width:100%;" name="SubmitPurchaseOrder" value="">Order</button> \
              </div> \
            </div> \
          `; 
  $(ViewWrapper).append(fieldButtonHTML); //Add field html
}
});*/
</script>

  <!-- Page level plugins -->
  <!--  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script> -->

  <script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap4.min.js"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="../js/select2.min.js"></script>

  <!-- Part Select scripts WO View -->
<script>
	var select = document.getElementById("part_select");
	multi(select, {
    enable_search: true
	});
</script>

 <!-- Asset Select scripts WO Create 

<script>
	var select = document.getElementById("asset_select");
	multi(select, {
		enable_search: true
  });
</script> -->

<script>
$(document).ready(function() {
    $('.Multi-Select').select2({
      width: 'resolve'
    });
});

</script>

<script>
setTimeout(function() {
    $('#alert').fadeOut('slow');
}, 3000); // <-- time in milliseconds

setTimeout(function() {
    $('#alert-scans').fadeOut('slow');
}, 15000); // <-- time in milliseconds

setTimeout(function() {
    $('#alert-scans-quantity').fadeOut('slow');
}, 15000); // <-- time in milliseconds
</script>

<script>
//Maybe expand for size of PO field? Currently allows 33 characters before tab
 $('#ScanLocation').keyup(function() {
	 if(this.value.length == $(this).attr('maxlength')) {
		 $('#ScanItem').focus(); 
		}
  }); 

  $('#ScanItem').keyup(function() {
	 if(this.value.length == $(this).attr('maxlength')) {
		 $('#ScanQuantity').focus(); 
		}
	}); 

  $('#ScanItemCheck').keyup(function() {
    var Gumdrop = this.value.substring(0, 2);
    console.log(Gumdrop);
    if(Gumdrop == '~P'){
      if(this.value.length == $(this).attr('maxlength')) {
        $('.SearchPane').focus(); 
      }
    } else {
      if(this.value.length == 8) {
        $('.SearchPane').focus(); 
      }
    }
	}); 
</script>

<script>
// Find all inputs on the DOM which are bound to a datalist via their list attribute.
var inputs = document.querySelectorAll('input[list]');
for (var i = 0; i < inputs.length; i++) {
  // When the value of the input changes...
  inputs[i].addEventListener('change', function() {
    var optionFound = false,
      datalist = this.list;
    // Determine whether an option exists with the current value of the input.
    for (var j = 0; j < datalist.options.length; j++) {
        if (this.value == datalist.options[j].value) {
            optionFound = true;
            break;
        }
    }
    // use the setCustomValidity function of the Validation API
    // to provide an user feedback if the value does not exist in the datalist
    if (optionFound) {
      this.setCustomValidity('');
    } else {
      this.setCustomValidity('Please select a valid value.');
    }
  });
}
</script>

<script> function getAisle(val) { $.ajax({
type: "POST",
url: "get_aisle.php",
data:'room_id='+val,
success: function(data){
    $("#aisle-list").html(data);
}
});} </script>

<script> function getColumn(val) { $.ajax({
type: "POST",
url: "get_column.php",
data:'aisle_id='+val,
success: function(data){
    $("#column-list").html(data);
}
});} </script>

<script> function getShelf(val) { $.ajax({
type: "POST",
url: "get_shelf.php",
data:'column_id='+val,
success: function(data){
    $("#shelf-list").html(data);
}
});} </script>

<!--
Original... One per page...
<script>
document.querySelector('input[list]').addEventListener('input', function(e) {
    var input = e.target,
        list = input.getAttribute('list'),
        options = document.querySelectorAll('#' + list + ' option'),
        hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
        inputValue = input.value;

    hiddenInput.value = inputValue;

    for(var i = 0; i < options.length; i++) {
        var option = options[i];

        if(option.innerText === inputValue) {
            hiddenInput.value = option.getAttribute('data-value');
            break;
        }
    }
});
</script>-->

<script>
document.querySelectorAll('input[list]').forEach(input => input.addEventListener('input', function(e) {
    var input = e.target,
        list = input.getAttribute('list'),
        options = document.querySelectorAll('#' + list + ' option'),
        hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
        inputValue = input.value;

    hiddenInput.value = inputValue;

    for(var i = 0; i < options.length; i++) {
        var option = options[i];

        if(option.innerText === inputValue) {
            hiddenInput.value = option.getAttribute('data-value');
            break;
        }
    }
}));

$(window).on('load', function () {
  var WOText = $('#ApprovalChange').text();
    if(WOText == 'Denied'){
      $("select").attr("disabled", "disabled");
      $("input").attr("disabled", "disabled");
      $("textarea").attr("disabled", "disabled");
      document.querySelectorAll(`[name="UpdateWorkorderData"]`).forEach(el => el.remove());
    }
 });
</script>

</body>

</html>
