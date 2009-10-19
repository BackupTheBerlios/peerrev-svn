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
		<title>Review Your Peers &ndash; Administration &ndash; Auftrag anzeigen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php">Status</a> > Auftrag Anzeigen</p>
		<hr noshade size="1">

<?php

require "../config/connect.php";
import_request_variables("G"); 

if($nr!="")
	$gutachtennr=$nr;

echo "<h1>Auftrag f&uuml;r Gutachter $id</h1>";

$query = "SELECT dokument FROM auftraege ".
	"WHERE aufgabenr = '$gutachtennr' AND gutachter = '$id' ";

echo "<p>$query</p>";

$rs = mysql_query($query, $con);
if (!$rs) {
	error_db();
}
else {
	$i = 1;
	$text="";
	while ($rs_array=mysql_fetch_row($rs)) {
// $gutachter[$j] bekommt das Dokument von auftrag[$j,$i]
		$rs2 = mysql_query("SELECT anmerkung FROM dokumente WHERE dokument = '$rs_array[0]' AND aufgabenr='$gutachtennr'", $con);
		$rs_array2 = mysql_fetch_row($rs2);
		$rs_array2[0]=str_replace('\n','<br>',$rs_array2[0]);
		$rs_array2[0]=stripslashes(str_replace('\r','',$rs_array2[0]));

		$text .= "<p><b>Gutachten " . $i . "</b><br>" . 
				"$uploadServer/aufgabe$gutachtennr/" . $rs_array[0] . "<br />" .
					 "Anmerkungen des Autors: \n" . $rs_array2[0]. "</p>";
		$i++;
	}
	echo $text;
}
mysql_close($con);

?>
		</div>
	</body>

</html>
