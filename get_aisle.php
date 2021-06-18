<?php 
include 'lib/dbcontroller.php'; 

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }


if(!empty($_POST["room_id"])) {
$query ="SELECT * FROM Location_Inventory_Aisle WHERE Room = '" . $_POST["room_id"] . "' ORDER BY Name";
$results = $mysqli->query($query); 

?> <option value="">Select Aisle</option><?php foreach($results as $Aisle) { ?> <option value="<?php echo $Aisle["id"]; ?>"><?php echo $Aisle["Name"]; ?></option><?php } }

?>

