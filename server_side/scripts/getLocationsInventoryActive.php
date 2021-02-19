<?php
 
$table = 'getLocationsInventoryActive';
 
$primaryKey = 'Room_ID';
 
$columns = array(
    array( 'db' => 'Room_ID', 'dt' => -4 ),
    array( 'db' => 'Aisle_ID', 'dt' => -3 ),
    array( 'db' => 'Column_ID', 'dt' => -2 ),
    array( 'db' => 'Shelf_ID', 'dt' => -1 ),
    array( 'db' => 'Room', 'dt' => 0 ),
    array( 'db' => 'Aisle', 'dt' => 1 ),
    array( 'db' => 'Column',  'dt' => 2 ),
    array( 'db' => 'Shelf',   'dt' => 3 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';

require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>