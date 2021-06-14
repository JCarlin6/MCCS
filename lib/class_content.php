<?php 

class Content{

    public static $db;


    /**
     * Content::__construct()
     *
     * @return
     */
    public function __construct()
    {
        self::$db = Registry::get("Database");
    }
    
    //Ajax Requests
    public function AddPONumber(){
        $LocationArray["VendorID"] = 'NULL';
        $LocationArray["ReqNumber"] = 'NULL';
        $LocationArray["PONumber"] = 'NULL';

        $SQLEntry = $this->InsertMultipleFields($LocationArray);
        $OrderNumber = self::$db->insert("Inventory_Detail_Order_Header", $SQLEntry);

        $return = $_POST;
        $return["OrderNumber"] = json_encode($OrderNumber);
        echo json_encode($return);
    }

    public function CheckWOInfo(){
        $WorkorderID = $_POST["WorkorderID"];

        $sql = "SELECT Inventory_Types.Type, Inventory_Detail.id, Inventory_Detail.QuantityOnOrder, Inventory_Detail.Description, Inventory_Detail.Active, Inventory_Detail.ModelNo, SUM(Inventory_Transaction_Summary.QuantityOnHand) AS QuantityOnHand, Vendor_Detail.Vendor_ID, Location_Inventory_Room.Name AS Room, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Column.Name AS ColumnName, Inventory_Detail_Location.id AS LocationID, Inventory_Transaction_Workorder_Summary.Quantity AS QuantityAffected, Inventory_Transaction_Summary.Location, Inventory_Transaction_Workorder_Summary.LocationPull FROM Inventory_Detail INNER JOIN Inventory_Types ON Inventory_Detail.Type = Inventory_Types.id LEFT OUTER JOIN Vendor_Detail ON Inventory_Detail.VendorID = Vendor_Detail.id CROSS JOIN Inventory_Detail_Location INNER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id LEFT OUTER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id LEFT OUTER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id LEFT OUTER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail.id = Inventory_Transaction_Summary.InventoryID INNER JOIN Inventory_Transaction_Workorder_Summary ON Inventory_Transaction_Workorder_Summary.InventoryID = Inventory_Transaction_Summary.InventoryID AND Inventory_Transaction_Workorder_Summary.LocationPull = Inventory_Detail_Location.id AND Inventory_Transaction_Summary.Location = Inventory_Detail_Location.id WHERE Inventory_Transaction_Workorder_Summary.Workorder = '$WorkorderID' AND Inventory_Transaction_Workorder_Summary.Quantity <> '0' AND Inventory_Detail_Location.id = Inventory_Transaction_Workorder_Summary.LocationPull GROUP BY Inventory_Detail.id, Inventory_Detail_Location.id";
        $reply = self::$db->fetch_all($sql);
        foreach ($reply AS $ReplyItem){
            $ItemID[] = $ReplyItem->id;
            $LocationID[] = $ReplyItem->LocationID;
            $Quantity[] = $ReplyItem->QuantityAffected;
            $Mixed[] = $ReplyItem->id . $ReplyItem->LocationID;
        }
        $return = $_POST;
        $return["Mixed"] = json_encode($Mixed);
        $return["ItemID"] = json_encode($ItemID);
        $return["LocationID"] = json_encode($LocationID);
        $return["QuantityUsed"] = json_encode($Quantity);
        echo json_encode($return);
    }

    public function AddPODetail(){
        $LocationArray["OrderNumber"] = $_POST["OrderNumber"];
        $LocationArray["InventoryID"] = $_POST["InventoryID"];
        $LocationArray["Quantity"] = 'NULL';

        $SQLEntry = $this->InsertMultipleFields($LocationArray);
        $OrderNumber = self::$db->insert("Inventory_Detail_Order_Detail", $SQLEntry);
    }

    public function RemovePODetail(){
        $OrderNumber = $_POST["OrderNumber"];
        $InventoryID = $_POST["InventoryID"];

        $sql = "DELETE FROM MCCS.Inventory_Detail_Order_Detail WHERE InventoryID='" . $InventoryID . "' AND OrderNumber='" . $OrderNumber . "'";
        $row = self::$db->query($sql);
    }

    public function UpdatePOPartDetail(){
        $OrderNumber = $_POST["OrderNumber"];
        $InventoryID = $_POST["InventoryID"];
        $PartQuantity = $_POST["PartQuantity"];
        $PartPrice = $_POST["PartPrice"];
        if(!empty($PartQuantity)){
            $sql = "UPDATE MCCS.Inventory_Detail_Order_Detail SET Quantity='$PartQuantity' WHERE InventoryID='" . $InventoryID . "' AND OrderNumber='" . $OrderNumber . "'";
            $row = self::$db->query($sql);
        }
        if(!empty($PartPrice)){
            $sql = "UPDATE MCCS.Inventory_Detail_Order_Detail SET Cost='$PartPrice' WHERE InventoryID='" . $InventoryID . "' AND OrderNumber='" . $OrderNumber . "'";
            $row = self::$db->query($sql);
        }
    }

    public function UpdatePOHeaderVendor(){
        $OrderNumber = $_POST["OrderNumber"];
        $VendorPost = $_POST["Vendor"];
        $VendorArray = explode('|', $VendorPost);
        $Vendor = $VendorArray[0];

        $sql = "SELECT id FROM MCCS.Vendor_Detail WHERE Vendor_ID='$Vendor'";
        $VendorQuery = self::$db->fetch_all($sql);
        if(!empty($VendorQuery)){
            $Vendor = $VendorQuery[0]->id;

            $sql = "UPDATE MCCS.Inventory_Detail_Order_Header SET VendorID='$Vendor' WHERE id='" . $OrderNumber . "'";
            $row = self::$db->query($sql);
        }
    }

    public function UpdatePOHeaderReq(){
        $OrderNumber = $_POST["OrderNumber"];
        $Req = $_POST["Req"];

        $sql = "UPDATE MCCS.Inventory_Detail_Order_Header SET ReqNumber='$Req' WHERE id='" . $OrderNumber . "'";
        $row = self::$db->query($sql);
    }

    public function UpdatePOHeaderPO(){
        $OrderNumber = $_POST["OrderNumber"];
        $PurchaseOrder = $_POST["PONumber"];

        $sql = "UPDATE MCCS.Inventory_Detail_Order_Header SET PONumber='$PurchaseOrder' WHERE id='" . $OrderNumber . "'";
        $row = self::$db->query($sql);
    }

    public function UpdateOrderPartTaxation(){
        $OrderNumber = $_POST["OrderNumber"];
        $InventoryID = $_POST["InventoryID"];
        $Taxable = $_POST["Taxable"];
        if($Taxable == "True"){
            $Taxable = "1";
        } else {
            $Taxable = "2";
        }

        $sql = "UPDATE MCCS.Inventory_Detail_Order_Detail SET Taxable='$Taxable' WHERE OrderNumber='" . $OrderNumber . "' AND InventoryID='" . $InventoryID . "'";
        $row = self::$db->query($sql);
    }

    public function UpdateOrderNoteDetail(){
        $OrderNumber = $_POST["OrderNumber"];
        $Note = $_POST["Note"];

        $sql = "UPDATE MCCS.Inventory_Detail_Order_Header SET Notes='$Note' WHERE id='" . $OrderNumber . "'";
        $row = self::$db->query($sql);
    }

    public function SubmitPurchaseOrder(){
        $OrderNumber = $_POST["OrderNumber"];
        $sql = "SELECT count(id) AS OrderTotal FROM MCCS.Inventory_Detail_Order_Detail WHERE OrderNumber='$OrderNumber'";
        $TotalOrderCountQuery = self::$db->fetch_all($sql);
        $TotalOrderCount = $TotalOrderCountQuery[0]->OrderTotal;

        $sql = "UPDATE MCCS.Inventory_Detail_Order_Header SET OrderTotal='$TotalOrderCount', Pending='1' WHERE id='" . $OrderNumber . "'";
        $row = self::$db->query($sql);

        //Get parts in order and update On-Order Table
        $sql = "SELECT InventoryID, Quantity FROM MCCS.Inventory_Detail_Order_Detail WHERE OrderNumber='$OrderNumber'";
        $PartQueryArray = self::$db->fetch_all($sql);
              
        foreach($PartQueryArray AS $InventoryID){
            $LocationArray["OrderID"] = $OrderNumber;
            $LocationArray["InventoryID"] = $InventoryID->InventoryID;
            $LocationArray["QuantityOrdered"] = $InventoryID->Quantity;
            $LocationArray["QuantityRemaining"] = $InventoryID->Quantity;
            $LocationArray["UID"] = $_SESSION["UID"];
            $LocationArray["OrderDate"] = 'NOW()';

            $SQLEntry = $this->InsertMultipleFields($LocationArray);
            self::$db->insert("Inventory_Transaction_OnOrder", $SQLEntry);
        }

        $newURL = "inventory.php?do=inventory&action=vieworder&orderid=$OrderNumber&msg=PartsOrdered";
        header("Location:/$newURL");
    }

    public function AddUser(){
        $UserArray["FirstName"] = $_POST["FirstName"];
        $UserArray["LastName"] = $_POST["LastName"];
        $UserArray["UserName"] = $_POST["UserName"];
        $UserArray["Password"] = SHA1($_POST["Password"]);
        $UserArray["Email"] = $_POST["Email"];
        $UserArray["Default_Facility"] = '1';
        $UserArray["Active"] = '1';

        $SQLEntry = $this->InsertMultipleFields($UserArray);
        self::$db->insert("user", $SQLEntry);

        $newURL = "controls.php?do=controller&action=users&msg=UserAdded";
        header("Location:/$newURL");
    }

    public function OrderListDetail($OrderID){
        $sql = "SELECT Vendor_Detail.Vendor_ID, Inventory_Detail_Order_Header.ReqNumber, Inventory_Detail_Order_Detail.Cost AS Cost, TRUNCATE(Cost/2, 3) AS CostPerPart, Inventory_Detail_Order_Header.PONumber, User.FirstName, User.LastName, Inventory_Transaction_OnOrder.OrderDate, Inventory_Transaction_OnOrder.OrderID, Inventory_Transaction_OnOrder.QuantityOrdered, Inventory_Transaction_OnOrder.QuantityRemaining, Inventory_Detail_Order_Detail.InventoryID, Inventory_Detail.Description FROM Inventory_Detail_Order_Header INNER JOIN Inventory_Transaction_OnOrder ON Inventory_Detail_Order_Header.id = Inventory_Transaction_OnOrder.OrderID INNER JOIN Vendor_Detail ON Vendor_Detail.id = Inventory_Detail_Order_Header.VendorID INNER JOIN User ON Inventory_Transaction_OnOrder.UID = User.id INNER JOIN Inventory_Detail_Order_Detail ON Inventory_Detail_Order_Header.id = Inventory_Detail_Order_Detail.OrderNumber INNER JOIN Inventory_Detail ON Inventory_Detail_Order_Detail.InventoryID = Inventory_Detail.id WHERE Inventory_Transaction_OnOrder.Fulfilled IS NULL AND Inventory_Detail_Order_Header.id = '$OrderID'";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function AssetClassList(){
        $sql = "SELECT Assets_Classes.id, Assets_Classes.Name FROM Assets_Classes WHERE Assets_Classes.Active = '1'";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function OrderListDetailPending($OrderID){
        $sql = "SELECT Vendor_Detail.Vendor_ID, Inventory_Detail_Order_Header.ReqNumber, Inventory_Detail_Order_Header.Notes, Inventory_Detail.Taxable, Inventory_Detail_Order_Detail.Taxable AS Taxable_Detail, Inventory_Detail_Order_Detail.Cost AS Cost, TRUNCATE(Inventory_Detail_Order_Detail.Cost / Inventory_Detail_Order_Detail.Quantity, 3) AS CostPerPart, Inventory_Detail_Order_Header.PONumber, Inventory_Detail_Order_Header.Pending, Inventory_Detail_Order_Header.Ordered, Inventory_Detail_Order_Header.Denied, User.FirstName, User.LastName, Inventory_Transaction_OnOrder.OrderDate, Inventory_Transaction_OnOrder.OrderID, Inventory_Detail_Order_Detail.Quantity AS QuantityOrdered, Inventory_Transaction_OnOrder.QuantityRemaining, Inventory_Detail_Order_Detail.InventoryID, Inventory_Detail.Description FROM Inventory_Detail_Order_Header INNER JOIN Inventory_Transaction_OnOrder ON Inventory_Detail_Order_Header.id = Inventory_Transaction_OnOrder.OrderID INNER JOIN Vendor_Detail ON Vendor_Detail.id = Inventory_Detail_Order_Header.VendorID INNER JOIN User ON Inventory_Transaction_OnOrder.UID = User.id INNER JOIN Inventory_Detail_Order_Detail ON Inventory_Detail_Order_Header.id = Inventory_Detail_Order_Detail.OrderNumber INNER JOIN Inventory_Detail ON Inventory_Detail_Order_Detail.InventoryID = Inventory_Detail.id AND Inventory_Detail_Order_Detail.InventoryID = Inventory_Transaction_OnOrder.InventoryID WHERE Inventory_Detail_Order_Header.id = '$OrderID'";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function ADorLocal(){
        $sql = "SELECT ActiveDirectory FROM _sitesettings";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function WorkOrderItemsSelected($Workorder){
        $sql = "SELECT Inventory_Detail.id, Inventory_Detail_Location.id AS LocationID, ABS(Inventory_Transaction_Workorder_Summary.Quantity) AS Quantity FROM Inventory_Detail INNER JOIN Inventory_Types ON Inventory_Detail.Type = Inventory_Types.id LEFT OUTER JOIN Vendor_Detail ON Inventory_Detail.VendorID = Vendor_Detail.id INNER JOIN Inventory_Detail_Location ON Inventory_Detail_Location.InventoryID = Inventory_Detail.id INNER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id LEFT OUTER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id LEFT OUTER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id LEFT OUTER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location INNER JOIN Inventory_Transaction_Workorder_Summary ON Inventory_Detail.id = Inventory_Transaction_Workorder_Summary.InventoryID WHERE Inventory_Transaction_Workorder_Summary.Workorder = '$Workorder' GROUP BY Inventory_Detail.id, Inventory_Detail_Location.id";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function WorkOrderChecklist($Workorder){
        $sql = "SELECT Workorder_PM_Checklist.Signer, Workorder_PM_Checklist.Notes, Workorder_PM_Checklist.Modified, Workorder_PM_Checklist.id, Workorder_PM_Checklist_Header.ChecklistDescription, User.FirstName, User.LastName FROM Workorder_PM_Checklist INNER JOIN Workorder_PM_Checklist_Header ON Workorder_PM_Checklist.ChecklistItem = Workorder_PM_Checklist_Header.id LEFT OUTER JOIN User ON Workorder_PM_Checklist.Signer = User.id WHERE Workorder_PM_Checklist.Workorder = '$Workorder'";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //User Permissions
    public function UserPermissions(){
        $UID = $_SESSION["UID"];
        $sql = "SELECT User.FirstName, User.LastName, User_Roles.Level, User_Roles.Description FROM User_Group_Details INNER JOIN User_Roles ON User_Group_Details.Role = User_Roles.id INNER JOIN User ON User.id = User_Group_Details.UID WHERE User.id='$UID' ORDER BY User_Roles.`Level` ASC";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function UserPermissionCheck($UID){
        $sql = "SELECT User_Roles.Level FROM User_Group_Details INNER JOIN User_Roles ON User_Group_Details.Role = User_Roles.id WHERE User_Group_Details.UID ='$UID' ORDER BY User_Roles.`Level` ASC";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }
    
    //Top Level Lists

        //Location Audit
        public function AuditInventoryLocation($AuditID){
            $sql = "SELECT User.FirstName, User.LastName, Audit_Location_Detail.InventoryID, Audit_Location_Detail.InspectedQty, Audit_Location_Detail.Modified, Audit_Location_Detail.Status, Inventory_Detail_Location.id, Inventory_Transaction_Summary.QuantityOnHand FROM Audit_Location_Detail LEFT OUTER JOIN User ON Audit_Location_Detail.Inspector = User.id INNER JOIN Audit_Location_Header ON Audit_Location_Detail.AuditID = Audit_Location_Header.id INNER JOIN Inventory_Detail_Location ON Audit_Location_Header.Room = Inventory_Detail_Location.Room AND Audit_Location_Header.Aisle = Inventory_Detail_Location.Aisle AND Audit_Location_Header.`Column` = Inventory_Detail_Location.`Column` AND Audit_Location_Header.Shelf = Inventory_Detail_Location.Shelf AND Audit_Location_Detail.InventoryID = Inventory_Detail_Location.InventoryID INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.InventoryID = Inventory_Transaction_Summary.InventoryID AND Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location WHERE Audit_Location_Detail.AuditID = '$AuditID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function AuditInventoryLocationItem($AuditID, $InventoryID){
            $sql = "SELECT User.FirstName, User.LastName, Audit_Location_Detail.InventoryID, Audit_Location_Detail.InspectedQty, Audit_Location_Detail.Modified, Audit_Location_Detail.Status, Inventory_Detail_Location.id, Inventory_Transaction_Summary.QuantityOnHand FROM Audit_Location_Detail LEFT OUTER JOIN User ON Audit_Location_Detail.Inspector = User.id INNER JOIN Audit_Location_Header ON Audit_Location_Detail.AuditID = Audit_Location_Header.id INNER JOIN Inventory_Detail_Location ON Audit_Location_Header.Room = Inventory_Detail_Location.Room AND Audit_Location_Header.Aisle = Inventory_Detail_Location.Aisle AND Audit_Location_Header.`Column` = Inventory_Detail_Location.`Column` AND Audit_Location_Header.Shelf = Inventory_Detail_Location.Shelf AND Audit_Location_Detail.InventoryID = Inventory_Detail_Location.InventoryID INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.InventoryID = Inventory_Transaction_Summary.InventoryID AND Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location WHERE Audit_Location_Detail.AuditID = '$AuditID' AND Audit_Location_Detail.InventoryID = ' $InventoryID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function AuditInventoryCurrentCount($AuditID, $InventoryID){
            $sql = "SELECT InspectedQty FROM Audit_Location_Detail WHERE Audit_Location_Detail.AuditID = '$AuditID' AND Audit_Location_Detail.InventoryID = ' $InventoryID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function AuditInventoryCheckAll($AuditID){
            $sql = "SELECT User.FirstName, User.LastName, Audit_Location_Detail.InventoryID, Audit_Location_Detail.InspectedQty, Audit_Location_Detail.Modified, Audit_Location_Detail.Status, Inventory_Detail_Location.id, Inventory_Transaction_Summary.QuantityOnHand FROM Audit_Location_Detail LEFT OUTER JOIN User ON Audit_Location_Detail.Inspector = User.id INNER JOIN Audit_Location_Header ON Audit_Location_Detail.AuditID = Audit_Location_Header.id INNER JOIN Inventory_Detail_Location ON Audit_Location_Header.Room = Inventory_Detail_Location.Room AND Audit_Location_Header.Aisle = Inventory_Detail_Location.Aisle AND Audit_Location_Header.`Column` = Inventory_Detail_Location.`Column` AND Audit_Location_Header.Shelf = Inventory_Detail_Location.Shelf AND Audit_Location_Detail.InventoryID = Inventory_Detail_Location.InventoryID INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.InventoryID = Inventory_Transaction_Summary.InventoryID AND Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location WHERE Audit_Location_Detail.AuditID = '$AuditID' AND Audit_Location_Detail.InspectedQty <> Inventory_Transaction_Summary.QuantityOnHand";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Location Audit
        public function AuditLocationList(){
            $sql = "SELECT Inventory_Detail.id AS InventoryID, Inventory_Detail.Description, Inventory_Transaction_Summary.QuantityOnHand, Location_Inventory_Room.Name AS Room, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Column.Name AS `Column`, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Column.id AS Column_ID, Location_Inventory_Shelf.id AS Shelf_ID FROM Inventory_Detail INNER JOIN Inventory_Detail_Location ON Inventory_Detail.id = Inventory_Detail_Location.InventoryID INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location AND Inventory_Detail_Location.InventoryID = Inventory_Transaction_Summary.InventoryID INNER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id INNER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id INNER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id INNER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id WHERE Inventory_Transaction_Summary.QuantityOnHand > 0 GROUP BY Location_Inventory_Room.Name, Location_Inventory_Aisle.Name, Location_Inventory_Column.Name, Location_Inventory_Shelf.Name";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Inventory Locations by ID
        
        public function InventoryLocationbyID($InventoryID){
            $sql = "SELECT Inventory_Detail_Location.id, Inventory_Detail_Location.InventoryID, Location_Inventory_Room.Name AS Room_Name, Inventory_Detail_Location.Room AS Room_ID, Location_Inventory_Aisle.Name AS Aisle_Name, Inventory_Detail_Location.Aisle AS Aisle_ID, Location_Inventory_Column.Name AS Column_Name, Inventory_Detail_Location.`Column` AS Column_ID, Location_Inventory_Shelf.Name AS Shelf_Name, Inventory_Detail_Location.Shelf AS Shelf_ID, Inventory_Detail_Location.`Default` AS `Default`, Inventory_Transaction_Summary.QuantityOnHand FROM Inventory_Detail_Location LEFT OUTER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id LEFT OUTER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id LEFT OUTER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id LEFT OUTER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.InventoryID = Inventory_Transaction_Summary.InventoryID AND Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location WHERE Inventory_Detail_Location.InventoryID = $InventoryID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function StartAudit(){
            $InventoryLocationArray = $_POST["InventoryLocationInput-hidden"];
            $InventoryLocation = explode(',', $InventoryLocationArray);
            $NamedArray["Room"] = $InventoryLocation[0];
            $Room = $InventoryLocation[0];
            $NamedArray["Aisle"] = $InventoryLocation[1];
            $Aisle = $InventoryLocation[1];
            $NamedArray["Column"] = $InventoryLocation[2];
            $Column = $InventoryLocation[2];
            $NamedArray["Shelf"] = $InventoryLocation[3];
            $Shelf = $InventoryLocation[3];
            $NamedArray["Inspector"] = $_SESSION["UID"];
            $NamedArray["Status"] = "2";
            //Create new header and grab the ID of audit
            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            $AuditID = self::$db->insert("Audit_Location_Header", $SQLEntry);

            //Find all parts that are at this location 
            $sql = "SELECT Location_Inventory_Column.Name AS `Column`, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Room.Name AS Room, Inventory_Detail_Location.InventoryID FROM User, Inventory_Detail_Location INNER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id INNER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id INNER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id INNER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id WHERE Location_Inventory_Room.id = '$Room' AND Location_Inventory_Aisle.id = '$Aisle' AND Location_Inventory_Column.id = '$Column' AND Location_Inventory_Shelf.id = '$Shelf' GROUP BY InventoryID";
            $row = self::$db->fetch_all($sql);

            unset($NamedArray, $SQLEntry);
            foreach($row AS $RowItem){
                $NamedArray["InventoryID"] = $RowItem->InventoryID;
                $NamedArray["AuditID"] = $AuditID;
                $NamedArray["Status"] = "2";

                $SQLEntry = $this->InsertMultipleFields($NamedArray);
                self::$db->insert("Audit_Location_Detail", $SQLEntry);
                unset($NamedArray, $SQLEntry);
            }

            $newURL = "audit.php?do=audit&action=locationaudit&auditid=$AuditID";
            header("Location:/$newURL");
        }

        public function DeleteInventoryLocation(){
            $InventoryID = $_POST["InventoryID"];
            $LocationID = $_POST["DeleteInventoryLocation"];

            $sql = "DELETE FROM MCCS.Inventory_Detail_Location WHERE id='" . $LocationID . "'";
            $row = self::$db->query($sql);

            $sql = "DELETE FROM MCCS.Inventory_Transaction_Summary WHERE InventoryID='" . $InventoryID . "' AND Location='" . $LocationID . "'";
            $row = self::$db->query($sql);

            $newURL = "inventory.php?do=inventory&action=view&partid=$InventoryID&msg=UpdatedInventory";
            header("Location:/$newURL");
        }

        //List Facilities
        public function ListFacilities(){
            $sql = "SELECT * FROM location_facility WHERE `disabled` IS NULL";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Department Lists By Facility
        public function DepartmentByFacility($Facility){
            $sql = "SELECT Location_Department.Name AS Department, Location_Department.id AS Department_ID, Location_Sub_Department.Disabled, Location_Sub_Department.Name AS Sub_Department, Location_Sub_Department.id AS Sub_Department_ID FROM Location_Sub_Department INNER JOIN Location_Department ON Location_Sub_Department.Department = Location_Department.id WHERE Location_Department.Facility = '$Facility'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function DepartmentByFacilityActive($Facility){
            $sql = "SELECT Location_Department.Name AS Department, Location_Department.id AS Department_ID, Location_Sub_Department.Disabled, Location_Sub_Department.Name AS Sub_Department, Location_Sub_Department.id AS Sub_Department_ID FROM Location_Sub_Department INNER JOIN Location_Department ON Location_Sub_Department.Department = Location_Department.id WHERE Location_Department.Facility = '$Facility' AND (Location_Department.Disabled IS NULL OR Location_Sub_Department.Disabled IS NULL)";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function DepartmentByFacilityDisabled($Facility){
            $sql = "SELECT Location_Department.Name AS Department, Location_Department.id AS Department_ID, Location_Sub_Department.Disabled, Location_Sub_Department.Name AS Sub_Department, Location_Sub_Department.id AS Sub_Department_ID FROM Location_Sub_Department INNER JOIN Location_Department ON Location_Sub_Department.Department = Location_Department.id WHERE Location_Department.Facility = '$Facility' AND (Location_Department.Disabled IS NOT NULL OR Location_Sub_Department.Disabled IS NOT NULL)";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Inventory Location Lists
        public function InventoryLocationByFacility($Facility){
            $sql = "SELECT Location_Inventory_Room.Name AS Room, Location_Inventory_Room.Disabled AS Room_Disabled, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Aisle.Disabled AS Aisle_Disabled, Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Column.Name AS `Column`, Location_Inventory_Column.Disabled AS Column_Disabled, Location_Inventory_Column.id AS Column_ID, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Shelf.Disabled AS Shelf_Disabled, Location_Inventory_Shelf.id AS Shelf_ID FROM Location_Inventory_Room LEFT OUTER JOIN Location_Inventory_Aisle ON Location_Inventory_Room.id = Location_Inventory_Aisle.Room LEFT OUTER JOIN Location_Inventory_Column ON Location_Inventory_Aisle.id = Location_Inventory_Column.Aisle LEFT OUTER JOIN Location_Inventory_Shelf ON Location_Inventory_Column.id = Location_Inventory_Shelf.`Column` WHERE Location_Inventory_Room.Facility = '$Facility' ORDER BY Room, Aisle, `Column`, Shelf";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function InventoryLocationByActive($Facility){
            $sql = "SELECT Location_Inventory_Room.Name AS Room, Location_Inventory_Room.Disabled AS Room_Disabled, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Aisle.Disabled AS Aisle_Disabled, Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Column.Name AS `Column`, Location_Inventory_Column.Disabled AS Column_Disabled, Location_Inventory_Column.id AS Column_ID, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Shelf.Disabled AS Shelf_Disabled, Location_Inventory_Shelf.id AS Shelf_ID FROM Location_Inventory_Room LEFT OUTER JOIN Location_Inventory_Aisle ON Location_Inventory_Room.id = Location_Inventory_Aisle.Room LEFT OUTER JOIN Location_Inventory_Column ON Location_Inventory_Aisle.id = Location_Inventory_Column.Aisle LEFT OUTER JOIN Location_Inventory_Shelf ON Location_Inventory_Column.id = Location_Inventory_Shelf.`Column` WHERE Location_Inventory_Room.Facility = '$Facility' AND (Location_Inventory_Room.Disabled IS NULL OR Location_Inventory_Aisle.Disabled IS NULL OR Location_Inventory_Column.Disabled IS NULL OR Location_Inventory_Shelf.Disabled IS NULL) ORDER BY Room, Aisle, `Column`, Shelf";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function InventoryLocationByDisabled($Facility){
            $sql = "SELECT Location_Inventory_Room.Name AS Room, Location_Inventory_Room.Disabled AS Room_Disabled, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Aisle.Disabled AS Aisle_Disabled, Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Column.Name AS `Column`, Location_Inventory_Column.Disabled AS Column_Disabled, Location_Inventory_Column.id AS Column_ID, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Shelf.Disabled AS Shelf_Disabled, Location_Inventory_Shelf.id AS Shelf_ID FROM Location_Inventory_Room LEFT OUTER JOIN Location_Inventory_Aisle ON Location_Inventory_Room.id = Location_Inventory_Aisle.Room LEFT OUTER JOIN Location_Inventory_Column ON Location_Inventory_Aisle.id = Location_Inventory_Column.Aisle LEFT OUTER JOIN Location_Inventory_Shelf ON Location_Inventory_Column.id = Location_Inventory_Shelf.`Column` WHERE Location_Inventory_Room.Facility = '$Facility' AND (Location_Inventory_Room.Disabled IS NOT NULL OR Location_Inventory_Aisle.Disabled IS NOT NULL OR Location_Inventory_Column.Disabled IS NOT NULL OR Location_Inventory_Shelf.Disabled IS NOT NULL) ORDER BY Room, Aisle, `Column`, Shelf";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Vendor Records
        public function getVendorList(){
            $sql = "SELECT Vendor_Type.NameType AS Type, Vendor_Detail.id, Vendor_Detail.Vendor_ID, Vendor_Detail.Name, Vendor_Detail.Phone, Vendor_Detail.City, Vendor_Detail.State, Vendor_Detail.Active FROM Vendor_Detail INNER JOIN Vendor_Type ON Vendor_Detail.Type = Vendor_Type.id";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getVendorActiveList(){
            $sql = "SELECT Vendor_Type.NameType AS Type, Vendor_Detail.id, Vendor_Detail.Vendor_ID, Vendor_Detail.Name, Vendor_Detail.Phone, Vendor_Detail.City, Vendor_Detail.State, Vendor_Detail.Active FROM Vendor_Detail INNER JOIN Vendor_Type ON Vendor_Detail.Type = Vendor_Type.id WHERE Active='1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getVendorInActiveList(){
            $sql = "SELECT Vendor_Type.NameType AS Type, Vendor_Detail.id, Vendor_Detail.Vendor_ID, Vendor_Detail.Name, Vendor_Detail.Phone, Vendor_Detail.City, Vendor_Detail.State, Vendor_Detail.Active FROM Vendor_Detail INNER JOIN Vendor_Type ON Vendor_Detail.Type = Vendor_Type.id WHERE Active<>'1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function VendorPageInfo($VendorTableID){
            $sql = "SELECT Location_Facility.id AS Facility_ID, Location_Facility.Name AS Facility_Assigned, Vendor_Detail.Global, REPLACE(REPLACE(Vendor_Detail.Active, '0', 'Inactive'), '1', 'Active') Active, Vendor_Detail.State, Vendor_Detail.Country, Vendor_Detail.ZipCode, Vendor_Detail.City, Vendor_Detail.Street, Vendor_Detail.Name, Vendor_Detail.Website, Vendor_Detail.Email, Vendor_Detail.Fax, Vendor_Detail.Phone, Vendor_Detail.Vendor_ID, Vendor_Type.NameType FROM Vendor_Detail LEFT OUTER JOIN Location_Facility ON Vendor_Detail.Facility_Assigned = Location_Facility.id INNER JOIN Vendor_Type ON Vendor_Detail.Type = Vendor_Type.id WHERE Vendor_Detail.id = '$VendorTableID' LIMIT 1";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Asset Records
        public function getAssetList(){
            $sql = "SELECT Assets.id, Assets.Name, Assets.Description, Assets_Classes.Name AS AssetClassName, Location_Facility.Name AS FacilityName, Assets.Status, Assets.InService, Assets.Active FROM Assets INNER JOIN Assets_Classes ON Assets.AssetClass = Assets_Classes.id LEFT OUTER JOIN Location_Facility ON Assets.Facility = Location_Facility.id ";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getActiveAssetList(){
            $sql = "SELECT Assets.id, Assets.Name, Assets.Description, Assets_Classes.Name AS AssetClassName, Location_Facility.Name AS FacilityName, Assets.Status, Assets.InService, Assets.Active FROM Assets INNER JOIN Assets_Classes ON Assets.AssetClass = Assets_Classes.id LEFT OUTER JOIN Location_Facility ON Assets.Facility = Location_Facility.id WHERE Active='1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getInActiveAssetList(){
            $sql = "SELECT Assets.id, Assets.Name, Assets.Description, Assets_Classes.Name AS AssetClassName, Location_Facility.Name AS FacilityName, Assets.Status, Assets.InService, Assets.Active FROM Assets INNER JOIN Assets_Classes ON Assets.AssetClass = Assets_Classes.id LEFT OUTER JOIN Location_Facility ON Assets.Facility = Location_Facility.id WHERE Active='2'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getActiveOOOAssetList(){
            $sql = "SELECT Assets.id, Assets.Name, Assets.Description, Assets_Classes.Name AS AssetClassName, Location_Facility.Name AS FacilityName, Assets.Status, Assets.InService, Assets.Active FROM Assets INNER JOIN Assets_Classes ON Assets.AssetClass = Assets_Classes.id LEFT OUTER JOIN Location_Facility ON Assets.Facility = Location_Facility.id WHERE InService='2'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function AssetPageInfo($AssetTableID){
            $sql = "SELECT Location_Facility.id AS Facility_ID, Location_Facility.Name AS Facility_Assigned, Assets.Name, Assets.ModelNo, Assets.SerialNo, Assets.AssetNo, Assets.Description, Assets.AssetClass, Assets.Priority, Assets.Status, Assets.InService, Assets.Active, Assets.CostCenter, Assets.Vendor, Assets.Notes, Location_Department.Name AS Department, Location_Sub_Department.Name AS Sub_Department, Location_Department.id AS Department_ID, Location_Sub_Department.id AS Sub_Department_ID FROM Assets LEFT OUTER JOIN Location_Facility ON Assets.Facility = Location_Facility.id LEFT OUTER JOIN Location_Department ON Assets.Department = Location_Department.id LEFT OUTER JOIN Location_Sub_Department ON Assets.Sub_Department = Location_Sub_Department.id WHERE Assets.id = '$AssetTableID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Inventory List
        public function getInventoryList(){
            $sql = "SELECT Inventory_Types.Type, Inventory_Detail.id, Inventory_Detail.QuantityOnOrder, Inventory_Detail.Description, Inventory_Detail.Active, Inventory_Detail.ModelNo, SUM(Inventory_Transaction_Summary.QuantityOnHand) AS QuantityOnHand, Vendor_Detail.Vendor_ID FROM Inventory_Detail INNER JOIN Inventory_Types ON Inventory_Detail.Type = Inventory_Types.id LEFT OUTER JOIN Inventory_Transaction_Summary ON Inventory_Transaction_Summary.InventoryID = Inventory_Detail.id LEFT OUTER JOIN Vendor_Detail ON Inventory_Detail.VendorID = Vendor_Detail.id GROUP BY Inventory_Detail.id LIMIT 10";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getInventoryListWithLocation(){
            $sql = "SELECT Inventory_Types.Type, Inventory_Detail.id, Inventory_Detail.QuantityOnOrder, Inventory_Detail.Description, Inventory_Detail.Active, Inventory_Detail.ModelNo, SUM(Inventory_Transaction_Summary.QuantityOnHand) AS QuantityOnHand, Vendor_Detail.Vendor_ID, Location_Inventory_Room.Name AS Room, Location_Inventory_Aisle.Name AS Aisle, Location_Inventory_Shelf.Name AS Shelf, Location_Inventory_Column.Name AS ColumnName, Inventory_Detail_Location.id AS LocationID FROM Inventory_Detail INNER JOIN Inventory_Types ON Inventory_Detail.Type = Inventory_Types.id LEFT OUTER JOIN Vendor_Detail ON Inventory_Detail.VendorID = Vendor_Detail.id INNER JOIN Inventory_Detail_Location ON Inventory_Detail_Location.InventoryID = Inventory_Detail.id INNER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id LEFT OUTER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id LEFT OUTER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id LEFT OUTER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location GROUP BY Inventory_Detail.id, Inventory_Detail_Location.id";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getInventoryListOnOrder(){
            $sql = "SELECT Vendor_Detail.Vendor_ID, Inventory_Detail_Order_Header.ReqNumber, Inventory_Detail_Order_Header.PONumber, User.FirstName, User.LastName, Inventory_Transaction_OnOrder.OrderDate, Inventory_Transaction_OnOrder.OrderID FROM Inventory_Detail_Order_Header INNER JOIN Inventory_Transaction_OnOrder ON Inventory_Detail_Order_Header.id = Inventory_Transaction_OnOrder.OrderID INNER JOIN Vendor_Detail ON Vendor_Detail.id = Inventory_Detail_Order_Header.VendorID INNER JOIN User ON Inventory_Transaction_OnOrder.UID = User.id WHERE Inventory_Transaction_OnOrder.Fulfilled IS NULL AND Inventory_Detail_Order_Header.Ordered = '1' GROUP BY Inventory_Transaction_OnOrder.OrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getInventoryListFulfilled(){
            $sql = "SELECT Vendor_Detail.Vendor_ID, Inventory_Detail_Order_Header.ReqNumber, Inventory_Detail_Order_Header.PONumber, User.FirstName, User.LastName, Inventory_Transaction_OnOrder.OrderDate, Inventory_Transaction_OnOrder.OrderID FROM Inventory_Detail_Order_Header INNER JOIN Inventory_Transaction_OnOrder ON Inventory_Detail_Order_Header.id = Inventory_Transaction_OnOrder.OrderID INNER JOIN Vendor_Detail ON Vendor_Detail.id = Inventory_Detail_Order_Header.VendorID INNER JOIN User ON Inventory_Transaction_OnOrder.UID = User.id WHERE Inventory_Detail_Order_Header.Fulfilled = '1' AND Inventory_Detail_Order_Header.Ordered = '1' GROUP BY Inventory_Transaction_OnOrder.OrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }
        
        public function getInventoryListPending(){
            $sql = "SELECT Vendor_Detail.Vendor_ID, Inventory_Detail_Order_Header.ReqNumber, Inventory_Detail_Order_Header.PONumber, User.FirstName, User.LastName, Inventory_Transaction_OnOrder.OrderDate, Inventory_Transaction_OnOrder.OrderID FROM Inventory_Detail_Order_Header INNER JOIN Inventory_Transaction_OnOrder ON Inventory_Detail_Order_Header.id = Inventory_Transaction_OnOrder.OrderID INNER JOIN Vendor_Detail ON Vendor_Detail.id = Inventory_Detail_Order_Header.VendorID INNER JOIN User ON Inventory_Transaction_OnOrder.UID = User.id WHERE Inventory_Detail_Order_Header.Pending IS NOT NULL AND Inventory_Detail_Order_Header.Denied IS NULL GROUP BY Inventory_Transaction_OnOrder.OrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function InventoryVendorList($PartID){
            $sql = "SELECT Vendor_Detail.id, Vendor_Detail.Name, Vendor_Detail.Vendor_ID, Inventory_Detail_Vendor.VendorPartID FROM Inventory_Detail_Vendor INNER JOIN Vendor_Detail ON Inventory_Detail_Vendor.VendorID = Vendor_Detail.id WHERE Inventory_Detail_Vendor.InventoryID='$PartID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getInventoryActiveList(){
            $sql = "SELECT Inventory_Types.Type, Inventory_Detail.id, Inventory_Detail.Description, Inventory_Detail.Active, Inventory_Detail.ModelNo FROM Inventory_Detail INNER JOIN Inventory_Types ON Inventory_Detail.Type = Inventory_Types.id WHERE Inventory_Detail.Active='1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Inventory Page Info
        public function InventoryPageInfo($PartTableID){
            $sql = "SELECT User.LastName, User.FirstName, Inventory_Types.Type, Inventory_Detail.id, Inventory_Detail.Description, Inventory_Detail.Active, Inventory_Detail.ModelNo, Inventory_Detail.ReorderQuantity, Inventory_Detail.ReorderPoint, Inventory_Detail.Taxable, Inventory_Detail.Max, Inventory_Detail.Min, Inventory_Detail.ReorderMethod, Inventory_Detail.VendorPartID, Inventory_Detail.VendorID, Inventory_Detail.CreatedBy, Inventory_Detail.Notes, Inventory_Detail.BalanceAccount, Inventory_Detail.PartClass, Inventory_Detail.ManufacturerName, Inventory_Detail.UnitofWeight, Inventory_Detail.UnitofMeasure, Inventory_Detail.Weight, Inventory_Detail.MFRID, Inventory_Detail.MFRPartID, Inventory_Detail.PODescription, Inventory_Types.id AS TypeID, Vendor_Detail.Vendor_ID, Vendor_Detail.id AS VendorID, SUM(Inventory_Transaction_OnOrder.QuantityRemaining) AS OnOrder FROM Inventory_Detail LEFT OUTER JOIN Inventory_Types ON Inventory_Detail.Type = Inventory_Types.id LEFT OUTER JOIN Vendor_Detail ON Inventory_Detail.VendorID = Vendor_Detail.id LEFT OUTER JOIN User ON Inventory_Detail.CreatedBy = User.id LEFT OUTER JOIN Inventory_Transaction_OnOrder ON Inventory_Detail.id = Inventory_Transaction_OnOrder.InventoryID WHERE Inventory_Detail.id = '$PartTableID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function InventoryPartTypes(){
            $sql = "SELECT id, Type, Description FROM Inventory_Types WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Location Records
        public function AllAssetLocations(){
            $sql = "SELECT Location_Facility.Name AS Facility, Location_Department.Name AS Department, Location_Sub_Department.Name AS Sub_Department, Location_Department.Disabled AS Department_Disabled, Location_Sub_Department.Disabled AS Sub_Department_Disabled, Location_Facility.Disabled AS Facility_Disabled FROM Location_Department INNER JOIN Location_Facility ON Location_Department.Facility = Location_Facility.id LEFT OUTER JOIN Location_Sub_Department ON Location_Sub_Department.Department = Location_Department.id";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function ActiveAssetLocations(){
            $sql = "SELECT Location_Facility.Name AS Facility, Location_Department.Name AS Department, Location_Sub_Department.Name AS Sub_Department FROM Location_Department INNER JOIN Location_Facility ON Location_Department.Facility = Location_Facility.id LEFT OUTER JOIN Location_Sub_Department ON Location_Sub_Department.Department = Location_Department.id WHERE Location_Facility.Disabled IS NULL AND Location_Department.Disabled IS NULL AND Location_Sub_Department.Disabled IS NULL";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Workorder Records
        public function getAllWorkorders(){
            $sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id LEFT OUTER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id ORDER BY WorkOrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getAllOpenWorkorders(){
            //$sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id WHERE Workorder_Main.Status <> '11' ORDER BY WorkOrderID";
            $sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, SUBSTRING(DateSubmitted,1,10) AS SubmissionDate, DATEDIFF(NOW(),DateSubmitted) AS DateDiff, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id WHERE Workorder_Main.Status <> '11' ORDER BY WorkOrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getAllClosedWorkorders(){
            $sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id WHERE Workorder_Main.Status='11' ORDER BY WorkOrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getAllDeclinedWorkorders(){
            $sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id WHERE Workorder_Main.Status='3' ORDER BY WorkOrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getAllScheduledWorkorders(){
            $sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id WHERE Workorder_Main.Status='2' ORDER BY WorkOrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getAllInProcessWorkorders(){
            $sql = "SELECT User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, Workorder_Main.DateSubmitted, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.id AS WorkOrderID, Workorder_Main.AssignmentType, Assets.Description, Workorder_Main.Status, Workorder_Main.Priority, CategoricalPlacements_Detail.CategoryDetail AS StatusDetail, CategoricalPlacements_Detail_1.CategoryDetail AS PriorityDetail FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id INNER JOIN Workorder_AssetsSelected ON Workorder_AssetsSelected.WorkorderID = Workorder_Main.id INNER JOIN Assets ON Workorder_AssetsSelected.AssetDetailID = Assets.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.Status = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_Main.Priority = CategoricalPlacements_Detail_1.id WHERE Workorder_Main.Status='1' ORDER BY WorkOrderID";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

    //End Form AutoFill

    //Form AutoFill

    //Populate All States
    public function PopulateStates(){
        $sql = "SELECT * FROM Location_States";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //Populate Vendor Types
    public function PopulateVendorTypes(){
        $sql = "SELECT * FROM Vendor_Type";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //Populate City and State Names
    public function PopulateCities(){
        $sql = "SELECT id, City, State_Code FROM Location_Cities";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //WorkOrder Assignment Type
    public function PopulateWOAssignmentType(){
        $sql = "SELECT CategoricalPlacements_Detail.id, CategoricalPlacements_Detail.CategoryDetail FROM CategoricalPlacements_Detail WHERE CategoricalPlacements_Detail.CategoryName = 3";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //WorkOrder Priority
    public function PopulateWOPriority(){
        $sql = "SELECT CategoricalPlacements_Detail.id, CategoricalPlacements_Detail.CategoryDetail FROM CategoricalPlacements_Detail WHERE CategoricalPlacements_Detail.CategoryName = 2";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //WorkOrder Status
    public function PopulateWOStatus(){
        $sql = "SELECT CategoricalPlacements_Detail.id, CategoricalPlacements_Detail.CategoryDetail FROM CategoricalPlacements_Detail WHERE CategoricalPlacements_Detail.CategoryName = 1";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //WorkOrder List Assets (By Type) //This allows us to create categories
    public function PopulateWOAssets(){
        $sql = "SELECT Assets_Classes.Name AS Class_Name, Assets.Description, Assets.Name AS Asset_Name, Location_Facility.Name AS Facility_Name, Location_Department.Name AS Department_Name, Location_Sub_Department.Name AS Sub_Department_Name, Assets.id FROM Assets INNER JOIN Assets_Classes ON Assets.AssetClass = Assets_Classes.id LEFT OUTER JOIN Location_Sub_Department ON Assets.Department = Location_Sub_Department.Department AND Assets.Sub_Department = Location_Sub_Department.id LEFT OUTER JOIN Location_Department ON Location_Sub_Department.Department = Location_Department.id LEFT OUTER JOIN Location_Facility ON Location_Department.Facility = Location_Facility.id ORDER BY Class_Name";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    //WorkOrder List Details (View)
    public function PopulateWOInformation($WorkorderID){
        $sql = "SELECT Workorder_Main.HoursTaken, Workorder_Main.Authorized, Workorder_Main.Priority, Workorder_Main.AssignmentType, Workorder_Main.RequirePhoto, Workorder_Main.PhotoLocation, Workorder_Main.AssignedGroup, Workorder_Main.ActualEndDate, Workorder_Main.Status, User.id AS UserID, User.FirstName, User.LastName, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname, User_1.id AS Assigned_ID, Workorder_Main.IssueDescription, Workorder_Main.WorkDescription, Workorder_Main.DateLastEdited, Workorder_Main.RequestedEndDate, Workorder_Main.RequestedStartDate, CategoricalPlacements_Detail.id, CategoricalPlacements_Detail.CategoryName, User_1.id AS Assigned_UID, User.id AS UID, Workorder_Main.DateSubmitted, Workorder_Main.id AS WorkerOrderID FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id LEFT OUTER JOIN User User_1 ON Workorder_Main.AssignedUser = User_1.id LEFT OUTER JOIN CategoricalPlacements_Detail ON Workorder_Main.AssignmentType = CategoricalPlacements_Detail.CategoryDetail AND Workorder_Main.Priority = CategoricalPlacements_Detail.CategoryDetail AND Workorder_Main.Status = CategoricalPlacements_Detail.CategoryDetail WHERE Workorder_Main.id = '$WorkorderID'";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

        //WorkOrder List Details Continued (View) See Asset List (Multiple Possible)
        public function PopulateWOInformationAssets($WorkorderID){
            $sql = "SELECT Assets_Classes.Name, Assets.Description, Assets.Name, Location_Facility.Name, Location_Department.Name, Location_Sub_Department.Name, Assets.id FROM MCCS.Assets LEFT OUTER JOIN MCCS.Assets_Classes ON Assets.AssetClass = Assets_Classes.id LEFT OUTER JOIN MCCS.Location_Sub_Department ON Assets.Department = Location_Sub_Department.Department AND Assets.Sub_Department = Location_Sub_Department.id LEFT OUTER JOIN MCCS.Location_Department ON Location_Sub_Department.Department = Location_Department.id LEFT OUTER JOIN MCCS.Location_Facility ON Location_Department.Facility = Location_Facility.id INNER JOIN MCCS.Workorder_AssetsSelected ON Workorder_AssetsSelected.AssetDetailID = Assets.id WHERE Workorder_AssetsSelected.WorkorderID = '$WorkorderID'";
            $row = self::$db->fetch_all($sql);

            foreach($row AS $rowitem){
				$SimpleAssetArray[] = $rowitem->id;
			}

            return ($SimpleAssetArray) ? $SimpleAssetArray : 0;
        }

        //WorkOrder populate assigned user
        public function PopulateWOAssignedUser($WorkorderID){
            $sql = "SELECT User.FirstName, User.LastName, User.id AS UID, Workorder_WorkNotes.WorkNote, Workorder_WorkNotes.DateLastEdited, Workorder_WorkNotes.FileUploaded FROM Workorder_WorkNotes INNER JOIN User ON Workorder_WorkNotes.UserID = User.id WHERE WorkorderID = '$WorkorderID'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

    //GENERAL//

        //LIST ALL USERS 
        public function getUserListWithSelf(){
            $sql = "SELECT User.FirstName, User.LastName, User.id AS UID FROM User WHERE User.Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //LIST ALL GROUPS
        public function getAssignableGroups(){
            $sql = "SELECT id, Description FROM User_Group WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function getAssignableRoles(){
            $sql = "SELECT id, Description FROM User_Roles";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //LIST Units of Measure
        public function getUOM(){
            $sql = "SELECT id, Type, Description FROM Inventory_UnitofMeasures WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //LIST Vendor_ID's
        public function getVendorID(){
            $sql = "SELECT id, Vendor_ID, Name FROM Vendor_Detail WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //LIST Units of Weight
        public function getUOW(){
            $sql = "SELECT id, Type, Description FROM Inventory_UnitofWeights WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //LIST Reorder Methods
        public function getROM(){
            $sql = "SELECT id, Type FROM Inventory_ReorderMethods WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //LIST Part Classes
        public function getPartClasses(){
            $sql = "SELECT id, Class, Description FROM Inventory_Classes WHERE Active = '1'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }
        

    //END GENERAL//



    //End Form AutoFill

    public function UserGroupDeactivate(){
        $UserGroup = $_POST["UserGroupSelection"];
        //Determine if anything is checked
        if(!empty($UserGroup)){


            //Determine if assigned to anyone or exit

            //Determine if assigned to any PM's, WO's or exit

            //Delete ID if passed tests
            $SQL = "UPDATE user_group SET Active = '0' WHERE id = '$UserGroup'";
            echo $SQL;
            self::$db->query($SQL);
            $newURL = "controls.php?do=controller&action=groups&msg=GroupRemoved";
            header("Location:/$newURL");  
        } else {
            $newURL = "controls.php?do=controller&action=groups&msg=NothingChecked";
            header("Location:/$newURL");  
        }
    }

    public function UserRolesDelete(){
        $UserRole = $_POST["UserRoleSelection"];
        //Determine if anything is checked
        if(!empty($UserRole)){


            //Determine if assigned to anyone or exit

            //Determine if assigned to any PM's, WO's or exit

            //Delete ID if passed tests
            $SQL = "DELETE FROM user_roles WHERE id = '$UserRole'";
            self::$db->query($SQL);
            $newURL = "controls.php?do=controller&action=userroles&msg=RoleRemoved";
            header("Location:/$newURL");  
        } else {
            $newURL = "controls.php?do=controller&action=userroles&msg=NothingChecked";
            header("Location:/$newURL");  
        }
    }

    public function AddControlGroup(){
        $RoleArray["Description"] = $_POST["Group"];
        $RoleArray["Facility"] = $_POST["Facility"];
        $RoleArray["Active"] = 1;

        if(empty($_POST["Group"]) || empty($_POST["Facility"])){
            $newURL = "controls.php?do=controller&action=groups&msg=EmptyFields";
            header("Location:/$newURL");  
            die();
        }

        $SQLEntry = $this->InsertMultipleFields($RoleArray);
        $InventoryLocation = self::$db->insert("user_group", $SQLEntry);

        $newURL = "controls.php?do=controller&action=groups&msg=GroupAdded";
        header("Location:/$newURL");  
    }

    public function AddRoleGroup(){
        $RoleArray["Level"] = $_POST["Level"];
        $RoleArray["Group"] = $_POST["Groups"];
        $RoleArray["Description"] = $_POST["Role"];

        if(empty($_POST["Groups"]) || empty($_POST["Level"])){
            $newURL = "controls.php?do=controller&action=userroles&msg=EmptyFields";
            header("Location:/$newURL");  
            die();
        }

        $SQLEntry = $this->InsertMultipleFields($RoleArray);
        $InventoryLocation = self::$db->insert("user_roles", $SQLEntry);

        $newURL = "controls.php?do=controller&action=userroles";
        header("Location:/$newURL");  
    }

    //Update Forms
    public function DenyWOItemCall($WorkOrderID){
        $sql = "UPDATE MCCS.Workorder_Main SET Authorized = '2', `Status` = '3' WHERE id='" . $WorkOrderID . "'";
        $row = self::$db->query($sql);

        $sql = "SELECT User.FirstName, User.LastName, User.Email FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id WHERE Workorder_Main.id = '$WorkOrderID'";
        $RequestorInfo = self::$db->fetch_all($sql);
        $FirstName = $RequestorInfo[0]->FirstName;
        $LastName = $RequestorInfo[0]->LastName;
        $Email = $RequestorInfo[0]->Email;
        $Username = $_SESSION["Username"];
        $UsernameArray = explode('.',$Username);
        $POIFName = $UsernameArray[0];
        $POILName = $UsernameArray[1];
        $ToList[] = "$Email";

        include 'sitevariables.php';

        $body = " $FirstName, <br /><br /> A WorkOrder has been <u>declined</u> by: $POIFName $POILName under WorkOrder: $WorkOrderID. <br /><br /> Please visit the referenced WorkOrder by clicking this <a href=\"http://$SiteAddress/default.php?do=workorders&action=view&workorderid=$WorkorderID\">link</a><br /><br />  Thank you. ";
        $body = $this->MailNotice($body);
        $MailSubject = "System WorkOrder Declined";
        $this->InternaltoExternalMailCall($body, $MailSubject, $ToList);

        return;
    }

    public function DenyWOItem(){
        $WorkOrderID = $_POST["WorkOrderID"];
        $sql = "UPDATE MCCS.Workorder_Main SET Authorized = '2', `Status` = '3' WHERE id='" . $WorkOrderID . "'";
        $row = self::$db->query($sql);

        $sql = "SELECT User.FirstName, User.LastName, User.Email FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id WHERE Workorder_Main.id = '$WorkOrderID'";
        $RequestorInfo = self::$db->fetch_all($sql);
        $FirstName = $RequestorInfo[0]->FirstName;
        $LastName = $RequestorInfo[0]->LastName;
        $Email = $RequestorInfo[0]->Email;
        $Username = $_SESSION["Username"];
        $UsernameArray = explode('.',$Username);
        $POIFName = $UsernameArray[0];
        $POILName = $UsernameArray[1];
        $ToList[] = "$Email";

        include 'sitevariables.php';

        $body = " $FirstName, <br /><br /> A WorkOrder has been <u>declined</u> by: $POIFName $POILName under WorkOrder: $WorkOrderID. <br /><br /> Please visit the referenced WorkOrder by clicking this <a href=\"http://$SiteAddress/default.php?do=workorders&action=view&workorderid=$WorkorderID\">link</a><br /><br />  Thank you. ";
        $body = $this->MailNotice($body);
        $MailSubject = "System WorkOrder Declined";
        $this->InternaltoExternalMailCall($body, $MailSubject, $ToList);
    }

    public function ApproveWOItem(){
        $WorkOrderID = $_POST["WorkOrderID"];
        $sql = "UPDATE MCCS.Workorder_Main SET Authorized = '1' WHERE id='" . $WorkOrderID . "'";
        $row = self::$db->query($sql);
    }

    public function ChecklistItem(){
        $NamedArray["Signer"] = $_SESSION["UID"];
        $NamedArray["Modified"] = 'NOW()';
        $WorkorderID = $_POST['workorderid'];
        $ChecklistID = array_shift(array_keys($_POST['ChecklistItem']));

        $SQLEntry = $this->UpdateMultipleFields($NamedArray);
        $sql = "UPDATE MCCS.Workorder_PM_Checklist $SQLEntry WHERE id='" . $ChecklistID . "'";
        $row = self::$db->query($sql);

        $newURL = "default.php?do=workorders&action=view&workorderid=$WorkorderID";
        header("Location:/$newURL");       
    }

    public function AssignUser(){
        $NamedArray["Role"] = $_POST["AssignedGroup"];
        $NamedArray["UID"] = $_POST["AssignedUserInput-hidden"];

        $SQLEntry = $this->InsertMultipleFields($NamedArray);
        $InventoryLocation = self::$db->insert("User_Group_Details", $SQLEntry);

        $newURL = "controls.php?do=controller&action=usergroups";
        header("Location:/$newURL");  
    }

    public function AddInventoryVendor(){
        $NamedArray["VendorID"] = $_POST["VendorID"];
        $NamedArray["InventoryID"] = $_POST["InventoryID"];
        $InventoryID = $_POST["InventoryID"];
        $NamedArray["VendorPartID"] = $_POST["VendorPartID"];

        $SQLEntry = $this->InsertMultipleFields($NamedArray);
        $InventoryLocation = self::$db->insert("Inventory_Detail_Vendor", $SQLEntry);
    }

    public function DeleteInventoryVendor(){
        $VendorID = $_POST["VendorID"];
        $InventoryID = $_POST["InventoryID"];

        $sql = "DELETE FROM MCCS.Inventory_Detail_Vendor WHERE InventoryID='" . $InventoryID . "' AND VendorID='" . $VendorID . "'";
        $row = self::$db->query($sql);
    }

    public function OrderApprove(){
        $InternalOrder= $_POST["InternalOrder"];
        $NamedArray["Pending"] = NULL;
        $NamedArray["Ordered"] = '1';
        $PartPrices = $_POST["PartPrice"];

        //Add Prices together to get sum as opposed to looking it up then doing the math
        $TotalPrice = 0;
        foreach($PartPrices AS $PiecePrice){
            $TotalPrice = $TotalPrice + $PiecePrice;
        }
        $NamedArray["OrderTotal"] = $TotalPrice;
        //add update if value besides 0 exists

        $SQLEntry = $this->UpdateMultipleFields($NamedArray);
        $sql = "UPDATE MCCS.Inventory_Detail_Order_Header $SQLEntry WHERE id='" . $InternalOrder . "'";
        $row = self::$db->query($sql);

        $newURL = "inventory.php?do=inventory&action=vieworder&orderid=$InternalOrder&msg=Approved";
        header("Location:/$newURL");        
    }


    public function OrderParts(){
        /////// ANTIQUATED ////////
        $InventoryID = $_POST["InventorySelection"];
        $NamedArray["QuantityOnOrder"] = $_POST["OrderQuantity"];

        //add update if value besides 0 exists

        $SQLEntry = $this->UpdateMultipleFields($NamedArray);
        $sql = "UPDATE MCCS.Inventory_Detail $SQLEntry WHERE id='" . $InventoryID . "'";
        $row = self::$db->query($sql);

        $newURL = "inventory.php?do=inventory&action=receive&msg=markedasordered";
        header("Location:/$newURL");        
    }

    public function ReceiveScanCode(){
        $PartScan = $_POST["ReceivePartScan"];
        $PartScanArray = explode('|', $PartScan);
        $InventoryID = $_POST["InventorySelectionOnOrder"];
        $RoomLocation = abs($PartScanArray[0]);
        $AisleLocation = abs($PartScanArray[1]);
        $ColumnLocation = abs($PartScanArray[2]);
        $ShelfLocation = abs($PartScanArray[3]);
        $UID = $_SESSION["UID"];
        $Quantity = abs($_POST["ReceiveQuantity"]);

        //See if Location is already associated with InventoryID
        $CountArray = count($PartScanArray);
        if($CountArray == 1){
            $ADDTOSELECT = "AND Inventory_Detail_Location.Room='$RoomLocation'";
        } elseif($CountArray == 2) {
            $ADDTOSELECT = "AND Inventory_Detail_Location.Room='$RoomLocation' AND Inventory_Detail_Location.Aisle='$AisleLocation'";
        } elseif($CountArray == 3) {
            $ADDTOSELECT = "AND Inventory_Detail_Location.Room='$RoomLocation' AND Inventory_Detail_Location.Aisle='$AisleLocation' AND Inventory_Detail_Location.`Column`='$ColumnLocation'";
        } elseif($CountArray == 4) {
            $ADDTOSELECT = "AND Inventory_Detail_Location.Room='$RoomLocation' AND Inventory_Detail_Location.Aisle='$AisleLocation' AND Inventory_Detail_Location.`Column`='$ColumnLocation' AND Inventory_Detail_Location.Shelf='$ShelfLocation'";
        }

        $sql = "SELECT Inventory_Detail_Location.id, Inventory_Detail_Location.InventoryID, Inventory_Detail_Location.Room, Inventory_Detail_Location.Aisle, Inventory_Detail_Location.`Column`, Inventory_Detail_Location.Shelf FROM Inventory_Detail_Location WHERE Inventory_Detail_Location.InventoryID = '$InventoryID' $ADDTOSELECT";
        $row = self::$db->fetch_all($sql);

        //If empty then add to part inventory

        if(empty($row)){
            $LocationArray["InventoryID"] = $InventoryID;
            $LocationArray["Room"] = $RoomLocation;
            $LocationArray["Aisle"] = $AisleLocation;
            $LocationArray["Column"] = $ColumnLocation;
            $LocationArray["Shelf"] = $ShelfLocation;

            $SQLEntry = $this->InsertMultipleFields($LocationArray);
            $InventoryLocation = self::$db->insert("Inventory_Detail_Location", $SQLEntry);

            $LocationSummaryArray["InventoryID"] = $InventoryID;
            $LocationSummaryArray["Location"] = $InventoryLocation;
            $LocationSummaryArray["QuantityOnHand"] = '0';
            $LocationSummaryArray["UID"] = $UID;
            $LocationSummaryArray["Active"] = '1';

            $SQLEntry = $this->InsertMultipleFields($LocationSummaryArray);
            self::$db->insert("Inventory_Transaction_Summary", $SQLEntry);

        } else {
            $InventoryLocation = $row[0]->id;
        }

        unset($sql,$row);
         
        //Find Quantity-On-Order
        $sql = "SELECT QuantityOnOrder FROM Inventory_Detail WHERE id='$InventoryID'";
        $row = self::$db->fetch_all($sql);
        $QuantityOnOrder = abs($row[0]->QuantityOnOrder);

        if($Quantity > $QuantityOnOrder){
            //Fail and redirect
            $newURL = "inventory.php?do=inventory&action=receive&msg=QuantityReceivedGTOO";
            header("Location:/$newURL");       
            die();
        } else {
            //Start Transactions

            //Update On-Order
            $ResultingOnOrder = $QuantityOnOrder - $Quantity;
            $sql = "UPDATE MCCS.Inventory_Detail SET QuantityOnOrder='$ResultingOnOrder' WHERE id='" . $InventoryID . "'";
            $row = self::$db->query($sql);

            //Add Received Transaction
            $InventoryQuantityArray["InventoryID"] = $InventoryID;
            $InventoryQuantityArray["Location"] = $InventoryLocation;
            $InventoryQuantityArray["UID"] = $UID;
            $InventoryQuantityArray["QuantityAdjustment"] = $Quantity; 
            $InventoryQuantityArray["Comments"] = "Received";
    
            $SQLEntry = $this->InsertMultipleFields($InventoryQuantityArray);
            self::$db->insert("Inventory_Transaction", $SQLEntry);
    
            unset($InventoryQuantityArray);

            //Add Inventory Transaction Summary
            $sql = "SELECT QuantityOnHand FROM Inventory_Transaction_Summary WHERE InventoryID='" . $InventoryID . "' AND Location='" . $InventoryLocation . "'";
            $row = self::$db->fetch_all($sql);
    
            $OHAdjustment = $Quantity + $row[0]->QuantityOnHand;
    
            $InventoryQuantityArray["InventoryID"] = $InventoryID;
            $InventoryQuantityArray["Location"] = $InventoryLocation;
            $InventoryQuantityArray["QuantityOnHand"] = $OHAdjustment;
            $InventoryQuantityArray["UID"] = $UID;
            $SQLEntry = $this->UpdateMultipleFields($InventoryQuantityArray);
            $sql = "UPDATE MCCS.Inventory_Transaction_Summary $SQLEntry WHERE InventoryID='" . $InventoryID . "' AND Location='" . $InventoryLocation . "'";
            $row = self::$db->query($sql);

            unset($InventoryQuantityArray);
        }

        $newURL = "inventory.php?do=inventory&action=receive&msg=markedasreceived";
        header("Location:/$newURL");        
    }

    public function CreateInventoryData(){        
        $NamedArray["Description"] = $_POST["InventoryDescription"];
        $NamedArray["Type"] = $_POST["PartTypeInput-hidden"];
        $NamedArray["UnitofMeasure"] = $_POST["UnitofMeasure"];
        $NamedArray["UnitofWeight"] = $_POST["UnitofWeight"];
        $NamedArray["PartClass"] = $_POST["InventoryClass"];
        $NamedArray["Notes"] = $_POST["Notes"];
        $NamedArray["BalanceAccount"] = $_POST["BalanceAccount"];
        $NamedArray["PODescription"] = $_POST["PODescription"];
        $NamedArray["ManufacturerName"] = $_POST["ManufacturerName"];
        $NamedArray["MFRID"] = $_POST["ManufacturerID"];
        $NamedArray["MFRPartID"] = $_POST["ManufacturerPartID"];
        $NamedArray["Weight"] = $_POST["Weight"];
        $NamedArray["Min"] = $_POST["Min"];
        $NamedArray["Max"] = $_POST["Max"];
        $NamedArray["ReorderMethod"] = $_POST["ROM"];
        $NamedArray["Taxable"] = $_POST["Taxable"];
        $NamedArray["Bulk"] = $_POST["Bulk"];
        $NamedArray["BulkCount"] = $_POST["BulkCount"];
        $NamedArray["VendorPartID"] = $_POST["VendorPartID"];
        $NamedArray["VendorID"] = $_POST["VendorIDInput-hidden"];
        $NamedArray["ReorderPoint"] = $_POST["ReorderPoint"];
        $NamedArray["ReorderQuantity"] = $_POST["ReorderQuantity"];
        $NamedArray["Active"] = $_POST["Active"];
        $NamedArray["CreatedBy"] = $_SESSION["UID"];

        $SQLEntry = $this->InsertMultipleFields($NamedArray);
        $InventoryID = self::$db->insert("Inventory_Detail", $SQLEntry);

        if($_POST["Room"] != ""){
            $InventoryLocationArray["InventoryID"] = $InventoryID;
            $InventoryLocationArray["Room"] = $_POST["Room"];
            $InventoryLocationArray["Aisle"] = $_POST["aisle"];
            $InventoryLocationArray["Column"] = $_POST["column"];
            $InventoryLocationArray["Shelf"] = $_POST["shelf"];

            $SQLEntry = $this->InsertMultipleFields($InventoryLocationArray);
            $lastid = self::$db->insert("Inventory_Detail_Location", $SQLEntry);

            $InventoryQuantityArray["InventoryID"] = $InventoryID;
            $InventoryQuantityArray["Location"] = $lastid;
            $InventoryQuantityArray["Active"] = '1';
            if(isset($_POST["QuantityOHInsert"])){
                $InventoryQuantityArray["QuantityOnHand"] = $_POST["QuantityOHInsert"];

                $InventoryQuantityTArray["InventoryID"] = $InventoryID;
                $InventoryQuantityTArray["Location"] = $lastid;
                $InventoryQuantityTArray["Comments"] = "Initial Inventory Transaction";
                if($InventoryQuantityArray["QuantityOnHand"] != ""){
                    $InventoryQuantityTArray["QuantityAdjustment"] = $InventoryQuantityArray["QuantityOnHand"];
                }
                unset($SQLEntry);

                $SQLEntry = $this->InsertMultipleFields($InventoryQuantityTArray);
                self::$db->insert("Inventory_Transaction", $SQLEntry);

                unset($SQLEntry,$InventoryQuantityTArray);
            }

            $SQLEntry = $this->InsertMultipleFields($InventoryQuantityArray);
            self::$db->insert("Inventory_Transaction_Summary", $SQLEntry);

            unset($InventoryQuantityArray);
        }

        if($_POST["OHQty"] != ""){
            $OHQtyArray = $_POST["OHQty"];
            foreach($OHQtyArray AS $Key => $OHQtyItem){
                //Update Transaction Table
                //Determine what previous quantity was
                $sql = "SELECT QuantityOnHand FROM Inventory_Transaction_Summary WHERE InventoryID='" . $InventoryID . "' AND Location='" . $Key . "'";
                $row = self::$db->fetch_all($sql);

                if(!is_null($row[0]->QuantityOnHand)){
                    if($row[0]->QuantityOnHand != $OHQtyItem){
                        //If adjustment is higher
                        if($row[0]->QuantityOnHand > $OHQtyItem){
                            //Subtract
                            $OHAdjustment = -$row[0]->QuantityOnHand + $OHQtyItem;
                        } else {
                            //Add
                            $OHAdjustment = $OHQtyItem - $row[0]->QuantityOnHand;
                        }

                        $InventoryQuantityArray["InventoryID"] = $InventoryID;
                        $InventoryQuantityArray["Location"] = $Key;
                        $InventoryQuantityArray["UID"] = $_SESSION["UID"];
                        $InventoryQuantityArray["QuantityAdjustment"] = $OHAdjustment;
                        $InventoryQuantityArray["Comments"] = "Manual Adjustment";

                        $SQLEntry = $this->InsertMultipleFields($InventoryQuantityArray);
                        self::$db->insert("Inventory_Transaction", $SQLEntry);

                        unset($InventoryQuantityArray,$OHAdjustment);
                    }
                }
                //Update Summary Accordinly
                $InventoryQuantityArray["QuantityOnHand"] = $OHQtyItem;
                $SQLEntry = $this->UpdateMultipleFields($InventoryQuantityArray);

                $sql = "UPDATE MCCS.Inventory_Transaction_Summary $SQLEntry WHERE InventoryID='" . $InventoryID . "' AND Location='" . $Key . "'";
                $row = self::$db->query($sql);

                unset($InventoryQuantityArray,$row);
            }
        }

        //var_dump($InventoryQuantityArray);
        //die();

        $newURL = "inventory.php?do=inventory&action=view&partid=$InventoryID&msg=CreatedInventoryItem";
        header("Location:/$newURL");
    }

        //Inventory Update
        public function UpdateInventoryData(){
            $InventoryID = $_POST["InventoryID"];
            $DefaultActionID = $_POST["DefaultAction"];
            
            $NamedArray["Description"] = $_POST["InventoryDescription"];
            $NamedArray["Type"] = $_POST["PartTypeInput-hidden"];
            $NamedArray["UnitofMeasure"] = $_POST["UnitofMeasure"];
            $NamedArray["UnitofWeight"] = $_POST["UnitofWeight"];
            $NamedArray["PartClass"] = $_POST["InventoryClass"];
            $NamedArray["Notes"] = $_POST["Notes"];
            $NamedArray["BalanceAccount"] = $_POST["BalanceAccount"];
            $NamedArray["PODescription"] = $_POST["PODescription"];
            $NamedArray["ManufacturerName"] = $_POST["ManufacturerName"];
            $NamedArray["MFRID"] = $_POST["ManufacturerID"];
            $NamedArray["MFRPartID"] = $_POST["ManufacturerPartID"];
            $NamedArray["Weight"] = $_POST["Weight"];
            $NamedArray["Min"] = $_POST["Min"];
            $NamedArray["Max"] = $_POST["Max"];
            $NamedArray["ReorderMethod"] = $_POST["ROM"];
            $NamedArray["Taxable"] = $_POST["Taxable"];
            $NamedArray["Bulk"] = $_POST["Bulk"];
            $NamedArray["BulkCount"] = $_POST["BulkCount"];
            $NamedArray["VendorPartID"] = $_POST["VendorPartID"];
            $NamedArray["VendorID"] = $_POST["VendorIDInput-hidden"];
            $NamedArray["ReorderPoint"] = $_POST["ReorderPoint"];
            $NamedArray["ReorderQuantity"] = $_POST["ReorderQuantity"];
            $NamedArray["Active"] = $_POST["Active"];

            $SQLEntry = $this->UpdateMultipleFields($NamedArray);

            $sql = "UPDATE MCCS.Inventory_Detail $SQLEntry WHERE id='" . $InventoryID . "'";
            $row = self::$db->query($sql);

            if(!empty($DefaultActionID)){
                $sql = "UPDATE MCCS.Inventory_Detail_Location SET `Default`=NULL WHERE InventoryID='" . $InventoryID . "'";
                self::$db->query($sql);

                $sql = "UPDATE MCCS.Inventory_Detail_Location SET `Default`='1' WHERE id='" . $DefaultActionID . "'";
                self::$db->query($sql);
            }

            if($_POST["Room"] != ""){
                $InventoryLocationArray["InventoryID"] = $InventoryID;
                $InventoryLocationArray["Room"] = $_POST["Room"];
                $InventoryLocationArray["Aisle"] = $_POST["aisle"];
                $InventoryLocationArray["Column"] = $_POST["column"];
                $InventoryLocationArray["Shelf"] = $_POST["shelf"];

                $SQLEntry = $this->InsertMultipleFields($InventoryLocationArray);
                $lastid = self::$db->insert("Inventory_Detail_Location", $SQLEntry);

                $InventoryQuantityArray["InventoryID"] = $InventoryID;
                $InventoryQuantityArray["Location"] = $lastid;
                $InventoryQuantityArray["Active"] = '1';
                if(isset($_POST["QuantityOHInsert"])){
                    $InventoryQuantityArray["QuantityOnHand"] = $_POST["QuantityOHInsert"];

                    $InventoryQuantityTArray["InventoryID"] = $InventoryID;
                    $InventoryQuantityTArray["Location"] = $lastid;
                    $InventoryQuantityTArray["Comments"] = "Initial Inventory Transaction";
                    if($InventoryQuantityArray["QuantityOnHand"] != ""){
                        $InventoryQuantityTArray["QuantityAdjustment"] = $InventoryQuantityArray["QuantityOnHand"];
                    }
                    unset($SQLEntry);

                    $SQLEntry = $this->InsertMultipleFields($InventoryQuantityTArray);
                    self::$db->insert("Inventory_Transaction", $SQLEntry);

                    unset($SQLEntry,$InventoryQuantityTArray);
                }

                $SQLEntry = $this->InsertMultipleFields($InventoryQuantityArray);
                self::$db->insert("Inventory_Transaction_Summary", $SQLEntry);

                unset($InventoryQuantityArray);
            }

            if($_POST["OHQty"] != ""){
                $OHQtyArray = $_POST["OHQty"];
                foreach($OHQtyArray AS $Key => $OHQtyItem){
                    //Update Transaction Table
                    //Determine what previous quantity was
                    $sql = "SELECT QuantityOnHand FROM Inventory_Transaction_Summary WHERE InventoryID='" . $InventoryID . "' AND Location='" . $Key . "'";
                    $row = self::$db->fetch_all($sql);

                    if(!is_null($row[0]->QuantityOnHand)){
                        if($row[0]->QuantityOnHand != $OHQtyItem){
                            //If adjustment is higher
                            if($row[0]->QuantityOnHand > $OHQtyItem){
                                //Subtract
                                $OHAdjustment = -$row[0]->QuantityOnHand + $OHQtyItem;
                            } else {
                                //Add
                                $OHAdjustment = $OHQtyItem - $row[0]->QuantityOnHand;
                            }

                            $InventoryQuantityArray["InventoryID"] = $InventoryID;
                            $InventoryQuantityArray["Location"] = $Key;
                            $InventoryQuantityArray["UID"] = $_SESSION["UID"];
                            $InventoryQuantityArray["QuantityAdjustment"] = $OHAdjustment;
                            $InventoryQuantityArray["Comments"] = "Manual Adjustment";
    
                            $SQLEntry = $this->InsertMultipleFields($InventoryQuantityArray);
                            self::$db->insert("Inventory_Transaction", $SQLEntry);
    
                            unset($InventoryQuantityArray,$OHAdjustment);
                        }
                    }
                    //Update Summary Accordinly
                    $InventoryQuantityArray["QuantityOnHand"] = $OHQtyItem;
                    $SQLEntry = $this->UpdateMultipleFields($InventoryQuantityArray);

                    $sql = "UPDATE MCCS.Inventory_Transaction_Summary $SQLEntry WHERE InventoryID='" . $InventoryID . "' AND Location='" . $Key . "'";
                    $row = self::$db->query($sql);

                    unset($InventoryQuantityArray,$row);
                }
            }

            //var_dump($InventoryQuantityArray);
            //die();

            $newURL = "inventory.php?do=inventory&action=view&partid=$InventoryID&msg=UpdatedInventory";
            header("Location:/$newURL");
        }

        //Vendor Update
        public function UpdateVendorData(){
            $VendorID = $_POST["VendorID"];
            
            $NamedArray["Vendor_ID"] = $_POST["VendorName"];
            $NamedArray["Name"] = $_POST["Name"];
            $NamedArray["Phone"] = $_POST["PhoneNumber"];
            $NamedArray["Fax"] = $_POST["FaxNumber"];
            $NamedArray["Email"] = $_POST["Email"];
            $NamedArray["Street"] = $_POST["Street"];
            $NamedArray["City"] = $_POST["City"];
            $NamedArray["Country"] = $_POST["Country"];
            $NamedArray["Website"] = $_POST["Website"];
            $NamedArray["Type"] = $_POST["NameType"];
            //$NamedArray["Facility_Assigned"] = $_POST["Facility"];
            $NamedArray["Active"] = $_POST["Active"];
            $State = explode(',', $NamedArray["City"]);
            $NamedArray["City"] = $State[0];
            $State = $State[1];
            $State = ltrim($State, ' ');
            $NamedArray["State"] = $State;

            $SQLEntry = $this->UpdateMultipleFields($NamedArray);

            $sql = "UPDATE MCCS.Vendor_Detail $SQLEntry WHERE id='" . $VendorID . "'";
            $row = self::$db->query($sql);

            $newURL = "vendors.php?do=vendors&action=view&vendorid=$VendorID&msg=UpdatedVendor";
            header("Location:/$newURL");
        }

        //Create Vendor
        public function CreateVendorData(){
            $VendorID = $_POST["VendorID"];
            
            $NamedArray["Vendor_ID"] = $_POST["VendorName"];
            $NamedArray["Name"] = $_POST["Name"];
            $NamedArray["Phone"] = $_POST["PhoneNumber"];
            $NamedArray["Fax"] = $_POST["FaxNumber"];
            $NamedArray["Email"] = $_POST["Email"];
            $NamedArray["Street"] = $_POST["Street"];
            $NamedArray["City"] = $_POST["City"];
            $NamedArray["Country"] = $_POST["Country"];
            $NamedArray["Website"] = $_POST["Website"];
            $NamedArray["Type"] = $_POST["NameType"];
            //$NamedArray["Facility_Assigned"] = $_POST["Facility"];
            $NamedArray["Active"] = $_POST["Active"];
            $State = explode(',', $NamedArray["City"]);
            $NamedArray["City"] = $State[0];
            $State = $State[1];
            $State = ltrim($State, ' ');
            $NamedArray["State"] = $State;

            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            $lastid = self::$db->insert("Vendor_Detail", $SQLEntry);

            $newURL = "vendors.php?do=vendors&action=view&vendorid=$lastid&msg=VendorCreated";
            header("Location:/$newURL");
        }

        function DayofWeek($Integer){
            if($Integer == 1){
                $Day = "Monday";
            } elseif ($Integer == 2){
                $Day = "Tuesday";
            } elseif ($Integer == 3){
                $Day = "Wednesday";
            } elseif ($Integer == 4){
                $Day = "Thursday";
            } elseif ($Integer == 5){
                $Day = "Friday";
            } elseif ($Integer == 6){
                $Day = "Saturday";
            } elseif ($Integer == 7){
                $Day = "Sunday";
            }
            return $Day;
        }

        public function PopulateRecentPMWorkorders(){
            $sql = "SELECT Workorder_Main.AssignedUser, Workorder_Main.AssignedGroup, Workorder_Main.Status, Workorder_PM_Workorder_Hist.WO FROM Workorder_PM_Workorder_Hist INNER JOIN Workorder_Main ON Workorder_PM_Workorder_Hist.WO = Workorder_Main.id WHERE Workorder_PM_Workorder_Hist.PM = '" . $PMID . "'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function PopulatePMChecklist($PMID){
            $sql = "SELECT Workorder_PM_Checklist_Header.ChecklistDescription FROM Workorder_PM_Checklist_Header WHERE Workorder_PM_Checklist_Header.PM = '" . $PMID . "'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        public function PopulatePMInformation($PMID){
            $sql = "SELECT User_Group.Description AS UserGroup, Workorder_PM.Asset, CategoricalPlacements_Detail.CategoryDetail AS Priority, Workorder_PM.NextRunDate, Workorder_PM.Assignee, Workorder_PM.DaysToComplete, Workorder_PM.Day, Workorder_PM.WorkDescription, User.FirstName, User.LastName, Workorder_PM.DateLastEdited, Workorder_PM.DateSubmitted, Workorder_PM.AuthorizationStep, Workorder_PM.Enabled, Workorder_PM.Deleted, Workorder_PM.Repetition, CategoricalPlacements_Detail_1.CategoryDetail AS AssignmentType, User_1.FirstName AS Assigned_Fname, User_1.LastName AS Assigned_Lname FROM Workorder_PM INNER JOIN User_Group ON Workorder_PM.AssignedGroup = User_Group.id INNER JOIN User ON Workorder_PM.Requestor = User.id INNER JOIN CategoricalPlacements_Detail ON Workorder_PM.Priority = CategoricalPlacements_Detail.id INNER JOIN CategoricalPlacements_Detail CategoricalPlacements_Detail_1 ON Workorder_PM.AssignmentType = CategoricalPlacements_Detail_1.id LEFT OUTER JOIN User User_1 ON Workorder_PM.Assignee = User_1.id WHERE Workorder_PM.id = '" . $PMID . "'";
            $row = self::$db->fetch_all($sql);
            return ($row) ? $row : 0;
        }

        //Deny PM
        public function PMDeny(){
            $PMID = $_POST["PMID"];

            //Check current step
            $sql = "SELECT AuthorizationStep FROM MCCS.Workorder_PM WHERE id='" . $PMID . "'";
            $row = self::$db->fetch_all($sql);
            $CurrentStep = $row[0]->AuthorizationStep;

            //Add User to authorization history
            $NamedArray["PM"] = $PMID;
            $NamedArray["UID"] = $_SESSION["UID"];
            $NamedArray["AuthorizationStep"] = $CurrentStep;
            $NamedArray["Denied"] = '1';
            $NamedArray["Modified"] = 'NOW()';

            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            self::$db->insert("Workorder_PM_Authorization", $SQLEntry);

            $sql = "UPDATE MCCS.Workorder_PM SET Deleted='1' WHERE id='" . $PMID . "'";
            self::$db->query($sql);

            $newURL = "default.php?do=workorders&action=viewpm&pmid=$PMID&msg=WorkorderUpdated";
            header("Location:/$newURL");
        }

        public function PMApprove(){
            $PMID = $_POST["PMID"];

            //Check current step
            $sql = "SELECT AuthorizationStep FROM MCCS.Workorder_PM WHERE id='" . $PMID . "'";
            $row = self::$db->fetch_all($sql);
            $CurrentStep = $row[0]->AuthorizationStep;

            //Add User to authorization history
            $NamedArray["PM"] = $PMID;
            $NamedArray["UID"] = $_SESSION["UID"];
            $NamedArray["AuthorizationStep"] = $CurrentStep;
            $NamedArray["Approved"] = '1';
            $NamedArray["Modified"] = 'NOW()';

            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            self::$db->insert("Workorder_PM_Authorization", $SQLEntry);

            //Update current step to Workorder_PM
            if($CurrentStep > '1'){
                $CurrentStep--;
                if($CurrentStep == '1'){
                    $sql = "UPDATE MCCS.Workorder_PM SET AuthorizationStep='$CurrentStep', `Enabled`='1' WHERE id='" . $PMID . "'";
                } else {
                    $sql = "UPDATE MCCS.Workorder_PM SET AuthorizationStep='$CurrentStep' WHERE id='" . $PMID . "'";
                }
                self::$db->query($sql);
            }

            $newURL = "default.php?do=workorders&action=viewpm&pmid=$PMID&msg=WorkorderUpdated";
            header("Location:/$newURL");
        }

        //Update PM
        public function UpdatePMData(){
            //Create normal Workorder
            $PMID = $_POST["PMID"];
            $NamedArray["Repetition"] = $_POST["Repetition"];
            $NamedArray["AssignedGroup"] = $_POST["AssignedGroup"];
            $NamedArray["Priority"] = $_POST["Priority"];
            $NamedArray["DaysToComplete"] = $_POST["DaysToComplete"];
            $NamedArray["DateLastEdited"] = date('Y-m-d H:i:s');
            $NamedArray["WorkDescription"] = $_POST["WorkInstruction"];
            $NamedArray["Priority"] = '13';
            //$NamedArray["AuthorizationStep"] = '3';
            if(!empty($_POST["AssignedUserInput"])){
                $NamedArray["Assignee"] = $_POST["AssignedUserInput-hidden"];
            } else {
                $NamedArray["Assignee"] = NULL;
            }

            //Check Closure
            $Closure = $_POST["Closure"];

            if($Closure == '1'){
                //UPDATE TO OPEN
                $sql = "UPDATE MCCS.Workorder_PM SET `Enabled` = '1', Deleted = NULL WHERE id='" . $PMID . "'";
                self::$db->query($sql);
            } elseif($Closure == '2') {
                //UPDATE TO CLOSED
                $sql = "UPDATE MCCS.Workorder_PM SET `Enabled` = NULL, Deleted = '1' WHERE id='" . $PMID . "'";
                self::$db->query($sql);
            }

            $sql = "SELECT * FROM MCCS.Workorder_PM WHERE Workorder_PM.AuthorizationStep='1' AND Workorder_PM.id='" . $PMID . "'";
            $CheckStatus = self::$db->fetch_all($sql);


            foreach($_POST["asset_selected"] AS $SelectedAsset){
                $NamedArray["Asset"] = $SelectedAsset;
            }
            $WorkChecklist = $_POST["workchecklist"];
        

            $Today = date("Y-m-d 00:00:00");
            $DateNow = date("Y-m-d"); 

            if($NamedArray["Repetition"] > '2') {
                $NamedArray["Day"] = $_POST["DayofMonth"];
                $Month = $_POST["MonthSelected"];
                $Year = $_POST["Year"];
                $Day = $NamedArray["Day"];
                $RequestedDate = "$Year-$Month-$Day";

                if ($DateNow > $RequestedDate) {
                    $newURL = "default.php?do=workorders&action=create&msg=DatePriorToToday";
                    header("Location:/$newURL");
                    die();
                }else{
                    $NamedArray["NextRunDate"] = date("$Year-$Month-$Day 00:00:00");
                }
                //$NamedArray["NextRunDate"] = date("Y-m-$Day H:i:s", strtotime("$Today +1 month"));
            } elseif($NamedArray["Repetition"] == '2') {
                $NamedArray["Day"] = $_POST["DayofWeek"];
                $DayofWeek = $this->DayofWeek($_POST["DayofWeek"]);
                $NamedArray["NextRunDate"] = date('Y-m-d H:i:s', strtotime("next $DayofWeek"));
            } else {
                $NamedArray["DaysToComplete"] = NULL;
                $NamedArray["NextRunDate"] = date('Y-m-d H:i:s',strtotime($Today . ' +1 day'));
            }

            $SQLEntry = $this->UpdateMultipleFields($NamedArray);
            $sql = "UPDATE MCCS.Workorder_PM $SQLEntry WHERE id='" . $PMID . "'";
            self::$db->query($sql);


            if($CheckStatus[0]->AuthorizationStep != '1'){
            //Delete Checklist items and populate current form as we are assuming that is the correct iteration
                $sql = "DELETE FROM MCCS.Workorder_PM_Checklist_Header WHERE PM='" . $PMID . "'";
                self::$db->query($sql);

                foreach($WorkChecklist AS $ChecklistItem){
                    $SecondArray["PM"] = $PMID;
                    $SecondArray["ChecklistDescription"] = $ChecklistItem;

                    $SQLEntry = $this->InsertMultipleFields($SecondArray);
                    self::$db->insert("Workorder_PM_Checklist_Header", $SQLEntry);
                    unset($SQLEntry);
                }
            }
            $newURL = "default.php?do=workorders&action=viewpm&pmid=$PMID&msg=WorkorderUpdated";
            header("Location:/$newURL");

        }

        //Create PM
        public function CreatePMData(){
            //Create normal Workorder
            $UID = $_POST["UID"];
            $UsernameArray = $_SESSION["Username"];
            $UsernameArray = explode('.', $UsernameArray);
            $Username = $UsernameArray[0] . ' ' . $UsernameArray[1];
            $NamedArray["Repetition"] = $_POST["Repetition"];
            $NamedArray["AssignedGroup"] = $_POST["AssignedGroup"];
            $NamedArray["AssignmentType"] = $_POST["AssignmentType"];
            $NamedArray["Priority"] = '13';
            $NamedArray["DaysToComplete"] = $_POST["DaysToComplete"];
            $NamedArray["Requestor"] = $_SESSION["UID"];
            $NamedArray["DateLastEdited"] = date('Y-m-d H:i:s');
            $NamedArray["DateSubmitted"] = date('Y-m-d H:i:s');
            $NamedArray["WorkDescription"] = $_POST["PMWorkInstruction"];
            $NamedArray["AuthorizationStep"] = '3';
            $NamedArray["RequirePhoto"] = $_POST["PhotoRequired"];
            $NamedArray["Asset"] = $_POST["PMasset_selected"];
            $NamedArray["Assignee"] = $_POST["AssignedUserInput-hidden"];
            $NotifyingGroup = $_POST["AssignedGroup"];
            if(!empty($_POST["BiWeekly"])){
                $NamedArray["Multiplier"] = $_POST["BiWeekly"];
            }
            $WorkChecklist = $_POST["workchecklist"];

            $Today = date("Y-m-d 00:00:00");
            $DateNow = date("Y-m-d"); 

            if($NamedArray["Repetition"] > '2') {
                $NamedArray["Day"] = $_POST["DayofMonth"];
                if($NamedArray["Repetition"] == '3'){
                    $date = date("Y-m-01");
                    $newdate = strtotime ( '+1 month' , strtotime ( $date ) ) ;
                    $date = date('m/d/Y', $newdate);
                    $Datarray = explode('/', $date);
                    $Month = $Datarray[0];
                    $Year = $Datarray[2];
                    $Day = $Datarray[1];
                } else {
                    $Month = $_POST["MonthSelected"];
                    $Year = $_POST["Year"];
                    $Day = $NamedArray["Day"];
                }
                $RequestedDate = "$Year-$Month-$Day";

                if ($DateNow > $RequestedDate) {
                    $date = date("Y-m-01");
                    $newdate = strtotime ( '+1 month' , strtotime ( $date ) ) ;
                    $date = date('m/d/Y', $newdate);
                    $Datarray = explode('/', $date);
                    $Month = $Datarray[0];
                    $Year = $Datarray[2];
                    $Day = $Datarray[1];
                    $NamedArray["NextRunDate"] = date("$Year-$Month-$Day 00:00:00");
              //      echo $RequestedDate;
                //    $newURL = "default.php?do=workorders&action=create&msg=DatePriorToToday";
                  //  header("Location:/$newURL");
                    //die();
                }else{
                    $NamedArray["NextRunDate"] = date("$Year-$Month-$Day 00:00:00");
                }
                //$NamedArray["NextRunDate"] = date("Y-m-$Day H:i:s", strtotime("$Today +1 month"));
            } elseif($NamedArray["Repetition"] == '2') {
                $NamedArray["Day"] = $_POST["DayofWeek"];
                $DayofWeek = $this->DayofWeek($_POST["DayofWeek"]);
                $NamedArray["NextRunDate"] = date('Y-m-d H:i:s', strtotime("next $DayofWeek"));
            } else {
                $NamedArray["NextRunDate"] = date('Y-m-d H:i:s',strtotime($Today . ' +1 day'));
            }

            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            $lastid = self::$db->insert("Workorder_PM", $SQLEntry);
            unset($SQLEntry);

            foreach($WorkChecklist AS $ChecklistItem){
                $SecondArray["PM"] = $lastid;
                $SecondArray["ChecklistDescription"] = $ChecklistItem;
                
                $SQLEntry = $this->InsertMultipleFields($SecondArray);
                self::$db->insert("Workorder_PM_Checklist_Header", $SQLEntry);
                unset($SQLEntry, $SecondArray);
                echo "test";
            }


            $sql = "SELECT User_Roles.id FROM User_Roles INNER JOIN User_Group ON User_Roles.`Group` = User_Group.id WHERE User_Group.id = '$NotifyingGroup'";
            $CheckGroups = self::$db->fetch_all($sql);
            foreach($CheckGroups AS $Grouped){
                $Notify[] = $Grouped->id;
            }
            //Iterate through notifying groups and ascertain emails...
            $ToList = $this->MailingSQLIterate($Notify);

            include 'sitevariables.php';

            $body = " Team, <br /><br /> A PM has been created by: $Username under PM: $lastid. <br /><br /> Please visit the referenced PM by clicking this <a href=\"http://$SiteAddress/default.php?do=workorders&action=viewpm&pmid=$lastid\">link</a><br /><br />  Thank you. ";
            $body = $this->MailNotice($body);
            $MailSubject = "System WorkOrder Generated";
            $this->InternaltoExternalMailCall($body, $MailSubject, $ToList);

            $newURL = "default.php?do=workorders&action=viewpm&pmid=$lastid&msg=WorkorderSubmitted";
            header("Location:/$newURL");
        }

        //Create Workorder
        public function CreateWorkorderData(){
            $UID = $_POST["UID"];
            $UsernameArray = $_SESSION["Username"];
            $UsernameArray = explode('.', $UsernameArray);
            $Username = $UsernameArray[0] . ' ' . $UsernameArray[1];
            //Create normal Workorder
            $NamedArray["AssignmentType"] = $_POST["AssignmentType"];
            $NamedArray["Priority"] = $_POST["Priority"];
            if(!empty($_POST["RequestedStartDate"])){
                $NamedArray["RequestedStartDate"] = $_POST["RequestedStartDate"];
            } else {
                $NamedArray["RequestedStartDate"] = date('Y-m-d');
            }
            $NamedArray["RequestedEndDate"] = $_POST["RequestedEndDate"];
            $NamedArray["IssueDescription"] = $_POST["IssueDescription"];
            $NamedArray["WorkDescription"] = $_POST["WorkInstruction"];
            $NamedArray["Requestor"] = $_POST["UID"];
            $NamedArray["RequirePhoto"] = $_POST["PhotoRequired"];
            $NamedArray["Status"] = "12";
            $NamedArray["DateLastEdited"] = date('Y-m-d H:i:s');
            $NamedArray["DateSubmitted"] = date('Y-m-d H:i:s');
            $NamedArray["AssignedGroup"] = $_POST["WOAssignedGroup"];
            $NotifyingGroup = $_POST["WOAssignedGroup"];
            $SelectedAsset = $_POST["WOasset_selected"];

            //Perhaps migrate to JS later...
            if(empty($_POST["WorkInstruction"])){
                $newURL = "default.php?do=workorders&action=create&msg=WorkInstructionMissing";
                header("Location:/$newURL");        
                die();
            } elseif (empty($_POST["IssueDescription"])){
                $newURL = "default.php?do=workorders&action=create&msg=IssueDescriptionMissing";
                header("Location:/$newURL");        
                die();
            } elseif (empty($_POST["WOasset_selected"])){
                $newURL = "default.php?do=workorders&action=create&msg=AssetMissing";
                header("Location:/$newURL");        
                die();
            } elseif (empty($_POST["WOAssignedGroup"])){
                $newURL = "default.php?do=workorders&action=create&msg=AssignedGroupMissing";
                header("Location:/$newURL");        
                die();
            } elseif (empty($_POST["RequestedEndDate"])){
                $newURL = "default.php?do=workorders&action=create&msg=RequestedEndDateMissing";
                header("Location:/$newURL");        
                die();
            }
            
            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            $lastid = self::$db->insert("Workorder_Main", $SQLEntry);
            unset($SQLEntry);

            
                $AssetSelectionArray["WorkorderID"] = $lastid;
                $AssetSelectionArray["AssetDetailID"] = $SelectedAsset;

                $SQLEntry = $this->InsertMultipleFields($AssetSelectionArray);
                self::$db->insert("Workorder_AssetsSelected", $SQLEntry);

                unset($AssetSelectionArray);
            

            //Notify Groups 3 and 4 *perhaps add to db one day
            $sql = "SELECT User_Roles.id FROM User_Roles INNER JOIN User_Group ON User_Roles.`Group` = User_Group.id WHERE User_Group.id = '$NotifyingGroup'";
            $CheckGroups = self::$db->fetch_all($sql);
            foreach($CheckGroups AS $Grouped){
                $Notify[] = $Grouped->id;
            }
            //Iterate through notifying groups and ascertain emails...
            $ToList = $this->MailingSQLIterate($Notify);

            include 'sitevariables.php';

            $body = " Team, <br /><br /> A WorkOrder has been manually generated by: $Username under WorkOrder: $lastid. <br /><br /> Please visit the referenced WorkOrder by clicking this <a href=\"http://$SiteAddress/default.php?do=workorders&action=view&workorderid=$WorkorderID\">link</a><br /><br />  Thank you. ";
            $body = $this->MailNotice($body);
            $MailSubject = "System WorkOrder Generated";
            $this->InternaltoExternalMailCall($body, $MailSubject, $ToList);

            $newURL = "default.php?do=workorders&action=view&workorderid=$lastid&msg=WorkorderSubmitted";
            header("Location:/$newURL");        
        }

        public function MailingSQLIterate($Notify){
            $lastNElement = end($Notify);
            $sqlADD .= "WHERE ";
            foreach($Notify AS $NotifyID){    
                if($NotifyID != $lastNElement) {
                    $sqlADD .= "User_Roles.id = '$NotifyID' OR ";
                } else {
                    $sqlADD .= "User_Roles.id = '$NotifyID'";
                }
            }
            $sql = "SELECT User.FirstName, User.LastName, User.Email FROM User_Group_Details INNER JOIN User ON User_Group_Details.UID = User.id INNER JOIN User_Roles ON User_Group_Details.Role = User_Roles.id $sqlADD";
            $UserResult = self::$db->fetch_all($sql);

            foreach($UserResult AS $UserInfoItem){
                $ToList[] = $UserInfoItem->Email;
            }

            return $ToList;
        }

        public function MailingSQLIterateUser($Assignee){
            $sqlADD .= "WHERE User.id = '$Assignee'";
            $sql = "SELECT User.FirstName, User.LastName, User.Email FROM User_Group_Details INNER JOIN User ON User_Group_Details.UID = User.id INNER JOIN User_Roles ON User_Group_Details.Role = User_Roles.id $sqlADD";
            echo $sql;
            $UserResult = self::$db->fetch_all($sql);

            foreach($UserResult AS $UserInfoItem){
                $ToList[] = $UserInfoItem->Email;
            }

            return $ToList;
        }

        //Update Asset in WO from JS
        public function UpdateWOAssetDetail(){
            //Update ALL assets in each related table
            $SelectedAsset = $_POST["Asset"];
            $WorkOrderID = $_POST["WorkorderID"];
            $sql = "UPDATE MCCS.Workorder_AssetsSelected SET AssetDetailID='$SelectedAsset' WHERE WorkorderID='" . $WorkOrderID . "'";
            self::$db->query($sql);

            $sql = "UPDATE MCCS.Inventory_Transaction_Workorder_Summary SET Asset='$SelectedAsset' WHERE Workorder='$WorkOrderID'";
            self::$db->query($sql);
            
            $sql = "UPDATE MCCS.Inventory_Transaction_Workorder SET Asset='$SelectedAsset' WHERE Workorder='$WorkOrderID'";
            self::$db->query($sql);

            $response_array['status'] = 'success';  
            die(json_encode($response_array));
        }

        //Update WO Parts from JS
        public function UpdateWorkorderPartData(){
                
                $InventoryItem = $_POST["InventoryID"];
                $InventoryQuantity = $_POST["PartQuantity"];
                $InventoryLocation = $_POST["InventoryLocation"];
                $WorkOrderID = $_POST["WorkorderID"];
                $Asset = $_POST["Asset"];
                
                //Cast to int
                if($InventoryQuantity != 0){
                    (int)$InventoryQuantity = "-" . "$InventoryQuantity";
                }

                    
                    //See if Items in the two tables exist.
                    $sql = "SELECT id, Quantity FROM Inventory_Transaction_Workorder_Summary WHERE InventoryID = '$InventoryItem' AND Asset = '$Asset' AND Workorder = '$WorkOrderID' AND LocationPull = '$InventoryLocation' LIMIT 1";
                    $InvTransWorkorderSum = self::$db->fetch_all($sql);

                    $sql = "SELECT id, QuantityOnHand FROM Inventory_Transaction_Summary WHERE InventoryID = '$InventoryItem' AND `Location` = '$InventoryLocation'";
                    $InvTransSum = self::$db->fetch_all($sql);

                    //Update Transaction Workorder
                    $QuantityAdjustment = $InvTransWorkorderSum[0]->Quantity;
                    $InventoryQtyAdjust = $InvTransSum[0]->QuantityOnHand;
                    if(is_null($QuantityAdjustment)){
                        //Insert
                        $CorrectedQuantity = $InventoryQuantity;
                        $Workorder["Workorder"] = $WorkOrderID;
                        $Workorder["InventoryID"] = $InventoryItem;
                        $Workorder["Asset"] = $Asset;
                        $Workorder["LocationPull"] = $InventoryLocation;
                        $Workorder["QuantityAdjustment"] = "$InventoryQuantity";
                        $Workorder["UID"] = $_SESSION["UID"];
                        $Workorder["Modified"] = 'NOW()';

                        $SQL = $this->InsertMultipleFields($Workorder);
                        self::$db->insert("Inventory_Transaction_Workorder", $SQL);
                        $IDidSomething = TRUE;

                    } else {
                        //Insert taking into consideration previous input
                        if($InventoryQuantity < $QuantityAdjustment){
                            $CorrectedQuantity = $InventoryQuantity - $QuantityAdjustment;
                            $Workorder["Workorder"] = $WorkOrderID;
                            $Workorder["InventoryID"] = $InventoryItem;
                            $Workorder["Asset"] = $Asset;
                            $Workorder["LocationPull"] = $InventoryLocation;
                            $Workorder["QuantityAdjustment"] = $CorrectedQuantity;
                            $Workorder["UID"] = $_SESSION["UID"];
                            $Workorder["Modified"] = 'NOW()';

                            $SQL = $this->InsertMultipleFields($Workorder);
                            self::$db->insert("Inventory_Transaction_Workorder", $SQL);
                        } elseif($InventoryQuantity > $QuantityAdjustment) {
                            $CorrectedQuantity = $InventoryQuantity + abs($QuantityAdjustment);
                            $Workorder["Workorder"] = $WorkOrderID;
                            $Workorder["InventoryID"] = $InventoryItem;
                            $Workorder["Asset"] = $Asset;
                            $Workorder["LocationPull"] = $InventoryLocation;
                            $Workorder["QuantityAdjustment"] = $CorrectedQuantity;
                            $Workorder["UID"] = $_SESSION["UID"];
                            $Workorder["Modified"] = 'NOW()';

                            $SQL = $this->InsertMultipleFields($Workorder);
                            self::$db->insert("Inventory_Transaction_Workorder", $SQL);
                        }
                        $IDidSomething = TRUE;
                    }

                    unset($Workorder, $SQL);
                    //Update Workorder Summary
                    if(is_null($QuantityAdjustment)){
                        //Insert
                        $Workorder["Workorder"] = $WorkOrderID;
                        $Workorder["InventoryID"] = $InventoryItem;
                        $Workorder["Asset"] = $Asset;
                        $Workorder["LocationPull"] = $InventoryLocation;
                        $Workorder["Quantity"] = "$InventoryQuantity";
                        $Workorder["Modified"] = 'NOW()';

                        $SQL = $this->InsertMultipleFields($Workorder);
                        self::$db->insert("Inventory_Transaction_Workorder_Summary", $SQL);
                    } else {
                        //UPDATE taking into consideration previous input
                        $Workorder["Quantity"] = "$InventoryQuantity";
                        $Workorder["Modified"] = 'NOW()';

                        $SQLEntry = $this->UpdateMultipleFields($Workorder);
                        $sql = "UPDATE MCCS.Inventory_Transaction_Workorder_Summary $SQLEntry WHERE InventoryID='" . $InventoryItem . "' AND Workorder='" . $WorkOrderID . "' AND Asset='" . $Asset . "' AND LocationPull = '$InventoryLocation'";
                        self::$db->query($sql);

                    }

                    unset($Workorder, $SQL);
                    //die();
                

                if($IDidSomething == TRUE){
                    
                    //Update Transaction set for inventory
                        //Insert taking into consideration previous input
                        $TransactionAdjust = $CorrectedQuantity;
                        
                        $Workorder["InventoryID"] = $InventoryItem;
                        $Workorder["Location"] = $InventoryLocation;
                        $Workorder["QuantityAdjustment"] = $TransactionAdjust;
                        $Workorder["UID"] = $_SESSION["UID"];
                        $Workorder["Comments"] = "Workorder Consumption : $WorkOrderID";

                        $SQL = $this->InsertMultipleFields($Workorder);
                        self::$db->insert("Inventory_Transaction", $SQL);

                        unset($Workorder, $SQL);

                    //Update Transaction Summary - On hand //Should not be able to select one that does not have a OH balance... Will disable mark in inventory list
                        $FinalCount = $InventoryQtyAdjust + $TransactionAdjust;
                        $FinalCount = abs($FinalCount);
                        $UID = $_SESSION["UID"];
                        
                        $sql = "UPDATE MCCS.Inventory_Transaction_Summary SET QuantityOnHand='" . $FinalCount . "', UID='" . $UID . "' WHERE InventoryID='" . $InventoryItem . "' AND `Location`='" . $InventoryLocation . "'";
                        self::$db->query($sql);
                }

                $response_array['status'] = 'success';  
                die(json_encode($response_array));
            
        }

        //UpdateWorkorderData
        public function UpdateWorkorderData(){
            $WorkOrderID = $_POST["workorderid"];
            $NamedArray["AssignedUser"] = $_POST["AssignedUserInput-hidden"];
            $NamedArray["AssignedGroup"] = $_POST["AssignedGroup"];
            $NamedArray["AssignmentType"] = $_POST["AssignmentType"];
            $NamedArray["Priority"] = $_POST["Priority"];
            $NamedArray["Status"] = $_POST["Status"];
            $NamedArray["DateLastEdited"] = date('Y-m-d H:i:s');
            $NamedArray["ActualEndDate"] = $_POST["ActualEndDate"];
            $NamedArray["HoursTaken"] = $_POST["HoursTaken"];

            //Do a search to check if images and checklist is required to still be filled out
            $sql = "SELECT Workorder_PM_Checklist.Notes, Workorder_PM_Checklist.Modified, Workorder_PM_Checklist.id, Workorder_PM_Checklist_Header.ChecklistDescription, User.FirstName, User.LastName, Workorder_PM_Checklist.Signer FROM Workorder_PM_Checklist INNER JOIN Workorder_PM_Checklist_Header ON Workorder_PM_Checklist.ChecklistItem = Workorder_PM_Checklist_Header.id LEFT OUTER JOIN User ON Workorder_PM_Checklist.Signer = User.id WHERE Workorder_PM_Checklist.Workorder = '$WorkOrderID' AND Workorder_PM_Checklist.Signer IS NULL";
            $ChecklistCheck = self::$db->fetch_all($sql);

            unset($sql);

            $sql = "SELECT Workorder_PM_Checklist_Header.ChecklistDescription, Workorder_PM_Workorder_Hist.WO FROM Workorder_PM_Workorder_Hist INNER JOIN Workorder_PM_Checklist_Header ON Workorder_PM_Workorder_Hist.PM = Workorder_PM_Checklist_Header.PM WHERE Workorder_PM_Workorder_Hist.WO = '$WorkOrderID'";
            $ChecklistOptionCheck = self::$db->fetch_all($sql);

            unset($sql);

            $sql = "SELECT Workorder_Main.RequirePhoto, Workorder_Main.PhotoLocation FROM Workorder_Main WHERE Workorder_Main.id = '$WorkOrderID'";
            $PhotoCheck = self::$db->fetch_all($sql);
            $PhotoRequired = $PhotoCheck[0]->RequirePhoto;
            $PhotoLocation = $PhotoCheck[0]->PhotoLocation;

            unset($sql);

            $sql = "SELECT Workorder_Main.Status FROM Workorder_Main WHERE Workorder_Main.id = '$WorkOrderID'";
            $StatusCheck = self::$db->fetch_all($sql);
            $OriginalStatus = $StatusCheck[0]->Status;

            if(($_POST["Status"] == '11') AND (empty($_POST["ActualEndDate"]))){
                $newURL = "default.php?do=workorders&action=view&workorderid=$WorkOrderID&msg=WorkorderCannotCloseWithoutEndDate";
                header("Location:/$newURL");
                die();
            }

            if(($_POST["Status"] == '11') AND (!empty($ChecklistCheck)) AND (!empty($ChecklistOptionCheck))){
                $newURL = "default.php?do=workorders&action=view&workorderid=$WorkOrderID&msg=WorkorderCannotCloseWithoutChecklist";
                header("Location:/$newURL");
                die();
            }

            if(($_POST["Status"] == '11') AND ($PhotoRequired == '1') AND (is_null($PhotoLocation))){
                $newURL = "default.php?do=workorders&action=view&workorderid=$WorkOrderID&msg=WorkorderCannotCloseWithoutPhoto";
                header("Location:/$newURL");
                die();
            }

            if(($_POST["Status"] == '11') AND (!is_null($Hours))){
                $newURL = "default.php?do=workorders&action=view&workorderid=$WorkOrderID&msg=WorkorderCannotCloseWithoutHours";
                header("Location:/$newURL");
                die();
            }

            //Upload Image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            $target_dir = "/var/www/html/archive/workorders/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $BaseName = basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if(!empty($BaseName) AND ($uploadOk == 1)){
                move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                $NamedArray["PhotoLocation"] = $BaseName;
            }

            $SQLEntry = $this->UpdateMultipleFields($NamedArray);
            $sql = "UPDATE MCCS.Workorder_Main $SQLEntry WHERE id='" . $WorkOrderID . "'";
            self::$db->query($sql);
            

            if(!empty($_POST["WorkNotes"])){
                $WorkNotes["UserID"] = $_SESSION["UID"];
                $WorkNotes["WorkNote"] = $_POST["WorkNotes"];
                $WorkNotes["WorkorderID"] = $WorkOrderID;
                $WorkNotes["DateLastEdited"] = date('Y-m-d H:i:s');

                $SQLEntry = $this->InsertMultipleFields($WorkNotes);
                self::$db->insert("Workorder_WorkNotes", $SQLEntry);
                unset($SQLEntry);

                //Email Informing Notes Added

                $sql = "SELECT User.id, User.FirstName, User.LastName, User.Email FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id WHERE Workorder_Main.id = '$WorkOrderID'";
                $RequestorInfo = self::$db->fetch_all($sql);
                if($RequestorInfo[0]->id != $_SESSION["UID"]){
                    $FirstName = $RequestorInfo[0]->FirstName;
                    $LastName = $RequestorInfo[0]->LastName;
                    $Email = $RequestorInfo[0]->Email;
                    $Username = $_SESSION["Username"];
                    $UsernameArray = explode('.',$Username);
                    $POIFName = $UsernameArray[0];
                    $POILName = $UsernameArray[1];
                    $ToList[] = "$Email";
                    $EmailActive = True;
                } else {
                    //Email Assignee
                    unset($RequestorInfo);
                    $sql = "SELECT User.id, User.FirstName, User.LastName, User.Email FROM Workorder_Main INNER JOIN User ON Workorder_Main.AssignedUser = User.id WHERE Workorder_Main.id = '$WorkOrderID'";
                    $RequestorInfo = self::$db->fetch_all($sql);
                    if(!empty($RequestorInfo[0]->id)){
                        $FirstName = $RequestorInfo[0]->FirstName;
                        $LastName = $RequestorInfo[0]->LastName;
                        $Email = $RequestorInfo[0]->Email;
                        $Username = $_SESSION["Username"];
                        $UsernameArray = explode('.',$Username);
                        $POIFName = $UsernameArray[0];
                        $POILName = $UsernameArray[1];
                        $ToList[] = "$Email";
                        $EmailActive = True;
                    } else {
                        $EmailActive = False;
                    }
                }
                if($EmailActive == True){

                    include 'sitevariables.php';

                    $body = " $FirstName, <br /><br /> Notes have been added to WorkOrder by: $POIFName $POILName under WorkOrder: $WorkOrderID. <br /><br /> Please visit the referenced WorkOrder by clicking this <a href=\"http://$SiteAddress/default.php?do=workorders&action=view&workorderid=$WorkorderID\">link</a><br /><br />  Thank you. ";
                    $body = $this->MailNotice($body);
                    $MailSubject = "System WorkOrder Closed";
                    $this->InternaltoExternalMailCall($body, $MailSubject, $ToList);
                }
            }

            //IF declined email requestor
            if($_POST["Status"] == '3'){
                $this->DenyWOItemCall($WorkOrderID);
            }

            //IF closed email requestor
            if(($_POST["Status"] == '11') AND ($OriginalStatus <> '11') AND ($_POST["AssignmentType"] != '10')){
                $sql = "SELECT User.FirstName, User.LastName, User.Email FROM Workorder_Main INNER JOIN User ON Workorder_Main.Requestor = User.id WHERE Workorder_Main.id = '$WorkOrderID'";
                $RequestorInfo = self::$db->fetch_all($sql);
                $FirstName = $RequestorInfo[0]->FirstName;
                $LastName = $RequestorInfo[0]->LastName;
                $Email = $RequestorInfo[0]->Email;
                $Username = $_SESSION["Username"];
                $UsernameArray = explode('.',$Username);
                $POIFName = $UsernameArray[0];
                $POILName = $UsernameArray[1];
                $ToList[] = "$Email";

                include 'sitevariables.php';

                $body = " $FirstName, <br /><br /> A WorkOrder has been <u>closed</u> by: $POIFName $POILName under WorkOrder: $WorkOrderID. <br /><br /> Please visit the referenced WorkOrder by clicking this <a href=\"http://$SiteAddress/default.php?do=workorders&action=view&workorderid=$WorkorderID\">link</a><br /><br />  Thank you. ";
                $body = $this->MailNotice($body);
                $MailSubject = "System WorkOrder Closed";
                $this->InternaltoExternalMailCall($body, $MailSubject, $ToList);
            }

            $newURL = "default.php?do=workorders&action=view&workorderid=$WorkOrderID&msg=WorkorderUpdated";
            header("Location:/$newURL");
        }

        //Asset Location Delete
        public function AssetLocationDelete(){
            $DepartmentSelectionArray = $_POST["ALDepartmentSelection"];
            $Sub_DepartmentSelectionArray = $_POST["ALSelection"];

            foreach($DepartmentSelectionArray AS $DepartmentSelectionItem){
                $sql = "DELETE FROM MCCS.Location_Department WHERE id='" . $DepartmentSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($Sub_DepartmentSelectionArray AS $Sub_DepartmentSelectionItem){
                $sql = "DELETE FROM MCCS.Location_Sub_Department WHERE id='" . $Sub_DepartmentSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            $newURL = "locations.php?do=assets&action=all&msg=AssetLocationDeleted";
            header("Location:/$newURL");
        }

        //Asset Location Active
        public function AssetLocationActivate(){
            $DepartmentSelectionArray = $_POST["ALDepartmentSelection"];
            $Sub_DepartmentSelectionArray = $_POST["ALSelection"];

            foreach($DepartmentSelectionArray AS $DepartmentSelectionItem){
                $sql = "UPDATE MCCS.Location_Department SET Disabled=NULL WHERE id='" . $DepartmentSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($Sub_DepartmentSelectionArray AS $Sub_DepartmentSelectionItem){
                $sql = "UPDATE MCCS.Location_Sub_Department SET Disabled=NULL WHERE id='" . $Sub_DepartmentSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            $newURL = "locations.php?do=assets&action=all&msg=AssetLocationActive";
            header("Location:/$newURL");
        }

        //Asset Location Disable
        public function AssetLocationDisable(){
            $DepartmentSelectionArray = $_POST["ALDepartmentSelection"];
            $Sub_DepartmentSelectionArray = $_POST["ALSelection"];

            foreach($DepartmentSelectionArray AS $DepartmentSelectionItem){
                $sql = "UPDATE MCCS.Location_Department SET Disabled='1' WHERE id='" . $DepartmentSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($Sub_DepartmentSelectionArray AS $Sub_DepartmentSelectionItem){
                $sql = "UPDATE MCCS.Location_Sub_Department SET Disabled='1' WHERE id='" . $Sub_DepartmentSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            $newURL = "locations.php?do=assets&action=all&msg=Disabled";
            header("Location:/$newURL");
        }

        //Asset Location Create
        public function AddAssetLocation(){
            //Add default facility (Allow for improvements)
            $NamedArray["Facility"] = '1';
            $NamedArray["Name"] = $_POST["Department"];
            $Department = $_POST["Department"];

            //Check and see if records for either already exist
            if(!empty($Department)){
                $sql = "SELECT id FROM Location_Department WHERE Location_Department.Name LIKE '%$Department%'";
                $row = self::$db->fetch_all($sql);
                //If a selection was not found we have to first create the department and then we can create a sub department
                if(empty($row)){
                    $SQLEntry = $this->InsertMultipleFields($NamedArray);
                    $DepartmentID = self::$db->insert("Location_Department", $SQLEntry);
                } else {
                    //Carry Department ID forward to Sub_Department
                    $DepartmentID = $row[0]->id;
                    $DepartmentExisted = True;
                }
            }

            unset($row);

            $SubNamedArray["Department"] = $DepartmentID;
            $SubNamedArray["Name"] = $_POST["Sub_Department"];
            $Sub_Department = $_POST["Sub_Department"];

            if(!empty($Sub_Department)){
                $sql = "SELECT id FROM Location_Sub_Department WHERE Location_Sub_Department.Name LIKE '%$Sub_Department%'";
                $row = self::$db->fetch_all($sql);
                //If a selection was not found we have to first create the department and then we can create a sub department
                if(empty($row)){
                    $SQLEntry = $this->InsertMultipleFields($SubNamedArray);
                    $Sub_DepartmentID = self::$db->insert("Location_Sub_Department", $SQLEntry);
                } else {
                    $Sub_DepartmentID = $row[0]->id;
                    $Sub_DepartmentExisted = True;
                }

                unset($row);

                //!!See if there is a match between Department and Sub_Department and THEN add or error out
                $sql = "SELECT Location_Sub_Department.id AS Sub_Department_ID, Location_Sub_Department.Department FROM Location_Sub_Department WHERE Location_Sub_Department.id = '$Sub_DepartmentID' AND Location_Sub_Department.Department = '$DepartmentID'";
                $row = self::$db->fetch_all($sql);

                if(empty($row)){
                    $FinalNamedArray["Department"] = $DepartmentID;
                    $FinalNamedArray["Name"] = $Sub_Department;
                    $SQLEntry = $this->InsertMultipleFields($FinalNamedArray);
                    $Sub_DepartmentID = self::$db->insert("Location_Sub_Department", $SQLEntry);
                }
                
            } elseif(isset($DepartmentExisted)){
                //!!Return with error since they selected a pre-existing department with no sub department inserted
            }

            $newURL = "locations.php?do=assets&action=all";
            header("Location:/$newURL");

        }
        
        //Inventory Location Activate
        public function InventoryLocationDelete(){
            $RoomSelectionArray = $_POST["ILRSelection"];
            $AisleSelectionArray = $_POST["ILASelection"];
            $ColumnSelectionArray = $_POST["ILCSelection"];
            $ShelfSelectionArray = $_POST["ILSSelection"];

            foreach($RoomSelectionArray AS $RoomSelectionItem){
                $sql = "DELETE FROM MCCS.Location_Inventory_Room WHERE id='" . $RoomSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($AisleSelectionArray AS $AisleSelectionItem){
                $sql = "DELETE FROM MCCS.Location_Inventory_Aisle WHERE id='" . $AisleSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($ColumnSelectionArray AS $ColumnSelectionItem){
                $sql = "DELETE FROM MCCS.Location_Inventory_Column WHERE id='" . $ColumnSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($ShelfSelectionArray AS $ShelfSelectionItem){
                $sql = "DELETE FROM MCCS.Location_Inventory_Shelf WHERE id='" . $ShelfSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            $newURL = "locations.php?do=inventory&action=all&msg=InventoryLocationDeleted";
            header("Location:/$newURL");
        }

        //Inventory Location Activate
        public function InventoryLocationActivate(){
            $RoomSelectionArray = $_POST["ILRSelection"];
            $AisleSelectionArray = $_POST["ILASelection"];
            $ColumnSelectionArray = $_POST["ILCSelection"];
            $ShelfSelectionArray = $_POST["ILSSelection"];

            foreach($RoomSelectionArray AS $RoomSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Room SET Disabled=NULL WHERE id='" . $RoomSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($AisleSelectionArray AS $AisleSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Aisle SET Disabled=NULL WHERE id='" . $AisleSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($ColumnSelectionArray AS $ColumnSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Column SET Disabled=NULL WHERE id='" . $ColumnSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($ShelfSelectionArray AS $ShelfSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Shelf SET Disabled=NULL WHERE id='" . $ShelfSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            $newURL = "locations.php?do=inventory&action=all&msg=InventoryLocationActivated";
            header("Location:/$newURL");
        }

        //Inventory Location Disable
        public function InventoryLocationDisable(){
            $RoomSelectionArray = $_POST["ILRSelection"];
            $AisleSelectionArray = $_POST["ILASelection"];
            $ColumnSelectionArray = $_POST["ILCSelection"];
            $ShelfSelectionArray = $_POST["ILSSelection"];

            foreach($RoomSelectionArray AS $RoomSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Room SET Disabled='1' WHERE id='" . $RoomSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($AisleSelectionArray AS $AisleSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Aisle SET Disabled='1' WHERE id='" . $AisleSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($ColumnSelectionArray AS $ColumnSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Column SET Disabled='1' WHERE id='" . $ColumnSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            foreach($ShelfSelectionArray AS $ShelfSelectionItem){
                $sql = "UPDATE MCCS.Location_Inventory_Shelf SET Disabled='1' WHERE id='" . $ShelfSelectionItem . "'";
                $row = self::$db->query($sql);
            }

            $newURL = "locations.php?do=inventory&action=all&msg=InventoryLocationDisabled";
            header("Location:/$newURL");
        }

        public function PartInventoryLabelPrint(){
            $ShelfSelectionArray = $_POST["LabelINVSelection"];

            foreach($ShelfSelectionArray AS $ShelfSelectionItem){
                $LocationText = "$ShelfSelectionItem|0000000|0000000|0000000";
                $LocationBarcode = "$ShelfSelectionItem|0000000|0000000|0000000";

                $this->InventoryPartLabelPrint($LocationText,$LocationBarcode);
                unset($LocationText, $LocationBarcode);
            }

            $newURL = "inventory.php?do=inventory&action=all";
            header("Location:/$newURL");

        }

        public function InventoryLocationLabelPrint(){
            $RoomSelectionArray = $_POST["ILRSelection"];
            $AisleSelectionArray = $_POST["ILASelection"];
            $ColumnSelectionArray = $_POST["ILCSelection"];
            $ShelfSelectionArray = $_POST["ILSSelection"];

            foreach($RoomSelectionArray AS $RoomSelectionItem){
                $sql = "SELECT Location_Facility.Name AS Facility_Name, Location_Facility.id AS Facility_ID, Location_Inventory_Room.Name AS Room_Name, Location_Inventory_Room.id AS Room_ID FROM Location_Inventory_Room INNER JOIN Location_Facility ON Location_Inventory_Room.Facility = Location_Facility.id WHERE Location_Inventory_Room.id = '$RoomSelectionItem'";
                $row = self::$db->fetch_all($sql);

                $LocationText = $row[0]->Room_Name;
                $LocationBarcode = $row[0]->Room_ID;
                $this->InventoryLabelPrint($LocationText,$LocationBarcode);
                unset($sql, $row, $LocationText, $LocationBarcode);
            }

            foreach($AisleSelectionArray AS $AisleSelectionItem){
                $sql = "SELECT Location_Facility.Name AS Facility_Name, Location_Facility.id AS Facility_ID, Location_Inventory_Room.Name AS Room_Name, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.Name AS Aisle_Name, Location_Inventory_Aisle.id AS Aisle_ID FROM Location_Inventory_Room INNER JOIN Location_Facility ON Location_Inventory_Room.Facility = Location_Facility.id INNER JOIN Location_Inventory_Aisle ON Location_Inventory_Aisle.Room = Location_Inventory_Room.id WHERE Location_Inventory_Aisle.id = $AisleSelectionItem";
                $row = self::$db->fetch_all($sql);

                $LocationText = $row[0]->Room_Name . "~" . $row[0]->Aisle_Name;
                $LocationBarcode = $row[0]->Room_ID . "|" . $row[0]->Aisle_ID;
                $this->InventoryLabelPrint($LocationText,$LocationBarcode);
                unset($sql, $row, $LocationText, $LocationBarcode);
            }

            foreach($ColumnSelectionArray AS $ColumnSelectionItem){
                $sql = "SELECT Location_Facility.Name AS Facility_Name, Location_Facility.id AS Facility_ID, Location_Inventory_Room.Name AS Room_Name, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.Name AS Aisle_Name, Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Column.Name AS Column_Name, Location_Inventory_Column.id AS Column_ID FROM Location_Inventory_Room INNER JOIN Location_Facility ON Location_Inventory_Room.Facility = Location_Facility.id INNER JOIN Location_Inventory_Aisle ON Location_Inventory_Aisle.Room = Location_Inventory_Room.id INNER JOIN Location_Inventory_Column ON Location_Inventory_Column.Aisle = Location_Inventory_Aisle.id WHERE Location_Inventory_Column.id = $ColumnSelectionItem";
                $row = self::$db->fetch_all($sql);

                $LocationText = $row[0]->Room_Name . "~" . $row[0]->Aisle_Name . "~" . $row[0]->Column_Name;
                $LocationBarcode = $row[0]->Room_ID . "|" . $row[0]->Aisle_ID . "|" . $row[0]->Column_ID;
                $this->InventoryLabelPrint($LocationText,$LocationBarcode);
                unset($sql, $row, $LocationText, $LocationBarcode);
            }

            foreach($ShelfSelectionArray AS $ShelfSelectionItem){
                $sql = "SELECT Location_Facility.Name AS Facility_Name, Location_Facility.id AS Facility_ID, Location_Inventory_Room.Name AS Room_Name, Location_Inventory_Room.id AS Room_ID, Location_Inventory_Aisle.Name AS Aisle_Name, Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Column.Name AS Column_Name, Location_Inventory_Column.id AS Column_ID, Location_Inventory_Shelf.Name AS Shelf_Name, Location_Inventory_Shelf.id AS Shelf_ID FROM Location_Inventory_Room INNER JOIN Location_Facility ON Location_Inventory_Room.Facility = Location_Facility.id INNER JOIN Location_Inventory_Aisle ON Location_Inventory_Aisle.Room = Location_Inventory_Room.id INNER JOIN Location_Inventory_Column ON Location_Inventory_Column.Aisle = Location_Inventory_Aisle.id INNER JOIN Location_Inventory_Shelf ON Location_Inventory_Shelf.`Column` = Location_Inventory_Column.id WHERE Location_Inventory_Shelf.id = $ShelfSelectionItem";
                $row = self::$db->fetch_all($sql);

                $LocationText = $row[0]->Room_Name . "~" . $row[0]->Aisle_Name . "~" . $row[0]->Column_Name . "~" . $row[0]->Shelf_Name;
                $LocationBarcode = $row[0]->Room_ID . "|" . $row[0]->Aisle_ID . "|" . $row[0]->Column_ID . "|" . $row[0]->Shelf_ID;
                $this->InventoryLabelPrint($LocationText,$LocationBarcode);
                unset($sql, $row, $LocationText, $LocationBarcode);
            }

            $newURL = "locations.php?do=inventory&action=all";
            header("Location:/$newURL");

        }

        public function AuditLocation(){
            //Get data from POST
            $ScanItem = $_POST["ScanItem"];
            $ScanLocation = $_POST["ScanLocation"];
            $AuditID = $_POST["AuditID"];
            $UID = $_SESSION["UID"];

            //Strip Tilde` from strings
            $ScanItem = str_replace("~", "", "$ScanItem");
            if($ScanItem[0] != 'P'){
                header('Content-type: application/json');
                $response_array['status'] = 'error';  
                die(json_encode($response_array));
            }
            $ScanLocation = str_replace("~", "", "$ScanLocation");
            if($ScanLocation[0] != 'I'){
                header('Content-type: application/json');
                $response_array['status'] = 'error';  
                die(json_encode($response_array));
            }
            $ScanItem = str_replace("P", "", "$ScanItem");
            $ScanLocation = str_replace("I", "", "$ScanLocation");

            //Explode string to an array by pipe
            $ScanItemArray = explode("|", $ScanItem);
            $ScanLocationArray = explode("|", $ScanLocation);

            //Define variables
            $NamedArray["Room"] = ltrim($ScanLocationArray[0], '0'); //Room
            $RoomID = $NamedArray["Room"];
            $NamedArray["Aisle"] = ltrim($ScanLocationArray[1], '0'); //Aisle
            $AisleID = $NamedArray["Aisle"];
            $NamedArray["Column"] = ltrim($ScanLocationArray[2], '0'); //Column
            $ColumnID = $NamedArray["Column"];
            $NamedArray["Shelf"] = ltrim($ScanLocationArray[3], '0'); //Shelf
            $ShelfID = $NamedArray["Shelf"];
            $NamedArray["InventoryID"] = ltrim($ScanItemArray[0], '0'); //Inventory ID
            $InventoryID = ltrim($ScanItemArray[0], '0'); //Inventory ID
            $PurchaseOrder = $ScanItemArray[1]; //PurchaseOrder

            
            //Check Total Inventory
            $StartingArray = $this->AuditInventoryLocationItem($AuditID, $InventoryID);
            $StartingCount = $StartingArray[0]->QuantityOnHand;

            //Check current count
            $WorkingArray = $this->AuditInventoryCurrentCount($AuditID, $InventoryID);
            $WorkingCount = $WorkingArray[0]->InspectedQty;

            if($StartingCount > $WorkingCount){
                $WorkingCount++;
                if($StartingCount == $WorkingCount){
                    //Assume completed
                    $sql = "UPDATE MCCS.Audit_Location_Detail SET Inspector = '$UID', `Status` = '1', InspectedQty = '$WorkingCount' WHERE InventoryID='" . $InventoryID . "' AND AuditID='" . $AuditID . "'";
                    self::$db->query($sql);
                    $response_array['status'] = 'partcomplete';
                } else {
                    //Assume still in progress
                    $sql = "UPDATE MCCS.Audit_Location_Detail SET InspectedQty = '$WorkingCount' WHERE InventoryID='" . $InventoryID . "' AND AuditID='" . $AuditID . "'";
                    self::$db->query($sql);
                    $response_array['status'] = 'success';      
                }
            } else {
                //error out
            }

            $GeneralClosure = $this->AuditInventoryCheckAll($AuditID);
            if(empty($GeneralClosure)){
                $sql = "UPDATE MCCS.Audit_Location_Header SET `Status` = '1' WHERE id='" . $AuditID . "'";
                self::$db->query($sql);
            }


            $response_array['InventoryID'] = "$InventoryID"; 
            die(json_encode($response_array));
        }

        public function WithdrawItems(){
            //Get data from POST
            $ScanItem = $_POST["ScanItem"];
            $ScanLocation = $_POST["ScanLocation"];
            $Quantity = $_POST["Quantity"];
            $Asset = $_POST["Asset"];

            if($Asset == '0'){
                header('Content-type: application/json');
                $response_array['status'] = 'asseterror';  
                die(json_encode($response_array));
            }

            //Strip Tilde` from strings
            $ScanItem = str_replace("~", "", "$ScanItem");
            if($ScanItem[0] != 'P'){
                header('Content-type: application/json');
                $response_array['status'] = 'error';  
                die(json_encode($response_array));
            }
            $ScanLocation = str_replace("~", "", "$ScanLocation");
            if($ScanLocation[0] != 'I'){
                header('Content-type: application/json');
                $response_array['status'] = 'error';  
                die(json_encode($response_array));
            }
            $ScanItem = str_replace("P", "", "$ScanItem");
            $ScanLocation = str_replace("I", "", "$ScanLocation");

            //Explode string to an array by pipe
            $ScanItemArray = explode("|", $ScanItem);
            $ScanLocationArray = explode("|", $ScanLocation);

            //Define variables
            $NamedArray["Room"] = ltrim($ScanLocationArray[0], '0'); //Room
            $RoomID = $NamedArray["Room"];
            $NamedArray["Aisle"] = ltrim($ScanLocationArray[1], '0'); //Aisle
            $AisleID = $NamedArray["Aisle"];
            $NamedArray["Column"] = ltrim($ScanLocationArray[2], '0'); //Column
            $ColumnID = $NamedArray["Column"];
            $NamedArray["Shelf"] = ltrim($ScanLocationArray[3], '0'); //Shelf
            $ShelfID = $NamedArray["Shelf"];
            $NamedArray["InventoryID"] = ltrim($ScanItemArray[0], '0'); //Inventory ID
            $InventoryID = ltrim($ScanItemArray[0], '0'); //Inventory ID
            
            //If ALL Zero's everything will be trimmed. This means we need to add a zero back from a string that equals ''
            if($RoomID == ''){
                $RoomID = '0';
            }
            if($AisleID == ''){
                $AisleID = '0';
            }
            if($ColumnID == ''){
                $ColumnID = '0';
            }
            if($ShelfID == ''){
                $ShelfID = '0';
            }

            //Find Location ID
            $sql = "SELECT id FROM Inventory_Detail_Location WHERE Room='" . $RoomID . "' AND Aisle='" . $AisleID . "' AND `Column`='" . $ColumnID . "' AND Shelf='" . $ShelfID . "' AND InventoryID='" . $InventoryID . "'";
            $InventoryDetailLocation = self::$db->fetch_all($sql);
            $InventoryDetailID = $InventoryDetailLocation[0]->id;

            //Check if quantity remaining is accurate 
            $sql = "SELECT QuantityOnHand FROM Inventory_Transaction_Summary WHERE InventoryID='" . $InventoryID . "' AND `Location` = '$InventoryDetailID'";
            $SanityCheck = self::$db->fetch_all($sql);
            //$Result = $SanityCheck[0]->QuantityRemaining;
            (int)$Result = $SanityCheck[0]->QuantityOnHand;

            if(($Quantity > $Result) OR ($Quantity == '0') OR ($Quantity == "")){
                $response_array['status'] = 'QuantityError';  
                die(json_encode($response_array));
            }

            
            (int)$OHQtyItem = $Quantity;
            if(!is_null($Result)){
                //If adjustment is higher
                if($Result >= $OHQtyItem){
                    //Subtract
                    (int)$OHAdjustment = $Result - $OHQtyItem;

                    $InventoryQuantityArray["InventoryID"] = $InventoryID;
                    $InventoryQuantityArray["Location"] = $InventoryDetailID;
                    $InventoryQuantityArray["UID"] = $_SESSION["UID"];
                    $InventoryQuantityArray["QuantityAdjustment"] = -$OHQtyItem;
                    $InventoryQuantityArray["Comments"] = "Inventory Withdraw";
                    $InventoryQuantityArray["Asset"] = "$Asset";

                    $SQLEntry = $this->InsertMultipleFields($InventoryQuantityArray);
                    self::$db->insert("Inventory_Transaction", $SQLEntry);

                    unset($InventoryQuantityArray);

                    $sql = "UPDATE MCCS.Inventory_Transaction_Summary SET QuantityOnHand = '$OHAdjustment'  WHERE InventoryID='" . $InventoryID . "' AND `Location`='" . $InventoryDetailID . "'";
                    $row = self::$db->query($sql);

                    unset($InventoryQuantityArray);

                    $response_array['status'] = 'success';  
                    die(json_encode($response_array));

                }
            }        
            

        }

        public function ReceivedItems(){
            //Get data from POST
            $ScanItem = $_POST["ScanItem"];
            $ScanLocation = $_POST["ScanLocation"];
            $Quantity = $_POST["Quantity"];
            $OrderID = $_POST["OrderID"];

            //Strip Tilde` from strings
            $ScanItem = str_replace("~", "", "$ScanItem");
            if($ScanItem[0] != 'P'){
                header('Content-type: application/json');
                $response_array['status'] = 'error';  
                die(json_encode($response_array));
            }
            $ScanLocation = str_replace("~", "", "$ScanLocation");
            if($ScanLocation[0] != 'I'){
                header('Content-type: application/json');
                $response_array['status'] = 'error';  
                die(json_encode($response_array));
            }
            $ScanItem = str_replace("P", "", "$ScanItem");
            $ScanLocation = str_replace("I", "", "$ScanLocation");

            //Explode string to an array by pipe
            $ScanItemArray = explode("|", $ScanItem);
            $ScanLocationArray = explode("|", $ScanLocation);

            //Define variables
            $NamedArray["Room"] = ltrim($ScanLocationArray[0], '0'); //Room
            $RoomID = $NamedArray["Room"];
            $NamedArray["Aisle"] = ltrim($ScanLocationArray[1], '0'); //Aisle
            $AisleID = $NamedArray["Aisle"];
            $NamedArray["Column"] = ltrim($ScanLocationArray[2], '0'); //Column
            $ColumnID = $NamedArray["Column"];
            $NamedArray["Shelf"] = ltrim($ScanLocationArray[3], '0'); //Shelf
            $ShelfID = $NamedArray["Shelf"];
            $NamedArray["InventoryID"] = ltrim($ScanItemArray[0], '0'); //Inventory ID
            $InventoryID = ltrim($ScanItemArray[0], '0'); //Inventory ID
            $PurchaseOrder = $ScanItemArray[1]; //PurchaseOrder

            //Check if quantity remaining is accurate 
            $sql = "SELECT QuantityRemaining FROM Inventory_Transaction_OnOrder WHERE OrderID='" . $OrderID. "' AND InventoryID='" . $InventoryID . "'";
            $SanityCheck = self::$db->fetch_all($sql);
            //$Result = $SanityCheck[0]->QuantityRemaining;
            $Result = $SanityCheck[0]->QuantityRemaining;
            if($Quantity > $Result){
                $response_array['status'] = 'QuantityError';  
                die(json_encode($response_array));
            }

            //If ALL Zero's everything will be trimmed. This means we need to add a zero back from a string that equals ''
            if($RoomID == ''){
                $RoomID = '0';
            }
            if($AisleID == ''){
                $AisleID = '0';
            }
            if($ColumnID == ''){
                $ColumnID = '0';
            }
            if($ShelfID == ''){
                $ShelfID = '0';
            }

            //Check if inventory location exists
            $sql = "SELECT id FROM Inventory_Detail_Location WHERE Room='" . $RoomID. "' AND Aisle='" . $AisleID . "' AND `Column`='" . $ColumnID . "' AND Shelf='" . $ShelfID . "' AND InventoryID='" . $InventoryID . "' LIMIT 1";
            $Decarte = self::$db->fetch_all($sql);
            if(empty($Decarte)){
                //Add to Inventory Location if doesn't already exist
                $SQLEntry = $this->InsertMultipleFields($NamedArray);
                $InventoryDetailLocation = self::$db->insert("Inventory_Detail_Location", $SQLEntry);
            } else {
                $InventoryDetailLocation = $Decarte[0]->id;
            }

            //Take away from OnOrder
            $sql = "UPDATE MCCS.Inventory_Transaction_OnOrder SET QuantityRemaining = QuantityRemaining - $Quantity WHERE OrderID='" . $OrderID . "' AND InventoryID='" . $InventoryID . "'";
            self::$db->query($sql);

            //Add to transaction table
            $TransactionArray["InventoryID"] = "$InventoryID";
            $TransactionArray["Location"] = "$InventoryDetailLocation";
            $TransactionArray["QuantityAdjustment"] = "$Quantity";
            $TransactionArray["UID"] = $_SESSION["UID"];
            $TransactionArray["Comments"] = "Received Item - PO: $PurchaseOrder";

            $SQLEntry = $this->InsertMultipleFields($TransactionArray);
            self::$db->insert("Inventory_Transaction", $SQLEntry);

            //Check if summary exists
            unset($Decarte, $SQLEntry);
            $sql = "SELECT id FROM Inventory_Transaction_Summary WHERE `Location`='" . $InventoryDetailLocation . "' AND InventoryID='" . $InventoryID . "' LIMIT 1";
            $Decarte = self::$db->fetch_all($sql);

            if(empty($Decarte)){
                //Create summary if doesn't exist
                $SummaryArray["InventoryID"] = "$InventoryID";
                $SummaryArray["Location"] = "$InventoryDetailLocation";
                $SummaryArray["QuantityOnHand"] = "$Quantity";
                $SummaryArray["UID"] = $_SESSION["UID"];
                $SummaryArray["Active"] = "1";
                $SQLEntry = $this->InsertMultipleFields($SummaryArray);
                self::$db->insert("Inventory_Transaction_Summary", $SQLEntry);
            } else {
                //Add to summary if exists
                $sql = "UPDATE MCCS.Inventory_Transaction_Summary SET QuantityOnHand = QuantityOnHand + '" . $Quantity . "' WHERE `Location`='" . $InventoryDetailLocation . "' AND InventoryID='" . $InventoryID . "'";
                self::$db->query($sql);
            }

            //See if we are at 0 in On order... If so, mark inventory item fulfilled
            $sql = "SELECT QuantityRemaining FROM Inventory_Transaction_OnOrder WHERE OrderID='" . $OrderID . "' AND InventoryID='" . $InventoryID . "'";
            $ItemMarkedZero = self::$db->fetch_all($sql);

            $ItemMarked = $ItemMarkedZero[0]->QuantityRemaining;

            if($ItemMarked == '0'){
                $sql = "UPDATE MCCS.Inventory_Transaction_OnOrder SET Fulfilled = '1' WHERE OrderID='" . $OrderID . "' AND InventoryID='" . $InventoryID . "'";
                self::$db->query($sql);

                //see if all other items are at quantity 0. If so, update header 
                $sql = "SELECT Fulfilled FROM Inventory_Transaction_OnOrder WHERE OrderID='" . $OrderID . "'";
                $SummaryMarkedArray = self::$db->fetch_all($sql);
                foreach($SummaryMarkedArray AS $SummaryMarked){
                    $SummaryItem = $SummaryMarked->Fulfilled;
                    if(empty($SummaryItem)){
                        $Unfulfilled = True;
                    }
                }
                if($Unfulfilled != True){
                    $sql = "UPDATE MCCS.Inventory_Detail_Order_Header SET Fulfilled = '1' WHERE id='" . $OrderID . "'";
                    self::$db->query($sql);
                }
            }


            //Subtract from DOM 
            $response_array['status'] = 'success';  
            $response_array['InventoryID'] = "$InventoryID"; 
            $response_array['QuantityScanned'] = "$Quantity"; 
            die(json_encode($response_array));
        }

        public function ReceiveLabelPrint(){
            $NumberofLabels = $_POST["LabelsToPrint"];
            $InventoryID = $_POST["InventoryID"];
            $PurchaseOrder = $_POST["PurchaseOrder"];
            $Vendor = $_POST["Vendor"];
            $i = 0;

            $LocationText = "$InventoryID|$PurchaseOrder|$Vendor|1";
            //Inventory ID | Purchase Order | Vendor | Quantity
            $LocationBarcode = "$InventoryID|$PurchaseOrder|$Vendor|1";
            while($i < $NumberofLabels){
                $this->InventoryPartLabelPrint($LocationText,$LocationBarcode);
                $i++;
            }
        }

        function InventoryPartLabelPrint($LocationText,$LocationBarcode){
            unset($cmds);

            $LocationBarcodeArray = explode('|', $LocationBarcode);
            $LocationTextItem = explode('|',$LocationText);
            $InventoryID = $LocationTextItem[0];

            //Add default location
            $sql = "SELECT Inventory_Detail_Location.id, Inventory_Detail_Location.InventoryID, Location_Inventory_Room.Name AS Room_Name, Inventory_Detail_Location.Room AS Room_ID, Location_Inventory_Aisle.Name AS Aisle_Name, Inventory_Detail_Location.Aisle AS Aisle_ID, Location_Inventory_Column.Name AS Column_Name, Inventory_Detail_Location.`Column` AS Column_ID, Location_Inventory_Shelf.Name AS Shelf_Name, Inventory_Detail_Location.Shelf AS Shelf_ID, Inventory_Detail_Location.`Default` AS `Default`, Inventory_Transaction_Summary.QuantityOnHand FROM Inventory_Detail_Location LEFT OUTER JOIN Location_Inventory_Room ON Inventory_Detail_Location.Room = Location_Inventory_Room.id LEFT OUTER JOIN Location_Inventory_Aisle ON Inventory_Detail_Location.Aisle = Location_Inventory_Aisle.id LEFT OUTER JOIN Location_Inventory_Column ON Inventory_Detail_Location.`Column` = Location_Inventory_Column.id LEFT OUTER JOIN Location_Inventory_Shelf ON Inventory_Detail_Location.Shelf = Location_Inventory_Shelf.id INNER JOIN Inventory_Transaction_Summary ON Inventory_Detail_Location.InventoryID = Inventory_Transaction_Summary.InventoryID AND Inventory_Detail_Location.id = Inventory_Transaction_Summary.Location WHERE Inventory_Detail_Location.InventoryID = $InventoryID AND Inventory_Detail_Location.`Default` = '1'";
            $DefaultLocation = self::$db->fetch_all($sql);
            $Room = $DefaultLocation[0]->Room_Name;
            $Aisle = $DefaultLocation[0]->Aisle_Name;
            $Column = $DefaultLocation[0]->Column_Name;
            $Shelf = $DefaultLocation[0]->Shelf_Name;

            if(empty($Room)){
                $Room = "";
            }

            if(empty($Aisle)){
                $Aisle = "";
            }

            if(empty($Column)){
                $Column = "";
            }

            if(empty($Shelf)){
                $Shelf = "";
            }


            $length = 7;
            foreach($LocationBarcodeArray AS $LocationBarcodeItem){
                $LocationBarcodeArrayPadded[] = str_pad($LocationBarcodeItem,$length,"0", STR_PAD_LEFT);
            }
            $LocationBarcode = implode('|',$LocationBarcodeArrayPadded);


            
            $cmds = '' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^MCY' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA';
            $cmds .= '^FWN^CFD,24^PW380^LH0,0' .PHP_EOL;
            $cmds .= '^CI0^PR2^MNY^MTT^MMT^MD0.0^JJ0,0^PON^PMN^LRN' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^DFR:TEMP_FMT.ZPL' .PHP_EOL;
            $cmds .= '^LRN' .PHP_EOL;
            $cmds .= '^FO232,40^BQN,2,4^FH^FDLA,P' . $LocationBarcode . '^FS' .PHP_EOL;
            $cmds .= '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,50^FDItem ID: ' . $LocationTextItem[0] . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,75^FDRoom: ' . $Room . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,100^FDAisle: ' . $Aisle . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,125^FDColumn: ' . $Column . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,150^FDShelf: ' . $Shelf . '^FS' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^XFR:TEMP_FMT.ZPL' .PHP_EOL;
            $cmds .= '^PQ1,0,1,Y' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^IDR:TEMP_FMT.ZPL' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;

            unset($LocationTextItem);
            //$cmds .= '<STX>H1;o149,20;f3;c63;h17;w15;d3,' . $LocationText . ';<ETX>';

            $file = tmpfile();
            fwrite($file, "$cmds");
            $path = stream_get_meta_data($file)['uri'];

            echo file_get_contents("$path");

echo "<br />";

            exec("lp -d \"ITPrinter\" $path ");
        //Print Label

            return;
        }

        function InventoryLabelPrint($LocationText,$LocationBarcode){
            unset($cmds);

            $LocationBarcodeArray = explode('|', $LocationBarcode);
            $length = 7;
            foreach($LocationBarcodeArray AS $LocationBarcodeItem){
                $LocationBarcodeArrayPadded[] = str_pad($LocationBarcodeItem,$length,"0", STR_PAD_LEFT);
            }
            $LocationBarcode = implode('|',$LocationBarcodeArrayPadded);


            $LocationTextItem = explode('~',$LocationText);
            $cmds = '' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^MCY' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA';
            $cmds .= '^FWN^CFD,24^PW380^LH0,0' .PHP_EOL;
            $cmds .= '^CI0^PR2^MNY^MTT^MMT^MD0.0^JJ0,0^PON^PMN^LRN' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^DFR:TEMP_FMT.ZPL' .PHP_EOL;
            $cmds .= '^LRN' .PHP_EOL;
            $cmds .= '^FO232,40^BQN,2,4^FH^FDLA,I' . $LocationBarcode . '^FS' .PHP_EOL;
            $cmds .= '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,50^FDRoom: ' . $LocationTextItem[0] . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,75^FDAisle: ' . $LocationTextItem[1] . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,100^FDColumn: ' . $LocationTextItem[2] . '^FS' .PHP_EOL;
            $cmds .= '^AAN,18,10^FO24,125^FDShelf: ' . $LocationTextItem[3] . '^FS' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^XFR:TEMP_FMT.ZPL' .PHP_EOL;
            $cmds .= '^PQ1,0,1,Y' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;
            $cmds .= '^XA' .PHP_EOL;
            $cmds .= '^IDR:TEMP_FMT.ZPL' .PHP_EOL;
            $cmds .= '^XZ' .PHP_EOL;

            unset($LocationTextItem);
            //$cmds .= '<STX>H1;o149,20;f3;c63;h17;w15;d3,' . $LocationText . ';<ETX>';

            $file = tmpfile();
            fwrite($file, "$cmds");
            $path = stream_get_meta_data($file)['uri'];

            echo file_get_contents("$path");

echo "<br />";

            exec("lp -d \"ITPrinter\" $path ");
        //Print Label

            return;
        }

        public function addInventoryLocation(){
            //Add default facility (Allow for improvements)
            $NamedArray["Facility"] = '1';
            $NamedArray["Name"] = $_POST["Room"];
            $Room = $_POST["Room"];

            //Check and see if records for either already exist
            if(!empty($Room)){
                $sql = "SELECT id FROM Location_Inventory_Room WHERE Location_Inventory_Room.Name = '$Room'";
                $row = self::$db->fetch_all($sql);
                //If a selection was not found we have to first create the department and then we can create a sub department
                if(empty($row)){
                    $SQLEntry = $this->InsertMultipleFields($NamedArray);
                    $RoomID = self::$db->insert("Location_Inventory_Room", $SQLEntry);
                } else {
                    //Carry Department ID forward to Sub_Department
                    $RoomID = $row[0]->id;
                    $RoomExisted = True;
                }
            }

            unset($row);

            $RoomNamedArray["Room"] = $RoomID;
            $RoomNamedArray["Name"] = $_POST["Aisle"];
            $Aisle = $_POST["Aisle"];

            if(!empty($Aisle)){
                $sql = "SELECT id FROM Location_Inventory_Aisle WHERE Location_Inventory_Aisle.Name = '$Aisle'";
                $row = self::$db->fetch_all($sql);
                //If a selection was not found we have to first create the department and then we can create a sub department
                if(empty($row)){
                    $SQLEntry = $this->InsertMultipleFields($RoomNamedArray);
                    $AisleID = self::$db->insert("Location_Inventory_Aisle", $SQLEntry);
                } else {
                    $AisleID = $row[0]->id;
                    $AisleExisted = True;
                }

                unset($row);

                //!!See if there is a match between Department and Sub_Department and THEN add or error out
                $sql = "SELECT Location_Inventory_Aisle.id AS Aisle_ID, Location_Inventory_Aisle.Room FROM Location_Inventory_Aisle WHERE Location_Inventory_Aisle.id = '$AisleID' AND Location_Inventory_Aisle.Room = '$RoomID'";
                $row = self::$db->fetch_all($sql);

                if(empty($row)){
                    $FinalNamedArray["Room"] = $RoomID;
                    $FinalNamedArray["Name"] = $Aisle;
                    $SQLEntry = $this->InsertMultipleFields($FinalNamedArray);
                    $AisleID = self::$db->insert("Location_Inventory_Aisle", $SQLEntry);
                }
                
            } elseif(isset($RoomExisted)){
                //!!Return with error since they selected a pre-existing department with no sub department inserted
            }

            unset($row, $FinalNamedArray);

            $AisleNamedArray["Aisle"] = $AisleID;
            $AisleNamedArray["Name"] = $_POST["Column"];
            $Column = $_POST["Column"];
            
            if(!empty($Column)){
                $sql = "SELECT id FROM Location_Inventory_Column WHERE Location_Inventory_Column.Name = '$Column'";
                $row = self::$db->fetch_all($sql);
                //If a selection was not found we have to first create the department and then we can create a sub department
                if(empty($row)){
                    $SQLEntry = $this->InsertMultipleFields($AisleNamedArray);
                    $ColumnID = self::$db->insert("Location_Inventory_Column", $SQLEntry);
                } else {
                    $ColumnID = $row[0]->id;
                    $ColumnExisted = True;
                }
            
                unset($row);
            
                //!!See if there is a match between Department and Sub_Department and THEN add or error out
                $sql = "SELECT Location_Inventory_Column.id AS Column_ID, Location_Inventory_Column.Aisle FROM Location_Inventory_Column WHERE Location_Inventory_Column.id = '$ColumnID' AND Location_Inventory_Column.Aisle = '$AisleID'";
                $row = self::$db->fetch_all($sql);
            
                if(empty($row)){
                    $FinalNamedArray["Aisle"] = $AisleID;
                    $FinalNamedArray["Name"] = $Column;
                    $SQLEntry = $this->InsertMultipleFields($FinalNamedArray);
                    $ColumnID = self::$db->insert("Location_Inventory_Column", $SQLEntry);
                }
                
            } elseif(isset($AisleExisted)){
                //!!Return with error since they selected a pre-existing department with no sub department inserted
            }
            
            unset($row, $FinalNamedArray);

            $ColumnNamedArray["Column"] = $ColumnID;
            $ColumnNamedArray["Name"] = $_POST["Shelf"];
            $Shelf = $_POST["Shelf"];
            
            if(!empty($Shelf)){
                $sql = "SELECT id FROM Location_Inventory_Shelf WHERE Location_Inventory_Shelf.Name = '$Shelf'";
                $row = self::$db->fetch_all($sql);
                //If a selection was not found we have to first create the department and then we can create a sub department
                if(empty($row)){
                    $SQLEntry = $this->InsertMultipleFields($ColumnNamedArray);
                    $ShelfID = self::$db->insert("Location_Inventory_Shelf", $SQLEntry);
                } else {
                    $ShelfID = $row[0]->id;
                    $ShelfExisted = True;
                }
            
                unset($row);
            
                //!!See if there is a match between Department and Sub_Department and THEN add or error out
                $sql = "SELECT Location_Inventory_Shelf.id AS Shelf_ID, Location_Inventory_Shelf.Column FROM Location_Inventory_Shelf WHERE Location_Inventory_Shelf.id = '$ShelfID' AND Location_Inventory_Shelf.Column = '$ColumnID'";
                $row = self::$db->fetch_all($sql);
            
                if(empty($row)){
                    $FinalNamedArray["Column"] = $ColumnID;
                    $FinalNamedArray["Name"] = $Shelf;
                    $SQLEntry = $this->InsertMultipleFields($FinalNamedArray);
                    $ShelfID = self::$db->insert("Location_Inventory_Shelf", $SQLEntry);
                }
                
            } elseif(isset($ColumnExisted)){
                //!!Return with error since they selected a pre-existing department with no sub department inserted
            }
            
            unset($row, $FinalNamedArray);

            $newURL = "locations.php?do=inventory&action=all";
            header("Location:/$newURL");
        }


        //Asset Create
        public function CreateAssetData(){
            $AssetID = $_POST["AssetID"];
            
            
            $NamedArray["Name"] = $_POST["Name"];
            $NamedArray["Description"] = $_POST["Description"];
            $NamedArray["AssetClass"] = $_POST["AssetClass"];
            $NamedArray["Priority"] = $_POST["Priority"];
            $NamedArray["Notes"] = $_POST["Notes"];
            $NamedArray["Status"] = $_POST["Status"];
            $NamedArray["InService"] = $_POST["InService"];
            $NamedArray["Vendor"] = $_POST["Vendor"];
            $NamedArray["CostCenter"] = $_POST["CostCenter"];
            $NamedArray["ModelNo"] = $_POST["ModelNo"];
            $NamedArray["SerialNo"] = $_POST["SerialNo"];
            $NamedArray["AssetNo"] = $_POST["AssetNo"];
            $NamedArray["Facility"] = $_POST["Facility"];
            $NamedArray["Active"] = $_POST["Active"];

            //If not in service then clear out assignment area
            if($NamedArray["InService"] != '2'){
                $DepartmentIDArray = explode(',',$_POST["EndLocationInput-hidden"]);
                $DepartmentID = $DepartmentIDArray[0];
                if(!empty($DepartmentID)){
                $NamedArray["Department"] = $DepartmentID;
                }
                if(!empty($_POST["Department"])){
                    if(empty($NamedArray["Department"])){
                        $NamedArray["Department"] = $_POST["Department"];
                    }
                }

                $SubDepartmentIDArray = explode(',',$_POST["EndLocationInput-hidden"]);
                $SubDepartmentID = $SubDepartmentIDArray[1];
                $SubDepartmentID = substr($SubDepartmentID, 1);
                $NamedArray["Sub_Department"] = $SubDepartmentID;
                if(!empty($_POST["Sub_Department"])){
                    if(empty($NamedArray["Sub_Department"])){
                        $NamedArray["Sub_Department"] = $_POST["Sub_Department"];
                    }
                }
                
            } else {
                $NamedArray["Department"] = "";
                $NamedArray["Sub_Department"] = "";
            }
            
            //Need to see what the previous was and compare... 
            //$NamedArray["Department"] = $_POST["Department"];
            //$NamedArray["Sub_Department"] = $_POST["Sub_Department"];

            $SQLEntry = $this->InsertMultipleFields($NamedArray);
            var_dump($SQLEntry);
            $lastid = self::$db->insert("Assets", $SQLEntry);

            $newURL = "assets.php?do=assets&action=view&assetid=$lastid&msg=AssetCreated";
            header("Location:/$newURL");
        }

        //Asset Update
        public function UpdateAssetData(){
            $AssetID = $_POST["AssetID"];
            
            
            $NamedArray["Name"] = $_POST["Name"];
            $NamedArray["Description"] = $_POST["Description"];
            $NamedArray["AssetClass"] = $_POST["AssetClass"];
            $NamedArray["Priority"] = $_POST["Priority"];
            $NamedArray["Notes"] = $_POST["Notes"];
            $NamedArray["Status"] = $_POST["Status"];
            $NamedArray["InService"] = $_POST["InService"];
            $NamedArray["Vendor"] = $_POST["Vendor"];
            $NamedArray["CostCenter"] = $_POST["CostCenter"];
            $NamedArray["ModelNo"] = $_POST["ModelNo"];
            $NamedArray["SerialNo"] = $_POST["SerialNo"];
            $NamedArray["AssetNo"] = $_POST["AssetNo"];
            $NamedArray["Facility"] = $_POST["Facility"];
            $NamedArray["Active"] = $_POST["Active"];

            //If not in service then clear out assignment area
            if($NamedArray["InService"] != '2'){
                if(empty(!$DepartmentIDArray)){
                    $DepartmentIDArray = explode(',',$_POST["EndLocationInput-hidden"]);
                    $DepartmentID = $DepartmentIDArray[0];
                    $NamedArray["Department"] = $DepartmentID;
                    if(!empty($_POST["Department"])){
                        if(empty($NamedArray["Department"])){
                            $NamedArray["Department"] = $_POST["Department"];
                        }
                    }

                    $SubDepartmentIDArray = explode(',',$_POST["EndLocationInput-hidden"]);
                    $SubDepartmentID = $SubDepartmentIDArray[1];
                    $SubDepartmentID = substr($SubDepartmentID, 1);
                    $NamedArray["Sub_Department"] = $SubDepartmentID;
                    if(!empty($_POST["Sub_Department"])){
                        if(empty($NamedArray["Sub_Department"])){
                            $NamedArray["Sub_Department"] = $_POST["Sub_Department"];
                        }
                    }
                }
            } else {
                $NamedArray["Department"] = "";
                $NamedArray["Sub_Department"] = "";
            }
            
            //Need to see what the previous was and compare... 
            //$NamedArray["Department"] = $_POST["Department"];
            //$NamedArray["Sub_Department"] = $_POST["Sub_Department"];

            $SQLEntry = $this->UpdateMultipleFields($NamedArray);
            $sql = "UPDATE MCCS.Assets $SQLEntry WHERE id='" . $AssetID . "'";
            $row = self::$db->query($sql);

            $newURL = "assets.php?do=assets&action=view&assetid=$AssetID&msg=UpdatedAsset";
            header("Location:/$newURL");
        }

    //SQL Builders
        //PHP < 7.3
        function array_key_last(array $array) {
            if( !empty($array) ) return key(array_slice($array, -1, 1, true));
        }
        //Update Multiple Fields
        public function UpdateMultipleFields($NamedArray){
            var_dump($NamedArray);
            $SQLEntry = 'SET ';
            foreach($NamedArray as $key => $value) {
                //Added if != 0 for logic reasons... remove this if other functions break
                if((empty($value)) AND $value != '0') {
                    $value = 'NULL';
                } elseif($value == 'NOW()'){
                    $value = 'NOW()';
                } else {
                    $value = "'$value'";
                }
                $SQLEntry .= "$key=$value";

                if( !function_exists('array_key_last') ) {
                    if ($key === $this->array_key_last($NamedArray)){
                        $SQLEntry .= '';
                    } else {
                        $SQLEntry .= ', ';
                    }
                } else {
                    if ($key === array_key_last($NamedArray)){
                        $SQLEntry .= '';
                    } else {
                        $SQLEntry .= ', ';
                    }  
                }
            }
            return ($SQLEntry) ? $SQLEntry : 0;
        }

        //Insert Multiple Fields
        public function InsertMultipleFields($NamedArray){
            foreach($NamedArray as $key => $value) {
                if(!empty($value)){
                    $SQLEntry = "$key^$value";
                    list($k, $v) = explode('^', $SQLEntry);
                    $result[$k] = $v;
                }

                unset($SQLEntry);
            }

            $SQLEntry = $result;

            return ($SQLEntry) ? $SQLEntry : 0;
        }

        public function CrontabEmail($host){
            $Testing = False;
            if ($Testing != True){
                require_once "Mail.php";
    
                //Define Common Elements
                $from = "Maintenance Control System <MCCS@MyAwesomeCompany.com>";
                $content = "text/html; charset=utf-8";
                $mime = "1.0";

                $sql = "SELECT `id`, `To`, `Subject`, `Body` FROM Crontab_Mailer WHERE Crontab_Mailer.Sending IS NULL";
                $CheckOutgoing = self::$db->fetch_all($sql);
                
                foreach($CheckOutgoing AS $Outgoing){
                    $recipients = "$Outgoing->To";
                    $MailSubject = "$Outgoing->Subject";
                    $body = "$Outgoing->Body";
                    $LastID = "$Outgoing->id";
        
                    if(empty($MailSubject)){
                        $subject = "*MCS Notification*";
                    } else {
                        $subject = "$MailSubject";
                    }
        
                    $headers = array ('From' => $from,
                    'To' => $recipients,
                    'Subject' => $subject,
                    'MIME-Version' => $mime,
                    'Content-type' => $content);

                    //Before send, update database to display sending
                    $sql = "UPDATE MCCS.Crontab_Mailer SET Sending = '1' WHERE id='" . $LastID . "'";
                    $row = self::$db->query($sql);

                    $smtp = Mail::factory('smtp',
                    array ('host' => $host,
                    'auth' => False));
                    $mail = $smtp->send($recipients , $headers, $body);
                    if (PEAR::isError($mail)) {
                        echo("<p>" . $mail->getMessage() . "</p>");
                    } else {
                        echo("<p>Message successfully sent!</p>");

                        //After successful launch delete traces
                        $sql = "DELETE FROM MCCS.Crontab_Mailer WHERE id='" . $LastID . "'";
                        $row = self::$db->query($sql);
                    }
                    
                }
            } else {
                echo "Testing mode is enabled. Emails will not be sent out.";
            }
        }

        //Email Functionality
        public function InternaltoExternalMailCall($body, $MailSubject, $ToList){
            $Testing = False;
            if ($Testing != True){
                
                $lastElement = end($ToList);
                foreach($ToList AS $ToItem){
                    if($ToItem != $lastElement) {
                        $to .= "$ToItem,";
                   } else {
                        $to .= "$ToItem";
                   }
                }
                
                

                if(empty($MailSubject)){
                    $subject = "*MCS Notification*";
                } else {
                    $subject = "$MailSubject";
                }

                $MailmanArray["To"] = "$to";
                $MailmanArray["Subject"] = "$subject";
                $MailmanArray["Body"] = "$body";
        
                $SQLEntry = $this->InsertMultipleFields($MailmanArray);
                self::$db->insert("Crontab_Mailer", $SQLEntry);

            } else {
                echo "Testing mode is enabled. Emails will not be sent out.";
            }
        }

        public function MailNotice($body){
            $body .= "<br /><br /><br /> <h3 style=\"text-align: center;color:red;\">***CONFIDENTIALITY NOTICE***</h3>
            <p style=\"text-align: center;\">***The contents of this automatically generated email message and any attachments are intended solely for the addressee(s)
            and may contain confidential and/or privileged information and may be legally protected from
            disclosure. If you are not the intended recipient of this message or their agent, or if this message
            has been addressed to you in error, please immediately alert the sender by reply email and then
            delete this message and any attachments. If you are not the intended recipient, you are hereby
            notified that any use, dissemination, copying, or storage of this message or its attachments is
            strictly prohibited.***</p><div style=\"text-align: center;\">--------------------------------</div>
            ";

            return $body;
        }

}

?>