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
		<title>Review Your Peers &ndash; Anmeldung</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

	<body class="user formular" id="anmelden">
		<?php
			include "menu.php";
			include "../config/common.inc.php";
		?>
		
		<div class="content">
			<h1>Anmeldung f&uuml;r das Gutachterverfahren</h1>
			<p>Die einmalige Anmeldung ist Voraussetzung f&uuml;r die Teilnahme am Gutachterverfahren. Diese Anmeldung kann zu einem beliebigen Zeitpunkt w&auml;hrend des Semester erfolgen.</p>
		</div>
		<div class="content form">
			<form action="anmelden_reply.php" method="POST" name="Anmeldenformular">
				<p>Nickname:<br>
					<input type="text" name="name" size="44"></p>
				<p>Matrikelnummer:<br>
					<input type="text" name="matrikelnr" size="17"></p>
				<p>Email-Adresse:<br>
					<input type="text" name="email" size="44"></p>
					<input type=hidden name="gruppe" value="1"></p>
				<p><input type="submit" name="anmelden_submit" value="Anmelden"></p>
			</form>
		</div>
		
		<div class="foot">
			<?php
				include "foot.php";
			?>			
		</div>

	</body>
</html>
