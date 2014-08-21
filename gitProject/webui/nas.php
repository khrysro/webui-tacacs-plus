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
<script language="JavaScript">
<!--
function _delete(obj)
{
	var msg = "Do you want really to delete "+obj+"?";

	if (confirm(msg)) {
		document.nasform.ip.value = obj;
		document.nasform.option.value = "3";
		document.nasform.submit();
	}
}

function _hover(nas)
{
	resultForm = document.nasform;
	getQueryXML(getVendorResults,"host","ip="+nas+"&host=1");
}

function _hover2(nas)
{
	document.nasform.ip.value = "";
	document.nasform.hostgroup.value = "";
	document.nasform.hkey.value = "";
	document.nasform.prompt1.value = "";
	document.nasform.loginacl.value = "";
	document.nasform.enableacl.value = "";
	document.nasform.enable.value = "";
	document.nasform.vendor.value = "";
}

function getNASResults()
{
	if (getRequest.readyState == 4) {
		var xmldoc = getRequest.responseXML.documentElement;

		resultForm.ip.value = xmldoc.getElementsByTagName('ip')[0].firstChild.nodeValue;
		if (xmldoc.getElementsByTagName('hostgroup')[0].firstChild != null)
			resultForm.hostgroup.value = xmldoc.getElementsByTagName('hostgroup')[0].firstChild.nodeValue;
		else
			resultForm.hostgroup.value = "";
		if (xmldoc.getElementsByTagName('hkey')[0].firstChild != null)
			resultForm.hkey.value = xmldoc.getElementsByTagName('hkey')[0].firstChild.nodeValue;
		else
			resultForm.hkey.value = "";
		if (xmldoc.getElementsByTagName('enable')[0].firstChild != null)
			resultForm.enable.value = xmldoc.getElementsByTagName('enable')[0].firstChild.nodeValue;
		else
			resultForm.enable.value = "";
		if (xmldoc.getElementsByTagName('prompt')[0].firstChild != null)
			resultForm.prompt1.value = xmldoc.getElementsByTagName('prompt')[0].firstChild.nodeValue;
		else
			resultForm.prompt1.value = "";
		resultForm.loginacl.value = xmldoc.getElementsByTagName('loginacl')[0].firstChild.nodeValue;
		resultForm.enableacl.value = xmldoc.getElementsByTagName('enableacl')[0].firstChild.nodeValue;
		resultForm.vendor.value = xmldoc.getElementsByTagName('vendor')[0].firstChild.nodeValue;
	}
	
}

function _modify(nas)
{
	resultForm = document.nasform;
	getQueryXML(getNASResults,"host","ip="+nas+"&host=1");
	resultForm.ip.disabled = true;
	resultForm.option.value = "2";
	resultForm._submit.value = "Modify";
}

function _required()
{
	var form = document.nasform;
	var ret  = true;
	var focus;
	var msg  = "";

	if (! form.ip.value) {
		msg = msg + "IP is required.\n";
		focus = form.ip;
		ret = false;
	}
		
	if (! form.hkey.value) {
		msg = msg + "HKEY is not required but recommended.\n";
	}
	
	if (! form.prompt1.value) {
		msg = msg + "Prompt is not required but recommended.\n";
	}
	
	if ( form.enable.value ) {
		if (form.enable.value != form.re_enable.value) {
			msg = msg + "Enable does not match.\n";
			focus = form.enable;
			ret = false;
		}
	}

	if (msg) alert(msg);
	if (focus) focus.focus();
	if (ret) form.ip.disabled = false;
	return ret;
}

//-->
</script>
<?php
$where = "";
switch ($option) {
   case 1:
	$network = preg_split('/', $ip);
	if (count($network)==1) $maskbits = 32;
	else $maskbits = $network[1];
	$crypt_enable = "";
	if ($enable) $crypt_enable = unixcrypt($enable);
	$result = @SQLQuery("INSERT INTO host (ip, hostgroup, hkey, enable, prompt, network, submask, loginacl, enableacl, vendor, host) VALUES('$ip','$hostgroup','$hkey','$crypt_enable','$prompt1',INET_ATON('".$network[0]."'),INET_ATON('".$netmask[$maskbits]."'),$loginacl,$enableacl,$vendor,1)", $dbi);
	break;
   case 2:
	$sqlcmd = "";
	if (!$enable) $sqlcmd = ", enable=''";
	if ($re_enable) $sqlcmd = ", enable='".unixcrypt($enable)."'";
	$result = @SQLQuery("UPDATE host SET hostgroup='$hostgroup', hkey='$hkey', prompt='$prompt1', loginacl=$loginacl, enableacl=$enableacl, vendor=$vendor $sqlcmd WHERE ip='$ip'", $dbi);
        if (!@SQLError($dbi))
                echo "<P><font color=\"red\">NAS($ip) modified - ($enable).</font></P>";
	break;
   case 3:
	$result = @SQLQuery("DELETE FROM host WHERE ip='$ip'", $dbi);
	break;

   case 4:
	$where = "AND hostgroup='$group'";
	break;
}

if ($debug) {
	$_ERROR = $_ERROR.@SQLError($dbi);
}

?>
<form name="nasform" method="post" action="?module=nas">
<fieldset class=" collapsible"><legend>NAS<?php if ($group) echo "s in group $group"; ?></legend>
<table border=0 width="100%">
<tr><td>
        <div id="_nas" class="_scrollwindow">
	<table border=1 class="_table2">
           <tr><th>IP</th><th>Group</th><th>HKey</th><th>Prompt</th><th>Login ACL</th><th>Enable ACL</th>
<?php
$acls = array();
$vnd_array = array();

$result = @SQLQuery("SELECT ip, hostgroup, hkey, prompt, loginacl, enableacl FROM host WHERE host=1 $where", $dbi);
while ($row=SQLFetchArray($result)) {
	$lacl = $row["loginacl"]?$row["loginacl"]:"&nbsp;";
	$eacl = $row["enableacl"]?$row["enableacl"]:"&nbsp;";
	echo "<tr><td width=80>".$row["ip"]."</td>"
	    ."<td width=80>".$row["hostgroup"]."</td>"
	    ."<td width=80>".$row["hkey"]."</td>"
	    ."<td width=190>".$row["prompt"]."</td>"
	    ."<td width=45><center>".$lacl."</center></td>"
	    ."<td width=45><center>".$eacl."</center></td>"
	    ."<td><a href=\"javascript:_modify('".$row["ip"]."')\" title=\"Modify NAS\"><img src=\"images/modify.gif\" width=25 border=0></img></a></td>";
	echo "<td><a href=\"javascript:_delete('".$row["ip"]."')\" title=\"Delete NAS\"><img src=\"images/trash.gif\" width=25 border=0></img></a></td></tr>\n";
}
@SQLFreeResult($result);
$result = @SQLQuery("SELECT id FROM acl WHERE type!=1 GROUP BY id", $dbi);
$i = 0;
while ($row = @SQLFetchRow($result)) {
	$acls[$i] = $row[0];
	$i++;
}
@SQLFreeResult($result);
$result = @SQLQuery("SELECT id, name FROM vendor ORDER BY name", $dbi);
while ($row = @SQLFetchRow($result)) {
	$vnd_array[$row[0]]=$row[1];
}
@SQLFreeResult($result);
?>
	</table>
        </div>
</td></tr>
<tr><td>
	<table class="_table">
	<tr><td>IP:</td><td><input type="text" name="ip" onChange="return _verify(this,'subnet');"></td>
	    <td>&nbsp;&nbsp;<input type="hidden" name="network"><input type="hidden" name="submask"></td>
	    <td>Vendor:</td><td><select name="vendor" style="width: 150px"><?php
		foreach ($vnd_array as $i=>$value) {
			echo "<option value=\"$i\">$value</option>";
		}
	     ?></select></td></tr>
	<tr><td>HKey:</td><td><input type="text" name="hkey"></td>
	    <td>&nbsp;&nbsp;</td>
	    <td>Group:</td><td><select name="hostgroup" style="width: 150px"><option value=""></option><?php
		$result = @SQLQuery("SELECT ip FROM host WHERE host=2", $dbi);
		while ($row = @SQLFetchArray($result)) {
			echo "<option value=\"".$row[0]."\">".$row[0]."</option>";
		}
	    ?></select></td></tr>
	<tr><td>Login ACL:</td><td><select name="loginacl"><option value="0"></option><?php
		foreach ($acls as $acl) {
			echo "<option value=\"$acl\">$acl</option>";
		} 
	    ?></select></td>
	    <td>&nbsp;&nbsp;</td>
	    <td>Enable ACL:</td><td><select name="enableacl"><option value="0"></option><?php
		foreach ($acls as $acl) {
			echo "<option value=\"$acl\">$acl</option>";
		}
	     ?></select></td></tr>
	<tr><td>Enable:</td><td><input type="password" name="enable"></td>
	    <td>&nbsp;&nbsp;</td>
	    <td>Re-Enable:</td><td><input type="password" name="re_enable" onChange="_checkpass(this,document.nasform.enable)"></td></tr>
	<tr><td>Prompt:</td><td colspan=4><textarea name="prompt1" cols="55" rows="10"></textarea></td></tr>
	<tr><td><input name="option" value="1" type="hidden"></td><td><input type="submit" name="_submit" value="Add" onclick="return _required();"> <input type="reset" onClick="return confirm('Are you sure you want to reset the data?')"></td></tr>
	</table>
</td></tr>
</table>
</fieldset>
</form>
