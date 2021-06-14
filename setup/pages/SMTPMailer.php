<?php
  $data = "<?php
  define(\"_VALID_PHP\", True);
  session_start();
  \$_SESSION['loggedin'] = true;
  require_once(\"../../init.php\");
  ini_set('display_errors', 1);

  \$host = \"$SMTPAddress\";
  \$content->CrontabEmail(\$host);

  ?>";

  $fp = fopen("../server_side/cron/MinuteMail.php", 'w');
  fwrite($fp, $data);
  fclose($fp);

?>