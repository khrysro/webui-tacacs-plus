<?php

$tacacs_menu = array (
	array('?module=client_acl','Client ACL',true),
	array('?module=nas_acl','NAS ACL',true),
	array('?module=attrib','Attributes',true),
	array('?module=command','Commands',true),
	array('?module=nas','NAS',true),
	array('?module=nas_group','NAS Group',true),
	array('?module=user','Users',true),
	array('?module=user_group','User Groups',true),
	array('?module=profile','Profiles',true),
	array('?module=vendor','Vendors',true)
);

$system_menu = array (
	array('?module=soptions','Options',true),
	array('?module=suser','Users',true)
);

$failure_menu = array(
	array('?module=component','Component',true),
	array('?module=failure','Failed component',true),
	array('?module=rfail','Report',true)
);

$admin_menu = array (
	array($tacacs_menu,'TACACS',false),
	array($system_menu,'System',false)
);

$admin5_menu = array (
	array($tacacs_menu,'TACACS',false),
);

$report_menu = array (
	array('?module=access','Access',true),
	array('?module=account','Accounting',true)
);

$main_menu = array (
	array('?module=main','Home',true),
	array('?module=change','Change Password',true),
	array('?module=verify','Verify Password',true),
);

$priv15_menu = array (
	array($admin_menu,'Administration',false)
//	array($failure_menu,'Failures',false),
);

$priv5_menu = array (
	array($admin5_menu,'Administration',false)
//	array($failure_menu,'Failures',false),
);

$priv1_menu = array (
	array($report_menu,'Reports',false),
	array('javascript:logoff();','Logout',true)
);

function Nav($menu, $submenu)
{
	global $BROWSER_AGENT;

	foreach ($menu as $mnu) {
		if ($mnu[2]) {
			echo "<li><a href=\"".$mnu[0]."\">".$mnu[1]."</a></li>\n";
		} else {
			echo "<li><a href=\"javascript:getPage(0);\">".$mnu[1]."</a>";
			echo "<div id=\"".$mnu[1]."\"><ul>\n";
			Nav($mnu[0],true);
			echo "</ul></div></li>\n";
		}
	}
}

Nav($main_menu, false);

switch($_ret) {
case 15:	Nav($priv15_menu, false);
		break;
case 5:		Nav($priv5_menu, false);
		break;
}

if ($_ret > 0)		Nav($priv1_menu, false);


?>

