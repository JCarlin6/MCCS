<?php
 
$table = 'getVendorListActive';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => -1 ),
    array( 'db' => 'Vendor_ID', 'dt' => 0 ),
    array( 'db' => 'Name', 'dt' => 1 ),
    array( 'db' => 'Phone',  'dt' => 2 ),
    array( 'db' => 'Type',   'dt' => 3 ),
    array( 'db' => 'City',     'dt' => 4 ),
    array( 'db' => 'State',     'dt' => 5 ),
    array( 'db' => 'Active',     'dt' => 6 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>