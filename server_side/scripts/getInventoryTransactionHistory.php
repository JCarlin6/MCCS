<?php
 

$PartTableID = urldecode($_GET['partid']);

$table = 'getInventoryTransactionHistory';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'Location', 'dt' => 1 ),
    array( 'db' => 'QuantityAdjustment', 'dt' => 2 ),
    array( 'db' => 'Employee', 'dt' => 3 ),
    array( 'db' => 'Comments',  'dt' => 4 ),
    array( 'db' => 'Modified',   'dt' => 5 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    //SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "InventoryID = '$PartTableID'")
);

?>