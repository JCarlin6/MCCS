<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "profile": 
      (file_exists("profile/default.tpl.php")) ? include("inventory/default.tpl.php") : include("inventory/default.tpl.php");
    break;

    default:
      include("profile/default.tpl.php");
    break;

    endswitch;

?>