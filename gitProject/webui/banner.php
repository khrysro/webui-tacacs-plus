<?php

function Banner()
{
	global $banner, $banner_gif, $logo_gif, $version, $release, $ads;
	echo "<table bgcolor=\"#9999CC\" border=0 cellpadding=0 cellspacing=0 width=\"100%\">\n"
	    ."<tr><td width=\"20%\">";
	if (file_exists("images/$logo_gif"))
		echo "<img src=\"images/$logo_gif\"></img></td>\n";
	else
		echo "&nbsp;</td>\n";
	echo "    <td width=\"80%\">";
	if (file_exists("images/$banner_gif"))
		echo "<img src=\"images/$banner_gif\"></img></td>\n";
	else
		echo "<h1><font color=\"dark blue\">$banner $version</font></h1></td><td>$ads</td>\n";
	echo "</tr>\n</table>\n";
}

?>
