<?php
/*
    Copyright (C) 2003-2011 Young Consulting, Inc

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

if (checkLoginXML($_COOKIE["login"],$dbi) < 5) {
	CloseDatabase($dbi);
        echo "<script language=\"JavaScript\"> top.location.href=\"index.php?module=main\"; </script>";
}

echo "<script language=\"Javascript\">\n";
$_atb = 0;
$result = @SQLQuery("SELECT name FROM attribute WHERE vid=0", $dbi);
while ($row = @SQLFetchArray($result)) {
        echo "attribs[$_atb] = \"".$row[0]."\";";
        $_atb++;
}
echo "</script>\n";
?>

<form name="serviceform" method="post" action="node.php?<?php echo "_ret=$_ret&pid=$pid&uid=$uid&_service=1";?>">
<fieldset class="_collapsible"><legend id="_serviceset">Services for <?php echo $uid; ?></legend>
<table border=0 width="100%">
<tr><td>
	<table border=1 width="100%" class="_table2">
	<tr><th width=25>Service</th><th width=25>Type</th><th width=25>Vendor</th><th>Attribute</th><th>Value</th></tr>
<?php
        for ($i = 0; $i < count($svc_array); $i++) {
                echo "<tr align=center>";
                echo "<td>".$svc_type[$svc_array[$i]["service"]]."</td>";
                echo "<td>".$node_type[$svc_array[$i]["type"]]."</td>";
                echo "<td>".$vnd_array[$svc_array[$i]["vid"]]."</td>";
                echo "<td>".$svc_array[$i]["value1"]."</td>";
                echo "<td>".$svc_array[$i]["value"]."</td>";
              /*  echo "<td width=25><a href=\"Javascript:_modify(svc_info[$i])\" title=\"Modify Service\"><img src=\"images/modify.gif\" width=25 border=0></a></td>"; */
                echo "<td><input type=\"image\" width=25 border=0 src=\"images/trash.gif\" onclick=\"return _delete(this.form,svc_info[$i]);\" title=\"Delete Service\"></td></tr>\n";
        }
?>
	</table>
</tr></td>
<tr><td>
	<table class="_table">
	<tr><td width="50">Service:</td><td><input type="hidden" name="seq" value="0"> <select name="service">
<?php
	foreach ($svc_type as $j=>$value) {
		echo "<option value=\"$j\">".$value;
	}
?>
		</select></td></tr>
	<tr><td width="50">Type:</td><td><select name="type">
		<option value="50">arg
		<option value="51">optarg
		</select></td></tr>
	<tr><td width="50">Vendor:</td><td><select name="vid" onchange="javascript:_getAttribs(this)"><?php
		foreach ($vnd_array as $i=>$value) {
			echo "<option value=\"$i\">$value";
		}
		?></select></td></tr>
	<tr><td width="50">Attribute:</td><td><div id="autosuggest"><ul></ul></div><input type="text" id="attrib" name="value1" size="20"><script language="Javascript">new AutoSuggest(document.getElementById("attrib"),attribs);</script></a></td></tr>
	<tr><td width="50">Value:</td><td><input type="text" name="value" size="35"></td></tr>
	<tr><td width="50"><input type="hidden" name="option" value="1">
	        <input type="hidden" name="attr" value="0"></td>
	    <td><input type="submit" name="_ssubmit" value="Add" onClick="return _check_serviceform(this.form)"> <input type="reset" value="Reset" onClick="return confirm('Are you sure you want to reset the data')"></td></tr>
	</table>
</td></tr>
</table>
</fieldset>
</form>
