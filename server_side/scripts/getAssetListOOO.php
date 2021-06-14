<?php
 
$table = 'getAssetListOOO';
 
$primaryKey = 'id';
 
$columns = array(
    array( 'db' => 'id', 'dt' => -1 ),
    array( 'db' => 'Name', 'dt' => 0 ),
    array( 'db' => 'Description', 'dt' => 1 ),
    array( 'db' => 'AssetClassName',  'dt' => 2 ),
    array( 'db' => 'FacilityName',   'dt' => 3 ),
    array( 'db' => 'Status',     'dt' => 4 ),
    array( 'db' => 'InService',     'dt' => 5 ),
    array( 'db' => 'Active',     'dt' => 6 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>