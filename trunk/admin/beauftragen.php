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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachter beauftragen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > Gutachter beauftragen</p>
		<hr noshade size="1">

<?php
require "../config/connect.php";
//require "/Library/WebServer/mail.php";
import_request_variables("G", "getv_"); 

if($getv_nr!="")
	$gutachtennr=$getv_nr;

print "<h2>Gutachten f&uuml;r Aufgabe $gutachtennr in Auftrag geben</h2>";

if(!mysql_query("DELETE FROM $database.auftraege WHERE aufgabenr='$gutachtennr'"))
		error_db();
else 
		echo "<p>Alte Auftr&auml;ge der Aufgabe $gutachtennr gel&ouml;scht</p>";

switch ($getv_mode)
{
/***************************************************
	Alle Auftraege zum aktuellen Gutachten loeschen
****************************************************/

	case ("erase"):
		if (!mysql_query("DELETE FROM auftraege WHERE aufgabenr='$gutachtennr'"))
			error_db();
		else
			echo "<p>Alte Auftraege entfernt!</p>";
	
		if (!mysql_query("UPDATE dokumente SET punkte = '0', validiert = 'n' WHERE aufgabenr = '$gutachtennr'"))
			error_db();
		else
			echo "<p>Alte Punkte entfernt!</p>";
		break;

/***************************************************
// Liste mit allen Gutachtern und ihren Dokumenten anlegen
// Jedem Gutachter $anzahlGutachten Dokumente zuordnen, nur nicht sein eigenes. 
// Alle Dokumente muessen $anzahlGutachten-mal verteilt werden.
// Die Gruppenzugehörigkeit muss ebenfalls berücksichtigt werden.
****************************************************/
	case ("create"):

//Anzahl der eingereichten Dokumente bestimmen	
		$query = "SELECT * FROM dokumente WHERE aufgabenr = '$gutachtennr'";
		$rs = mysql_query($query, $con);
		if (!$rs)
			error_db();
		$anzahlDokumente = mysql_num_rows ($rs);

//Gutachter ist nur, wer ein Dokument einreicht
		$anzahlGutachter = $anzahlDokumente;
		$anzahlGruppen = gruppenzahl();

		echo "<p>Anzahl Auftr&auml;ge: $anzahlDokumente </p>";
		echo "<p>Anzahl Gutachten pro Gutachter: $anzahlGutachten</p>";
		

//Gruppenlisten einrichten		
		for ($i=1;$i<=$anzahlGruppen; $i++) {
			$gruppe[$i]=array();
			$query = "SELECT dokumente.autor, dokumente.dokument, dokumente.anmerkung, gutachter.email, gutachter.gruppe ".
					" FROM dokumente, gutachter WHERE dokumente.aufgabenr = '$gutachtennr' AND gutachter.gruppe='$i' AND gutachter.matrikelnr = dokumente.autor";
			$rs = mysql_query($query, $con);
					
			$index = 0;
			while ($rs_array = mysql_fetch_row($rs)){
				$mitglied = new gutachter();
				$mitglied->matrikelnummer = $rs_array[0]; 
				$mitglied->dokument = $rs_array[1];  
				$mitglied->anmerkung = $rs_array[2]; 
				$mitglied->mail = $rs_array[3];
				$mitglied->gruppe = $rs_array[4];
				$gruppe[$i][$index] = $mitglied;
				$index++;	
			}
		}
		
		$groesse1 = count($gruppe[1]);
		$groesse2 = count($gruppe[2]);		
		echo "<p>Anzahl Gruppen: $anzahlGruppen mit $groesse1 ";
		if ($groesse2 > 0)
			echo "und $groesse2 ";
		echo "Mitgliedern</p>";

//Gruppengröße anpassen
		while (($groesse1 + $groesse2) % $anzahlGruppen <> 0){
			$dummy = new gutachter();
			$dummy->matrikelnummer = -1;
			$dummy->dokument="-1";
			$dummy->gruppe = 1;
			$dummy->anmerkung = "Joker";
			$gruppe[1][] = $dummy;
			echo "<p>Dummy eingefuegt</p>";	
			$groesse1 = count($gruppe[1]);
			$groesse2 = count($gruppe[2]);
		}
// Gruppen egalisieren, zunächst nur für zwei Gruppen
		if ($anzahlGruppen == 2){
			$groesse1 = count($gruppe[1]);
			$groesse2 = count($gruppe[2]);
		
//	Jetzt bestimmen wir die größte und die kleinste Gruppe		
			if ($groesse1 == $groesse2){
				$differenz = 0;
			}
			if ($groesse1 > $groesse2){
				$groessteGruppe = 1;
				$kleinsteGruppe = 2;
				$differenz = $groesse1-$groesse2;
			}		
			if ($groesse2 > $groesse1){
				$groessteGruppe = 2;
				$kleinsteGruppe = 1;
				$differenz = $groesse2-$groesse1;
			}		
			
			while ($differenz > 0) {
 				$wechselGutachter= array_pop($gruppe[$groessteGruppe]);
				$wechselGutachter->gruppe = $kleinsteGruppe;
				$gruppe[$kleinsteGruppe][] = $wechselGutachter;
				$differenz = count($gruppe[$groessteGruppe])-count($gruppe[$kleinsteGruppe]);
	// echo "<p>".count($gruppe[$groessteGruppe])."-".count($gruppe[$kleinsteGruppe])."=$differenz";
			} 
			echo ("<p>Gruppen egalisiert mit ".count($gruppe[$groessteGruppe])." und ".count($gruppe[$kleinsteGruppe])." Mitglieder</p>");	
					
		}

// To be continued…	

//Gutachter gruppenweise mischen und Listennachbarn eintragen
			
//Gutachteraufträge bestimmen
	for ($i=1;$i<=$anzahlGruppen;$i++) { //Alle Gruppen mischen
		shuffle($gruppe[$i]);		
	}

	for ($i=1;$i<=$anzahlGruppen;$i++) {
		$gruppenGroesse = count($gruppe[$i]); //inzwischen sind alle Gruppen gleich groß
		for ($l=0; $l<$gruppenGroesse; $l++) {
			$gruppe[$i][$l]->nachbar = $l+1;
		}
		$gruppe[$i][$gruppenGroesse-1]->nachbar = 0;
	}
	
	for ($i=1;$i<=$anzahlGruppen;$i++) {		
		if ($i<$anzahlGruppen) { //Damit die letzte Gruppe auch etwas zu begutachten hat...
			$nachbarGruppe = $gruppe[$i+1];
		}
		else {
			$nachbarGruppe = $gruppe[1]; 
		}

		echo "<div style='float:left; border:1px solid black; width: 500px; margin:1em;padding:0 1em;'>";
		echo "<p><b>Gruppe $i</b></p>";	
		echo "<p>Anzahl in Gruppe $i: ".$gruppenGroesse."</p>";

		for ($j=0; $j<$gruppenGroesse; $j++) { //Alle Mitglieder der aktuellen Gruppe
			$gutachter = $gruppe[$i][$j];
			$partner = $nachbarGruppe[$j];
			echo "<p><b>Auftraege fuer $gutachter->matrikelnummer (Gruppe $gutachter->gruppe): </b>";
			for ($k=1;$k<=$anzahlGutachten;$k++){ //wir fangen mit dem nächsten Nachbarn an, sonst würden sich zwei unmittelbar benachbarte Autoren immer gegenseitig begutachten 
				// for ($g=1;$g<=$k;$g++){
				// 					$gutachterIndex = $gutachter->nachbar; //int
				// 					$gutachter = $gruppe[$j][$gutachterIndex];
				// 				}
				$partner = $nachbarGruppe[$partner->nachbar];
				$auftrag = $partner->dokument;

				
				$query = "INSERT INTO auftraege (aufgabenr, gutachter, dokument) ".
						"VALUES ('$gutachtennr', '$gutachter->matrikelnummer', '$auftrag')";
				echo "<p>$query";
				if (!mysql_query($query, $con)) {
					error_db();
				}
				else {
					// echo "ok";
				}	
			}
			echo "</p>";
		}
		echo "</div>";
	}

	echo "<div style='clear:both'></div>";

}//end switch

function nachbar($gutachter, $anzahl){
	global $gruppe;
	$puffer = $gutachter->nachbar;
	for ($g=1;$g<$anzahl;$g++){
		$gutachter = $puffer->nachbar;
	}
	return $puffer;
}

mysql_close($con);

?>

<?php
// Jeder Gutachter wird als eigenes Objekt geführt.

	class gutachter {
		var $index;
		var $matrikelnummer;
		var $dokument;
		var $anmerkung;
		var $mail;
		var $gruppe; 
		var $nachbar;
		
		function __construct() {
		}
	}

?>	
		
	</body>

</html>