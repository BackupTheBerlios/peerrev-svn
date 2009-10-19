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
		<title>Review Your Peers &ndash; Administration</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
		<link rel="icon" href="../pix/favicon.ico" type="image/ico" />
		<style media="screen">
			#content {position:relative;}
			.left {float:left; padding: 1em; margin-right: 1em}
			.right {float: rigth;  padding: 1em; margin-left: 30em }
			.wrap, form {border: 1px solid black; padding: 1em; width: 20em; margin: 1em;}
		</style>
	</head>

	<body bgcolor="#ffffff">
	<?php 
		require "../config/connect.php"; 
	?>


		<div id="content">
				<div class="left">
					<img src="../pix/tiger.jpg">
				</div>
				<div class="right">
					<h1>Administration<br>
					<?php	
						print "<p>Heute ist der ".date("d.m.Y, H:i:s", strtotime($datum))." Uhr</p>".
						"<p>Aufgabenr: $aufgabeNummer<br>";
						if ($aufgabeNummer>0){
							print "Dateiendung: $dateiendung<br />".
							"Aufgabe gestellt: $aufgabeStellenDatum<br />".
							"Dokument Abgabedatum: $aufgabeAbgebenDatum<br />".
							"Anzahl Gutachten: $anzahlGutachten</p>".
							"<p>Gutachtennr.: $gutachtennr<br />".
							"Gutachten Abgabedatum: $gutachtenAbgebenDatum</p>";
							
						}
				
					?>
			</h1>
				</div>
						<hr noshade size="1">
		<div class="left">
			<div class="wrap">
				<h2>Commandline</h2>
						<p><a href="command.php">Command<br>
						</a>Mysql_queries zum Testen der Skript-Privilegien.</p>
			</div>
			<div class="wrap">			
					<h2>Evaluation</h2>
					<p><a href="auswerten_gutachter.php">Gutachter auswerten<br>
						</a>Details zu Punkten, Gutachten, Aufgaben und Rang.</p>
					<p><a href="../users/punktestand.php">Ranking</a><br>
						Aktueller Punktestand im Vergleich.</p>
					<p><a href="../users/top.php">Best Practices</a><br>
						Die besten Dokumente.</p>
					<p><a href="anmerkung_gutachter.php">Gutachter kommentieren</a><br>
						Kommentare zur Gutachtern und Autoren.</p>
					<p></p>
			</div>
			<div class="wrap">
				<h2>Tools</h2>
				<p><a href="../config/termine.php">Termine</a><br />
					Organisation der Termine.</p>
				<p><a href="statistik.php">Punkteverteilung</a><br />
					Grafische Anzeige der Punkteverteilung.</p>
				<p><a href="nullpunkte.php">Nullpunkte</a><br>
					Dokumente mit 0-Punkte-Gutachten.</p>
				<p><a href="nachreichen_dokument.php">Dokument nachreichen</a><br>
					In Ausnahmef&auml;llen bei Frist&uuml;berschreitung.</p>
				<p><a href="tool.php?mode=bemerkungen">Bemerkungen<br>
						</a>Anzeige der Bemerkungen. Viele Probleme werden gespeichert.</p>
				<p><a href="dig.php">data digging<br>
						</a>Mustersuche in Gutachten.</p>
			</div>

		</div>
		<div class="right">
			<form action="status.php" method="get" name="Statusformular">
							<h2>Status</h2>
							<div align="left">
								<p>Aufgabe <input type="text" name="nr" size="3" value="<?php echo $gutachtennr; ?>"> <a href="javascript:document.Statusformular.submit()">Anzeigen</a></p>
							</div>
						</form>
				<form action="beauftragen.php" method="get" name="Auftragformular">
					<h2>Gutacherauftr&auml;ge f&uuml;r </h2>
					<div align="left">
						<p>Aufgabe <input type="text" name="nr" size="3" value="<?php echo $gutachtennr; ?>"> <a href="javascript:document.Auftragformular.submit()">Erzeugen</a><input type="hidden" name="mode" value="create"></p>
					</div>
				</form>
				<form action="mailen_auftraege.php" method="get" name="AuftragMailformular">
					<h2>Gutacherauftr&auml;ge f&uuml;r </h2>
					<div align="left">
						<p>Aufgabe <input type="text" name="nr" size="3" value="<?php echo $gutachtennr; ?>"> <a href="javascript:document.AuftragMailformular.submit()">Mailen</a></p>
					</div>
				</form>
				<form action="bewerten.php" method="get" name="bewertenFormular">
					<h2>Gutacherauftr&auml;ge f&uuml;r </h2>
					<div align="left">
						<p>Aufgabe <input type="text" name="nr" size="3" value="<?php echo $gutachtennr; ?>"> <a href="javascript:document.bewertenFormular.submit()">Bewerten</a></p>
					</div>
				</form>
				
				
				
			</div>
			<div style="clear:both"></div>
							
			<hr noshade size="1">
			<h2>Werkzeuge zu Testzwecken</h2>
			<p>Nur im Debug-Mode verwenden, der in debug.php eingestellt werden kann</p>
		<?php
			print "<p>Debug-Modus ist ";
			$debug ? print "eingeschaltet" : print "ausgeschaltet";
			print "</p>";
		?>

			<div class="left">
	 			<div class="wrap">
					<p><a href="tool.php?mode=gutachter">Anmeldungen generieren (2 Gruppen)</a></p>
				</div>
				<p></p>
				<form name="dokumenteErzeugenFormular" action="tool.php" method="get">
					<h2>Dokumente f&uuml;r</h2>
					<p>Aufgabe <input type="text" name="nr" size="3" value="<?php echo $gutachtennr; ?>"> <a href="javascript:document.dokumenteErzeugenFormular.submit()">Erzeugen</a><input type="hidden" name="mode" value="dokumente"></p>
				</form>
				<form name="gutachtenErzeugenFormular" action="tool.php" method="get">
					<h2>Gutachten f&uuml;r</h2>
					<p>Aufgabe <input type="text" name="nr" size="3" value="<?php echo $gutachtennr; ?>"> <a href="javascript:document.gutachtenErzeugenFormular.submit()">Erzeugen</a><input type="hidden" name="mode" value="gutachten"></p>
				</form>
				<form name="tabelleLoeschenFormular" action="tool.php" method="get">
					<h2>Tabelle</h2>
					<h2><select name="tabelle" size="1">
							<option selected value="auftraege">auftraege</option>
							<option value="bemerkungen">bemerkungen</option>
							<option value="dokumente">dokumente</option>
							<option value="gutachter">gutachter</option>
							<option value="parameter">parameter</option>
						</select> </h2>
					<p> <a href="javascript:document.tabelleLoeschenFormular.submit()">Loeschen</a><input type="hidden" name="mode" value="clear"></p>
				</form>
			</div>
			<div class="right">
				<form>
					<h2>Benutzerseiten</h2>
						<p><a href="../users/anmelden.php">Anmelden</a></p>
						<p><a href="../users/dokument_einreichen.php">Dokument einreichen</a></p>
						<p><a href="../users/gutachten_abgeben.php">Gutachten abgeben</a></p>
						<p><a href="../users/gutachten_anzeigen.php">Gutachten anzeigen</a></p>
					</form>
			</div>
			
			<div style="clear:both"></div>
			
	</body>

</html>
