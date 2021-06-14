<?php
 
$table = 'getPastDueWO';
 
$primaryKey = 'WorkOrderID';
 
$columns = array(
    array( 'db' => 'WorkOrderID', 'dt' => 0 ),
    array( 'db' => 'DaysOverdue', 'dt' => 1 ),
    array( 'db' => 'Department',  'dt' => 2 ),
    array( 'db' => 'Description',   'dt' => 3 ),
    array( 'db' => 'AssetName',     'dt' => 4 ),
    array( 'db' => 'CategoryDetail',     'dt' => 5 ),
    array( 'db' => 'FirstName',     'dt' => 6 ),
    array( 'db' => 'RequestedEndDate',     'dt' => 7 ),
    array( 'db' => 'LastName',     'dt' => 8 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>