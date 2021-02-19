<?php
 
$table = 'getInventoryOrdersPending';
 
$primaryKey = 'OrderID';
 
$columns = array(
    array( 'db' => 'OrderID', 'dt' => 0 ),
    array( 'db' => 'Vendor_ID', 'dt' => 1 ),
    array( 'db' => 'ReqNumber',  'dt' => 2 ),
    array( 'db' => 'PONumber',   'dt' => 3 ),
    array( 'db' => 'FirstName',     'dt' => 4 ),
    array( 'db' => 'OrderDate',     'dt' => 5 ),
    array( 'db' => 'LastName',     'dt' => 6 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>