<?php
 
$table = 'getuserlist';
 
$primaryKey = 'UserName';
 
$columns = array(
    array( 'db' => 'FirstName', 'dt' => 0 ),
    array( 'db' => 'LastName', 'dt' => 1 ),
    array( 'db' => 'UserName', 'dt' => 2 ),
    array( 'db' => 'Email', 'dt' => 3 ),
    array( 'db' => 'Active', 'dt' => 4 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple($_GET,$sql_details,$table, $primaryKey, $columns )
);

?>