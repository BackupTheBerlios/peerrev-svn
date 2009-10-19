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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachterauftr&auml;ge mailen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > Gutachter beauftragen</p>
		<hr noshade size="1">

<?php
require "../config/connect.php";

import_request_variables("G", "getv_"); 

if($getv_nr!="")
	$gutachtennr=$getv_nr;

print "<h2>Gutachten f&uuml;r Aufgabe $gutachtennr verschicken</h2>";


/***************************************************
Email an alle Gutachter schicken. 
Darin Name des Dokuments Anmerkungen 
des Autors und Abgabetermin.
****************************************************/

	//Abgabedatum der Gutachten erfragen
		$rs = mysql_query("SELECT gutachtenAbgebenDatum FROM parameter WHERE aufgabeNummer='$gutachtennr'");
		if (!$rs) {
			error_db();
		}
		else {
			$row=mysql_fetch_object($rs);
			$gutachtenAbgebenDatum = $row->gutachtenAbgebenDatum;
		}

	//Aufträge für die Gutachter zusammen stellen
		$query = "SELECT dokumente.autor, gutachter.email FROM dokumente, gutachter WHERE dokumente.aufgabenr='$gutachtennr' AND gutachter.matrikelnr=dokumente.autor";
		$rs_autoren = mysql_query($query);
		
		echo "<p><b>".mysql_num_rows($rs_autoren)."</b> Auftr&auml;ge zu verschicken</p>";
		
		if (!$rs_autoren) {
			db_error();
			exit();
		}

		while ($row = mysql_fetch_object($rs_autoren))	{
			$autor = $row->autor;
			$email = $row->email;
		
			// exit ("Aktuell: $autor: $email");
			
			// $rs_mail = mysql_query("SELECT email FROM gutachter WHERE matrikelnr = '$autoren[0]'");
			// $mail = mysql_fetch_row($rs_mail);
		
			$query="SELECT auftraege.dokument, dokumente.anmerkung FROM auftraege, dokumente ".
					"WHERE auftraege.aufgabenr = '$gutachtennr' AND auftraege.gutachter='$autor' ".
					"AND dokumente.dokument=auftraege.dokument AND dokumente.aufgabenr='$gutachtennr'";

		echo "<p>$query</p>";
			$rs_auftrag = mysql_query($query, $con);			
			if (!$rs_auftrag)
				error_db();
		
		
			$mailtext = "<b>Gutachterauftrag an Gutachter $autor, $email f&uuml;r Aufgabe $gutachtennr:</b><br>\n\n".
						"Bitte bis zum " . date("d.m.y", strtotime($gutachtenAbgebenDatum)) . ", " . date("H:i:s",strtotime($gutachtenAbgebenDatum)) . " Uhr folgende Gutachten abgeben: \n\n";
			
			$i = 1;
			while($auftrag = mysql_fetch_object($rs_auftrag)) {					
				$mailtext .= "<p><b>Gutachten $i : Dokument $uploadServer/aufgabe$gutachtennr/". $auftrag->dokument. " \n</b><br>\n" .
					 		"Anmerkungen des Autors: <br>\n" . $auftrag->anmerkung . "\n<p>\n";
				$i++;
			}
			$mailtext .= "<p>Hinweise zur Bewertung und zum Umgang mit unleserlichen Dokumenten:<br>\n $uploadServer/users/regeln.php#unlesbar</p>";
	
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "From: $admin_mailaddress\r\n"; //sender-address
			
			echo"<p>$email<br />Digitale Medien: Gutachterauftrag $gutachtennr<br />$mailtext</p>";

/*
//Mailen 
			if (!mail($email, "Digitale Medien: Gutachterauftrag $gutachtennr", $mailtext, $headers))
				print("--> FEHLER BEI MAIL!: $email<br>");
			else
			{
				print ("<p>$email erfolgreich</p>");
				$mailtext = "";
			}
*/
			echo "<hr noshade size='1'>";
			
		} // end while $rs_autoren
		
mysql_close($con);

// Die folgenden Zeilen werden nur zu Test-Zwecken frei gegeben 
// 
// $email = "c2852909@tyldd.com";
// 
// $headers  = "MIME-Version: 1.0\r\n";
// $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
// $headers .= "From: $admin_mailaddress\r\n"; //sender-address
// $mailtext .= "Probemail";
// 
// if (!mail($email, "Digitale Medien: Gutachterauftrag $gutachtennr", $mailtext, $headers))
// 	print("--> FEHLER BEI MAIL!: $email<br>");
// else
// {
// 	print ("<p>$email erfolgreich</p>");
// }
	

?>
	</body>

</html>
