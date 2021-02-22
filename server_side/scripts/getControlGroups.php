<?php
 
$table = 'getControlGroup';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => -1 ),
    array( 'db' => 'Group_Description', 'dt' => 0 ),
    array( 'db' => 'Name',   'dt' => 1 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>