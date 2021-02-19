<?php

// DB table to use
$table = 'Assets';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'Name',  'dt' => 1 ),
    array( 'db' => 'Description',   'dt' => 2 )
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'Ilikecheese2',
    'db'   => 'MCCS',
    'host' => 'localhost'
);

?>