<!--
/* Copyright 2005, 2006 Jochen Koubek - v0.1.2. 
For contributors see contributors.txt.

This file is part of the software 'Review Your Peers'.

'Review Your Peers' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<meta name="generator" content="Adobe GoLive 6">
		<title>Review Your Peers &ndash; Administration &ndash; Gutachter anzeigen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php">Status</a> > Gutachter anzeigen</p>
		<hr noshade size="1">


<?php
require "../config/connect.php";
import_request_variables("G"); 

echo "<h1><a href='begutachten_gutachter?gutachter=$gutachter&nr=$nr'>$gutachter</a>";

$rs=mysql_query("SELECT dokument FROM dokumente WHERE autor='$gutachter' AND aufgabenr='$nr'");
$rs_array=mysql_fetch_row($rs);
if (!$rs)
{
	error_db();
}
echo " | <a href='anzeigen_dokument.php?dokument=$rs_array[0]&nr=$nr'>".$rs_array[0]."</a></h1>";
 
$rs = mysql_query("SELECT dokument, gutachten, punkte FROM auftraege WHERE gutachter='$gutachter' AND aufgabenr='$nr' ORDER BY dokument", $con);
	if (!$rs)
	{
		error_db();
	}
	else
	{
		echo "<table border=1 cellpadding = 10>";
		echo "<tr><td width='50%'><h2>Dokument</h2></td><td width='50%'><h2>Gutachten</h2></td></tr>";
		while ($rs_array = mysql_fetch_row($rs))// Auftraege des aktuellen Gutachters
		{
			$query2="SELECT anmerkung FROM dokumente WHERE dokument='$rs_array[0]' AND aufgabenr='$nr'";
			$rs2 = mysql_query($query2);
			if (!$rs2)
			{	
				echo $query2;
				error_db();
			}
			$rs_array2 = mysql_fetch_row($rs2);
			if ($rs_array[2] != "")
			{
				echo "<tr><td valign='top'><p><b><a href='anzeigen_dokument?dokument=$rs_array[0]&nr=$nr'>$rs_array[0]</a></b><br>".$rs_array2[0]."</p></td>".
					"<td valign='top'><p><b>$rs_array[2] Punkte<br>Gutachten:</b><br>$rs_array[1]</p></td>".					
					"</tr>\n\n";
			}
		}
	}
	echo "</table>";
		
mysql_close($con);
?>
					</td>
				</tr>
			</table>
		</div>
	</body>

</html>