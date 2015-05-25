<?php
$banner = "Web UI";
$version = "1.5";
$release = "b9";

$banner_gif = "banner.gif";

$_ERROR = "";
$debugmsg = "";
$_login = 0;
$_ret = 0;
$_prvlvl = 1;
$_crypt_uname = "";
$module = 0;
$option = 0;
$group = "";
$username = "";
$password = "";

$_vrows = array(25,50,100,0);
/* $_auth_method = array(1=>"local", 2=>"radius", 3=>"ldap", 4=>"sldap", 5=>"securid"); */
$_auth_method = array(1=>"local", 5=>"securid");

/****************************************************************************
** Please ONLY change the information below.
*****************************************************************************/

$redundancy = 0;

$dbtype = "mysql";
$dbname = "tacacs";

//Primary/Single,Backup/Redundant server
$dbhost = array("localhost","");
$dbpass = array("tac_plus","tac_plus");
$dbuname = array("tacacs","tacacs");

$debug = 1;
$demo = 0;
$pass_size = 8;
$changetime = 7;  //Inform when to change
$expiretime = 45; //Password expiration.  0 means no expiration.

$company_name = "Your Company Name";
$sitename = ""; //Add your information
$logo_gif = "logo.gif";  //replace with your companies gif
$message = "Please replace images/logo.gif with your company's logo"
          ." and this message with your company's legal message.";

$ads = "";
?>
