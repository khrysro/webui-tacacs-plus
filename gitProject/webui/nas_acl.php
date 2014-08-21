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
function _delete(id, seq)
{
	var msg = "Do you want really to delete ACL ID="+id+" and Sequence="+seq+"?";

	if (confirm(msg)) {
		document.aclform.id.value = id;
		document.aclform.seq.value = seq;
		document.aclform.option.value = "3";
		document.aclform.submit();
	}
}

function _hover(acl)
{
	resultForm = document.aclform;
	getQueryXML(getVendorResults,"acl","type=2&id="+id+"&seq="+seq);
}

function _hover2(acl)
{
	document.aclform.id.value = "";
	document.aclform.seq.value = "";
	document.aclform.permission.value = "";
	document.aclform.ugvalue.value = "";
	document.aclform.value1.value = "";
}

function getVendorResults()
{
	if (getRequest.readyState == 4) {
		var xmldoc = getRequest.responseXML.documentElement;

		resultForm.id.value = xmldoc.getElementsByTagName('id')[0].firstChild.nodeValue;
		resultForm.seq.value = xmldoc.getElementsByTagName('seq')[0].firstChild.nodeValue;
		resultForm.oldseq.value = xmldoc.getElementsByTagName('seq')[0].firstChild.nodeValue;
		if (xmldoc.getElementsByTagName('permission')[0].firstChild != null)
			aclform.permission.value = xmldoc.getElementsByTagName('permission')[0].firstChild.nodeValue;
		else
			aclform.permission.value = "";
		if (xmldoc.getElementsByTagName('value')[0].firstChild != null)
			aclform.ugvalue.value = xmldoc.getElementsByTagName('value')[0].firstChild.nodeValue;
		else
			aclform.ugvalue.value = "";
		if (xmldoc.getElementsByTagName('value1')[0].firstChild != null)
			aclform.value1.value = xmldoc.getElementsByTagName('value1')[0].firstChild.nodeValue;
		else
			aclform.value1.value = "";
	}
}

function _modify(id,seq)
{
	resultForm = document.aclform;
	getQueryXML(getVendorResults,"acl","type=2&id="+id+"&seq="+seq);
	resultForm.id.disabled = true;
	resultForm.option.value = "2";
	resultForm._submit.value = "Modify";
}

function _required()
{
	var form = document.aclform;
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
		
	if (! form.seq.value) {
		msg = msg + "Sequence is required.\n";
		if (!focus) {
			focus = form.seq;
		}
		ret = false;
	} else {
		var anum=/(^\d+$)/;
		if (!anum.test(form.seq.value)) {
			msg = msg + "Sequence is not valid.\n";
			focus = form.seq;
			ret = false;
		}
		if ((form.seq.value<0)||(form.seq.value>9998)) {
			msg = msg + "Sequence number must be between 1 and 9998.\n";
			focus = form.seq;
			ret = false;
		}
	}
	
	if (msg) alert(msg);
	if (focus) focus.focus();
	if (ret) form.id.disabled = false;
	return ret;
}

//-->
</script>
<?php
switch ($option) {
   case 1:
	$result = @SQLQuery("INSERT INTO acl (id, seq, permission, value, value1, type) VALUES ($id, $seq, $permission, '$ugvalue', $value1, 2)", $dbi);
	break;
   case 2:
	$result = @SQLQuery("UPDATE acl SET seq=$seq, permission=$permission, value='$ugvalue', value1=$value1 WHERE id=$id AND seq=$oldseq AND type=2", $dbi);
	break;
   case 3:
	$result = @SQLQuery("SELECT ip FROM host WHERE loginacl=$id or enableacl=$id", $dbi);
	$numnas = @SQLNumRows($result);
	$result = @SQLQuery("SELECT seq FROM acl WHERE id=$id AND type=2", $dbi);
	if (@SQLNumRows($result)>1) {
		$result = @SQLQuery("DELETE FROM acl WHERE id=$id AND seq=$seq AND type=2", $dbi);
	} else {
		if ($numnas<1) {
			$result = @SQLQuery("DELETE FROM acl WHERE id=$id AND seq=$seq AND type=2", $dbi);
		} else
			echo "<P><font color=\"red\">Cannot delete ACL($id). There are too many dependancies.</font></P>";
	}
	break;
}
if ($debug) {
	$_ERROR=$_ERROR." ".@SQLError($dbi);
}
?>
<form name="aclform" method="post" action="?module=nas_acl">
<fieldset class=" collapsible"><legend>NAS ACL</legend>
<table border=0 width="100%">
<tr><td>
        <div id="_acls" class="_scrollwindow">
	<table border=1 class="_table2">
           <tr><th>ID</th><th>Sequence</th><th>Permission</th><th>User/Group</th><th>Profile</th>
<?php
$perm_type = array(57=>"permit", "deny");
$profile = array(0=>'&nbsp;');

$result = @SQLQuery("SELECT id, uid from user where user=3", $dbi);
while ($row = @SQLFetchArray($result)) {
	$profile[$row[0]] = $row[1];
}
@SQLFreeResult($result);

$result = @SQLQuery("SELECT id, seq, permission, value, value1 FROM acl WHERE type=2 ORDER BY id, seq", $dbi);
while ($row = @SQLFetchArray($result)) {
	if ($row["id"]) {
	    echo "<tr><td>".$row["id"]."</td>"
		."<td>".$row["seq"]."</td>"
		."<td>".$perm_type[$row["permission"]]."</td>"
		."<td>".$row["value"]."</td>"
		."<td>".$profile[$row["value1"]]."</td>"
		."<td><a href=\"javascript:_modify('".$row["id"]."','".$row["seq"]."')\" title=\"Modify ACL\"><img src=\"images/modify.gif\" width=25 border=0></img></a></td>";
	    echo "<td><a href=\"javascript:_delete('".$row["id"]."','".$row["seq"]."')\" title=\"Delete\"><img src=\"images/trash.gif\" width=25 border=0></img></a></td></tr>\n";
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
	<tr><td>Sequence:</td><td><input type="text" name="seq" size=6 onChange="return _verify(this,'num');"><input type="hidden" name="oldseq"></td></tr>
	<tr><td>Permission:</td><td><select name="permission"><?php
		foreach ($perm_type as $i=>$j) {
			echo "<option value=\"$i\">$j</option>";
		}
	    ?></select></td></tr>
	<tr><td>User/Group:</td><td><select name="ugvalue" style="width: 160px"><option value="allusers">allusers</option><?php
		$result=@SQLQuery("SELECT uid, user FROM user WHERE user!=3 ORDER BY user", $dbi);
		while ($row = @SQLFetchArray($result)) {
			echo "<option value=\"".$row["uid"]."\">".$row["uid"];
			if ($row["user"]=="2") echo " (Group)";
			echo "</option>";
		}
		@SQLFreeResult($result);
	    ?></select></td></tr>
	<tr><td>Profile:</td><td><select name="value1" style="width: 160px"><?php
		foreach ($profile as $i=>$j) {
			echo "<option value=\"$i\">$j</option>";
		}
	    ?></select></td></tr>
	<tr><td><input name="option" value="1" type="hidden"><input name="type" value="2" type="hidden"></td><td><input type="submit" name="_submit" value="Add" onclick="return _required();"> <input type="reset" onClick="return confirm('Are you sure you want to reset the data?')"></td></tr>
	</table>
</td></tr>
</table>
</fieldset>
</form>
