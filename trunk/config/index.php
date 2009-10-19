<!--
--/* Copyright 2005, 2006 Jochen Koubek. 2007 Andrea Knaut. - v0.1.2. 
--For contributors see contributors.txt.

--This file is part of the software 'Gutachtersystem'.

--'Gutachtersystem' is free software; you can redistribute it and/or modify
--it under the terms of the MIT License.

--You should have received a copy of the MIT License
--along with this program. See COPYING.txt.
--The license-text is based on http://www.opensource.org/licenses/mit-license.php. */
-->

<!--
Diese Datei legt die RYP-Datenbank und den Nutzer an, über den später die Transaktionen ablaufen werden.
Die Informationen für den DB-Root müssen vorliegen, können aber auch aus einem Webformular gewonnen werden.

Gleiches gilt für alle Variablen im Kopfteil. Sie werden zunächst fest eingetragen, um später über ein Webinterfaceübernommen zu werden.

-->
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Setup Schritt 1</title>
	<meta name="author" content="Jochen Koubek">
	<link href="../css/webstyle_atmIn.css" rel="stylesheet" media="screen">
</head>
<body id="config" class="formular">
	<div id="head">
		<h1>Einrichten von Review Your Peers</h1>
		<p>Zur Einrichtung sind einige Daten erforderlich</p>
	</div>
	<div id="form">
		<form action="setup.php" method="POST" name="Anmeldenformular">
			<h2>Konfiguration der Datenbank</h2>
			<p>MySQL Admin: Benutzername<br>
				<input type="text" name="loginRoot" size="17"></p>
			<p>MySQL Admin: Passwort<br>
				<input type="text" name="passwordRoot" size="17"></p>
			<p>Datenbank-Name<br>
				<input type="text" name="database" size="17"></p>
			<p>Datenbank-User: Benutzername<br>
				<input type="text" name="loginUser" size="17"></p>
			<p>Datenbank-User: Passwort<br>
				<input type="text" name="passwordUser" size="17"></p>			

			<hr size="1" noshade>
			<h2>Angaben zum Kursleiter</h2>
			<p>Name des Kursleiters<br>
				<input type="text" name="adminName" size="17"></p>
			<p>Email-Adresse des Kursleiters<br>
				<input type="text" name="adminEmail" size="17"></p>
			<p>Passwort f&uuml;r den Admin-Bereich (Login: admin)<br>
				<input type="text" name="adminPass" size="17"></p>
			<hr size="1" noshade>
			<h2>Konfiguration des Kurses</h2>
			<p>Datum und Uhrzeit der ersten Aufgabe eingeben<br>
				<input type="text" name="anfangsDatum" size="17"></p>
			<p>Anzahl Aufgaben<br>
				<input type="text" name="aufgabenAnzahl" size="3" value="10"></p>
			<p>Anzahl Gutachten pro Aufgabe<br>
				<input type="text" name="gutachtenAnzahl" size="3" value="3"></p>
			<p>Anzahl Gruppen<br>
				<input type="text" name="gruppenAnzahl" size="3" value="1"></p>	
				
			<p><input type="submit" name="anmelden_submit" value="Abschicken"></p>
		</form>
	</div>
</body>

</html>