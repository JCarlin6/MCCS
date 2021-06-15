# MCCS
Welcome to the Maintenance Content Control System (MCCS)!

Features:

Workorder Management
Inventory Management
Asset Management
Vendor Management
Asset and Inventory Location Management
Auditing
Reports
User Management
Active Directory Integration (Optional)

Pre-requisites:
PHP7.2 >
MySQL
LDAP Enabled (If using Active Directory)
PHPMail Enabled
Apache / Nginx
Curl

Installation:
Copy or clone contents to web directory
Navigate to the /Setup/ Directory from web browser
  Ensure PHP Mailer & LDAP is setup and enabled in PHP (LDAP if using Active Directory) Go through setup as instructed on webpage(s)

Post Installation:
Remove Setup Directory
Create Crontab for Mailer:
  Example: * * * * * curl localhost/server_side/MinuteMail.php
    All mail is sent to a queue which is then sent from the above script location disabling this will prevent any mail generated from the system from going outbound. Create Crontab for Master Cron
  Example: 1 0 * * * curl localhost/server_side/mastercron.php
    This enables the server to automatically notify of PM's that are setup in the system
