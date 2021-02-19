<?php
 
$table = 'getRoleList';
 
$primaryKey = 'FullName';
 
$columns = array(
    array( 'db' => 'FullName', 'dt' => 0 ),
    array( 'db' => 'GroupDescription', 'dt' => 1 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>