
        <?php
            if (!defined('DB_DATABASE')){
                define ('DB_USER', "jcarlin");
                define ('DB_PASSWORD', "M0nk3y!!");
                define ('DB_DATABASE', "mccs");
                define ('DB_HOST', "localhost");

                $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
            }
            $sql_details = array(
                'user' => 'jcarlin',
                'pass' => 'M0nk3y!!',
                'db'   => 'mccs',
                'host' => 'localhost'
            );
        ?>
        