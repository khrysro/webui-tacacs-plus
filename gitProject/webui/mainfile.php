<?php

$pagetitle = "WebUI 1.5$release";
$copyrights = "All logos and trademarks in this site are property of their respective owner, rest are (c)2002-2010 $company_name";

$netmask = array(
   "0.0.0.0","128.0.0.0","192.0.0.0","224.0.0.0","240.0.0.0",
   "248.0.0.0","252.0.0.0","254.0.0.0","255.0.0.0","255.128.0.0",
   "255.192.0.0","255.224.0.0","255.240.0.0","255.248.0.0","255.252.0.0",
   "255.254.0.0","255.255.0.0","255.255.128.0","255.255.192.0","255.255.224.0",
   "255.255.240.0","255.255.248.0","255.255.252.0","255.255.254.0","255.255.255.0",
   "255.255.255.128","255.255.255.192","255.255.255.224","255.255.255.240",
   "255.255.255.248","255.255.255.252","255.255.255.254","255.255.255.255" );

ob_start("ob_gzhandler");

if (!$debug) {
	error_reporting(E_ERROR);
}

function OpenTable()
{
	global $glob_width;
	echo "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 WIDTH=\"100%\">";
}

function CloseTable()
{
	echo "</TABLE>";
}

function OpenDatabase($host, $user, $password, $db)
{
	global $_ERROR, $dbtype;

	switch ($dbtype) {
	case "mysql":
		if (!($id=@mysql_pconnect($host[0], $user[0], $password[0]))) {
			$_ERROR = "Could not connect to MySQL database at ".$host[0];
			if ($redundancy) {
				if (!($id=@mysql_pconnect($host[1], $user[1], $password[1]))) {
					$_ERROR = "Could not connect to the backup MySQL database at ".$host[1];
				}
			}
		}
		if ($id) {
			if (!mysql_select_db($db, $id))
				$_ERROR = "Could not select the database";
		}

		break;
	case "odbc":
		if (!($id=@odbc_connect("DSN=$db", $user, $password, SQL_CURR_USE_ODBC)))
			$_ERROR = "Could not connect to ODBC database";
		break;
	}

	return $id;
}

function CloseDatabase($id)
{
	GLOBAL $dbtype, $_ERROR;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_close($id);
		break;
	case "odbc":
		@odbc_close($id);
		if (@odbc_error($id))
			$_ERROR = $odbc_errormsg($id);
		else
			$ret = 1;
		break;
	}

	return $ret;
}

function SQLQuery($_query, $id)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_query($_query, $id);
		break;
	case "odbc":
		$ret = @odbc_exec($id, $_query);
		break;
	}

	return $ret;
}

function SQLFetchRow($result)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_fetch_row($result);
		break;
	case "odbc":
		$ret = @odbc_fetch_row($result);
		break;
	}

	return $ret;
}

function SQLFetchArray($result)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_fetch_array($result);
		break;
	case "odbc":
		if (@odbc_fetch_row($result)) {
			for ($j=1; $j <= @odbc_num_fields($result); $j++) {
				$field_name = @odbc_field_name($result, $j);
				$ret[$field_name] = @odbc_result($result, $field_name);
			}
		}
		break;
	}

	return $ret;
}

function SQLNumRows($result)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_num_rows($result);
		break;
	case "odbc":
		$ret = @odbc_num_rows($result);
		break;
	}

	return $ret;
}

function SQLFreeResult($result)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_free_result($result);
		break;
	case "odbc":
		$ret = @odbc_free_result($result);
		break;
	}

	return $ret;
}

function SQLError($id)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_error($id);
		break;
	case "odbc":
		$ret = @odbc_errormsg($id);
		break;
	}

	return $ret;
}

function SQLAffectedRows($id)
{
	GLOBAL $dbtype;

	switch ($dbtype) {
	case "mysql":
		$ret = @mysql_affected_rows($id);
		break;
	case "odbc":
		$ret = 0;
		break;
	}

	return $ret;
}

function Login($name, $pass, $id)
{
	global $_crypt_uname, $_privlvl;
	$ret = 0;
	$result = SQLQuery("SELECT ENCRYPT(uid), password, priv_lvl, link FROM admin WHERE uid='$name'", $id);
	if (SQLNumRows($result)>0) {
		$row = SQLFetchRow($result);
		$_crypt_uname = $row[0];
		$_privlvl = $row[2];
		if ($row[3]) {
			$result = SQLQuery("SELECT uid, password from user WHERE uid='$name'", $id);
			$row = SQLFetchRow($result);
		}
		if (crypt($pass, $row[1]) == $row[1]) {
			$ret = $_privlvl;
		}
	}
	return $ret;
}

function checkLogin($name, $id)
{
	$ret = 0;

	$result = SQLQuery("SELECT priv_lvl,vrows FROM admin WHERE ENCRYPT(uid,'$name')='$name'", $id);
	if (SQLNumRows($result)>0) {
		$row = SQLFetchRow($result);
		$ret = $row[0];
		echo "<script language=\"JavaScript\">"
		    ."var admin_priv_lvl = ".$row[0].";"
		    ."var admin_vrows = ".$row[1].";"
		    ."</script>\n";
		SQLFreeResult($result);
	}
	return $ret;
}

function checkLoginXML($name, $id)
{
	$ret = 0;

	$result = SQLQuery("SELECT priv_lvl FROM admin WHERE ENCRYPT(uid,'$name')='$name'", $id);
	if (SQLNumRows($result)>0) {
		$row = SQLFetchRow($result);
		$ret = $row[0];
		SQLFreeResult($result);
	}
	return $ret;
}

function unixcrypt($password)
{
	return crypt($password);
}


function checkCookie($value, $value1, $id)
{
	$ret = checkLogin($_COOKIE[$value], $id);
	if ($ret<=0) {
		echo "<script language=\"JavaScript\">"
		    ." alert('You are not currently logged in.  Please login.');"
		    ." top.location.href = \"index.php\";"
		    ." </script>";
	}
	return $ret;
}

function updatePassword($field, $uid, $oldpass, $newpass, $id)
{
	$ret = 0;

	if (!empty($newpass)) {
		$c_newpass = unixcrypt($newpass);
		$sqlcmd = sprintf("UPDATE user set %s='%s', flags=0 WHERE uid='%s' AND ENCRYPT('%s',password)=password", $field, $c_newpass, $uid, $oldpass);
		$result = SQLQuery($sqlcmd, $id); 
		$ret = SQLAffectedRows($id);
	}

	return $ret;
}

function verifyPassword($field, $uid, $password, $id)
{
	$ret = 0;

	if (!empty($password)) {
		$result = SQLQuery("SELECT uid FROM user WHERE uid='$uid' AND ENCRYPT('$password',$field)=$field", $id);
		$ret = SQLAffectedRows($id);
	}

	return $ret;
}

foreach ($_POST as $key=>$val) {
        eval("\$$key = '$val';");
}
foreach ($_GET as $key=>$val)
        eval("\$$key = '$val';");

?>
