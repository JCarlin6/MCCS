<?php
  define("_VALID_PHP", true);
  
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');

  //Basic Login verification
  if (isset($_POST['submit'])){
    $user = strtolower($_POST['username']);
    $pass = SHA1($_POST['password']);

    include '../lib/dbcontroller.php';

    if ($result = $mysqli->query("SELECT id FROM User WHERE lower(UserName)='$user' AND `Password`='$pass'")) {
    while($row = mysqli_fetch_assoc($result)) {
        session_start();
        $_SESSION["UID"] = $row["id"];
        $_SESSION["loggedin"] = True;
        $_SESSION["Username"] = $_POST['username'];
    }
    }
    $mysqli->close();

    
    echo "Success";
    $newURL = "default.php";
    header("Location:/$newURL");
       
       
      // session OK, redirect to home page
      if (isset($_SESSION['redir'])){
          header('Location: /');
          exit();
      }
  } 
  ?>