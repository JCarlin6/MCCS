<?php
 
 $UID = urldecode($_GET['uid']);

$table = 'getWorkordersTeam';
 
$primaryKey = 'WorkOrderID';
 
$columns = array(
    array( 'db' => 'WorkOrderID', 'dt' => 0 ),
    array( 'db' => 'FullName', 'dt' => 1 ),
    array( 'db' => 'SubmissionDate', 'dt' => 2 ),
    array( 'db' => 'RequestedEndDate', 'dt' => 3 ),
    array( 'db' => 'DateDiffCheck', 'dt' => 4 ),
    array( 'db' => 'PriorityDetail', 'dt' => 5 ),
    array( 'db' => 'StatusDetail',  'dt' => 6 ),
    array( 'db' => 'Description',   'dt' => 7 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    //SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "UID = '$UID'")
);

?>