<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "inventory": 
      (file_exists("inventory/default.tpl.php")) ? include("inventory/default.tpl.php") : include("inventory/default.tpl.php");
    break;

    case"onhand":
      (file_exists("inventory/tasks/onhand.tpl.php")) ? include("inventory/tasks/onhand.tpl.php") : include("inventory/tasks/onhand.tpl.php");
    break;

    case"onorder":
      (file_exists("inventory/tasks/onorder.tpl.php")) ? include("inventory/tasks/onorder.tpl.php") : include("inventory/tasks/onorder.tpl.php");
    break;

    case"itemlocation":
      (file_exists("inventory/tasks/itemlocation.tpl.php")) ? include("inventory/tasks/itemlocation.tpl.php") : include("inventory/tasks/itemlocation.tpl.php");
    break;

    case"machinelist":
      (file_exists("inventory/tasks/machinelist.tpl.php")) ? include("inventory/tasks/machinelist.tpl.php") : include("inventory/tasks/machinelist.tpl.php");
    break;

    default:
      include("inventory/default.tpl.php");
    break;

    endswitch;

?>