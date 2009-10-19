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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachten anzeigen</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php">Status</a> > Gutachten anzeigen</p>
		<hr noshade size="1">
<?php

require "../config/connect.php";
import_request_variables("G", ""); 

if  ($mode == "erase")
{
	$query = "UPDATE auftraege SET gutachten = '', punkte = '', datum='0000-00-00 00:00:00' WHERE id = '$id'";
	$rs = mysql_query($query, $con);
	if (!$rs)
	{
		error_db();
	}

	else
	{
		echo "<p>$Gutachten $id wurde erfolgreich entfernt. Auftrag bleibt erhalten.</p>";
	}
}
else
{
	$query = "SELECT * FROM auftraege WHERE id = '$id'";

	$rs = mysql_query($query, $con);
	if (!$rs)
	{
		error_db();
	}

	else
	{
		$rs_array=mysql_fetch_row($rs);
		print "<p>Gutachter $rs_array[2] | Dokument $rs_array[3]:<br>";
		$rs_array[4]=str_replace('\n','<br>',$rs_array[4]);
		$rs_array[4]=str_replace('\r','',$rs_array[4]);

		print $rs_array[4] . "<br>";
		print "$rs_array[5] Punkte<p>";
	}

}


mysql_close($con);
?>
	</body>

</html>