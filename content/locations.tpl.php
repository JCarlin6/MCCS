<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "inventory": 
      (file_exists("locations/inventory.tpl.php")) ? include("locations/inventory.tpl.php") : include("locations/inventory.tpl.php");
    break;

    case"assets":
      (file_exists("locations/assets.tpl.php")) ? include("locations/assets.tpl.php") : include("locations/assets.tpl.php");
    break;

    default:
      include("locations/default.tpl.php");
    break;

    endswitch;

?>