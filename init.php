<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php //error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

  $BASEPATH = str_replace("init.php", "", realpath(__FILE__));
  define("BASEPATH", $BASEPATH);
  
  $configFile = BASEPATH . "lib/config.ini.php";
  if (file_exists($configFile)) {
      require_once($configFile);
  } else {
      header("Location: setup/");
	  exit;
  }
  
  require_once(BASEPATH . "lib/class_db.php");
  
  require_once(BASEPATH . "lib/class_registry.php");
  Registry::set('Database',new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE));
  $db = Registry::get("Database");
  $db->connect();
  
  //require_once(BASEPATH . "lib/functions.php");
  //include(BASEPATH . "lib/headerRefresh.php");
  
  if (!defined("_PIPN")) {
    require_once(BASEPATH . "lib/class_filter.php");
    $request = new Filter();  
  }

  //Load Content Class
  require_once(BASEPATH . "lib/class_content.php");
  Registry::set('Content',new Content());
  $content = Registry::get("Content");

  if (isset($_SERVER['HTTPS'])) {
      $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
  } else {
      $protocol = 'http';
  }
  
  //$dir = (Registry::get("Core")->site_dir) ? '/' . Registry::get("Core")->site_dir : '';
  $dir = '/';
  $url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $dir);
  $site_url = $protocol . "://" . $url;

  define("SITEURL", $site_url);
  define("ADMINURL", $site_url."/admin");
  define("UPLOADS", BASEPATH . "uploads/");
  define("UPLOADURL", SITEURL . "/uploads/");
  define("THEMEDIR", BASEPATH . "themes/");
  define("THEMEURL", SITEURL . "/themes/");
  define("THEME", BASEPATH . "themes/");

} else {
  $newURL = "login.php";
  header("Location:/$newURL");
}
  
?>