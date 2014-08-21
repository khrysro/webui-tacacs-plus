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

require_once("config.php");
require_once("mainfile.php");

$dbi=OpenDatabase($dbhost, $dbuname, $dbpass, $dbname);

if (!checkLoginXML($_COOKIE["login"],$dbi)) {
        echo "<script language=\"JavaScript\"> top.location.href=\"index.php?module=main\"; </script>";
}


$where = "";
$where2 = "";

if ($table == "failure") {
	if ($fail_date) {
		$where = "WHERE date='$fail_date'";
	}

	if ($vid) {
		if ($where) {
			$where = "WHERE vid=$vid";
		} else {
			$where .= " AND vid=$vid";
		}
	}
} else {
	if ($user) {
		$where = "WHERE uid='$user'";
	}

	if ($nas) {
		if ($where) {
			$where .= " AND ";
		} else {
			$where = "WHERE ";
		}

 		$where .= "nas LIKE '$nas%'";
	}

	if ($sdate || $edate) {
		if ($where) {
			$where .= " AND ";
		} else {
			$where = "WHERE ";
		}

		if ($sdate && $edate) {
			$where .= "date BETWEEN '$sdate' AND '$edate'";
		} else {
			$where .= "date LIKE '$sdate%'";
		}
	}
}

$where .= " ORDER BY date DESC ";

if ($vrows) {
	$where2 .= " LIMIT ".$vrows;
	if ($offset) {
		$where2 .= " OFFSET ".$offset;
	}
}
$result = @SQLQuery("SELECT * FROM $table $where $where2", $dbi);
$_ERROR = @SQLError($dbi);
if (@SQLNumRows($result) > 0) {
	echo "<table border=1 cellspacing=1 cellpadding=2 class=\"_table2\">\n";
	switch ($table) {
	   case "access":
		echo "<TR><th>Date</th><th>NAS</th><th>Terminal</th><th>User ID</th><th>Client IP</th><th>Service</th><th>Status</th></tr>\n";
		break;

	   case "accounting":
		echo "<tr><th>Date</th><th>NAS</th><th>User</th><th>Terminal</th><th>Client</th><th>Type</th><th>Service</th><th>Priv Level</th><th>Command</th><th>Elapsed Time</th><th>Bytes In</th><th>Bytes Out</th></tr>";
		break;

	   case "failure":
		echo "<tr><th>Date</th><th>Vendor</th><th>Component</th><th>Impact</th><th>Description</th></tr>\n";
		break;
	}
	while ($row=SQLFetchRow($result)) {
		echo "<tr>";
		foreach ($row as $item) {
			echo "<td>";
			if ($item) echo "$item";
			else echo "&nbsp;";
			echo "</td>";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
	SQLFreeResult($result);
}

CloseDatabase($dbi);
?>
