<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "vendors": 
      (file_exists("vendors/default.tpl.php")) ? include("vendors/default.tpl.php") : include("vendors/default.tpl.php");
    break;

    default:
      include("vendors/default.tpl.php");
    break;

    endswitch;

?>