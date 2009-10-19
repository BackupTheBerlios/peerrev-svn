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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachter begutachten</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php">Status</a> > Gutachter begutachten</p>
		<hr noshade size="1">
	<form action="begutachten_gutachter.php" method="get" name="formular">

<?php
require "../config/connect.php";
import_request_variables("G"); 

if ($nr!="")
	$gutachtennr=$nr;

echo "<p>$letzter_gutachter: ";
/*
$faktor = 100;
for ($i=1;$i<=$anzahl_gutachten;$i++)
{
	$gutachtenpunkte = "gutachten$i";
	echo ${$gutachtenpunkte}." | ";
	if (${$gutachtenpunkte} == 0)
		$faktor -= 10;
}
echo "Faktor: * $faktor = ";

$rs=mysql_query("SELECT punkte FROM auftraege WHERE gutachter='$letzter_gutachter' AND aufgabenr='$gutachtennr'", $con);
while ($rs_array=mysql_fetch_row($rs))
{
	echo $rs_array[0]*$faktor/100 ." | ";
}
*/
echo "<input type='hidden' name='letzter_gutachter' value='$gutachter'>\n";

echo "<h1>Gutachter $gutachter</h1>";

$query = "SELECT * FROM gutachter WHERE matrikelnr = '$gutachter'";

$rs = mysql_query($query, $con);
if (!$rs)
{
	error_db();
}

else
{	
	do 
	{
		$rs_array=mysql_fetch_row($rs);
		$rs2 = mysql_query("SELECT dokument, gutachten, punkte FROM auftraege WHERE gutachter='$rs_array[0]' AND aufgabenr='$gutachtennr' ORDER BY dokument");
		if (!$rs2)
		{
			error_db();
		}
		else
		{
			$dokument=mysql_num_rows($rs2);
		}
	}
	while ($rs2_array>0);
	
	print "<p>Name: $rs_array[1]:<br>Email: $rs_array[2]<br>Angemeldet am $rs_array[5]</p>";
	echo "<table border=1 cellpadding = 10>";
	echo "<tr><td><h2>Dokument</h2></td><td><h2>Gutachten</h2></td><td><h2>ok</h2></td></tr>";
	$gutachtenindex = 1;
	while ($rs_array2 = mysql_fetch_row($rs2))// Auftraege des aktuellen Gutachters
	{
		$rs3 = mysql_query("SELECT anmerkung FROM dokumente WHERE dokument = '$rs_array2[0]' AND aufgabenr='$nr'");
		$rs_array3 = mysql_fetch_row($rs3);
		if ($rs_array2[2] != "")
		{
				echo "<tr><td valign='top'><p><b>$rs_array2[0]</b><br>".stripslashes($rs_array3[0])."</p></td>".
					"<td valign='top'><p><b>$rs_array2[2] Punkte<br>Gutachten:</b><br>$rs_array2[1]</p></td>".
					"<td width='80' valign='top'><input type='checkbox' name='gutachten$gutachtenindex' value='1' checked>".
					"</tr>\n\n";
		}
		$gutachtenindex++;
	}
	echo "</table>";
}
//naechsten Gutachter bestimmen		
	$rs = mysql_query("SELECT matrikelnr FROM gutachter ORDER BY matrikelnr ASC", $con);
	if (!$rs)
		echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";

	$rs_array = mysql_fetch_row($rs);
	while ($rs_array[0] != $gutachter) //vorspulen
	{	
		$rs_array = mysql_fetch_row($rs);
	}
	
	do 
	{
		$rs_array=mysql_fetch_row($rs);
		$rs2 = mysql_query("SELECT dokument FROM dokumente WHERE autor='$rs_array[0]' AND aufgabenr='$gutachtennr'");
		if (!$rs2)
		{
			error_db();
		}
		else
		{
			$dokumentda=mysql_num_rows($rs2);
		}
	}
	while ($rs_array && $dokumentda==0);
	
	if($rs_array)
	{
		echo "<p>N&auml;chster Gutachter: $rs_array[0]</p>";
		echo "<p><input type='hidden' name='gutachter' value='$rs_array[0]'></p>";
		echo "<p><input type='submit' name='submitButtonName' value='Zum n&auml;chsten Gutachter'>";
		echo "<input type='hidden' name='nr' value='$gutachtennr'></p>";

	}
	else
	{
		echo "<p>Fertig!</p>";
	}

mysql_close($con);
?>
						</form>
	</body>

</html>