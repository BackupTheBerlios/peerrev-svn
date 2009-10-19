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
		<title>Review Your Peers &ndash; Administration &ndash; Command</title>
		<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
	</head>

<?php	
	 require "../config/connect.php";
?>

	<body bgcolor="#ffffff">
		<div align="center">
			<table width="398" border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td>
						<h1><img src="../pix/tool.jpg" alt="" width="110" height="81" border="0"></h1>
						<h1>
							MySQL-Command</h1>
							<p> Enter SQL-Command for database <b> <?php echo $database; ?></b>, for example: <br /><code> SELECT * FROM PARAMETER;</code><br /> to get all entries from table <i>parameter</i>.
					</td>
				</tr>
				<tr>
					<td><hr>
<?php					

		import_request_variables("P"); 
		
		if ($text != "")
		{
			$text = stripslashes($text);
			print "<h2>$text</h2>";
			$rs = mysql_query($text, $con);
			if(!$rs)
				echo "<p>" . mysql_errno() . ": " . mysql_error() . "</p>";
			else
			{
				print "<table border=1 cellpadding = 5>";
				while ($rs_array = mysql_fetch_row($rs))
				{
					print "<tr>";
					for ($i=0; $i<count($rs_array); $i++)
					{
						print "<td>$rs_array[$i]</td>\n";
					}
					print "</tr>";
				}
				print "</table>";
			}
		}
	
		mysql_close($con);
?>
						<p></p>
					</td>
				</tr>
				<tr>
					<td>
						<form action="command.php" method="post" name="Formular">
							<p></p>
							<p>Mysql_Befehl:<br>
								<textarea name="text" rows="7" cols="61"></textarea></p>
							<p><input type="submit" name="submitButtonName" value="Abschicken"></p>
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<p></p>	
					</td>
				</tr>
				<tr>
					<td valign=top>
						<div align="left"><hr />
							<p>
							<a style="font-size: 120%;" href="index.php" target="_top">Back</a>
							</p>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<p></p>
		<p></p>
	</body>

</html>