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
		<title>Review Your Peers &ndash; Administration &ndash; Daten suchen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > Daten suchen</p>
		<hr noshade size="1">
			<h1>Data Digger</h1>
							<?php

require "../config/connect.php";
import_request_variables("G","");

$uploaddir = "$uploadDir/aufgabe".$nr."/"; 

$query="SELECT distinct dokument, gutachten FROM auftraege WHERE gutachten LIKE '%$suchwort%' AND aufgabenr='$nr'";
$rs = mysql_query($query);
if (mysql_num_rows($rs)==0)
	echo "<p>Keine Übereinstimmung gefunden</p>";
else
{
	while ($rs_array = mysql_fetch_row($rs))
	{
		$downloaddir = $uploaddir . $rs_array[0];
		$rs_array[1]=str_replace('\n','<br>',$rs_array[1]);
		$rs_array[1]=str_replace('\r','',$rs_array[1]);

		echo "<p><a href='$downloaddir'>$rs_array[0]</a>:<br>$rs_array[1]</p>";
	}
}

?>
							<p></p>
				<hr noshade size="1">
							<p></p>
						<form id="FormName" action="dig.php" method="get" name="FormName">
								<p><input type="text" name="nr" size="2"><br>
										Aufgabenr.</p>
								<p><input type="text" name="suchwort" size="24"><br>
									Suchstring</p>
								<p><input type="submit" name="submitButtonName" value="Suchen"></p>
	</body>

</html>