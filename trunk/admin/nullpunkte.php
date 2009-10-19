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
	<title>Review Your Peers &ndash; Administration &ndash; Nullpunkte</title>
	<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
</head>

<body>
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > Nullpunkte</p>
		<hr noshade size="1">
<?php
	require "../config/connect.php";
	import_request_variables("G");
	if ($nr=="")
		$nr=$gutachtennr-1;
	echo "<h1>Dokument mit 0-Punkte-Gutachten f&uuml;r Aufgabe $nr</h1>";
		
	$rs=mysql_query("SELECT dokument, gutachten from auftraege WHERE punkte=0 AND aufgabenr=$nr ORDER BY dokument");
	if (!$rs)
		error_db();
	else {
		while ($row=mysql_fetch_object($rs)) {
			echo "<p><a href='".$uploadDir."aufgabe$nr/$row->dokument'>$row->dokument</a><br>$row->gutachten</p><hr noshade size='1'>";
		}
	}
?>
		<p></p>
		<hr noshade size="1">
		<p></p>
		<form id="FormName" action="nullpunkte.php" method="get" name="FormName">
				<p>Aufgabennr.: <input type="text" name="nr"></input></p>
				<p><input type="submit"></p>
			</form>
		</div>
	</body>

</html>