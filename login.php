<?php
//If already logged in... Redirect to main page
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $newURL = "default.php";
    header("Location:/$newURL");
}
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/login.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<!------ Include the above in your HEAD tag ---------->
<body>

<div class="container login-container">
<?php 
$url = $_SERVER['REQUEST_URI'];
$exploded_url = explode( "=", $url ); 
if(!empty($exploded_url[1])){
    $AlertActive = $exploded_url[1]; 
}
if(!empty($AlertActive)){
if ($AlertActive != NULL){ ?>
  <div id="alert" class="alert alert-danger" style="width: 50%; margin: auto; margin-bottom: 10px; padding: 10px; text-align: center;">
    <strong>Login Failure:</strong> Please retry username and password...
  </div>
<?php }} ?>
    <div class="col-6 login-form" style="margin:auto;">
        <h3>Login</h3>
        <form enctype="multipart/form-data" action="/ajax/usercontroller.php?" method="post">
            <div class="form-group">
                <input type="text" class="form-control" required="required" autocomplete="off" name="username" placeholder="First.Last@MyAwesomeCompany.com" value="" />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" required="required" autocomplete="off" name="password" placeholder="********" value="" />
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btnSubmit" value="Login" />
            </div>
        </form>
    </div>
</div>
</body>
</html>