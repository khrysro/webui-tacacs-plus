<?php
switch ($option) {
case 1:
	if (updatePassword($type, $uid, $oldpass, $newpass, $expiretime, $dbi) > 0) {
		echo "<script language=\"JavaScript\"> alert('Changed password for $uid');";
	}
	else {
		echo "<script language=\"JavaScript\"> alert('Cannot change password for $uid');";
	}
	echo "close();</script>";
}

?>
<script language="JavaScript">
<!--
function check(element)
{
	var form = element.form;
	var ret = 0, msg;

	if (element.value != form.newpass.value) {
		msg = "Password does not match. Please retry.";
		ret = 1;
	} else if (element.value.length < <?php echo $pass_size; ?>) {
		msg = "Password is too small.  Minimum size is <?php echo $pass_size; ?> characters.";
		ret = 1;
	}

	if (ret) {	
		alert(msg);
		element.value = form.newpass.value = "";
		form.newpass.focus();
	}

}
function _check(obj)
{
	var ret = true;

	if (obj.form.uid.value == "") {
		alert("Username is required.");
		obj.form.uid.focus();
		ret = false;
	} else if (obj.form.oldpass.value == "") {
		alert("Password is required.");
		obj.form.oldpass.focus();
		ret = false;
	} else if (obj.form.newpass.value == "") {
		alert("New Password cannot be blank.");
		obj.form.newpass.focus();
		ret = false;
	}

	return ret;
}
		
-->
</script>
<div id="change" class="_table">
       <form name="change_password" method="post" action="?module=change&option=1">
	   <fieldset class=" collapsible"><legend>Change Password</legend>
           <table align="left" border=0>
	      <tr><td>Username:</td><td><input type="text" name="uid"></td></tr>
	      <tr><td>Password:</td><td><input type="password" name="oldpass"></td></tr>
	      <tr><td>Change:  </td><td><select name="type">
		  <option value="password">password
		  <option value="enable">enable
		  <option value="pap">pap
		                   </select>
	      <tr><td>New Password:</td><td><input type="password" name="newpass"></td></tr>
	      <tr><td>Re-type Password:</td><td><input type="password" name="retype" onchange="check(this);"></td></tr>
	      <tr><td></td><td><input type="submit" value="Change" onClick="return _check(this);"></td></tr>
	  </table>
	  </fieldset>
       </form>
</div>
