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
		<title>Review Your Peers &ndash; Administration &ndash; Zusatzpunkte</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>
	<?php
	import_request_variables("G"); 	
		require "../config/connect.php";
	?>
	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php?nr=<?php echo $nr ?>">Status </a> > Gutachten nachtragen</p>
		<hr noshade size="1">
		<h1>Gutachten nachtragen</h1>
<?php		
	
		if ($send != "")
		{
			$query="UPDATE dokumente SET zusatzpunkte='$zusatzpunkte' WHERE dokument='$dokument' AND aufgabenr='$nr'";
			echo "<p>$query</p>";
			if (!mysql_query($query, $con))
				error_db();
			else
				echo "<p>eingetragen</p>";
		}
	
		mysql_close($con);
		
?>

						<form action="aendern_zusatzpunkte.php" method="get" name="Formular">
							<p></p>
				<p>Aufgabennr:<br>
					<input name="nr" value="<?php echo $nr; ?>"></p>
				<p>Dokument:<br>
								<input name="dokument" value="<?php echo $dokument; ?>"></p>
				<p>Zusatzpunkte:<br>
					<input name="zusatzpunkte" value="<?php echo $zusatzpunkte; ?>"></p>
				<p><input type="submit" name="send" value="Eintragen"></p>
			</form>
			</div>
		</body>

</html>