<?php

// Important Functions that must be available in several scripts

/************************************* 
	Clean Inputs to avoid injections  
**************************************/

function clean($eingabe, $laenge) {
	$eingabe = substr($eingabe, 0, $laenge);
	$eingabe = htmlentities($eingabe, ENT_QUOTES);
	$eingabe = nl2br($eingabe);
	$eingabe = stripcslashes($eingabe);
	return $eingabe;
}

/*************************************** 
	Database Error
****************************************/

function error_db() {
	if ($debug) {
		echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";
	}
	else {
		echo "<p>Fehler bei der Datenbank</p>";
	}
}

/*************************************** 
	Count Groups
****************************************/

function gruppenzahl() {
	$gruppe = mysql_query("SELECT DISTINCT gruppe FROM gutachter");
	return mysql_num_rows($gruppe);
}

/*************************************** 
	Validate Reviews
****************************************/

function validiere_gutachten($gutachtennr, $gutachter) {
	global $anzahlGutachten;
	$query = "SELECT * FROM auftraege WHERE aufgabenr ='$gutachtennr' AND gutachter='$gutachter' AND dokument = '-1'";
	$rs = mysql_query($query);
	if (!$rs)
		error_db();
	else {
		$dummyGutachten = mysql_num_rows($rs);
		$anzahlGutachten -= $dummyGutachten;
	}
	
	$query = "SELECT * FROM auftraege WHERE aufgabenr ='$gutachtennr' AND gutachter='$gutachter' AND gutachten != '' AND dokument != '-1'";
	$rs = mysql_query($query);
	if (!$rs)
		error_db();
	else {
		$abgegebeneGutachten = mysql_num_rows($rs);
		print "<p>$abgegebeneGutachten von $anzahlGutachten Gutachten abgegeben.</p>";
		if ($abgegebeneGutachten == $anzahlGutachten) {
			if(!mysql_query("UPDATE dokumente SET validiert = 'j' WHERE autor='$gutachter' AND aufgabenr='$gutachtennr'"))
				error_db();
			else {
				print "<p>Gutachter $gutachter hat alle Gutachten fuer Aufgabe $gutachtennr abgegeben.<br />Die Punkte fuer das eigene Dokument werden freigeschaltet.</p>"; 
			}
		}
		else {
			print("<p>Noch ". ($anzahlGutachten - $abgegebeneGutachten) . " Gutachten sind <a href=\"javascript:history.back()\">abzugeben</a>.</p>");
		}
		mysql_free_result($rs);
	}	
}

?>