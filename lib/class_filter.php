<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  final class Filter
  {
	  public static $id = null;
      public static $get = array();
      public static $post = array();
      public static $cookie = array();
      public static $files = array();
      public static $server = array();
      private static $marker = array();
	  public static $msgs = array();
	  public static $showMsg;
	  public static $action = null;
	  public static $do = null;

      public function __construct(){
		  
          $_GET = self::clean($_GET);
          $_POST = self::clean($_POST);
          $_COOKIE = self::clean($_COOKIE);
          $_FILES = self::clean($_FILES);
          $_SERVER = self::clean($_SERVER);

          self::$get = $_GET;
          self::$post = $_POST;
          self::$cookie = $_COOKIE;
          self::$files = $_FILES;
          self::$server = $_SERVER;

		  self::getAction();
		  self::getDo();
		  self::$id = self::getId();
      }


	  private static function getId(){
		  if (isset($_REQUEST['id'])) {
			  self::$id = (is_numeric($_REQUEST['id']) && $_REQUEST['id'] > -1) ? intval($_REQUEST['id']) : false;
			  self::$id = sanitize(self::$id);
			  
			  if (self::$id == false) {
				  DEBUG == true ? self::error("You have selected an Invalid Id", "Filter::getId()") : self::ooops();
			  } else
				  return self::$id;
		  }
	  }
	  
      public static function clean($data){
          if (is_array($data)) {
              foreach ($data as $key => $value) {
                  unset($data[$key]);

                  $data[self::clean($key)] = self::clean($value);
              }
          } else {
			  if (ini_get('magic_quotes_gpc')) {
				  $data = stripslashes($data);
			  } else {
				  $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
			  }
		  }

          return $data;
      }

      
	  
	  private static function getAction(){
		  if (isset(self::$get['action'])) {
			  $action = ((string)self::$get['action']) ? (string)self::$get['action'] : false;
			  
			  if ($action == false) {
				  self::error("You have selected an Invalid Action Method","Filter::getAction()");
			  } else
				  return self::$action = $action;
		  }
	  }
	  	  
	  private static function getDo(){
		  if (isset(self::$get['do'])) {
			  $do = ((string)self::$get['do']) ? (string)self::$get['do'] : false;
			  
			  if ($do == false) {
				  self::error("You have selected an Invalid Do Method","Filter::getDo()");
			  } else
				  return self::$do = $do;
		  }
	  }


	  public static function dodate($format, $date){
		return utf8_encode(strftime(Registry::get("Core")->$format, strtotime($date)));
	  } 


	  public static function dotime($time){
		return utf8_encode(strftime(Registry::get("Core")->time_format, strtotime($time)));
	  } 


	  
  }
?>