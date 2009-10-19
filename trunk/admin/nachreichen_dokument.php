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
<?php		import_request_variables("PG"); 
		if($nr!="")
			$gutachtennr=$nr;
?>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php?nr=<?php echo $nr ?>">Status </a>&gt; Dokument nachreichen</p>
		<hr noshade size="1">
		<h1>Dokument nachreichen</h1>
			<?php		
	
		require "../config/connect.php";
		
		if ($sucheNummer != "")
		{
			$query="SELECT matrikelnr, name FROM gutachter WHERE name LIKE '%$name%'";
			$rs=mysql_query($query);
			if(!$rs)
				echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";
			if (mysql_num_rows($rs) >0)
			{
				$rs_array=mysql_fetch_row($rs);
				$autor = $rs_array[0];
				$name = $rs_array[1];
				$dokument = $autor.".zip";
			}
		}		
		if ($send != "")
		{
			print "<h2>$text</h2>";
			$rs=mysql_query("SELECT * FROM dokumente WHERE autor='$autor' AND aufgabenr='$nr'");
			if (mysql_num_rows($rs)!=0)
				echo "<p>Dokument bereits vorhanden!</p>";
			else
			{
				$query = "INSERT INTO dokumente (aufgabenr, autor, dokument, anmerkung, validiert, punkte, datum) ".
				"VALUES ('$nr', '$autor', '$dokument', '$anmerkung', 'j', '$punkte', '$datum')";
				echo "<p>$query</p>";
			
				$rs = mysql_query($query, $con);
				if(!$rs)
					echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";
				else
					echo "<p>Eingef&uuml;gt</p>";
			}
		}
		
		if (($autor!="") AND ($nr!=""))
		{
			$rs=mysql_query("SELECT dokument, autor, aufgabenr, anmerkung, punkte FROM dokumente WHERE aufgabenr='$nr' AND autor='$autor'", $con);
			$rs_array=mysql_fetch_row($rs);
			$dokument=$rs_array[0];
			$autor=$rs_array[1];
			$nr=$rs_array[2];
			$anmerkung=$rs_array[3];
			$punkte=$rs_array[4];
		}
		mysql_close($con);
		
?>
			<form id="FormName" action="nachreichen_dokument.php" method="post" name="FormName">
				<p>Name <input type="text" name="name" size="24" value="<?php echo $name; ?>"> <input type="submit" name="sucheNummer" value="Suchen"></p>
			</form>
			<form action="nachreichen_dokument.php" method="post" name="Formular">
							<p></p>
							<p>Aufgabenr:<br>
								<input name="nr" value="<?php echo $nr; ?>"></p>	
							<p>Autor:<br>
								<input name="autor" value="<?php echo $autor; ?>"></p>	
							<p>Dokument:<br>
								<input name="dokument" value="<?php echo $dokument; ?>"></p>	
							<p>Anmerkung:<br>
								<textarea name="anmerkung" rows="7" cols="61"><?php echo $anmerkung ?></textarea></p>
				<p>Punkte:<br>
								<input name="punkte" value="<?php echo $punkte ?>"></p>
				<p><input type="submit" name="send" value="Nachreichen"></p>
			</form>
			</div>
		</body>

</html>