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
		<title>Review Your Peers &ndash; Administration &ndash; Gutachtennachtrag</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>
<?php	
	require "../config/connect.php";
	import_request_variables("PG"); 
	if($nr!="")
		$gutachtennr=$nr;
	if (!isset($send))	
		$send="";
	
?>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php?nr=<?php echo $nr ?>">Status </a> > Gutachten nachtragen</p>
		<hr noshade size="1">
		<h1>Gutachten nachtragen</h1>

<?php						
		if ($send != "")
		{
			$query="UPDATE auftraege SET punkte='$punkte', gutachten='$gutachten' WHERE dokument='$dokument' AND id='$id'";
			echo "<p>$query</p>";
			
			if (!mysql_query($query, $con)) {
				error_db();
			}
			else { 
				validiere_gutachten ($gutachtennr, $gutachter);
			}
		}

		$rs=mysql_query("SELECT dokument, gutachter, aufgabenr, gutachten, punkte FROM auftraege WHERE id='$id'", $con);
		$row=mysql_fetch_object($rs);
		$dokument=$row->dokument;
		$gutachter=$row->gutachter;
		$nr=$row->aufgabenr;
		$gutachten=$row->gutachten;
		$punkte=$row->punkte;

		mysql_close($con);
		
?>

						<form action="aendern_gutachten.php" method="post" name="Formular">
							<p></p>
							<p>ID:<br>
								<input name="id" value="<?php echo $id; ?>"></p>	
							<p>Gutachter:<br>
								<input name="gutachter" value="<?php echo $gutachter; ?>"></p>	
							<p>Dokument:<br>
								<input name="dokument" value="<?php echo $dokument; ?>"></p>	
							<p>Gutachten:<br>
								<textarea name="gutachten" rows="7" cols="61"><?php echo $gutachten ?></textarea></p>
							<p>Punkte:<br>
								<input name="punkte" value="<?php echo $punkte ?>"></p>
								<input type="hidden" name="nr" value="<?php echo $nr ?>">
							<p><input type="submit" name="send" value="Eintragen"></p>
						</form>
			</div>
		</body>

</html>