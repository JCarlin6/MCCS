<?php
  $data = "<?php
  define(\"_VALID_PHP\", true);
  
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  define('DOMAIN_FQDN', '$FQDN'); //Replace with REAL DOMAIN FQDN
  define('LDAP_SERVER', '$LDAPServer');
  //define('LDAP_SERVER', '$LDAPServer');  //Replace with REAL LDAP SERVER Address
  //Basic Login verification
  if (isset(\$_POST['submit'])){
      \$_POST['username'] = str_ireplace(\"@$FQDN\", \"\", \$_POST['username']);
      \$user = strip_tags(\$_POST['username']) .'@'. DOMAIN_FQDN;
      \$pass = stripslashes(\$_POST['password']);
      \$conn = ldap_connect(\"ldap://\". LDAP_SERVER .\"/\");
      if (!\$conn)
          \$err = 'Could not connect to LDAP server';
      else
      {
  //        define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);  //Already defined in PHP 5.x  versions
          ldap_set_option(\$conn, LDAP_OPT_PROTOCOL_VERSION, 3);
          ldap_set_option(\$conn, LDAP_OPT_REFERRALS, 0);
          \$bind = @ldap_bind(\$conn, \$user, \$pass);
          ldap_get_option(\$conn, LDAP_OPT_DIAGNOSTIC_MESSAGE, \$extended_error);
          if (!empty(\$extended_error))
          {
              \$errno = explode(',', \$extended_error);
              \$errno = \$errno[2];
              \$errno = explode(' ', \$errno);
              \$errno = \$errno[2];
              \$errno = intval(\$errno);
              if (\$errno == 532){
                  \$err = 'Unable to login: Password expired';
              }
              \$newURL = \"login.php?msg=LoginFailed\";
              header(\"Location:/\$newURL\");
          }
          elseif (\$bind)
          {
        //determine the LDAP Path from Active Directory details
              \$base_dn = array(\"CN=Users,DC=\". join(',DC=', explode('.', DOMAIN_FQDN)), 
                  \"OU=Users,OU=People,DC=\". join(',DC=', explode('.', DOMAIN_FQDN)));
              \$result = ldap_search(array(\$conn,\$conn), \$base_dn, \"(cn=*)\");
              if (!count(\$result))
                  \$err = 'Result: '. ldap_error(\$conn);
              else
              {
                  session_start();
                  \$_SESSION[\"loggedin\"] = True;
                  \$_SESSION[\"Username\"] = \$_POST['username'];
                  \$Username = \$_POST['username'];
                  \$Username = strtolower(\$Username);
                  \$Name = explode('.', \$Username);
                  \$FirstName = \$Name[0];
                  if(!empty(\$Name[1])){
                    \$LastName = \$Name[1];
                  } else {
                    \$LastName = '';
                  }


                  include '../lib/dbcontroller.php';


                // Perform query
                if (\$result = \$mysqli->query(\"SELECT id FROM User WHERE lower(UserName)='\$Username'\")) {
                    //If 0 found insert ID else
                    if(\$result->num_rows == 0){
                        \$sql = \"INSERT INTO User (FirstName, LastName, UserName, Email, Active) VALUES ('\$FirstName', '\$LastName', '\$Username', '\$Username@$FQDN', '1')\";
                        \$mysqli->query(\$sql);
                        if (\$result = \$mysqli->query(\"SELECT id FROM User WHERE UserName='\$Username'\")) {
                            while(\$row = mysqli_fetch_assoc(\$result)) {
                                \$_SESSION[\"UID\"] = \$row[\"id\"];
                            }
                        }
                    } else {
                        while(\$row = mysqli_fetch_assoc(\$result)) {
                            \$_SESSION[\"UID\"] = \$row[\"id\"];
                         }
                    }
                }

                \$mysqli->close();

                  echo \"Success\";
                  \$newURL = \"default.php\";
                  header(\"Location:/\$newURL\");
          /* Do your post login code here */
              }
          }
      }
      // session OK, redirect to home page
      if (isset(\$_SESSION['redir']))
      {
          header('Location: /');
          exit();
      }
      elseif (!isset(\$err)) \$err = 'Result: '. ldap_error(\$conn);
      ldap_close(\$conn);
  } 
  ?>";

  $fp = fopen("../ajax/usercontroller.php", 'w');
  fwrite($fp, $data);
  fclose($fp);

?>