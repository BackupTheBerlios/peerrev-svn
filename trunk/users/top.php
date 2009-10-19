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
		<meta name="generator" content="Adobe GoLive 6">
		<title>Review Your Peers &ndash; Beste Dokumente</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
	</head>

<?php 
//Anzahl der Punkte, nach denen gefiltert wird.
	$filter = 22;
?>
	<body class="user" id="top">
		<div id="head">
			<h2>Dokumente >= <?php print $filter ?> Punkte</h2>
		</div>
<?php
require "../config/connect.php";

for ($i=1; $i<$aufgabeNummer-1; $i++)
{
	print "<p><b>Aufgabe $i</b></br>";
	$rs=mysql_query("SELECT * FROM dokumente WHERE aufgabenr=$i AND (punkte+zusatzpunkte>=22) ORDER BY (punkte+zusatzpunkte)", $con);
	if(!$rs)
	{
//		error_db();
		print "Datenbankfehler";
	}
	else
	{
		while ($rs_query=mysql_fetch_row($rs))
		{
			print "<a href='$uploadserver/$uploadDir/aufgabe$i/$rs_query[2]'>$rs_query[2] (". ($rs_query[6]+$rs_query[8]).")</a><br>";
		}
	}
	print "</p>";
}
mysql_close($con);

?>
	</div>

	<div class="foot">
		<?php
			include "foot.php";
		?>			
	</div>

	</body>

</html>
