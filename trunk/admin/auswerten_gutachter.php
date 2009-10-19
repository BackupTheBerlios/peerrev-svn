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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachten auswerten</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
		<style>
			div 
			{
				float: left;
			}
			#kommentar, #studien 
			{
				width: 20em;
				margin-left: 2em;
				margin-bottom: 1em;
				padding: 1em;
				border: 1px solid black;
			}
			#rang
			{
				clear: both;
			}
		</style>
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > Gutachter auswerten</p>
		<hr noshade size="1">


		<form action="auswerten_gutachter.php" method="get" name="formular">
			<input type="text" name="nr">
			<input type="submit">
		</form>

<?php
require "../config/connect.php";
import_request_variables("G"); 

//
//Daten zum Gutachter anzeigen
//
echo "<h1>Gutachter $nr</h1>";

$query = "SELECT * FROM gutachter WHERE matrikelnr = '$nr'";

$rs = mysql_query($query, $con);
if (!$rs)
{
	error_db();
}

else
{
	$rs_array=mysql_fetch_row($rs);
	print "<p>Name: $rs_array[1]:<br>Email: $rs_array[2]<br>Angemeldet am $rs_array[5]</p>";
	
}

//
// Ergebnis berechnen
//
echo "<div>";
echo "<table border=1 cellpadding=10><tr><th><h2>Aufgabenr</h2></th><th><h2>Gutachten</h2></th><th><h2>Punkte</h2></th></tr>";

$gesamtpunkte = 0;
$gesamtpunkte_validiert = 0;
for ($i=1; $i<=$anzahlAufgaben; $i++)
{
	$query1 = "SELECT punkte, validiert, dokument FROM dokumente WHERE autor=$nr AND aufgabenr=$i";
	$query2 = "SELECT gutachten FROM auftraege WHERE gutachter=$nr AND aufgabenr=$i AND gutachten!=''";
	
	$rs1 = mysql_query($query1, $con);
	if($rs1)
		$eingereicht = mysql_num_rows($rs1);
	if ($eingereicht == 0)
	{	
		$rs1_array[0]="-";
		$anzahl_gutachten="-";
	}
	else
	{
		$rs1_array=mysql_fetch_row($rs1);
		$rs2 = mysql_query($query2, $con);
		$anzahl_gutachten=mysql_num_rows($rs2);
	}	
	
	echo "<tr><td><p><a href='anzeigen_dokument.php?dokument=$rs1_array[2]&nr=$i'>$i</a></p></td><td><p>$anzahl_gutachten</p></td><td><p>";
	if ($rs1_array[1] == "j")
	{
		echo "<b>$rs1_array[0]</b>";
		$gesamtpunkte_validiert += $rs1_array[0];
		$gesamtpunkte += $rs1_array[0];

	}
	else
	{
		echo "$rs1_array[0]";
		$gesamtpunkte += $rs1_array[0];
		
	}
	echo "<p></td></tr>\n";
	
}

echo "<tr><td><p><b>Validierte Punkte:</b><br>Gesamte Punkte:</p></td><td>&nbsp;</td><td><p><b>$gesamtpunkte_validiert</b><br>$gesamtpunkte</p></td></table>";
echo "</div>";

//Kommentar
echo "<div id='kommentar'><h1>Anmerkung</h1><p>$rs_array[5]</p></div>";

// Position im Ranking
$rs=mysql_query("SELECT matrikelnr FROM gutachter ORDER BY matrikelnr", $con);
if(!$rs)
{
	db_error();
}
else
{
	$anzahl_gutachter=mysql_num_rows($rs);
	for($i=0;$i<$anzahl_gutachter;$i++)
	{
		$rs_array=mysql_fetch_row($rs);
		$gutachter[$i][0] = $rs_array[0]; //Matrikelnr.
		$gutachter[$i][1] = 0; //Punkte default
		
		$query2 = "SELECT punkte FROM dokumente WHERE autor='".$gutachter[$i][0]."' AND validiert='j'";
		
		$rs2 = mysql_query($query2,$con);
		while($rs2_array=mysql_fetch_row($rs2))
		{
			$gutachter[$i][1] += $rs2_array[0];	
		}
		$punkte[$i][0] = $gutachter[$i][1]; //Punkte
		$punkte[$i][1] = $gutachter[$i][0]; //Gutachter

	}
	
	if ($punkte[0][1]!=0) //sortiere nur, wenn Gutachter vorhanden sind
	rsort($punkte);		
	$rang=0;
	$punkte_alt=1000;
	for($i=0;$i<$anzahl_gutachter;$i++)
	{
			if ($punkte_alt > $punkte[$i][0])
				$rang++;
			if ($punkte[$i][1] == $nr)
				$aktueller_rang = $rang;
			$punkte_alt=$punkte[$i][0];

	}
	
	echo "<div id='rang'>";
	echo "<h1>Rang: $aktueller_rang</h1>";
	echo "<table cellpadding=5 cellspacing=5><tr><th></th><th><a href='ranking.php'>Gutachter</a></th><th><a href='../ranking.php?order=punkte'>Punkte</a></th></tr>";
	
	$rang=0;
	$punkte_alt=1000;
	for($i=0;$i<$anzahl_gutachter;$i++)
	{
			if ($punkte_alt > $punkte[$i][0])
				$rang++;
			if ($punkte[$i][1] == $nr)
				echo "<tr style='background-color:red'>";
			else	
				echo"<tr>";
			echo "<td><p>$rang</p></td><td><p>";
			echo $punkte[$i][1];
			echo "</p></td><td><p>".$punkte[$i][0]."</p></td></tr>";
			$punkte_alt=$punkte[$i][0];
	}
	

	echo "</table>";
	echo "</div>";
}
mysql_close($con);
?>
	
		</div>
	</body>

</html>