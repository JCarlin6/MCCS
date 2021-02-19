<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "controller": 
      (file_exists("controls/default.tpl.php")) ? include("controls/default.tpl.php") : include("controls/default.tpl.php");
    break;

    default:
      include("controls/default.tpl.php");
    break;

    endswitch;

?>