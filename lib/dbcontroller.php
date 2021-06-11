
        <?php
            if (!defined('DB_DATABASE')){
                define ('DB_USER', "");
                define ('DB_PASSWORD', "");
                define ('DB_DATABASE', "mccs");
                define ('DB_HOST', "localhost");

                $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
            }
            $sql_details = array(
                'user' => '',
                'pass' => '',
                'db'   => 'mccs',
                'host' => 'localhost'
            );
        ?>
        