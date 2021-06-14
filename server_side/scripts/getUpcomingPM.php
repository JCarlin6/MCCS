<?php
 
$table = 'getUpcomingPM';
 
$primaryKey = 'PMID';
 
$columns = array(
    array( 'db' => 'PMID', 'dt' => 0 ),
    array( 'db' => 'Asset', 'dt' => 1 ),
    array( 'db' => 'Assignee', 'dt' => 2 ),
    array( 'db' => 'Department',  'dt' => 3 ),
    array( 'db' => 'WorkDescription',   'dt' => 4 ),
    array( 'db' => 'NextRunDate',   'dt' => 5 ),
    array( 'db' => 'DueDate',   'dt' => 6 ),
    array( 'db' => 'AuthGroup',   'dt' => 7 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>