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

Changes:
02/02/2010	Andrew Young
	-Fix bug in first insert
*/

if (!eregi("index.php",$_SERVER['PHP_SELF'])) {
        Header("Location: index.php");
        die();
}

if ($_ret < 5) {
        echo "<script language=\"JavaScript\"> top.location.href=\"?module=main\"; </script>";
}
?>

<script language="Javascript" src="user.js"></script>

<?php
$where = "";
switch ($option) {
  case 1:
	$sqlcmd = "INSERT INTO user (id, uid, gid, comment, auth, password, enable, arap, pap, chap, mschap, expires, b_author, a_author, svc_dflt, cmd_dflt, maxsess, acl_id, shell, homedir, user";
	if ($flags) $sqlcmd .= ", flags"; 
	$sqlcmd .= ") VALUES ($id, '$uid', '$gid', '$comment', $auth, '".unixcrypt($password)."','".unixcrypt($enable)."','$arap','".unixcrypt($pap)."','$chap','$mschap','$expires','$b_author','$a_author',$svc_dflt,$cmd_dflt,$maxsess,$acl_id,'$shell','$homedir', 1";
	if ($flags) $sqlcmd .= ", 2";
	$sqlcmd .= ")";
	$result = @SQLQuery("$sqlcmd", $dbi);
	break;
  case 2:
	$sqlcmd = "UPDATE user set id=$id, gid='$gid', comment='$comment', auth=$auth, expires='$expires', disable=$disable, b_author='$b_author',a_author='$a_author',svc_dflt=$svc_dflt,cmd_dflt=$cmd_dflt,maxsess=$maxsess,acl_id=$acl_id, shell='$shell', homedir='$homedir'";
	if ($re_password) $sqlcmd .= ", password='".unixcrypt($password)."'";
	if ($re_enable) $sqlcmd .= ", enable='".unixcrypt($enable)."'";
	if ($re_arap) $sqlcmd .= ", arap='$arap'";
	if ($re_pap) $sqlcmd .= ", pap='".unixcrypt($pap)."'";
	if ($re_chap) $sqlcmd .= ", chap='$chap'";
	if ($re_mschap) $sqlcmd .= ", mschap='$mschap'";
	if ($flags) $sqlcmd .= ", flags=2";
	else $sqlcmd .= ", flags=0";
	$result = @SQLQuery("$sqlcmd WHERE uid='$uid'",$dbi);
	if (!@SQLError($dbi))
		echo "<P><font color=\"red\">User($uid) modified.</font></P>";
	break;
  case 3:
	$result = @SQLQuery("SELECT id FROM acl WHERE value='$uid'", $dbi);
	if (@SQLNumRows($result) > 0) {
		echo "<P><font color=\"red\">Cannot delete user($uid). There are too many dependancies.</font></P>";
	} else {
		$result = @SQLQuery("DELETE FROM node WHERE uid='$uid'", $dbi);
		$result = @SQLQuery("DELETE FROM user WHERE uid='$uid'", $dbi);
		$result = @SQLQuery("DELETE FROM contact_info WHERE uid='$uid'", $dbi);
	}
	break;
  case 4:
	$where = "AND gid='$group'";
	break;
}

if ($debug) {
	$_ERROR.=@SQlError($dbi);
}

?>
<form name="userform" method="post" action="?module=user">
<fieldset class="_collapsible"><legend>Users <?php if ($group) echo "in group $group"; ?></legend>
<table border=0 width="100%">
<tr><td>
	<div id="_user" class="_scrollwindow">
	<table border=1 class="_table2">
	<tr><th>ID</th><th>User</th><th>Group</th><th>Comment</th><th>Expires</th><th>ACL</th>
<?php
$result = @SQLQuery("SELECT disable, id, uid, gid, comment, expires, acl_id FROM user WHERE user=1 $where ORDER BY id", $dbi);
while ($row = @SQLFetchArray($result)) {
	$style = "";
	$acl = $row["acl_id"]?$row["acl_id"]:"&nbsp;";
	if ($row["disable"]) $style="style=\"color:red\"";
	else {
                if (strcmp($row["expires"],"0000-00-00 00:00:00")) {
                        $_now = strtotime("now");
                        $_expires = strtotime($row["expires"]);

                        if ($_now > $_expires) {
                                $style="style=\"color:red\"";
                        } else if ((($_expires - $_now) <= $changetime*24*60*60)) {
                                $style="style=\"color:orange\"";
                        }
                }
	}
	echo "<tr><td $style>".$row["id"]."</td>"
	    ."<td width=90 $style>".$row["uid"]."</td>"
	    ."<td width=90 $style>".$row["gid"]."</td>"
	    ."<td width=190 $style>".$row["comment"]."</td>"
	    ."<td $style>".$row["expires"]."</td>"
	    ."<td $style>".$acl."</td>"
	    ."<td><a href=\"javascript:_modify('".$row["id"]."','".$row["uid"]."','1')\" title=\"Modify User\"><img src=\"images/modify.gif\" width=25 border=0></a>"."</td>"
	    ."<td><a href=\"javascript:_openCommand('".$row["id"]."','".$row["uid"]."')\" title=\"Add/Modify Commands\"><img src=\"images/command.gif\" width=25 border=0></a>"."</td>"
	    ."<td><a href=\"javascript:_openService('".$row["id"]."','".$row["uid"]."')\" title=\"Add/Modify Services\"><img src=\"images/service.gif\" width=25 border=0></a>"."</td>"
	    ."<td><a href=\"javascript:_delete('".$row["uid"]."');\" title=\"Delete\"><img src=\"images/trash.gif\" width=25 border=0></img></a></td></tr>\n";
}
?>
	</table>
	</div>
<tr><td>
	<table class="_table">
	<tr><td width="50">Disable:</td><td><input type="checkbox" name="check_disable" onclick="Javascript:_checked(this,document.userform.disable)"><input type="hidden" name="disable" value="0"></td>
	<tr><td width="50">ID:</td><td><input type="text" name="id" size=5 value="0"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Access List:</td><td><select name="acl_id" size="1" style="width: 150px"><option value="0"><?php
	$result = @SQLQuery("SELECT id FROM acl WHERE type=1 GROUP BY id",$dbi);
	while ($row = SQLFetchRow($result)) {
		echo "<option value=\"".$row[0]."\">".$row[0];
	} ?></td>
    	<tr><td width="50">User ID:</td><td><input type="text" name="uid" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Group:</td><td><select name=gid size=1 style="width: 150px"><option value=""><?php
	$result = @SQLQuery("SELECT uid FROM user WHERE user=2", $dbi);
	while ($row = @SQLFetchRow($result)) {
		echo "<option value=\"".$row[0]."\">".$row[0];
	} ?></select>
    	<tr><td width="50">Comment:</td><td colspan="4"><input type="text" name="comment" size="64">&nbsp;<a href="Javascript:_open_contact(document.userform.uid,document.forms['userform'].elements['comment'])"><img width=25 src="images/identity.gif" border=0></img></a></td>
    	<tr><td width="50">Auth Meth:</td><td><select name="auth" size="1" style="width: 150px" onchange="_check_method(this)"><?php
	foreach ($_auth_method as $i=>$method) {
		echo "<option value=\"$i\">$method";
	} ?></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Expires:</td><td><input type="text" name="expires" size="20">&nbsp;<a href="Javascript:open_tcalendar(document.forms['userform'].elements['expires']);"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click here to pick a date"></img></a></td>
	<tr class="_passwords"><td colspan="2">Change Password at next login:&nbsp;&nbsp;<input type="checkbox" name="check_flags" onclick="Javascript:_checked(this,document.userform.flags)"><input type="hidden" name="flags" value="0"></td>
	    <td>&nbsp;&nbsp;</td>
	    <td></td><td></td>
    	<tr class="_passwords"><td width="50">Password:</td><td><input type="password" name="password" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Re-Password:</td><td><input type="password" name="re_password" size="20" onblur="_checkpass(this,document.userform.password,document.userform.min_passwd)"></td>
    	<tr class="_passwords"><td width="50">Enable:</td><td><input type="password" name="enable" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Re-Enable:</td><td><input type="password" name="re_enable" size="20" onblur="_checkpass(this,document.userform.enable)"></td>
    	<tr class="_passwords"><td width="50">PAP:</td><td><input type="password" name="pap" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Re-PAP:</td><td><input type="password" name="re_pap" size="20" onblur="_checkpass(this, document.userform.pap)"></td>
    	<tr class="_passwords"><td width="50">ARAP:</td><td><input type="password" name="arap" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Re-ARAP:</td><td><input type="password" name="re_arap" size="20" onblur="_checkpass(this,document.userform.arap)"></td>
    	<tr class="_passwords"><td width="50">CHAP:</td><td><input type="password" name="chap" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Re-CHAP:</td><td><input type="password" name="re_chap" size="20" onblur="_checkpass(this,document.userform.chap)"></td>
    	<tr class="_passwords"><td width="50">MSCHAP:</td><td><input type="password" name="mschap" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Re-MSCHAP:</td><td><input type="password" name="re_mschap" size="20" onblur="_checkpass(this,document.userform.mschap)"></td>
    	<tr><td width="50">Before Authorization:</td><td><input type="text" name="b_author" size="20"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">After Authorization:</td><td><input type="text" name="a_author" size="20"></td>
    	<tr><td width="50">Service Default:</td><td><input type="checkbox" name="check_svc_dflt" onclick="Javascript:_checked(this,document.userform.svc_dflt);"><input type="hidden" name="svc_dflt" value="0"></td>
	    <td>&nbsp;&nbsp;</td>
    	    <td width="100">Command Default:</td><td><input type="checkbox" name="check_cmd_dflt" onclick="Javascript:_checked(this,document.userform.cmd_dflt);"><input type="hidden" name="cmd_dflt" value="0"></td>
    	<tr><td width="50">Max Session:</td><td><input type="text" name="maxsess" size="20" value="0"></td>
    	<tr><td width="50">Shell:</td><td><input type="text" name="shell" size="20"></td>
            <td>&nbsp;&nbsp;</td>
    	    <td width="100">Home Directory:</td><td colspan="2"><input type="text" name="homedir" size="40"></td>
	<tr><td width="50"><input name="option" value="1" type="hidden"><input name="min_passwd" type="hidden"></td><td><input type="submit" name="_submit" value="Add" width=8 onClick="return _check_user_form(1)"> <input type="reset" onClick="return confirm('Are you sure you want to reset the data?')"></td>
	</table>
</table>
</fieldset>
</form>

<?php
echo "<script language=\"Javascript\">\n"
    ."    document.userform.min_passwd.value = $pass_size;\n"
    ."</script>\n";
?>

<div id="theLayer" style="position:absolute;width:850;left:50;top:200;visibility:hidden">
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
