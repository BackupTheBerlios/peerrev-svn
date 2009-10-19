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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachten</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > Bewerten</p>
		<hr noshade size="1">
<?php

require "../config/connect.php";

import_request_variables("G");
if($nr!="")
	$gutachtennr=$nr;
	
// Liste mit allen Gutachtern und ihren Dokumenten anlegen
// Jedem Gutachter 5 Dokumente zuordnen, nur nicht sein eigenes. 
// Alle Dokumente muessen 5 mal verteilt werden.

$query = "SELECT autor, dokument ".
		" FROM dokumente WHERE aufgabenr = '$gutachtennr' ORDER BY autor ASC"; //Alle Autoren
$rs = mysql_query($query, $con);
if (!$rs)
{
	error_db();
}

else
{	
	$anzahl = mysql_num_rows ($rs);
	echo "<p>Anzahl: $anzahl </p>";
	echo "<p>Summe	= Summe_vorhandener_Gutachten + round(Fehlende_Gutachten*(Summe_vorhandener_Gutachten/(Ben&ouml;tigte_Gutachten - Fehlende_Gutachten)))</p>";

	print "<table border=1 cellpadding=5>\n";
	print "<td><p><b>Dokument</b></p></td>";
	//Tabellenkopf
	for ($i=0; $i<$anzahlGutachten; $i++)
	{
		print "<td><p><b>Gutachter " . ($i+1) . "</b></p></td>";
	}

	print "<td><p><b>Summe</b></p></td></tr>\n";

	//Tabelle füllen
	while ($rs_array = mysql_fetch_row($rs)) //Schleife über alle Autoren
	{
		$autor = $rs_array[0]; 
		$dokument = $rs_array[1];

		echo "<td><p>$dokument</p></td>";
		$rs2 = mysql_query("SELECT id, gutachter, gutachten, punkte FROM auftraege WHERE aufgabenr='$gutachtennr' AND dokument ='$dokument'");
		if (!$rs2)// Wer begutachtet das Dokument des aktuellen Gutachters?
		{
			error_db();
		}
		else
		{	
		
			$summe = 0;
			$fehlendeGutachten = 0;
			while ($rs_array2 = mysql_fetch_row($rs2))
			{
				$id2 = $rs_array2[0];
				$gutachter2 = $rs_array2[1];
				$gutachten2 = $rs_array2[2];
				$punkte2 = $rs_array2[3];
				
				
				if ($gutachten2 != "")
				{
					echo "<td><p><a href = 'anzeigen_gutachten.php?id=" . $id2 . "&nr=$gutachtennr'>" . $gutachter2 . "</a><br>".
						"$punkte2</p></td>";
				}
				else
				{
					echo "<td><p>$rs_array2[1]<br>$rs_array2[3]</p></td>";
				}				
				if ($punkte2 != -1)
				{
					$summe += $punkte2;
				}	
				else
				{
					$fehlendeGutachten++;
				}
			}
			echo "<td><p>$summe+round($fehlendeGutachten*($summe/($anzahlGutachten-$fehlendeGutachten)))=";
			if ($fehlendeGutachten > 0)
			{
				$summe = $summe + round($fehlendeGutachten*($summe/($anzahlGutachten-$fehlendeGutachten)));
			}

			echo "$summe</p></td>";
			if (!mysql_query("UPDATE dokumente SET punkte = '$summe' WHERE aufgabenr='$gutachtennr' AND dokument ='$dokument'"))
				error_db();
		}
		echo "</tr>";

	}
	print "</table>";
}

mysql_close($con);

?>
	</body>

</html>