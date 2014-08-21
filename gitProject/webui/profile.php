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

if (!eregi("index.php",$_SERVER['PHP_SELF'])) {
        Header("Location: index.php");
        die();
}

if ($_ret < 5) {
        echo "<script language=\"JavaScript\"> top.location.href=\"?module=main\"; </script>";
}
?>

<script language="Javascript">
<!--
function _check_profile_form()
{
	var ret = true;
	var form = document.profileform;
	var msg = "";

	if (!form.pid.value || form.pid.value==0) {
		msg = "ID cannot be blank or 0";
		form.pid.focus();
		ret = false;
	}

	if (!form.uid.value) {
		msg = msg + "\nName cannot be blank";
		if (ret) form.uid.focus();
		ret = false;
	}

	if (msg) alert(msg);

	return ret;
}

function _delete(pid, uid) {
        var msg = "Do you want really want to delete "+uid+"?";

        if (confirm(msg)) {
                document.profileform.pid.value = pid;
                document.profileform.uid.value = uid;
                document.profileform.option.value = "3";
                document.profileform.submit();
        }
}

-->
</script>

<?php
switch ($option) {
  case 1:
	@SQLQuery("INSERT INTO user (id, uid, user)  VALUES ($pid, '$uid', 3)", $dbi);
	break;
  case 3:
	$result = @SQLQuery("SELECT id FROM acl WHERE value1=$pid", $dbi);
	if (@SQLNumRows($result)==0) {
		@SQLQuery("DELETE FROM user WHERE uid='$uid'", $dbi);
		@SQLQuery("DELETE FROM node WHERE uid='$uid'", $dbi);
	} else {
		echo "<P><font color=\"red\">Cannot delete profile ($uid).  Profile has dependancies.</font></P>";
	}
	if ($debug) {
		$_ERROR=@SQlError($dbi);
	}
	break;
}

?>
<form name="profileform" method="post" action="?module=profile">
<fieldset class="_collapsible"><legend>Profiles</legend>
<table border=0 width="100%">
<tr><td>
	<div id="_profile" class="_scrollwindow">
	<table border=1 class="_table2">
	<tr><th width=50>ID</th><th width=100>Name</th>
<?php
$result = @SQLQuery("SELECT id, uid FROM user WHERE user=3 ORDER BY id", $dbi);
while ($row = @SQLFetchArray($result)) {
	echo "<tr><td width=50>".$row["id"]
	    ."<td width=100>".$row["uid"]
	    ."<td><a href=\"javascript:_openCommand('".$row["id"]."','".$row["uid"]."')\" title=\"Add/Modify Commands\"><img src=\"images/command.gif\" width=25 border=0></a>"
	    ."<td><a href=\"javascript:_openService('".$row["id"]."','".$row["uid"]."')\" title=\"Add/Modify Services\"><img src=\"images/service.gif\" width=25 border=0></a>"
	    ."<td><a href=\"javascript:_delete('".$row["id"]."','".$row["uid"]."');\" title=\"Delete Profiles\"><img src=\"images/trash.gif\" width=25 border=0></img></a>\n";
}
?>
	</table>
	</div>
<tr><td>
	<table class="_table">
	<tr><td width="50">ID:</td><td><input type="text" name="pid" size=8></td>
    	<tr><td width="50">Name:</td><td><input type="text" name="uid" size="20"></td>
	<tr><td width="50"><input name="option" value="1" type="hidden"></td><td><input type="submit" name="_submit" value="Add" width=8 onClick="return _check_profile_form()"></td>
	</table>
</table>
</fieldset>
</form>

<div id="theLayer" style="position:absolute;width:850;left:100;top:200;visibility:hidden">
<table id="theLayerTable" border="0" width="850" bgcolor="#000000" cellspacing="0" cellpadding="1" height="100">
<tr>
<td width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
  <td id="titleBar" style="cursor:move" width="100%" bgcolor="#9999CC">
  <ilayer width="100%" onSelectStart="return false">
  <layer width="100%" onMouseover="isHot=true;if (isN4) ddN4(theLayer)" onMouseout="isHot=false">
  <font face="Arial" color="#FFFFFF"><div id="titleName"></div></font>
  </layer>
  </ilayer>
  </td>
  <td style="cursor:hand" valign="top" bgcolor="red">
  <a href="Javascript:hideMe()"><font face=arial color="#FFFFFF" style="text-decoration:none">X</font></a>
  </td>
  </tr>
  <tr>
  <td width="100%" bgcolor="#FFFFFF" style="padding:4px" colspan="2">
	<iframe id="_nodeframe" width="100%" height="300" frameborder="0" scrolling="yes"></iframe>
  </td>
  </tr>
  </table> 
</td>
</tr>
</table>
</div>
