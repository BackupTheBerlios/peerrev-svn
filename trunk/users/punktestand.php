<!--/* Copyright 2005, 2006 Jochen Koubek - v0.1.2. 

For contributors see contributors.txt.

This file is part of the software 'Review Your Peers'.

'Review Your Peers' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */--!>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php 
	require "../config/connect.php";
	import_request_variables("G","get_");
?>

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<meta name="generator" content="Adobe GoLive 6">
		<title>Digitale Medien Gutachten</title>
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
		<link rel="icon" href="../pix/favicon.ico" type="image/ico" />
	</head>

	<body class="user" id="punktestand">
		<?php
			include "menu.php";
		?>

		<div class="content">
			<h1>Der aktuelle Punktestand im Vergleich.</h1>
			<p>Diese Tabelle beruht auf einer einfachen Abfrage der Gutachtendatenbank. Bei Autoren, die Dokumente ausserhalb des Gutachtersystems eingereicht haben, kann der hier angezeigte Punktestand vom tats&auml;chlichen Stand abweichen.<br />
				Durch Auswahl von <a href='punktestand.php'>Gutachter</a> oder <a href='punktestand.php?order=punkte'>Punkte</a> wird die Tabelle entsprechend geordnet.</p>

			<table cellpadding=5 cellspacing=5>
				<tr>
					<th>
						<?php
							if ($get_order == "punkte") {
								echo "<p>Rang</p>";
							}
						?>
					</th>
					<th><a href='punktestand.php'>Gutachter</a></th>
					<th><a href='punktestand.php?order=punkte'>Punkte</a></th>
				</tr>

			<?php
				$rs=mysql_query("SELECT matrikelnr FROM gutachter ORDER BY matrikelnr", $con);
				if(!$rs) {
					error_db();
				}
				else {
					$anzahlGutachter=mysql_num_rows($rs);
					for($i=0;$i<$anzahlGutachter;$i++) {
						$rs_array=mysql_fetch_row($rs);
						$gutachter[$i][0] = $rs_array[0];
						$gutachter[$i][1] = 0; //Punkte default
		
						$query2 = "SELECT punkte, zusatzpunkte FROM dokumente WHERE autor='".$gutachter[$i][0]."' AND validiert='j'";
						$rs2 = mysql_query($query2,$con);
						while($rs2_array=mysql_fetch_row($rs2))	{
							$gutachter[$i][1] += $rs2_array[0];	
							$gutachter[$i][1] += $rs2_array[1];
						}

						$punkte[$i][0] = $gutachter[$i][1];
						$punkte[$i][1] = $gutachter[$i][0];
					}
	
					rsort($punkte);		
	
					if ($get_order=="punkte") {
						$rang=0;
						$punkte_alt=1000;
						for($i=0;$i<$anzahlGutachter;$i++)	{
							if ($punkte_alt > $punkte[$i][0])
								$rang++;
							echo "<tr><td><p>$rang</p></td><td><p>".$punkte[$i][1]."</p></td><td><p>".$punkte[$i][0]."</p></td></tr>";
							$punkte_alt=$punkte[$i][0];
						}
					}
	
					else {
						for($i=0;$i<$anzahlGutachter;$i++) {
							echo "<tr><td></td><td><p>".$gutachter[$i][0]."</p></td><td><p>".$gutachter[$i][1]."</p></td></tr>";
						}
					}
				}
				mysql_close($con);
			?>
		</table>
	</div>
	
	<div class="foot">
		<?php
			include "foot.php";
		?>			
	</div>

	</body>
</html>
