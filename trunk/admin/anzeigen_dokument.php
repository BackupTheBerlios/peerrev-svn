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
		<title>Review Your Peers &ndash; Administration &ndash; Dokument anzeigen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php">Status</a> > Dokumentenstatus</p>
		<hr noshade size="1">
<?php

require "../config/connect.php";
import_request_variables("G"); 

if($nr!="")
	$gutachtennr=$nr;
	
echo "<h1>Gutachten f&uuml;r Dokument <a href='$uploadserver/$uploadDir/aufgabe$nr/$dokument'>$dokument</a></h1>";

$query = "SELECT autor, anmerkung FROM dokumente WHERE dokument = '$dokument' AND aufgabenr='$gutachtennr'";

$rs = mysql_query($query, $con);
if (!$rs)
{
	error_db();
}

else
{	
	$rs_array=mysql_fetch_row($rs);
	print "<p><b>Autor $rs_array[0]</b><br>Anmerkungen:<br>$rs_array[1]";

	echo "<table cellpadding = 10>";
	$query2 = "SELECT gutachter, gutachten, punkte FROM auftraege ".
				"WHERE dokument='$dokument' AND aufgabenr='$gutachtennr' ORDER BY gutachter";

	$rs2 = mysql_query($query2, $con);
	if (!$rs2)
	{
		error_db();
		echo $query2;
	}
	else
	{
		while ($rs_array2 = mysql_fetch_row($rs2)) // Gutachten zum Dokument
		{
			$rs_array2[1]=str_replace('\n','<br>',$rs_array2[1]);
			$rs_array2[1]=str_replace('\r','',$rs_array2[1]);

			echo "<tr><td valign='top'><p><b>Gutachter: <a href='anzeigen_gutachter.php?gutachter=$rs_array2[0]&nr=$nr'>$rs_array2[0]</a></b><br>".
				stripslashes($rs_array2[1])."<br>".
				"<b>$rs_array2[2] Punkte</p><hr></td></tr>";
		}
	}
	echo "</table>";
}
mysql_close($con);
?>
	</body>

</html>