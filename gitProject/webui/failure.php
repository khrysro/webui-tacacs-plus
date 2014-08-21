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

?>
<script language="JavaScript">
<!--
function _delete(obj)
{
	var msg = "Do you want really to delete "+obj+"?";

	if (confirm(msg)) {
		document.failform.id.value = obj;
		document.failform.option.value = "3";
		document.failform.submit();
	}
}

function _hover(fail)
{
	resultForm = document.failform;
	getQueryXML(getVendorResults,"fail","vid="+fail);
}

function _hover2(fail)
{
	document.failform.id.value = "";
	document.failform.name.value = "";
	document.failform.url.value = "";
	document.failform.scname.value = "";
	document.failform.scemail.value = "";
	document.failform.tsphone.value = "";
	document.failform.tsemail.value = "";
	document.failform.contract.value = "";
	document.failform.start.value = "";
	document.failform.end.value = "";
}

function getCompResults()
{
	if (getRequest.readyState == 4) {
		var xmldoc = getRequest.responseXML.documentElement;

		resultForm.id.value = xmldoc.getElementsByTagName('id')[0].firstChild.nodeValue;
		resultForm.name.value = xmldoc.getElementsByTagName('name')[0].firstChild.nodeValue;
		if (xmldoc.getElementsByTagName('url')[0].firstChild != null)
			failform.url.value = xmldoc.getElementsByTagName('url')[0].firstChild.nodeValue;
		else
			failform.url.value = "";
		if (xmldoc.getElementsByTagName('scname')[0].firstChild != null)
			failform.scname.value = xmldoc.getElementsByTagName('scname')[0].firstChild.nodeValue;
		else
			failform.scname.value = "";
		if (xmldoc.getElementsByTagName('scemail')[0].firstChild != null)
			failform.scemail.value = xmldoc.getElementsByTagName('scemail')[0].firstChild.nodeValue;
		else
			failform.scemail.value = "";
		if (xmldoc.getElementsByTagName('tsphone')[0].firstChild != null)
			failform.tsphone.value = xmldoc.getElementsByTagName('tsphone')[0].firstChild.nodeValue;
		else
			failform.tsphone.value = "";
		if (xmldoc.getElementsByTagName('tsemail')[0].firstChild != null)
			failform.tsemail.value = xmldoc.getElementsByTagName('tsemail')[0].firstChild.nodeValue;
		else
			failform.tsemail.value = "";
		if (xmldoc.getElementsByTagName('contract')[0].firstChild != null)
			failform.contract.value = xmldoc.getElementsByTagName('contract')[0].firstChild.nodeValue;
		else
			failform.contract.value = "";
		if (xmldoc.getElementsByTagName('start')[0].firstChild != null)
			failform.start.value = xmldoc.getElementsByTagName('start')[0].firstChild.nodeValue;
		else
			failform.start.value = "";
		if (xmldoc.getElementsByTagName('end')[0].firstChild != null)
			failform.end.value = xmldoc.getElementsByTagName('end')[0].firstChild.nodeValue;
		else
			failform.end.value = "";
	}
	
}

function _modify(fail)
{
	resultForm = document.failform;
	getQueryXML(getVendorResults,"fail","vid="+fail);
	resultForm.id.disabled = true;
	resultForm.name.disabled = true;
	resultForm.option.value = "2";
	resultForm._submit.value = "Modify";
}

function _required()
{
	var form = document.failform;
	var ret  = true;
	var focus;
	var msg  = "";

	if (! form.date.value) {
		msg += "Failed date is required.\n";
		focus = form.date;
		ret = false;
	}

	if (! form.nas.value) {
		msg = "NAS is required.\n";
		if (!focus) {
			focus = form.nas;
		}
		ret = false;
	}

	if (! form.cid.value) {
		msg = msg + "Component is required.\n";
		if (!focus) {
			focus = form.cid;
		}
		ret = false;
	}

	if (msg) alert(msg);
	if (focus) focus.focus();
	return ret;
}

function _getComp(obj, obj1) {
	resultForm = document.failform;
	getQueryXML(getCompResults,"vcomponent","vid="+obj.value+"&component="+obj1.value);
}

//-->
</script>
<?php
switch ($option) {
   case 1:
	$sqlcmd = sprintf("INSERT INTO failure VALUES(0,'%s','%s',%d,%d,'%s','%s','%s','%s','%s')", $nas, $date, $impact, $cid, $ticket_no, $vticket_no, $rma_no, $description, $resolution );
	break;
   case 3:
	$sqlcmd = sprintf("DELETE FROM failure WHERE id=%d", $id);
	break;
   default:
	$sqlcmd = "";
}
if ($sqlcmd != "") {
	if (!SQLQuery($sqlcmd,$dbi)) {
		$_ERROR="Cannot do transaction. SQL Error:- ".SQLError()." ".$sqlcmd;
	}
}

$impacts = array("none","severe","major","minor");
?>
<form name="failform" method="post" action="?module=failure">
<fieldset class="collapsible"><legend>Report Component Failure</legend>
<table border=0 width="100%">
<tr><td>
	<table class="_table">
	<tr><td>Failed Date:</td><td><input type="text" name="date"> <a href="Javascript:open_calendar(document.forms['failform'].elements['date']);"><img src="images/cal.gif" border=0></img></a></td>
	    <td>&nbsp;&nbsp;</td>
	    <td>Impact:</td><td><select name="impact"><?php
		foreach ($impacts as $i=>$j) {
			echo "<option value=\"$i\">$j</option>";
		}
	    ?></td></tr>
	<tr><td>NAS:</td><td><input type="text" name="nas"></td></tr>
	<tr><td>Vendor:</td><td><select name="vid" onChange="_getComp(this,document.forms['failform'].elements['component'])"><?php
		$result = @SQLQuery("SELECT id, name FROM vendor ORDER BY name", $dbi);
		while ($row = @SQLFetchArray($result)) {
			if ($row["id"])
				echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>";
		}
	    ?></select></td>
	    <td>&nbsp;&nbsp;</td>
	    <td>Component:</td><td><select name="component" onChange="_getComp(document.forms['failform'].elements['vid'],this)"><?php
		$result = @SQLQuery("SELECT id, description FROM component", $dbi);
		while ($row = @SQLFetchArray($result)) {
			echo "<option value=\"".$row["id"]."\">".$row["description"]."</option>";
		}
	    ?></select>
	    <br><select name="cid"><option value=""></option></select></td></tr>
	<tr><td>Ticket#:</td><td><input type="text" name="ticket_no"></td></tr>
	<tr><td>Vendor Ticket#:</td><td><input type="text" name="vticket_no"></td>
	    <td>&nbsp;&nbsp;</td>
	    <td>RMA#:</td><td><input type="text" name="rma_no"></td></tr>
	<tr><td>Description:</td><td colspan="4"><textarea name="decription" rows="5" cols="60"></textarea></td></tr>
	<tr><td>Resolution:</td><td colspan="4"><textarea name="resolution" rows="5" cols="60"></textarea></td></tr>
	<tr><td><input name="option" value="1" type="hidden"></td><td><input type="submit" name="_submit" value="Add" onclick="return _required();"> <input type="reset" onClick="return confirm('Are you sure you want to reset the data?')"></td></tr>
	</table>
</td></tr>
</table>
</fieldset>
</form>
