<div id="login" class="_table">
       <form name="frmLogon" method="post" action="">
	    <fieldset class=" collapsible"><legend>Admin Login</legend>
            <table align="left" border=0>
            <tr><td>Username:</td>
                <td><input type="text" name="username" size=20></td>
            </tr>
            <tr><td>Password:</td>
                <td><input type="password" name="password" size=20></td>
            </tr>
            <tr><td><input type="hidden" name="_login" value="<?php echo ($_ret)?'0':'1'; ?>"></td>
                <td><input type="submit" value="Logon" name="Logon"></td>
            </tr>
            </table>
	    </fieldset>
        </form>
</div>
<?php
	if ($_ret) {
		echo "<script language=\"javascript\"> document.getElementById(\"login\").style.display=\"none\"; </script>\n";
	}
?>
<div id="main">
<fieldset class=" collapsible"><legend>Welcome to <?php echo $company_name; ?> WebUI</legend>
<p class="p_body"><?php echo $message; ?></p>
</fieldset>
</div>
