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

if ($_ret < 15) {
        echo "<script language=\"JavaScript\"> top.location.href=\"?module=main\"; </script>";
}

$sqlcmd = "";

switch ($option) {
   case 1:
	if ($link) {
		$result = SQLQuery("SELECT uid FROM user WHERE uid='$uid'", $dbi);
		if (SQLNumRows($result) > 0) {
			$sqlcmd = sprintf("INSERT INTO admin VALUES ('%s','%s',%d,1,%d)",$uid,unixcrypt($password),$priv_lvl,$vrows);
		} else { $_ERROR="User ($uid) not found"; }
	} else {
		$sqlcmd = sprintf("INSERT INTO admin VALUES ('%s','%s',%d, %d, %d)",$uid,unixcrypt($password),$priv_lvl,0,$vrows);
	}
	break;

   case 2:
	if ($link) {
		$result = @SQLQuery("SELECT uid FROM user WHERE uid='$uid'", $dbi);
		if (@SQLNumRows($result) > 0) {
			$sqlcmd = "UPDATE admin set priv_lvl=$priv_lvl, link=1, vrows=$vrows WHERE uid='$uid'";
		 } else { $_ERROR="User ($uid) not found"; }
	} else {
		if ($re_password) $re_password = ", password='".unixcrypt($password)."'";
		$sqlcmd = "UPDATE admin set priv_lvl=$priv_lvl, link=0, vrows=$vrows $re_password WHERE uid='$uid'";
	}
	break;

   case 3:
	$sqlcmd = "DELETE FROM admin WHERE uid='$uid'";
	break;
}

if ($sqlcmd) {
	SQLQuery($sqlcmd, $dbi);
	if ($debug)
		$_ERROR=SQLError($dbi).$sqlcmd;
}

?>
<script language="Javascript">
<!--
function _checkRequired() {
        var form = document.userform;
        var msg = "";

        if (!form.uid.value ) {
                msg = msg + " Missing User ID.\n";
		form.uid.focus();
        }

	if (!form.admlink.checked) {
        	if ((form.option.value!=2)&& !form.password.value ) {
			if (!msg) {
				form.password.focus();
			}
                	msg = msg + " Missing Password.\n";
		}
        }

        if (msg) {
                alert(msg);
                return false;
        }

	if (form.option.value == "2") {
		var msg = "Do you want really want to modify "+form.uid.value+"?";
		if (!confirm(msg)) {
			return false;
		}
	}

	if (form.admlink.checked) {
		form.link.value = "1";
	} else {
		form.link.value = "0";
	}

        return true;
}

function _modify(obj) {
	document.userform.uid.value = obj[0];
	document.userform.uid.readOnly = true;
	document.userform.priv_lvl.value = obj[1];
	if (obj[2]==1) document.userform.admlink.checked = true;
	document.userform.vrows.value = obj[3];
	document.userform._submit.value = "Modify";
	document.userform.option.value = "2";
}

function _delete(obj) {
	var msg = "Do you want really want to delete "+obj[0]+"?";

	if (confirm(msg)) {
		document.userform.uid.value = obj[0];
		document.userform.option.value = "3";
		document.userform.submit();
	}
}

var admin = new Object;
//-->
</script>
<form name="userform" method="post" action="?module=suser">
<fieldset class="_collapsible"><legend>System Users</legend>
<table border=0 width="100%">
<tr><td>
	<div id="_susers" class="_scrollwindow">
	<table border=1 class="_table2">
	<tr><th>User</th><th>Privilege</th><th>Linked</th><th>View</th></tr>
<?php
	$result = SQLQuery("SELECT uid,priv_lvl,link,vrows FROM admin", $dbi);
	while ($row=SQLFetchArray($result)) {
		echo "<script language=\"JavaScript\">"
		    ."admin['".$row[0]."'] = new Array();"
		    ."admin['".$row[0]."'][0] = '".$row[0]."';"
		    ."admin['".$row[0]."'][1] = '".$row[1]."';"
		    ."admin['".$row[0]."'][2] = '".$row[2]."';"
		    ."admin['".$row[0]."'][3] = '".$row[3]."'; </script>\n";

		echo "<tr><td>".$row[0]."</td>"
		    ."<td>".$row[1]."</td>"
		    ."<td>";
		echo $row[2]?"Yes":"No"."</td>";
		echo "<td>".$row[3]."</td>";
		if ($row[0] != "admin") {
			echo "<td><a href=\"javascript:_modify(admin['".$row[0]."']);\"><img src='images/modify.gif' width=25></a></td>";
			echo "<td><a href=\"javascript:_delete(admin['".$row[0]."']);\"><img src='images/trash.gif' width=25></a></td>";
		} else {
			if (!$demo) {
				echo "<td><a href=\"javascript:_modify(admin['".$row[0]."']);\"><img src='images/modify.gif' width=25></a></td>";
				echo "<td><img src='images/trash.gif' width=25></td>";
			}
		}
		echo "</tr>\n";
	}
?>
	</table>
	</div>
</td></tr>
<tr><td>
	<table class="_table">
	<tr><td>User ID:</td><td><input name="uid" type="text" size="25"></td>
	    <td></td><td></td></tr>
	<tr><td>Password:</td><td><input name="password" type="password" size="25"></td>
	    <td>Re-Password:</td><td><input name="re_password" type="password" size="25" onBlur="javascript:return _checkpass(this,document.forms['userform'].elements['password']);"></td></tr>
	<tr><td>Privelege:</td><td><select name="priv_lvl">
					<option value="1">1</option>
					<option value="5">5</option>
					<option value="15">15</option>
				   </select>
			  </td>
	    <td></td><td></td></tr>
	<tr><td>Linked:</td><td><input name="admlink" type="checkbox"></td>
	    <td><input type="hidden" name="link"></td><td></td></tr>
	<tr><td>Rows to view:</td><td><select name="vrows">
<?php foreach($_vrows as $_item) {
	if (!$_item) {
echo "					<option value=\"$_item\">all</option>";
	} else {
echo "					<option value=\"$_item\">$_item</option>";
	}
      } ?>
				  </select></td>
	    <td></td><td></td></tr>
	<tr><td><input name="option" value="1" type="hidden"></td><td><input type="submit" name="_submit" value="Add" onClick="return _checkRequired();"></td>
	    <td></td><td></td></tr>
	</table>
</table>
</fieldset>
</form>
