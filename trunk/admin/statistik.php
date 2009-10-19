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
		<title>Review Your Peers &ndash; Administration &ndash; Statistik Gutachten</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
					<p><a href="index.php">Verwaltung</a> > <a href="status.php">Status</a> > Punkteverteilung</p>
		<hr noshade size="1">

			<h1>Punkteverteilung</h1>
			<table border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td>
<?php

require "../config/connect.php";

import_request_variables("G", ""); 

$sg = 0; //Summe gesamt
$psg = 0; //Punktesumme gesamt
for ($nr=1;$nr<=7; $nr++)
{
//	if ($nr==4) $nr++;

//statistische Auswertung
	for ($i=0;$i<=25;$i++)
	{
		$query="SELECT * FROM dokumente WHERE punkte=$i AND aufgabenr=$nr";
		//echo "<p>$query</p>";
		$rs = mysql_query($query);
		$anzahl[$i] = mysql_num_rows($rs);
	}

	echo "<table><tr>";
	for ($i=0;$i<=25;$i++)
	{
		$hoehe=$anzahl[$i]*5;	
		echo"<td valign='bottom'><img src='../pix/bar.gif' width='20' height='$hoehe'></td>";
	}
	echo "</tr><tr>";
	$summe = 0;
	$punktesumme = 0;
	for ($i=0;$i<=25;$i++)
	{
		echo"<td>$anzahl[$i]</td>";
		$summe += $anzahl[$i];

		$punktesumme += $anzahl[$i]*$i;
	
	}
	$sg += $summe;
	$psg += $punktesumme;
	
	echo "</tr><tr>";
	for ($i=0;$i<=25;$i++)
	{
		echo"<td>$i</td>";
	}

	echo "</tr></table>";
	echo "<h1>Aufgabe $nr, $summe Teilnehmer </h1>";
	echo "<hr noshade size='1'>";


}

if ($sg!=0)
Echo "<h1>Gesamtdurchschnitt: ". $psg/($sg*5) ."</h1>";


mysql_close($con);

?>
					</td>
				</tr>
			</table>
		</div>
	</body>

</html>