<div class="row">
    <div class="col text-center">
      <h2>Running Checks</h2>
    </div>
  </div>
  <?php
  $error=false;
  
  // declare function
  function find_SQL_Version() {
    $output = shell_exec('mysql -V');
    preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
    return @$version[0]?$version[0]:-1;
  }
  
  $php_version = phpversion();
  $builtWithLdap = extension_loaded('ldap');

  if($php_version<7){
    $error=true;
    $php_error="PHP version is $php_version - too old!";
    echo "<span style='color:red;'>PHP VERSION: $php_error</span>";
  } else {
    echo "<div class=\"row\">";
      echo "<div class=\"col text-center\">";
        echo "<span style='color:green;'>PHP VERSION: $php_version - OK!</span><br />";
      echo "</div>";
    echo "</div>";
    //var_dump(PHP_OS);
  }

  if($builtWithLdap == TRUE){
    echo "<div class=\"row\">";
      echo "<div class=\"col text-center\">";
        echo "<span style='color:green;'>LDAP Enabled - OK!</span>";
      echo "</div>";
    echo "</div>";
  } else {
    echo "<div class=\"row\">";
      echo "<div class=\"col text-center\">";
        echo "<span style='color:red;'>LDAP Enabled - NO!</span>";
        echo "<span style='color:red;'>(CANNOT USE ACTIVE DIRECTORY)</span>";
      echo "</div>";
    echo "</div>";
  }

  if (function_exists('mail')){
    echo "<div class=\"row\">";
      echo "<div class=\"col text-center\">";
        echo "<span style='color:green;'>PHP Mailer Enabled - OK!</span>";
      echo "</div>";
    echo "</div>";
  } else {
    echo "<div class=\"row\">";
      echo "<div class=\"col text-center\">";
        echo "<span style='color:red;'>PHP Mailer Enabled - NO!</span>";
      echo "</div>";
    echo "</div>";
  }
  

  if( ini_get("safe_mode") ){
    $error=true;
    $safe_mode_error="Please switch of PHP Safe Mode";
  }

  $_SESSION['myscriptname_sessions_work']=1;
  if(empty($_SESSION['myscriptname_sessions_work'])){
    $error=true;
    $session_error="Sessions must be enabled!";
  }
  $db_error=false;

  if($error == false){
    echo "<div class=\"row\">";
      echo "<div class=\"col text-center\" style=\"margin-top: 30px;\">";
        echo "<button type=\"button\" class=\"btn btn-success\" onclick=\"NavMain(1);\">Next</button>";
      echo "</div>";
    echo "</div>";
  }

  ?>