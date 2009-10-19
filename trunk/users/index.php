<!--/* Copyright 2005, 2006 Jochen Koubek. 2007 Andrea Knaut - v0.1.2. 

For contributors see contributors.txt.

This file is part of the software 'Review Your Peers'.

'Review Your Peers' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */--!>

<html>

	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<title>Review Your Peers</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
		<link rel="icon" href="../pix/favicon.ico" type="image/ico" />
	</head>

	<body class="user" id="start">
		<?php 		
			include "../config/connect.php";  //general configuration-file
			include "menu.php";
			print "<div class='content' id='task'>";
				if ($aufgabeNummer == 0) {
					print "<h1>Keine Aufgabe gestellt</h1>";
					print "<p>Derzeit kann kein Dokument eingereicht werden.</p>";
				}
				else if ($aufgabeNummer == -1) {
					print "<h1>Ende der &Uuml;bung</h1>";
					if ($gutachtennr > 0) {
						print "<p>Bitte die letzten Gutachten abgeben.";
					}
					else {
						print "<p>Vielen Dank f&uuml;r die Teilnahme.</p>";
					}
					print "</p>";
				}
				else {
					echo "<h1>Aufgabe $aufgabeNummer</h1>";
					$task ="../tasks/task$aufgabeNummer".".php";
					include $task; 	
				}
			print "</div>";
		?>
		
		<div class="foot">
			<?php
				include "foot.php";
			?>			
		</div>

	</body>
</html>