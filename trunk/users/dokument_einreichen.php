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
		<title>Review Your Peers &ndash; Einreichen</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

	<body class="user formular" id="einreichen">	
		<?php 
			require "../config/connect.php"; 
			include "menu.php";
		?>
		
		<div class="content" id="form">
			<form enctype="multipart/form-data" action="dokument_einreichen_reply.php" method="post" name="einreichenFormular">
				<?php
					if ($aufgabeNummer > 0) {
						print "<h1>Dokument f&uuml;r Aufgabe $aufgabeNummer einreichen (max. 10MB)</h1>";				
						echo"<p>Autor/in (nur Matr.-Nr. angeben):<br />
							<input type=\"text\" name=\"matrikelnr\" size=\"24\">
						</p>
						<p>Dokument:<br />
							<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000000\">
							<input type=\"file\" name=\"dokument\" size=\"38\">
						</p>
						<p>Begleitende Anmerkungen f&uuml;r Gutachter (Projekthintergrund etc.):<br />
							<textarea name=\"anmerkung\" rows=\"15\" cols=\"56\"></textarea>
						</p>
						<p>
							<input type=\"submit\" name=\"einreichen_submit\" value=\"Verbindlich einreichen\">
						</p>";							   
					} 
					else if ($aufgabeNummer == -1){
						print "<p>Die Abgabefrist f&uuml;r Aufgabe $letzteAufgabe ist wie <a href=\"index.php\">angek&uuml;ndigt</a> am ".
						date("d.m.y", strtotime($aufgabeAbgebenDatum)) . " um ".date("H:i", strtotime($aufgabeAbgebenDatum))." abgelaufen.</p>";
					}
					else {
						print "<p>Derzeit kann kein Dokument eingereicht werden.</p>";
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
