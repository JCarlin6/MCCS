<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "reporting": 
      (file_exists("reports/default.tpl.php")) ? include("reports/default.tpl.php") : include("reports/default.tpl.php");
    break;

    default:
      include("reports/default.tpl.php");
    break;

    endswitch;

?>