<!--/* Copyright 2005, 2006 Jochen Koubek - v0.1.2. 

For contributors see contributors.txt.

This file is part of the software 'Review Your Peers'.

'Review Your Peers' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */--!>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<meta name="generator" content="Adobe GoLive 6">
		<title>Review Your Peers &ndash; Gutachten</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

	<body class="user formular_reply" id="gutachten_reply">

<?php
require "../config/connect.php";
include "../users/menu.php";

print "<div class='content'>";
/**************************************************
	Dieses Skript ist der sensibelste Teil, weil er 
	direkt mit der Bewertung verknuepft ist.
	Die meisten Fehler koennen auf einem Taeuschungsversuch 
	beruhen und werden gespeichert.
		
	Folgende Fehlerquellen werden beruecksichtigt:
		1. Gutachter (Matrikelnr.)
			(a) fehlt im Formular
			(b) ist keine Ziffernfolge
			(c) ist nicht angemeldet
				Moeglicher Taeuschungsversuch (zusaetzliche Gutachten), wird gespeichert. 
		2. Das Dokument
			(a) kann im Formular fehlen
			(b) ist gar nicht angemeldet. Rechtschreibfehler, nicht speichern.
			(c) ist dem Gutachter nicht zugeordnet.
				Taeuschungsversuch, wird gespeichert
			(d) wurde bereits vom Gutachter begutachtet.
				Wird ebenfalls gespeichert
		3. Die Punktzahl
			(a) wurde im Formular nicht angegeben
			(b) liegt ausserhalb des zulaessigen Bereichs, d.h.
				das Formular wurde manipuliert. 
				Anfrage speichern.
		4. Das Gutachten
			(a) kann im Formular fehlen
			
	Falls es keine Bedenken gibt
		5. Fehler behandeln
		6. Gutachten eintragen
		7. Punkte des Gutachters validieren?

***************************************************/	

//	Vertrauensvorbehalt

import_request_variables("P", "dmvar_"); 
$server_ip = $_SERVER["REMOTE_ADDR"];
$gutachter = clean($dmvar_gutachter,10);
$dokument = clean($dmvar_dokument, 15);
$punkte = clean($dmvar_punkte, 2);
$gutachten = clean($dmvar_gutachten, 32768);
//printf("-->%s<br>",$gutachter);

/**************************************************
   1. Eingabe ueberpruefen: Gutachter (Matrikelnr.)
***************************************************/	 

// ***** (a) Gutachter im Formular angegeben? ******
if ($gutachter == "") {
	$error_gutachter = true;
	$error_gutachter_text = "Bitte Gutachter/in eingeben!";
}
elseif (!ereg("^[0-9]*$", $gutachter)) {
	$error_gutachter = true; 
	$error_gutachter_text = "Bitte die Matrikelnr. des Gutachters angeben!";

}
else {
	$rs = mysql_query("SELECT * FROM gutachter WHERE matrikelnr = '$gutachter'");
	if (!$rs) {	
		error_db();
	}

// ***** (b) Gutacher angemeldet? ******
	else {
		$results = mysql_num_rows($rs);
		if ($results == 0 ) {
			$error_gutachter = true;
			$error_gutachter_text = "Gutachter ist nicht angemeldet. <br>";
			if(!mysql_query("INSERT bemerkungen (bemerkung, datum) VALUES ('Unangemeldet: gutachter $gutachter hat von $server_ip versucht, Dokument $dokument zu begutachten', '$datum')")) {
				error_db();
			}
			else {
				$error_gutachter_text .= "Diese Anfrage von $server_ip wurde gespeichert.";
			}
		}
		else {
			$gutachter_error = false; //keine Bedenken bzgl Gutachter.
		}	
	}
}

/**************************************************
   2. Eingabe ueberpruefen: Dokument
***************************************************/	

// ***** (a) Dokument angegeben? ******

if ($dokument == "") {
	$error_dokument = true;
	$error_dokument_text = "Bitte Dokument eintragen!";
}

// ***** (b) gibt es das Dokument ueberhaupt? ******

else {
	$rs = mysql_query("SELECT * FROM dokumente WHERE dokument = '$dokument' AND aufgabenr = '$gutachtennr'");
	if (!$rs) {
		error_db();
	}
	else {
		$results = mysql_num_rows($rs);
		if ($results == 0)	{
			$error_dokument = true;
			$error_dokument_text = "Das Dokument $dokument wurde nicht eingereicht. ";
		}
	
// ***** (c) Dokument dem Gutachter zugeordnet? ******

		else {
			//echo "SELECT gutachten, aufgabenr FROM auftraege WHERE aufgabenr='$gutachtennr' AND gutachter='$gutachter' AND dokument='$dokument'";
			$rs = mysql_query("SELECT gutachten, aufgabenr FROM auftraege WHERE aufgabenr='$gutachtennr' AND gutachter='$gutachter' AND dokument='$dokument'");
			if (!$rs) {
				error_db();
			}
			else {
				$results = mysql_num_rows($rs);
				if ($results == 0) {
					$error_dokument = true;
					$error_dokument_text = "Gutachter $gutachter ist nicht fuer Dokument $dokument eingetragen.<br> ";
					if(!mysql_query ("INSERT bemerkungen (bemerkung, datum) VALUES ('Unautorisiert: $gutachter wollte Dokument $dokument von $server_ip begutachten','$datum')")) {
						error_db();
					}
					else {
						$error_dokument_text .=	"Diese Anfrage von $server_ip wurde gespeichert!";
					}
				}

// ***** (d) liegt bereits ein Gutachten von dem Gutachter vor? ******

				else {
					$rs_array = mysql_fetch_row($rs);
					if ($rs_array[0] != "")	{
						$error_dokument = true;
						$error_dokument_text = "Gutachter <i>$gutachter</i> hat das Dokument <i>$dokument</i> bereits begutachtet.<br> ";
				
						if(!mysql_query ("INSERT  bemerkungen (bemerkung,datum) VALUES ('Wiederholung: $gutachter wollte Dokument $dokument von $server_ip erneut begutachten','$datum')")) {	
							error_db();
						}
						else {
							$error_dokument_text .= "Diese Anfrage von $server_ip wurde gespeichert!";
						}
						mysql_free_result($rs);

					}
					else {
						$error_dokument = false; //keine weiteren Bedenken
					}
				}		
			}
		}
	}
}


/**************************************************
   3. Eingabe ueberpruefen: Punkte
***************************************************/	

	if ($punkte == "-1") {
		$error_punkte = true;
		$error_punkte_text = "Bitte Punktzahl eingeben";
	}
	
	else {
	/**
	  BEGIN CONFIGURATION AREA
	  change the regular expression '[0-5]' to your new scale of points given in gutachten.php - eg. '[1-10]' if you have a scale where the reviewer can choose to give 1, 2, 3, 4, 5, 6, 7, 8, 9 or 10 points.
	  If you change that setting you need to explain the criterias in regeln.php and regelnGutachten.php as well.
	**/
		if (!ereg("[0-5]",$punkte)) {
			$error_punkte = true;
			$error_punkte_text = "Punktzahl nicht zulaessig.<br> Manipulation des Formulars!<br>";
			if(!mysql_query ("INSERT bemerkungen (bemerkung, datum) VALUES ('Punktzahl: $gutachter wollte Dokument $dokument mit $punkte Punkten bewerten', '$datum')")) {
				error_db();
			}	
			else {
				$error_punkte_text .= "<br>Diese Anfrage wurde gespeichert!";
			}
		}
	/**
  		END CONFIGURATION AREA
	**/
		else {
			$error_punkte = false;
		}
	 }


/**************************************************
   4. Eingabe ueberpruefen: Gutachten
***************************************************/		
	
	if ($gutachten == "") {
		$error_gutachten = true;
		$error_gutachten_text = "Bitte ein Gutachten schreiben!";
	}

/**************************************************
	5. Fehler behandeln
***************************************************/	

	$error = ($error_gutachter OR $error_dokument OR $error_punkte OR $error_gutachten OR $error_db);
	if ($error) {
		print "<h2>Fehler beim Gutachten</h2><p>";
		if ($error_gutachter)
			print $error_gutachter_text . "</br>";
		if ($error_dokument)
			print $error_dokument_text . "</br>";
		if ($error_punkte)
			print $error_punkte_text . "</br>";
		if ($error_gutachten)
			print $error_gutachten_text . "</br>";
		print "</p>";
	}	
	
	else { //Kein Fehler
	
/**************************************************
	6. Gutachten in der Tabelle "Auftraege" eintragen
***************************************************/	
	
		$query = "UPDATE auftraege SET gutachten = '$gutachten', punkte = '$punkte', datum='$datum' WHERE ".
				 "(gutachter='$gutachter' AND dokument='$dokument' AND aufgabenr='$gutachtennr')";
		
		if (!mysql_query($query, $con))
			print "Datenbankfehler. Bitte beim <a href='mailto:$admin_mailaddress'>Administrator</a> melden";
		else {
			print "<h1>Gutachten erfoplgreich eingereicht</h1>";
		}

/**************************************************
	7. Punkte validieren?
***************************************************/	

		validiere_gutachten ($gutachtennr, $gutachter);
	}
	
mysql_close($con);
print "</div>";
?>

<div class="foot">
	<?php
		include "foot.php";
	?>			
</div>

</body>

</html>
