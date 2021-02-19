<?php
 
$table = 'getPMConducted';
 
$primaryKey = 'PMID';
 
$columns = array(
    array( 'db' => 'PMID', 'dt' => 0 ),
    array( 'db' => 'AssetName', 'dt' => 1 ),
    array( 'db' => 'TimesConducted',  'dt' => 2 ),
    array( 'db' => 'AverageCompletionTime',   'dt' => 3 ),
    array( 'db' => 'LastCompletionTime',   'dt' => 4 )
);
 
// SQL server connection information
include '../../lib/dbcontroller.php';
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>