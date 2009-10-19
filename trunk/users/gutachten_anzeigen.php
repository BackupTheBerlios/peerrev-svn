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
		<title>Review Your Peers &ndash; Gutachten ansehen</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

	<body class="user formular" id="gutachten_anzeigen">
		<?php
			import_request_variables("P", "dm_var_"); 
			require "../config/connect.php";
			include "menu.php";

			$gutachtennr=$gutachtennr-1; //nur die Gutachten zu bereits fertigen Aufgaben anzeigen
			isset($dm_var_autor) ? $autor = clean($dm_var_autor, 10) : $autor = "";
			isset($dm_var_nr) ? $nr = clean($dm_var_nr,2) : $nr = $gutachtennr;
		?>

		<div class="content">
		<?php
			if ($gutachtennr > 0) {
				print "<form action=\"gutachten_anzeigen.php\" method=\"POST\" name=\"Anmeldenformular\">\n".
					"<h1>Gutachten ansehen</h1>\n".
					"<p>Matrikelnr. des Autors:<br>\n".
					"<input type=\"text\" name=\"autor\" size=\"18\" value=\"$autor\"></p>\n".
					"<p>Aufgabenr: \n".
					"<select name=\"nr\">\n";
				  
					for ($i=1; $i<=$gutachtennr; $i++) {
						print "<option value='$i'";
						if ($i==$gutachtennr)
						print " selected"; 
						print">$i</option>\n"; 
					}
				
				print"</select>".
					"<p><input type=\"submit\" name=\"submitButtonName\" value=\"Gutachten zeigen\"></p>\n".
					"</form>";
			}
			else {
				echo "<p>Es liegen noch keine Gutachten vor!</p>";
			}
				if (!ereg("^[0-9]*$", $autor)) { 
					print "<p>Bitte die Matrikelnr. des Autors angeben!</p>";
				}
	
				if ($gutachtennr > 0) {
					if ((0 < $nr) && ($nr <= $gutachtennr)) {
						$query = "SELECT dokument, anmerkung, punkte, validiert, zusatzpunkte FROM dokumente WHERE autor = '$autor' AND aufgabenr='$nr'";
						getReviews($query);
					}
					else {
						echo "<p>Finger weg vom Formular!</p>";
					}
				}
			

				function getReviews($query) {
					global $con;
					global $nr;
					global $autor;
					$rs = mysql_query($query);
					if (!$rs) {
						print "<p>Datenbank-Fehler. Bitte erneut versuchen</p>";
					}
					elseif (mysql_num_rows($rs)>0) {	
						$rs_array = mysql_fetch_row($rs);
						$dokument = $rs_array[0];
						print "<h1>Gutachten f&uuml;r Dokument $dokument, Aufgabe $nr</h1>";
						print "<p><b>Autor $autor</b><br>Anmerkungen:<br>$rs_array[1]</p>";
						print "<b>". ($rs_array[2] + $rs_array[4])." Punkte ";
						if ($rs_array[3] == "j")
							print "werden angerechnet.";
						else
							print "werden aufgrund fehlender Gutachten nicht angerechnet.";
						print "</b><hr>";
	
						$query2 = "SELECT gutachten, punkte FROM auftraege ".
									"WHERE dokument='$dokument' AND aufgabenr='$nr' ORDER BY gutachter";
	
						$rs2 = mysql_query($query2, $con);
						if (!$rs2) {
							error_db();
						}
						else {
							while ($rs_array2 = mysql_fetch_row($rs2)) {// Gutachten zum Dokument
								if ($rs_array2[0]!="")
									print "<p>" . $rs_array2[0] . "<br>";
								if ($rs_array2[1]<0)
									echo "<p>Kein Gutachten abgegeben, Punkte werden aufgerundet.</p><hr size=1 noshade>";
								else						
									echo "<b>$rs_array2[1] Punkte</b></p><hr size=1 noshade>";
							}
						}
						print "<p><b>Zusatzpunkte: $rs_array[4]</b></p>";
					}
				}//end function

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
