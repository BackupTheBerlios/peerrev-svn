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
		<title>Review Your Peers &ndash; Einreichen</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

	<body class="user formular_reply" id="dokument_einreichen_reply">
		<?php
			require "../config/connect.php";
			include "../users/menu.php";
			
			print "<div class='content'>";
			
			print "<h1>Aufgabe$aufgabeNummer</h1>";
			
/**************************************************
	Folgende Fehlerquellen werden beruecksichtigt:
		1. Der Autor
			(a) fehlt im Formular
			(b) ist keine Ziffernfolge
			(c) ist nicht angemeldet 
			(d) hat bereits ein Dokument eingereicht
		2. Das eingereichte Dokument
			(a) fehlt im Formular
			(b) entspricht nicht der Namenskonvention
	Falls kein Fehler auftritt:
		3. Dokument sichern	
		4. Daten eintragen
***************************************************/	

/**************************************************
	0. Vertrauensvorbehalt
***************************************************/	

			import_request_variables("P", "dmvar_"); 

			$autor = clean($dmvar_matrikelnr, 10);
			$anmerkung = clean($dmvar_anmerkung, 32768);
			$error_db = false;

/**************************************************
   1. Eingabe Ueberpruefen: Autor (Matrikelnr.)
***************************************************/	 

			if ($autor == "") {
				$error_autor = true; 
				$error_autor_text = "Bitte Autor/in angeben.";
			}
			elseif (!ereg("^[0-9]*$", $autor)) {
				$error_autor = true; 
				$error_autor_text = "Bitte die Matrikelnummer des Autors angeben";
			}
			else {
				$rs = mysql_query("SELECT * FROM gutachter WHERE matrikelnr='$autor'", $con);
				if (!$rs) {
					$error_autor = true; 
					$error_autor_text = "Fehler bei der Datenbank";
				}
				else {
					$result = mysql_num_rows ($rs);
					if ($result == 0) {
						$error_autor = true; 
						$error_autor_text = "Autor/in ist nicht <a href='anmelden.php'>angemeldet</a>.";
					}	
					else { //Die eingegebene Matrikelnr ist angemeldet.
						$rs = mysql_query("SELECT * FROM dokumente WHERE aufgabenr = '$aufgabeNummer' AND autor = '$autor'");
						if (!$rs) {
							$error_autor = true; 
							$error_autor_text = "Fehler bei der Datenbank!";
						}
						else {
							$result = mysql_num_rows ($rs);
							if ($result != 0) {
								$error_autor = true;
								$error_autor_text = "Autor $autor hat bereits ein Dokument eingereicht.";
							} 
							else //Es gibt noch kein Dokument zur aktuellen †bung
							{
								$error_autor = false; //Von der Autorseite gibt es keine weiteren Bedenken.
							}
						}
					}
				}
			}

/**************************************************
   2. Eingabe ueberpruefen: Dokument
***************************************************/	 

			$dokument = $_FILES['dokument']['name'];

			if ($dokument == "") {
				$error_dokument = true;
				$error_dokument_text = "Bitte ein Dokument angeben.";
			}
			else {
				$namensKonvention = "(.txt|.zip)" . "$";
			//echo $namenskonvention;
	
				if ((!ereg($namensKonvention, $dokument))) {
					$error_dokument = true;
					$error_dokument_text = "Dokument <i>$dokument</i> bitte als .$dateiendung oder .txt einreichen.";
				}	
				else {
					$error_dokument = false; //keine Bedenken von Dokumentseite
				}	
			}

/**************************************************
   3a. Falls alles gut gegangen ist, werden die
   	  Daten in ÇdokumentÈ eingetragen
***************************************************/

			$error = ($error_autor OR $error_dokument OR $error_db);

			if ($error) {
				echo "<h2>Problem beim Einreichen</h2>\n";
				echo "<p>";
				if ($error_autor) {
					echo "<b>Autor:</b> $error_autor_text<br>";
				}
				if ($error_dokument) {
					echo "<b>Dokument:</b> $error_dokument_text<br>";		
				}
				echo "</p>";

			}
			
/**************************************************
  3b.  Dokument sichern
***************************************************/

			else {	
				echo "<p>Daten ok. Versuche Upload...</p>";
				$file_ext=".zip";
				$path_parts = pathinfo($_FILES['dokument']['name']);
				if ( $path_parts["extension"]=="txt" ) 
					$file_ext=".txt";

				$uploadpath = "$uploadDir/aufgabe$aufgabeNummer/" . $autor.$file_ext;
				$dokument = $autor.$file_ext;

				if (!move_uploaded_file($_FILES['dokument']['tmp_name'], $uploadpath)) {
			  	  	$error_upload = true;
			  	  	$error_upload_text = "Probleme beim Upload";
				}  
				elseif ($_FILES['dokument']['size'] <= 1024 ) {
					$error_upload = true;
					$error_upload_text = "Die Datei ist kleiner als 1 kB und damit wahrscheinlich defekt.";
				}
				else {
					echo "<h2>Die Datei wurde mit ". $_FILES['dokument']['size'] . " Bytes erfolgreich gespeichert.</h2>";
					$error_upload = false;
					$query = "INSERT INTO dokumente (aufgabenr, autor, dokument, anmerkung, datum) ".
							"VALUES ('$aufgabeNummer', '$autor', '$dokument', '$anmerkung', '$datum')";
					$rs = mysql_query($query, $con);
					if (!$rs) {
						$error_database=true;
					}
				}
	
				if ($error_upload || $error_database) {
					echo "<p>$error_upload_text $error_database_text: Dokument bitte erneut einreichen!</p>";
				}
		
				else {
					echo "<p>".date("d.m.Y H:i:s", strtotime($datum))." Uhr<br>Autor $autor hat<br>Dokument $dokument<br>erfolgreich eingereicht</p>";
				}			
			}	

			mysql_close($con);
		?>
		</div>	

		<div class="foot">
			<?php
				include "foot.php";
			?>			
		</div>

	</body>

</html>
