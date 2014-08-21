<?php
/*
    Copyright (C) 2003  Young Consulting, Inc
                                                                                                                                                                 
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

if ($_ret < 5) {
	echo "<script language=\"Javascript\"> top.location.href=\"index.php?module=main\"; </script>";
}

$dbi=OpenDatabase($dbhost, $dbuname, $dbpass, $dbname);

switch ($option) {
   case 1:
	$result = @SQLQuery("INSERT INTO contact_info (uid, fname, surname, address1, address2, address3, phone, email) VALUES ('$uid', '$fname', '$surname', '$address1', '$address2', '$address3', '$phone', '$email')", $dbi);
	break;
   case 2:
	$result = @SQLQuery("UPDATE contact_info SET fname='$fname', surname='$surname', address1='$address1', address2='$address2', address3='$address3', phone='$phone', email='$email' WHERE uid='$uid'");
	break;
}

$row = array();
$result = @SQLQuery("SELECT * FROM contact_info WHERE uid='$uid'", $dbi);
echo @SQLError($dbi);
if (SQLNumRows($result) > 0) {
	$option = 2;
	$row = @SQLFetchArray($result);
	@SQLFreeResult($result);
} else {
	$option = 1;
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<script language="Javascript">
<!--
function _require() {
parent.document.userform.comment.value = document.contactform.fname.value + " " + document.contactform.surname.value;
}
-->
</script>
<form name="contactform" method="post" action="contact.php?_ret=1&uid=<?php echo $uid; ?>">
<fieldset class="_collapsible"><legend id="_serviceset">Contact Information for <?php echo $uid; ?></legend>
<table border=0 width="100%" class="_table2">
<tr><td>
	<table class="_table2">
	<tr><td>Name:</td>
	    <td><input name="fname" type="text" size="30" value="<?php echo $row["fname"]; ?>"></td>
	    <td><input title="surname" name="surname" type="text" size="30" value="<?php echo $row["surname"]; ?>"></td>
	</tr>
	<tr><td>Address:</td>
	    <td colspan="2"><input name="address1" type="text" size="66" value="<?php echo $row["address1"]; ?>"</td>
	</tr>
	<tr><td></td>
	    <td colspan="2"><input name="address2" type="text" size="66" value="<?php echo $row["address2"]; ?>"></td>
	</tr>
	<tr><td></td>
	    <td colspan="2"><input name="address3" type="text" size="66" value="<?php echo $row["address3"]; ?>"></td>
	</tr>
	<tr><td>Phone:</td>
	    <td><input name="phone" type="text" size="20" value="<?php echo $row["phone"]; ?>"></td>
	    <td></td>
	</tr>
	<tr><td>Email:</td>
	    <td colspan="2"><input name="email" type="text" size="66" value="<?php echo $row["email"]; ?>"></td>
	</tr>
	<tr><td><input name="option" type="hidden" value="<?php echo $option; ?>"></td>
	    <td><input name="_submit" type="submit" value="<?php if ($option==1) echo "Add"; else echo "Modify"; ?>" onClick="return _require();">&nbsp;<input type="reset" onClick="return confirm('Are you sure that you want to reset?');"></td>
	</tr></table>
</td></tr>
</table>
</fieldset>
</form>
<?php
CloseDatabase($dbi);
?>
</body>
</html>
