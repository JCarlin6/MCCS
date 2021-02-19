<?php
  if (!defined("_VALID_PHP")){
    die('Direct access to this location is not allowed.');
  }

    switch(Filter::$do): case "workorders": 
      (file_exists("workorders/default.tpl.php")) ? include("workorders/default.tpl.php") : include("workorders/default.tpl.php");
    break;

    case"open":
      (file_exists("workorders/open.tpl.php")) ? include("workorders/open.tpl.php") : include("main.php");
    break;

    case"pending":
      (file_exists("workorders/pending.tpl.php")) ? include("workorders/pending.tpl.php") : include("main.php");
    break;

    case"closed":
      (file_exists("workorders/closed.tpl.php")) ? include("workorders/closed.tpl.php") : include("main.php");
    break;

    default:
      include("workorders/default.tpl.php");
    break;

    endswitch;

?>