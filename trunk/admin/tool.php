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
		<title>Review Your Peers &ndash; Administration &ndash; Tools</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body>
		<div id="content">
		<img src="../pix/tool.jpg" alt="" width="110" height="81" border="0">
		<p><a href="index.php">Verwaltung</a> > Tools</p>
		<hr noshade size="1">
<?php

require "../config/connect.php";

if(!$debug) {
	exit ("Diese Werkzeuge funktionieren nur im Debug-Modus");
}

import_request_variables("G", "getv_"); 

if (isset($getv_nr)) {
	if($getv_nr!="") {
		$gutachtennr=$getv_nr;
	}
}

echo "<h1>tool: " . $getv_mode . "</h1>";

/*

	Die meisten dieser Werkzeuge sind sehr gefaehrlich und nur in der Testphase sinnvoll.
	Sie sollten daher im laufenden Betrieb ausgeschaltet sein.
	Kontrollieren koennte man das durch eine Zustandsvariable
	$state ={test, debug, live}
	Das ist was fuer v1.0

*/
switch ($getv_mode)
{

/*************************************
	Gutachterliste erzeugen: 
	Anmelden aller Gutachter
**************************************/

	case ("gutachter"):
	
		if(!mysql_query("DELETE FROM $database.gutachter"))
			error_db();
		else 
			echo "gutachter geputzt<br>Neue Gutachter eintragen:<br>";
		
		for ($i=1; $i<50; $i++)
		{
			$gruppe = rand(1,2);//Diese Zahl wird bei der Anmeldung vorgegeben
			// $gruppe = 1;
			$query = "INSERT INTO $database.gutachter VALUES ('11$i', 'name$i', 'name$i@mail.de','$gruppe','','$datum')";
			echo "<p>" . $query . "</p>";
			if (!mysql_query($query, $con))
				error_db();
		}
		break;
				
/********************************
	Dokumente erzeugen.
	3/4 der angemeldeten 
	Gutachter reichen ein Dokument ein.
	Benoetigt $nr
*********************************/

	case ("dokumente"):
		
		if ($getv_nr == "")
			$getv_nr = $aufgabeNummer;
		if(!mysql_query("DELETE FROM dokumente WHERE aufgabenr = '$getv_nr'"))
			error_db();
		else
			echo "<p><b>dokumente f&uuml;r Aufgabe $getv_nr geputzt<br>Neue Dokumente f&uuml;r Aufgabe $getv_nr eintragen:</b></p>";
		
		$rs=mysql_query("SELECT aufgabeNummer, dateiendung FROM parameter");
		
		while ($rs_array=mysql_fetch_row($rs))
		{
			$endung[$rs_array[0]]=$rs_array[1];
		}
				
		$rs = mysql_query ("SELECT matrikelnr FROM gutachter", $con);
		while ($rs_array = mysql_fetch_row($rs))
		{
			$autor = $rs_array[0];
			$dokument = "$autor.$endung[$getv_nr]";
			if (rand(1,4) > 1)
			{
				$query = "INSERT INTO dokumente (autor, dokument, aufgabenr, anmerkung, datum) VALUES ('$autor','$dokument','$getv_nr','anmerkung$autor','$datum')";
				echo "<p>" . $query . "</p>";
	
				if (!mysql_query($query, $con))
					error_db();
			}
		
		}
		break;
			

/********************************
	Gutachten erzeugen
*********************************/

 	case ("gutachten"):
		
		if ($getv_nr == "")
		$getv_nr = $aufgabeNummer;		
		$rs = mysql_query("SELECT id FROM auftraege WHERE aufgabenr='$getv_nr'");
		if (!$rs)
			error_db();
		else
		{
			while ($rs_array = mysql_fetch_row($rs))
			{
				$punkte = rand(-1,5);
				if ($punkte == -1)
					$gutachten = "";
				else
					$gutachten = "gutachten: blafaselschwafel.";
				if (!mysql_query("UPDATE auftraege SET gutachten = '$gutachten', punkte = '$punkte', datum='$datum' WHERE id = $rs_array[0]"))
					error_db();
				else
					echo "<p>Gutachten $rs_array[0]: $punkte Punkte, $gutachten</p>";
			}
		}
		
	//Gutachten validieren
		print "<p><b>Validierung &uuml;berpr&uuml;fen</b></p>";
		
		$query = "SELECT matrikelnr FROM gutachter";
		$rs = mysql_query($query, $con);
		if (!$rs)
			error_db();
		else
		{
			while ($row = mysql_fetch_object($rs))
			{
				$gutachter = $row->matrikelnr;
				validiere_gutachten ($gutachtennr, $gutachter);
			}
		}
		break;

/********************************
	Tabelle loeschen
	mode=clear&tabelle=
*********************************/

	case ("clear"):
		
		$query = "DELETE FROM " . $getv_tabelle;
		echo $query;
		$rs = mysql_query($query, $con);
	
		if(!$rs)
		{
			error_db();
		}
		else
		{
			echo "<p>...$getv_tabelle geputzt</p>";
		}		
		break;
/********************************
	Bemerkungen
	mode=bemerkungen
*********************************/
	case ("bemerkungen"):
	
		$query = "SELECT * FROM bemerkungen";
		echo $query;
		$rs = mysql_query($query, $con);
	
		if(!$rs)
		{
			error_db();
		}
		else
		{
			while ($rs_array = mysql_fetch_row($rs))
			{
				print "<p><b>$rs_array[2]:</b><br>$rs_array[1]</p>";
			}
		}
		break;
		
/********************************
	Parameter
	mode=parameter
*********************************/
	case ("parameter"):
	
		print "<p>Heute ist $datum<br>".
			"aufgabenr: $aufgabeNummer<br>".
			"Gutachtennr.: $gutachtennr<br>".
			"dateiendung: $dateiendung<br>".
			"Aufgabe stellen: $aufgabeStellenDatum<br>".
			"Dokument Abgabedatum: $dokumentAbgebenDatum<br>".
			"Anzahl Gutachten: $gutachtenAnzahl<br>".
			"Gutachten Abgabedatum: $gutachtenAbgebenDatum</p>";
		break;
}

mysql_close($con);
?>

	</body>

</html>