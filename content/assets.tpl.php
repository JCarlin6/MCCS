<?php 
    if (!defined("_VALID_PHP")){
        die('Direct access to this location is not allowed.');
    }

    switch(Filter::$do): case "assets": 
        (file_exists("assets/default.tpl.php")) ? include("assets/default.tpl.php") : include("assets/default.tpl.php");
    break;

    case"machinelist":
        (file_exists("assets/tasks/machinelist.tpl.php")) ? include("assets/tasks/machinelist.tpl.php") : include("assets/tasks/machinelist.tpl.php");
    break;

    default:
        include("assets/default.tpl.php");
    break;

    endswitch;
?>