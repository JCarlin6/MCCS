<?php 
include 'lib/dbcontroller.php'; 

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }


if(!empty($_POST["aisle_id"])) {
$query ="SELECT * FROM Location_Inventory_Column WHERE Aisle = '" . $_POST["aisle_id"] . "' ORDER BY Name";
$results = $mysqli->query($query); 

?> <option value="">Select Column</option><?php foreach($results as $Column) { ?> <option value="<?php echo $Column["id"]; ?>"><?php echo $Column["Name"]; ?></option><?php } }

?>

