<?php
 
$table = 'getAuditLocationList';
 
$primaryKey = 'AuditID';
 
$columns = array(
    array( 'db' => 'AuditID', 'dt' => 0 ),
    array( 'db' => 'Room', 'dt' => 1 ),
    array( 'db' => 'FirstName',  'dt' => 2 ),
    array( 'db' => 'Modified',   'dt' => 3 ),
    array( 'db' => 'Status',   'dt' => 4 ),
    array( 'db' => 'Aisle',   'dt' => 5 ),
    array( 'db' => 'Column',   'dt' => 6 ),
    array( 'db' => 'Shelf',   'dt' => 7 ),
    array( 'db' => 'LastName',   'dt' => 8 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>