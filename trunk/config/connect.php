<!--
/* Copyright 2005, 2006 Jochen Koubek. 2007 Andrea Knaut - v0.1.2. 

For contributors see contributors.txt.

This file is part of the software 'Review Your Peers'.

'Review Your Peers' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */
-->

<?php  

  include 'common.inc.php'; //Die konfigurierbaren Parameter
  include "lib.php"; //Wichtige Funktionen
  include "debug.php"; //Kann individuell eingestellt werden

// $con ist die id der im folgenden angegebenen Verbindung
  $con = mysql_connect($dbms, $login, $password);

// $db_ist_da prueft den Zugang zur Datenbank
// 1 = DB ist ansprechbar
  $db_ist_da = mysql_select_db ($database, $con);
  
/*************************************** 
	Auslesen der aktuellen Parameter
****************************************/

//Aktuelle Aufgabe bestimmen

	$letzteAufgabe = mysql_num_rows(mysql_query("SELECT * FROM parameter"));
	$anzahlAufgaben = $letzteAufgabe;
	$rowLast = mysql_fetch_object(mysql_query("SELECT * FROM parameter WHERE aufgabeNummer='$letzteAufgabe'"));

//Aufgabe irgendwo zwischen AufgabeStellen und AufgabeAbgeben
	$rs=mysql_query("SELECT * FROM parameter WHERE aufgabeStellenDatum <= '$datum' AND aufgabeAbgebenDatum > '$datum'");
	if ($row=mysql_fetch_object($rs)){
		$aufgabeNummer = $row->aufgabeNummer;
	}	
	else {
//Sind wir bereits jenseits der letzten Aufgabe?
		if ($datum > $rowLast->aufgabeAbgebenDatum) {
			$aufgabeNummer = -1;
		}
		else {
			$aufgabeNummer = 0;
		}
	}

//Gutachten irgendwo zwischen AufgabeStellen und AufgabeAbgeben
	$rs=mysql_query("SELECT * FROM parameter WHERE gutachtenVerteilenDatum <= '$datum' AND gutachtenAbgebenDatum > '$datum'");
	if ($row=mysql_fetch_object($rs)){
		$gutachtennr = $row->aufgabeNummer;
	}	
	else {
//Sind wir bereits jenseits des letzten Gutachtens?
		if ($datum > $rowLast->gutachtenAbgebenDatum) { 
			$gutachtennr = -1;
		}
		else {
			$gutachtennr = 0;	
		}
	}
	
	// echo ("$datum :: Aufgabe $aufgabeNummer :: Gutachten $gutachtennr");

//Zeile einlesen mit den Daten fuer die aktuelle Aufgabe aus Tabelle 'parameter'
if ($aufgabeNummer > 0) {
	$rs = mysql_query("SELECT * FROM parameter WHERE aufgabeNummer='$aufgabeNummer'", $con);
	if ($rs) {
		$row = mysql_fetch_object($rs);
// Auslesen des Abgabedatums
		$aufgabeStellenDatum = $row->aufgabeStellenDatum;
		$aufgabeAbgebenDatum = $row->aufgabeAbgebenDatum;
		$dateiendung = $row->dateiendung;
		$anzahlGutachten = $row->gutachtenAnzahl;	
//Zeile einlesen mit den Daten fuer die aktuellen Gutachten aus Tabelle 'parameter'
		$rs = mysql_query("SELECT * FROM parameter WHERE aufgabeNummer=$gutachtennr", $con);
		$row = mysql_fetch_object($rs);
// Auslesen des Abgabedatums
		$gutachtenAbgebenDatum = $row->gutachtenAbgebenDatum;
		$gutachtenVerteilenDatum = $row->gutachtenVerteilenDatum;
	}	
	else {
		error_db();
	}
}
/* Folgende Parameter werden hier bereit gestellt:
$con					:	Die Verbindung zur Datenbank
$datum					:	Das aktuelle Datum

$anzahlAufgaben			:	Die Gesamtzahl der Aufgaben
$letzteAufgabe			:	Die Aufgabennummer der letzten Aufgabe.

$aufgabeNummer			:	Die aktuelle Aufgabe
$gutachtennr			:	Das aktuell anzufertigende Gutachten
$aufgabeStellenDatum	: 	Wann wurde die Aufgabe gestellt?
$aufgabeAbgebenDatum	: 	Wann muss sie abgegeben werden?
$anzahlGutachten		:	Wie viele Gutachten sind anzufertigen?
$gutachtenVerteilenDatum:	Wann werden die Gutachteraufträge verteilt?
$gutachtenAbgebenDatum	:	Bis wann müssen die Gutachten abgegeben werden?

$letztesDokumentAbgegeben	Es werden keine Dokumente mehr angenommen
$letztesGutachtenAbgegeben	Es werden auch keine Gutachten mehr angenommen

*/

	
?>
