<?php
 
$table = 'getWorkordersPending';
 
$primaryKey = 'WorkOrderID';
 
$columns = array(
    array( 'db' => 'WorkOrderID', 'dt' => 0 ),
    array( 'db' => 'FullName', 'dt' => 1 ),
    array( 'db' => 'AssignedFullName', 'dt' => 2 ),
    array( 'db' => 'SubmissionDate', 'dt' => 3 ),
    array( 'db' => 'RequestedEndDate', 'dt' => 4 ),
    array( 'db' => 'DateDiffCheck', 'dt' => 5 ),
    array( 'db' => 'PriorityDetail', 'dt' => 6 ),
    array( 'db' => 'StatusDetail',  'dt' => 7 ),
    array( 'db' => 'Description',   'dt' => 8 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>