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
function _delete(id, name, vid, vendor)
{
	var vendor_name = vendor?vendor:"All";
	var msg = "Do you want really to delete Command="+name+" for Vendor="+vendor_name+"?";

	if (confirm(msg)) {
		document.commandform.id.value = id;
		document.commandform.vid.value = vid;
		document.commandform.option.value = "3";
		document.commandform.submit();
	}
}

function _hover(id, vid)
{
	resultForm = document.commandform;
	getQueryXML(getVendorResults,"command","id="+id+"&vid="+vid);
}

function _hover2(id, vid)
{
	document.commandform.id.value = "";
	document.commandform.name.value = "";
	document.commandform.vid.value = "";
	document.commandform.descr.value = "";
	document.commandform.auth.value = "0";
}

function getVendorResults()
{
	if (getRequest.readyState == 4) {
		var xmldoc = getRequest.responseXML.documentElement;

		resultForm.id.value = xmldoc.getElementsByTagName('id')[0].firstChild.nodeValue;
		resultForm.name.value = xmldoc.getElementsByTagName('name')[0].firstChild.nodeValue;
		if (xmldoc.getElementsByTagName('descr')[0].firstChild != null)
			resultForm.descr.value = xmldoc.getElementsByTagName('descr')[0].firstChild.nodeValue;
		else
			resultForm.descr.value = "";
		resultForm.auth.value = xmldoc.getElementsByTagName('auth')[0].firstChild.nodeValue;
		resultForm.vid.value = xmldoc.getElementsByTagName('vid')[0].firstChild.nodeValue;
	}
}

function _modify(id, vid)
{
	resultForm = document.commandform;
	getQueryXML(getVendorResults,"command","id="+id+"&vid="+vid);
	resultForm.id.disabled = true;
	resultForm.name.disabled = true;
	resultForm.option.value = "2";
	resultForm._submit.value = "Modify";
}

function _required()
{
	var form = document.commandform;
	var ret  = true;
	var focus;
	var msg  = "";

	if (! form.id.value) {
		msg = msg + "ID is required.\n";
		focus = form.id;
		ret = false;
	} else {
		var anum=/(^\d+$)/;
		if (!anum.test(form.id.value)) {
			msg = msg + "ID is not valid.\n";
			focus = form.id;
			ret = false;
		}
	}
		
	if (! form.name.value) {
		msg = msg + "Name is required.\n";
		if (!focus) {
			focus = form.name;
		}
		ret = false;
	}
	
	if (msg) alert(msg);
	if (focus) focus.focus();
	if (ret) { form.id.disabled = false; form.name.disabled = false; }
	return ret;
}

//-->
</script>
<?php
switch ($option) {
   case 1:
	$result = @SQLQuery("INSERT INTO command (id, name, descr, auth, vid) VALUES ($id, '$name', '$descr', $auth, $vid)", $dbi);
	break;
   case 2:
	$result = @SQLQuery("UPDATE command SET descr='$descr', auth=$auth, vid=$vid WHERE id=$id AND vid=$vid", $dbi);
	break;
   case 3:
	$result = @SQLQuery("SELECT id FROM node WHERE attr=$id AND vid=$vid", $dbi);
	if (@SQLNumRows($result)<1) {
		$_ERROR = "DELETE FROM command WHERE id=$id AND vid=$vid";
		$result = @SQLQuery("DELETE FROM command WHERE id=$id AND vid=$vid", $dbi);
	} else {
		echo "<P><font color=\"red\">Cannot delete Command($id) for Vendor($vid). There are too many dependancies.</font></P>";
	}
	break;
}
if ($debug) {
	$_ERROR=$_ERROR." ".@SQLError($dbi);
}
?>
<form name="commandform" method="post" action="?module=command">
<fieldset class=" collapsible"><legend>Commands</legend>
<table border=0 width="100%">
<tr><td>
        <div id="_acls" class="_scrollwindow">
	<table border=1 class="_table2">
           <tr><th>ID</th><th width="100">Command</th><th width="180">Description</th><th>Authen</th><th>Vendor</th>
<?php
$attr_auth = array("all","tacacs","radius");
$vendor = array();

$result = @SQLQuery("SELECT id, name FROM vendor ORDER BY id", $dbi);
while ($row = @SQLFetchArray($result)) {
	$vendor[$row[0]]=$row[1];
}

$result = @SQLQuery("SELECT id, name, descr, auth, vid FROM command", $dbi);
while ($row = @SQLFetchArray($result)) {
	if ($row["id"]) {
	    echo "<tr><td>".$row["id"]."</td>"
		."<td width=\"100\">".$row["name"]."</td>"
		."<td width=\"180\">".$row["descr"]."</td>"
		."<td align=\"center\">".$attr_auth[$row["auth"]]."</td>"
		."<td>".$vendor[$row["vid"]]."</td>"
		."<td><a href=\"javascript:_modify('".$row["id"]."','".$row["vid"]."')\"><img src=\"images/modify.gif\" width=25 border=0></img></a></td>";
	    echo "<td><a href=\"javascript:_delete('".$row["id"]."','".$row["name"]."','".$row["vid"]."','".$vendor[$row["vid"]]."')\"><img src=\"images/trash.gif\" width=25 border=0></img></a></td></tr>\n";
	}
}
@SQLFreeResult($result);
?>
	</table>
        </div>
</td></tr>
<tr><td>
	<table class="_table">
	<tr><td>ID:</td><td><input type="text" name="id" size=6 onChange="return _verify(this,'num');"></td></tr>
	<tr><td>Command:</td><td><input type="text" name="name" size=20></td></tr>
	<tr><td>Description:</td><td><input type="text" name="descr" size=20></td></tr>
	<tr><td>Authen:</td><td><select name="auth"><?php
		foreach ($attr_auth as $i=>$j) {
			echo "<option value=\"$i\">$j</option>";
		}
	    ?></select></td></tr>
	<tr><td>Vendor:</td><td><select name="vid"><?php
		foreach ($vendor as $i=>$j) {
			echo "<option value=\"$i\">$j</option>";
		}
	    ?></select></td></tr>
	<tr><td><input name="option" value="1" type="hidden"></td><td><input type="submit" name="_submit" value="Add" onclick="return _required();"> <input type="reset" onClick="return confirm('Are you sure you want to reset the data?')"></td></tr>
	</table>
</td></tr>
</table>
</fieldset>
</form>
