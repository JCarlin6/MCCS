<?php
 
$table = 'getUserRoles';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => -1 ),
    array( 'db' => 'Role_Description', 'dt' => 0 ),
    array( 'db' => 'Group_Description', 'dt' => 1 ),
    array( 'db' => 'Level',  'dt' => 2 ),
    array( 'db' => 'Name',   'dt' => 3 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>