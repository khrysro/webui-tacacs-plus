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

if (!eregi("index.php",$_SERVER['PHP_SELF'])) {
	Header("Location: index.php");
	die();
}

if (!$_ret) {
        echo "<script language=\"JavaScript\"> top.location.href=\"?module=main\"; </script>";
}
?>
<script language="JavaScript">
<!--
function Search() {
	var src = "result.php?$_ret=1&table=access";

	src += "&sdate=" + document.forms["access"].sdate.value;
	src += "&edate=" + document.forms["access"].edate.value;
	src += "&user=" + document.forms["access"].user.value;
	src += "&nas=" + document.forms["access"].nas.value;
	src += "&vrows=" + document.forms["access"].vrows.value;

	getResults(src);
}
//-->
</script>
<form name="access" method="post" action="index.php?module=access">
<fieldset class="_collapsible"><legend>Access Report</legend>
<table border=0 width="100%">
<tr><td>
	<table class="_table">
	<tr><td>Start Date:</td>
	    <td><input type="text" name="sdate" size=20>&nbsp;<a href="javascript:open_tcalendar(document.forms['access'].elements['sdate']);"><img src="images/cal.gif"></img></a></td>
	    <td width="30">&nbsp;</td>
	    <td>End Date:</td>
	    <td><input type="text" name="edate" size=20>&nbsp;<a href="javascript:open_tcalendar(document.forms['access'].elements['edate']);"><img src="images/cal.gif"></img></a></td>
	</tr>
	<tr><td>User:</td>
	    <td><input type="text" name="user" size=20 onKeyUp="javascript:_openSearch(this)"><br><div id="_list" style="position:absolute; visibility:hidden"><select id="_dynlist" onChange="_select(document.forms['access'].elements['user'])" style="width: 150"></select></div></td>
	    <td width="30">&nbsp;</td>
	    <td>NAS:</td>
	    <td><input type="text" name="nas" size=20></td>
	</tr>
	<tr><td>Rows to view:</td>
	    <td><select name="vrows">
<?php foreach($_vrows as $_item) {
        if (!$_item) {
echo "                                  <option value=\"$_item\">all</option>";
        } else {
echo "                                  <option value=\"$_item\">$_item</option>";
        }
      } ?>
		</select><script language="JavaScript">document.forms["access"].vrows.value=admin_vrows;</script></td>
	    <td width="30">&nbsp;</td>
	    <td><a href="javascript:Search();"><img src="images/search.gif" height=15></img></a></td>
	    <td></td>
	</tr>
	</table>
</td></tr>
<tr><td> <div id="_results"> </div>
</td></tr>
</table>
</fieldset>
</form>
