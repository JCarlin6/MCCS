<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "audit": 
      (file_exists("audit/audit.tpl.php")) ? include("audit/audit.tpl.php") : include("audit/audit.tpl.php");
    break;


    default:
      include("audit/default.tpl.php");
    break;

    endswitch;

?>