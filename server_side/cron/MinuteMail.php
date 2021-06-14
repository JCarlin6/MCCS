<?php
  define("_VALID_PHP", True);
  session_start();
  $_SESSION['loggedin'] = true;
  require_once("../../init.php");
  ini_set('display_errors', 1);

  $host = "";
  $content->CrontabEmail($host);

  ?>