<?php
 
$table = 'getPMClosed';
 
$primaryKey = 'WO';
 
$columns = array(
    array( 'db' => 'PM', 'dt' => 0 ),
    array( 'db' => 'WO', 'dt' => 1 ),
    array( 'db' => 'Description', 'dt' => 2 ),
    array( 'db' => 'Name', 'dt' => 3 ),
    array( 'db' => 'RequestedStartDate', 'dt' => 4 ),
    array( 'db' => 'RequestedEndDate', 'dt' => 5 ),
    array( 'db' => 'Asset_Name',  'dt' => 6 ),
    array( 'db' => 'Location',   'dt' => 7 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>