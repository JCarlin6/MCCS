<?php
 
$table = 'getPMActive';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'Name', 'dt' => 1 ),
    array( 'db' => 'Description', 'dt' => 2 ),
    array( 'db' => 'UserName', 'dt' => 3 ),
    array( 'db' => 'NextRunDate', 'dt' => 4 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>