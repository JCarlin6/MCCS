<?php 
include 'lib/dbcontroller.php'; 

// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }


if(!empty($_POST["column_id"])) {
$query ="SELECT * FROM Location_Inventory_Shelf WHERE `Column` = '" . $_POST["column_id"] . "' ORDER BY Name";
var_dump($query);
$results = $mysqli->query($query); 

?> <option value="">Select Shelf</option><?php foreach($results as $Shelf) { ?> <option value="<?php echo $Shelf["id"]; ?>"><?php echo $Shelf["Name"]; ?></option><?php } }

?>

