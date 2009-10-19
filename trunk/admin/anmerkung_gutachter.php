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
		<title>Review Your Peers &ndash; Administration &ndash; Anmerkung Gutachter</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>
	<?php		
		import_request_variables("PG"); 		
	?>

	<body bgcolor="#ffffff">
		<div id="content">
		<p><a href="index.php">Verwaltung</a> > <a href="status.php?nr=<?php echo $nr ?>">Status </a>&gt; Anmerkung zu Gutachter</p>
		<hr noshade size="1">
		<h1>Anmerkung zu Gutachter</h1>
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
				$matrikelnr = $rs_array[0];
				$name = $rs_array[1];
			}
		}
				
		if ($send != "")
		{
			print "<h2>$text</h2>";
			$query="UPDATE gutachter SET anmerkung='$anmerkung' WHERE matrikelnr='$matrikelnr'";
			echo "<p>$query</p>";
			if ($matrikelnr != "")
			{
				$rs = mysql_query($query, $con);
				if(!$rs)
					echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";
			}
			else
				echo "Matrikelnr. fehlt!";
			

		}
		
		if ($matrikelnr != "")
		{
			$rs = mysql_query("SELECT name, anmerkung FROM gutachter WHERE matrikelnr = '$matrikelnr'", $con);
			if(!$rs)
				echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";
				$rs_array=mysql_fetch_row($rs);
				$name = $rs_array[0];			
				$anmerkung = $rs_array[1];

		}
		mysql_close($con);
		
?>
			<form id="FormName" action="anmerkung_gutachter.php" method="post" name="FormName">
				<p>Name <input type="text" name="name" size="24"> <input type="submit" name="sucheNummer" value="Suchen"></p>
			</form>
			<p></p>
			<form action="anmerkung_gutachter.php" method="post" name="Formular">
							<p></p>
							<p>Name des Gutachters:<br>
								<input name="name" value="<?php echo $name; ?>"></p>
				<p>ID des Gutachters:<br>
					<input name="matrikelnr" value="<?php echo $matrikelnr; ?>"></p>
				<p>Anmerkung:<br>
								<textarea name="anmerkung" rows="7" cols="61"><?php echo $anmerkung ?></textarea></p>
				<p><input type="submit" name="send" value="Eintragen"></p>
			</form>
			</div>
		</body>

</html>