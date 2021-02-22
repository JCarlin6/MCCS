<?php
if (isset($_POST['SQLDetails'])):
    if (intval($_POST['SQLDetails']) == 0 || empty($_POST['SQLDetails'])):
        $MySQLServerAddress = $_POST["MySQLServerAddress"];
        $MySQLDB = $_POST["MySQLDB"];
        $MySQLUsername = $_POST["MySQLUsername"];
        $MySQLPassword = $_POST["MySQLPassword"];

        $_SESSION["MySQLServerAddress"] = $_POST["MySQLServerAddress"];
        $_SESSION["MySQLDB"] = $_POST["MySQLDB"];
        $_SESSION["MySQLUsername"] = $_POST["MySQLUsername"];
        $_SESSION["MySQLPassword"] = $_POST["MySQLPassword"];

          include 'pages/relational_db.php';
         
          include 'pages/class_db.php';

        unset($data);
        $data['status'] = 'success';  
        echo json_encode($data);
        die();
    endif;
  endif;

  if (isset($_POST['UserInfoAD'])):
    if (intval($_POST['UserInfoAD']) == 0 || empty($_POST['UserInfoAD'])):
        $FQDN = strtoupper($_POST["FQDN"]);
        $LDAPServer = $_POST["LDAPServer"];

        define('LDAP_SERVER', "$LDAPServer");
        $conn = ldap_connect("ldap://". LDAP_SERVER ."/");
        if (!$conn){
          $err = 'Could not connect to LDAP server';
          $data['status'] = "$conn"; 
          echo json_encode($data);
          die();
        }

          include 'pages/user_file_AD.php';

        unset($data);
        $data['status'] = 'success';  
        echo json_encode($data);
        die();
    endif;
  endif;

  if (isset($_POST['UserInfo'])):
    if (intval($_POST['UserInfo']) == 0 || empty($_POST['UserInfo'])):
        $Username = $_POST["Username"];
        $Password = SHA1($_POST["Password"]);

        $MySQLServerAddress = $_COOKIE["MySQLServerAddress"];
        $MySQLUsername = $_COOKIE["MySQLUsername"];
        $MySQLPassword = $_COOKIE["MySQLPassword"];
        $MySQLDB = $_COOKIE["MySQLDB"];

        $conn = new mysqli("$MySQLServerAddress","$MySQLUsername","$MySQLPassword","$MySQLDB");

        $sql = "TRUNCATE user;";
        $conn->query("$sql");

        $sql = "ALTER TABLE user AUTO_INCREMENT = 1;";
        $conn->query("$sql");

        $sql = "INSERT INTO `user` (FirstName,LastName,UserName,`PASSWORD`,Email,Default_Facility,Active) VALUES ('Site','Admin','$Username','$Password','SiteAdmin@AwesomeCompany.com','1','1');";
        $conn->query("$sql");

        $sql = "INSERT INTO `user_group_details` (UID,Role) VALUES ('1','1');";
        $conn->query("$sql");

        $sql = "DROP TABLE IF EXISTS `_SiteSettings`;";
        $conn->query("$sql");

        $sql = "CREATE TABLE `_SiteSettings` (`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci';";
        $conn->query("$sql");

        $sql = "ALTER TABLE `_sitesettings`	ADD COLUMN `ActiveDirectory` INT(11) NOT NULL DEFAULT '0' AFTER `id`;";
        $conn->query("$sql");

        $sql = "INSERT INTO `$MySQLDB`.`_sitesettings` (`id`, `ActiveDirectory`) VALUES ('1','0')";
        $conn->query("$sql");

          include 'pages/user_file_internal.php';

        unset($data);
        $data['status'] = 'success';  
        echo json_encode($data);
        die();
    endif;
  endif;

  if (isset($_POST['SubmitSMTPInfo'])):
    if (intval($_POST['SubmitSMTPInfo']) == 0 || empty($_POST['SubmitSMTPInfo'])):

      $SMTPAddress = $_POST["SMTPServerAddress"];
        
        include 'pages/SMTPMailer.php';

      unset($data);
      $data['status'] = 'success';  
      echo json_encode($data);
      die();
    endif;
  endif;

  if (isset($_POST['DefaultSite'])):
    if (intval($_POST['DefaultSite']) == 0 || empty($_POST['DefaultSite'])):

      $DefaultSiteName = $_POST["DefaultSiteName"];
      $SiteName = $_POST["DefaultSiteName"];
      $SitePath = $_POST["SitePath"];
      $Street = $_POST["Street"];
      $City = $_POST["City"];
      $State = $_POST["State"];
      $Country = $_POST["Country"];

      include 'pages/site_data.php';
        
      $MySQLServerAddress = $_COOKIE["MySQLServerAddress"];
      $MySQLUsername = $_COOKIE["MySQLUsername"];
      $MySQLPassword = $_COOKIE["MySQLPassword"];
      $MySQLDB = $_COOKIE["MySQLDB"];

      $conn = new mysqli("$MySQLServerAddress","$MySQLUsername","$MySQLPassword","$MySQLDB");

      $sql = "INSERT INTO `$MySQLDB`.`location_facility` (`Name`, `Street`, `City`, `State`, `Country`) VALUES ('$DefaultSiteName','$Street', '$City', '$State', '$Country')";
      $conn->query("$sql");

      unset($data);
      $data['status'] = 'success';  
      echo json_encode($data);
      die();
    endif;
  endif;

?>