<?php
/*
    Copyright (C) 2003-2009 Young Consulting, Inc

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require_once("config.php");
require_once("mainfile.php");

$dbi=OpenDatabase($dbhost, $dbuname, $dbpass, $dbname);

require_once ("banner.php");
//require_once ("nav.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><title><?php echo "$pagetitle"; ?></title>
<link rel="stylesheet" type="text/css" href="style.css" />
<?php
$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
unset($BROWSER_AGENT);
unset($BROWSER_VER);

if (preg_match( '|MSIE ([0-9].[0-9]{1,2})|',$HTTP_USER_AGENT,$log_version)) { 
  $BROWSER_VER=$log_version[1]; 
  $BROWSER_AGENT='IE'; 
} elseif (preg_match( '|Opera ([0-9].[0-9]{1,2})|',$HTTP_USER_AGENT,$log_version)) { 
  $BROWSER_VER=$log_version[1]; 
  $BROWSER_AGENT='OPERA'; 
} elseif (preg_match( '|Firefox/([0-9\.]+)|',$HTTP_USER_AGENT,$log_version)) { 
  $BROWSER_VER=$log_version[1]; 
  $BROWSER_AGENT='FIREFOX'; 
} elseif (preg_match( '|Safari/([0-9\.]+)|',$HTTP_USER_AGENT,$log_version)) { 
  $BROWSER_VER=$log_version[1]; 
  $BROWSER_AGENT='SAFARI'; 
} else { 
  $BROWSER_VER=0; 
  $BROWSER_AGENT='OTHER'; 
} 

if ($BROWSER_AGENT=='IE') {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style_ie.css\" />\n";
}
?>
</head>
<body bgcolor="#F0F0F0" text="#000000" link="0000FF">
<?php
   if ($_login) {
	if (($_ret=Login($username,$password,$dbi)) > 0) {
		setcookie("login",$_crypt_uname);
	}
   } else {
   	$_ret = checkLogin($_COOKIE["login"], $dbi);
   }
?>
<script language="JavaScript" src="ajax.js"></script>
<script language="JavaScript" src="tacacs.js"></script>
<script language="JavaScript" src="calendar3.js"></script>
<table border=0 cellspacing=0 cellpadding=0 width=850>
<tr><td>
<div id="banner">
<?php Banner(); ?>
</div>
<tr><td>
<table border=0 cellspacing=0 cellpadding=0 width="100%" height="250">
<tr>
     <td bgcolor="#FFFFFF" width="20%" valign="top">
	<div id="nav" class="menu"><ul>
		<?php
			include("nav.php");
		?>
	</ul></div>
     <td width="80%" valign="top">
	<?php
	if ($_ret&&$module) {
		include ($module.".php");
	} else {
		$nmodule=$module?$module:"main";
		include ($nmodule.".php");
	}?>
     </td>
</tr>
</table>
<tr> <td><br>
<table border=0 width="100%" height=25>
<tr>
	<div id="footer">
	<td width="20%">&nbsp;<?php echo $_ERROR; ?>
	<td width="60%"><center><font color="#1c5d91"><?php echo "$copyrights"; ?></font></center>
	<td width="20%">&nbsp;
	</div>
</table>
<tr> <td><center><font size=-3 color="#1c5d91">(c)2002-2010 <a href="mailto:baram01@hotmail.com">Young Consulting</a></center>
</table>
</td></tr>
</body></html>
<?php
CloseDatabase($dbi);
?>
