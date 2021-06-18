<?php
 
$table = 'getPartType';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'Type', 'dt' => 1 ),
    array( 'db' => 'Description', 'dt' => 2 ),
    array( 'db' => 'Active', 'dt' => 3 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple($_GET,$sql_details,$table, $primaryKey, $columns )
);

?>