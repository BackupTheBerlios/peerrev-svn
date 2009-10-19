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
		<title>Review Your Peers &ndash; Anmelden</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

	<body class="user formular_reply" id="anmelden">

		<?php
			include "../users/menu.php";
		?>
		<div class="content">

			<?php
				require "../config/connect.php";

				/**************************************************
					Folgende Fehlerquellen werden beruecksichtigt:
						1. Die Matrikelnr. 
							(a) kann fehlen
							(b) ist keine Ziffernfolge
							(c) kann bereits angemeldet sein
						2. Der Name kann fehlen
						3. Die Email-Adresse kann fehlen
		
					Falls kein Fehler auftritt:
						4. Daten in Tabelle 'gutachter' eintragen.
				***************************************************/	

				/**************************************************
				   0. Trustno1
				***************************************************/	

				import_request_variables("P", "dmvar_"); 
				$matrikelnr = clean($dmvar_matrikelnr, 10);
				$name = clean($dmvar_name, 32);
				$email = clean($dmvar_email, 64);

				/**************************************************
				   1. Eingabe Ueberpruefen: Autor (Matrikelnr.)
				***************************************************/	 

				if ($matrikelnr == "") {
					$error_autor = true;
					$error_autor_text = "Bitte eine Matrikelnummer eintragen!";
				}
				elseif (!ereg("^[0-9]*$", $matrikelnr)) {
					$error_autor = true; 
					$error_autor_text = "Bitte die Matrikelnr. des Autors in Zahlen angeben!";
				}
				else {
					$rs = mysql_query("SELECT * FROM gutachter WHERE matrikelnr = '$matrikelnr'");
					if (!$rs) {
						error_db();
					}
					else {
						$result = mysql_num_rows($rs);
						if ($result > 0 ) {
							$error_autor = true;
							$error_autor_text = "Es gibt bereits einen Gutachter mit dieser Matrikelnummer!";
						}
						else {
							$error_autor = false;
						}
					}
				}	


				/**************************************************
				   2. Eingabe ŸberprŸfen: Name
				***************************************************/	

				if ($name == "") {
					$error_name = true;
					$error_name_text = "Bitte einen Namen eintragen!";
				}
				else {
					$error_name = false;
				}

				/**************************************************
				   3. Eingabe ŸberprŸfen: Email
				***************************************************/	

				if ($email == ""){
					$error_email = true;
					$error_email_text = "Bitte eine Email-Adresse eingeben!";
				}				
				else {
					$error_email = false;
				}

				/**************************************************
				   3. Eingabe ŸberprŸfen: Gruppe
				***************************************************/	

				if (!ereg("^[1-2]*$", $gruppe)) {
					$error_gruppe = true;
					$error_gruppe_text = "Finger weg vom Formular!";
				}				
				else {
					$error_gruppe = false;
				}
				/**************************************************
					4. Daten in die Tabelle ÇgutachterÈ eintragen, 
					   falls alles gut gegangen ist.
				***************************************************/	

				$error = ($error_autor OR $error_name OR $error_email OR $error_gruppe);

				if ($error) {
					print("<h2>Problem mit den Eingabedaten:</h2><p>");
					if ($error_autor)
						print "$error_autor_text<br>";
					if ($error_name)
						print "$error_name_text<br>";	
					if ($error_email)
						print "$error_email_text<br>";	
					if ($error_gruppe)
						print "$error_gruppe_text<br>";	
					print "</p>";
				}

				else {
					$query = "INSERT gutachter (name,matrikelnr,email,gruppe,datum) VALUES ('$name', '$matrikelnr','$email','$gruppe', '$datum')";
					$rs = mysql_query($query, $con);
					if (!$rs) {
						echo "<p>Datenbankfehler: Daten bitte erneut eingeben!</p>";
					}
					else {
						echo "<p><b>".date("d.m.Y", strtotime($datum))." - Eintrag erfolgreich</b></p><p>Gutachter: $name<br>Matrikelnr: $matrikelnr<br>Email: $email<br>";
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
