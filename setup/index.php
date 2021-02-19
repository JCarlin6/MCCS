<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div id="content">
<div class="row">
    <div class="col text-center">
      <h1>Maintenance Control System</h1>
    </div>
  </div>
</div>
<ul class="nav nav-tabs justify-content-center" style="margin-left: 25px; margin-right: 25px; margin-top: 10px; margin-bottom: 15px;">
  <li class="nav-item">
      <a class="nav-link active" onclick="NavMain(0);" href="#">Check Run</a>
  </li>
  <li class="nav-item">
      <a class="nav-link" href="#">MySQL Information</a>
  </li>
  <li class="nav-item">
      <a class="nav-link" onclick="NavMain(2);" href="#">AD Setup</a>
  </li>
  <li class="nav-item">
      <a class="nav-link" onclick="NavMain(3);" href="#">SMTP Setup</a>
  </li>
  <li class="nav-item">
      <a class="nav-link" onclick="NavMain(4);" href="#">Site Setup</a>
  </li>
</ul>
<div id="Switcher[0]" style="display: block;">
  <?php include 'tabs/main.tpl.php'; ?>
</div>
<div id="Switcher[1]" style="display: none;">
  <?php include 'tabs/sql.tpl.php'; ?>
</div>
<div id="Switcher[2]" style="display: none;">
  <?php include 'tabs/ad.tpl.php'; ?>
</div>
<div id="Switcher[3]" style="display: none;">
  <?php include 'tabs/smtp.tpl.php'; ?>
</div>
<div id="Switcher[4]" style="display: none;">
  <?php include 'tabs/sitesetup.tpl.php'; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

<script>
function DefaultSite(){
  var DefaultSiteName = document.getElementById("DefaultSiteName").value;
  var Street = document.getElementById("Street").value;
  var City = document.getElementById("City").value;
  var State = document.getElementById("State").value;
  var Country = document.getElementById("Country").value;
  document.getElementById("DefaultSiteAdd").remove();

  //Send to SQL
  $.ajax({
    type: "POST",
    dataType: "json",
    url: '/setup/logic.php',
    data: {
      name: 'DefaultSite',
      DefaultSite: 'True',
      DefaultSiteName: DefaultSiteName,
      Street: Street,
      City: City,
      State: State,
      Country: Country
    },
    success: function(data){
      console.log('Default Site Information Added');
    }
  });
  var innerHTML = `\
    <div class="row">\
      <div class="col">\
        <button type="button" class="btn btn-success">Complete</button>
      </div>\
    </div>\
  `
  var node = document.getElementById("SiteForm");
  $(node).append(innerHTML);
}
</script>

<script>
function SubmitSQLInfo(){
//Grab Post Info
var MySQLServerAddress = document.getElementById("MySQLServerAddress").value;
var MySQLDB = document.getElementById("MySQLDB").value;
var MySQLUsername = document.getElementById("MySQLUsername").value;
var MySQLPassword = document.getElementById("MySQLPassword").value;
document.getElementById("SQLDetails").remove();

//Send to SQL
  $.ajax({
    type: "POST",
    dataType: "json",
    url: '/setup/logic.php',
    data: {
      name: 'SQLDetails',
      SQLDetails: 'True',
      MySQLServerAddress: MySQLServerAddress,
      MySQLDB: MySQLDB,
      MySQLUsername: MySQLUsername,
      MySQLPassword: MySQLPassword
    },
    success: function(data){
      console.log('SQL Information Saved');
    }
  });
  var innerHTML = `\
    <div class="row">\
      <div class="col">\
        <button type="button" class="btn btn-success" onclick="NavMain(2);">Next</button>
      </div>\
    </div>\
  `
  var node = document.getElementById("SQLForm");
  $(node).append(innerHTML);
}
</script>

<script>
function ShowADDetail(){
  var ADUserChoice = document.getElementById("UserController").value;
  console.log(ADUserChoice);
  if(ADUserChoice == '1'){
    document.getElementById(`ADPath`).style.display = "block";
    document.getElementById(`UserPath`).style.display = "none";
  } else {
    document.getElementById(`UserPath`).style.display = "block";
    document.getElementById(`ADPath`).style.display = "none";
  }
}
</script>

<script>
function SubmitADInfo(){
  //Grab Post Info
  var FQDN = document.getElementById("FQDN").value;
  var LDAPServer = document.getElementById("LDAPServer").value;

//Send to SQL
  $.ajax({
    type: "POST",
    dataType: "json",
    url: '/setup/logic.php',
    data: {
      name: 'UserInfoAD',
      UserInfoAD: 'True',
      FQDN: FQDN,
      LDAPServer: LDAPServer
    },
    success: function(data){
      console.log(data.status);
      document.getElementById(`UserControllerDiv`).style.display = "none";
      document.getElementById("ADSubmit").remove();

      var innerHTML = `\
      <div class="row">\
        <div class="col">\
          <button type="button" class="btn btn-success" onclick="NavMain(3);">Next</button>
        </div>\
      </div>\
      `
      var node = document.getElementById("ADPathForm");
      $(node).append(innerHTML);
    }
  });
}
</script>

<script>
function SubmitUserInfo(){
  //Grab Post Info
  var Username = document.getElementById("AdminUsername").value;
  var Password = document.getElementById("AdminPassword").value;

//Send to SQL
  $.ajax({
    type: "POST",
    dataType: "json",
    url: '/setup/logic.php',
    data: {
      name: 'UserInfo',
      UserInfo: 'True',
      Username: Username,
      Password: Password
    },
    success: function(data){
      console.log(data.status);
      document.getElementById(`UserControllerDiv`).style.display = "none";
      document.getElementById("UserSubmit").remove();

      var innerHTML = `\
      <div class="row">\
        <div class="col">\
          <button type="button" class="btn btn-success" onclick="NavMain(3);">Next</button>
        </div>\
      </div>\
      `
      var node = document.getElementById("UserPathForm");
      $(node).append(innerHTML);
    }
  });
}
</script>

<script>
function SubmitSMTPServer(){
//Grab Post Info
var MySQLServerAddress = document.getElementById("SMTPServerAddress").value;
document.getElementById("SMTPServer").remove();

//Send to SQL
  $.ajax({
    type: "POST",
    dataType: "json",
    url: '/setup/logic.php',
    data: {
      name: 'SubmitSMTPInfo',
      SubmitSMTPInfo: 'True',
      SMTPServerAddress: MySQLServerAddress
    },
    success: function(data){
      console.log('SMTP Setup');
    }
  });
  var innerHTML = `\
    <div class="row">\
      <div class="col">\
        <button type="button" class="btn btn-success" onclick="NavMain(4);">Next</button>
      </div>\
    </div>\
  `
  var node = document.getElementById("SMTPForm");
  $(node).append(innerHTML);
}
</script>

<script>
  function NavMain(n){
    var NavTabs = $('.nav-tabs .nav-link').length;
    $(".nav-tabs .nav-link").removeClass('active');
    $(".nav-tabs .nav-link").eq(n).addClass('active');
    for(var i = 0; i < NavTabs; i++){
      if(i == n){
        document.getElementById(`Switcher[${n}]`).style.display = "block";
      } else {
        if(i < n){
          $(".nav-tabs .nav-link").eq(i).css({'background-color':'green', 'color': 'white'});
        }
        document.getElementById(`Switcher[${i}]`).style.display = "none";
      }
    }
  }

    var select = document.getElementById("part_select");
    multi(select, {
      enable_search: true
    });
</script>
</body>
</html>