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
		<?php
			import_request_variables("G", "getv_"); 
		?>
	</head>

	<body class="user formular" id="gutachten">
		<?php
			include "../users/menu.php";
		?>		
		<div class="content">
			<form action="gutachten_abgeben_reply.php" method="post" name="Gutachtenformular">
				<?php 
					require "../config/connect.php";
					
 				
					if ($gutachtennr > 0) {
						echo"<h1>Gutachten f&uuml;r Aufgabe $gutachtennr einreichen:</h1>";
						mysql_close($con); 
						echo "<p>Gutachter/in (nur Matr. Nr. eingeben):<br>
						<input type=\"text\" name=\"gutachter\" value = \"";
						print($getv_gutachter); 
						echo"\" size=\"24\"></p>
						<p>Begutachtetes Dokument (inkl. Dateiendung):<br>
						<input type=\"text\" name=\"dokument\" value=\"";
						print($getv_dokument); 
						echo "\" size=\"28\"></p>
	<!--
 		 BEGIN CONFIGURATION AREA
 	 	add or remove <option value>-tags for another scale of points - you have to adjust gutachten_reply.php as well then
	-->
						<p>Punktzahl:<br>
						<select name=\"punkte\" size=\"1\">
							<option value=\"-1\">Bewertung</option>
							<option value=\"0\">0 Punkte</option>
							<option value=\"1\">1 Punkt</option>
							<option value=\"2\">2 Punkte</option>
							<option value=\"3\">3 Punkte</option>
							<option value=\"4\">4 Punkte</option>
							<option value=\"5\">5 Punkte</option>
						</select></p>
<!--
 	 END CONFIGURATION AREA
-->
						<p>Gutachten:<br>
						<textarea name=\"gutachten\" rows=\"16\" cols=\"56\"></textarea></p>
						<input type=\"submit\" name=\"gutachten_submit\" value=\"Gutachten abschicken\"></p>";
					}	
				   	else if ($gutachtennr == -1 ) {
						echo "<p>Die &Uuml;bung ist beendet. Die Frist f&uuml;r die letzten Gutachten ist abgelaufen.</p>";
					}
					else {
						echo "<p>Derzeit kann kein Gutachten abgegeben werden</p>";
					}

?>
									
				</form>
		</div>

		<div class="foot">
			<?php
				include "foot.php";
			?>			
		</div>
		
	</body>

</html>
