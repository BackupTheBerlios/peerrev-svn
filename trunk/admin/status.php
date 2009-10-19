<!--
/* Copyright 2005, 2006 Jochen Koubek - v0.1.2. 
For contributors see contributors.txt.

This file is part of the software 'Gutachtersystem'.

'Gutachtersystem' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */
-->


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<title>Review Your Peers &ndash; Administration &ndash; Status Gutachten</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
		<style media="screen">
			ul#tabnav {
				list-style-type: none;
				margin: 0;
				padding-left: 40px;
				padding-bottom: 24px;
				border-bottom: 1px solid #000;
				font: bold 11px verdana, arial, sans-serif;
			}
			ul#tabnav li {
				float: left;
				height: 21px;
				background-color: #1032b5;
				color: #FFFFFF;
				margin: 2px 2px 0 2px;
				border: 1px solid #000;

			}
			ul#tabnav a:link, ul#tabnav a:visited {
				display: block;
				color: #FFFFFF;
				background-color: transparent;
				text-decoration: none;
				padding: 0.4em 2em;
			}
			ul#tabnav a:hover {
				background-color: #869cf4;
				color: #FFFFFF;
			}
			#content {
				border-top: none;
			}
		</style>
	</head>

	<body bgcolor="#ffffff">

<?php 

	require "../config/connect.php";
	import_request_variables("G", "getv_"); 

	if ($getv_nr!="")
		$gutachtennr=$getv_nr;

	echo "<ul id='tabnav'>";
	for ($i=1; $i<=$anzahlAufgaben;$i++) {
		if($i==$gutachtennr) {
			echo "<li class='auswahl' style='border-bottom: 1px solid #fff;color: #000000;background-color: #FFFFFF;'><a  href='#' style='color:#000'>$i</a></li>";
		}
		else {
			echo "<li class='nichtwahl'><a href='status.php?nr=$i'>$i</a></li>";
		}
	}
	echo "</ul>";

?>

<div id='content'>
<p><a href="index.php">Verwaltung</a> > Status</p>
<hr noshade size="1">
<h1>Status von Aufgabe <?php echo $gutachtennr; ?></h1>

<?php 
// Anzahl abgegebener Gutachten fuer Aufgabe anzeigen
$query = "SELECT autor FROM dokumente WHERE aufgabenr = '$gutachtennr' AND validiert ='j'";
$anzahlValidierterGutachten = mysql_num_rows(mysql_query($query, $con));

$query = "SELECT autor, dokument, punkte, validiert, zusatzpunkte ".
		" FROM dokumente WHERE aufgabenr = '$gutachtennr'";

if (isset($getv_order)) {
	$query .= " ORDER BY $getv_order";
}
else {
	$query .= " ORDER BY autor";
}

$rs = mysql_query($query, $con);
if (!$rs) {
	error_db();
}

else {	
	$anzahl = mysql_num_rows ($rs);
	echo "<p>Anzahl Dokumente f&uuml;r Aufgabe $gutachtennr: $anzahl <br />Anzahl validierter Gutachter f&uuml;r Aufgabe $gutachtennr: $anzahlValidierterGutachten</p>";

//Tabellenkopf erstellen
	print "<table border=1 cellpadding=5>\n";
	print "<tr><td><p><b><a href='status.php?order=autor&nr=$gutachtennr'>Gutachter</a><br>Auftrag</b></p></td>";
	for ($i=0; $i<$anzahlGutachten; $i++) {
		print "<td><p><b>Gutachter-<br>auftrag " . ($i+1) . "</b></p></td>";
	}
	print "<td><p><b><a href='status.php?order=dokument'>Dokument</a></b><br><a href='status.php?order=punkte&nr=$gutachtennr'>pt.</a> | <a href='status.php?order=validiert&nr=$gutachtennr'>val.?</a></p></td>";
	for ($i=1; $i<=$anzahlGutachten; $i++) {
		print "<td><p><b>" . ($i) . ".</b></p></td>";
	}
	print "<td>+</td>";

	print "</tr>\n";

//Tabelle fŸllen
	while ($rs_array = mysql_fetch_row($rs)) { //Schleife Ÿber Alle Gutacher
		$gutachter = $rs_array[0]; 
		$dokument = $rs_array[1];
		$punkte = $rs_array[2];
		$validiert = $rs_array[3];
				
		$rs2 = mysql_query("SELECT id, dokument, gutachten, punkte FROM auftraege WHERE aufgabenr='$gutachtennr' AND gutachter ='$gutachter'");
		if (!$rs2) { // Hole die AuftrŠge des aktuellen Gutachters
			error_db();
		}
		else {
			$rs_gruppe = mysql_fetch_row(mysql_query("SELECT gruppe FROM gutachter WHERE matrikelnr='$gutachter'"));
			$gruppe = $rs_gruppe[0];
			echo "<tr><td><a href = 'anzeigen_gutachter.php?gutachter=$gutachter&nr=$gutachtennr'>$gutachter ($gruppe)</a><br><a href='anzeigen_auftrag.php?id=$gutachter&nr=$gutachtennr'>Auftrag</a><br><a href = 'begutachten_gutachter.php?gutachter=$gutachter&nr=$gutachtennr'>Evaluieren</a></td>";
			if (mysql_num_rows($rs2) > 0) {
				while ($rs_array2 = mysql_fetch_row($rs2)) { //Schleife Ÿber alle AuftrŠge des aktuellen Gutachters
					if ($rs_array2[2] != "") {
						echo "<td><p><a href='anzeigen_gutachten.php?id=" . $rs_array2[0] . "'>" . $rs_array2[1] . "</a> ".
							 "(<a href='aendern_gutachten.php?id=" . $rs_array2[0] . "&nr=$gutachtennr'>+</a>)<br>".
							 "$rs_array2[3]".
							 "</p></td>\n";
					}
					else {
						$rs_gruppe = mysql_fetch_row(mysql_query("SELECT dokumente.autor, gutachter.gruppe FROM dokumente, gutachter WHERE dokumente.dokument='$rs_array2[1]' AND gutachter.matrikelnr=dokumente.autor"));
						$gruppe = $rs_gruppe[1];

						echo "<td><p>$rs_array2[1] ".
						"(<a href='aendern_gutachten.php?id=" . $rs_array2[0] . "&nr=$gutachtennr'>+</a>)<br>".
						"$gruppe</p></td>\n";
					}				
				}
			}
			else {
				for ($i=1; $i<=$anzahlGutachten;$i++) {
					echo "<td>-</td>\n";
				}
			}
		}
		echo "<td><p><a href='anzeigen_dokument.php?dokument=$dokument&nr=$gutachtennr'>$dokument</a><br>$punkte | $validiert</p></td>";
		
		$rs3 = mysql_query("SELECT id, gutachter, gutachten, punkte FROM auftraege WHERE aufgabenr='$gutachtennr' AND dokument ='$dokument'");
		if (!$rs3) {// Wer begutachtet das Dokument des aktuellen Gutachters?
			error_db();
		}
		else {	
			if (mysql_num_rows($rs3) >0) {
				while ($rs_array3 = mysql_fetch_row($rs3)) {
					if ($rs_array3[2] != "") {
						echo "<td><p><a href = 'anzeigen_gutachten.php?id=" . $rs_array3[0] . "'>" . $rs_array3[1] . "</a><br>".
							"$rs_array3[3]</p></td>";
					}
					else {
						echo "<td><p>$rs_array3[1]</p></td>";
					}				
				}
			}
			else {
				for ($i=1; $i<=$anzahlGutachten;$i++) {
					echo "<td>-</td>\n";
				}
			}
		}
		echo "<td><a href='aendern_zusatzpunkte.php?dokument=$dokument&nr=$gutachtennr&zusatzpunkte=$rs_array[4]'>$rs_array[4]</a></td>";
		echo "</tr>";

	}
	print "</table>";
}
echo "</div>";
mysql_close($con);

?>
	</body>

</html>
