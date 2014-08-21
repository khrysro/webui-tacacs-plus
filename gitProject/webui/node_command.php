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
$_cmd = 0;
$result = @SQLQuery("SELECT name FROM command WHERE vid=0", $dbi);
while ($row = @SQLFetchArray($result)) {
        echo "commands[$_cmd] = \"".$row[0]."\";";
        $_cmd++;
}
echo "</script>\n";
?>
<form name="serviceform" method="post" action="node.php?<?php echo "_ret=$_ret&pid=$pid&uid=$uid&_service=56";?>">
<fieldset class="_collapsible"><legend id="_commandset">For <?php echo $uid; ?></legend>
<table border=0 width="100%">
<tr><td>
	<table border=1 width="100%" class="_table2">
	<tr><th width=10>Sequence</th><th width=15>Access</th><th width=25>Vendor</th><th width=150>Command</th><th width=200>Argument</th><th colspan=2>&nbsp;</th></tr>
<?php
        for ($i = 0; $i < count($svc_array); $i++) {
                echo "<tr align=center>";
		echo "<td width=10>".$svc_array[$i]["seq"]."</td>";
                echo "<td width=15>".$node_type[$svc_array[$i]["type"]]."</td>";
                echo "<td width=25>".$vnd_array[$svc_array[$i]["vid"]]."</td>";
                echo "<td width=150>".$svc_array[$i]["value"]."</td>";
                echo "<td width=200>".$svc_array[$i]["value1"]."</td>";
             /*   echo "<td width=25><a href=\"Javascript:_modify(svc_info[$i])\" title=\"Modify Command\"><img src=\"images/modify.gif\" width=25 border=0></a></td>"; */
                echo "<td width=25><input type=\"image\" width=25 border=0 src=\"images/trash.gif\" onclick=\"return _delete(this.form,svc_info[$i]);\" title=\"Delete Command\">\n";
        }
?>
	</table>
</td></tr>
<tr><td>
	<table class="_table">
	<tr><td width="50">Sequence:</td><td><input type="text" name="seq" size="5"></td></tr>
	<tr><td width="50">Permit:</td><td><select name="type"><option value="57">permit<option value="58">deny</select></td></tr>
	<tr><td width="50">Vendor:</td><td><select name="vid" onchange="javascript:_getCommands(this)"><?php
		foreach ($vnd_array as $i=>$value) {
			echo "<option value=\"$i\">$value";
		}
		?></select></td></tr>
	<tr><td width="50">Command:</td><td><div id="autosuggest"><ul></ul></div><input type="text" id="command" name="value" size="20"><script language="Javascript">new AutoSuggest(document.getElementById("command"),commands);</script></td>
	<tr><td width="50">Argument:</td><td><input type="text" name="value1" size="35"></td></tr>
	<tr><td width="50"></td><td><input name="option" value="1" type="hidden">
		<input name="service" value="56" type="hidden">
		<input name="attr" type="hidden" value="0">
	        <input type="submit" name="_submit" value="Add" onClick="return _check_serviceform(this.form)"> <input type="reset" onClick="return confirm('Are you sure you want to reset the data?')"></td></tr>
	</table>
</td></tr>
</table>
</fieldset>
</form>
