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

	<body bgcolor="#ffffff">
		<div align="center">
			<table width="398" border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td>
						<h1><img src="../pix/tool.jpg" alt="" width="110" height="81" border="0"></h1>
						<h1>
							Regul&auml;rer Ausdruck</h1>
					</td>
				</tr>
				<tr>
					<td><hr>
<?php					
		require "../config/connect.php";
		
		import_request_variables("PG"); 
		$regex="^[0-9]*.[a-z]*$";
		
		echo "<p>$ausdruck<br>$regex<br> ";

		if (!ereg($regex, $ausdruck))
		{
			$echo = "Fehler";	
		}
		else
			echo "OK";
			
		

?>
						<p><a href="status.php">-> status</a></p>
					</td>
				</tr>
				<tr>
					<td>
						<form action="regex.php" method="get" name="Formular">
							<p></p>
							<p>Zu testender Ausdruck:<br>
								<textarea name="ausdruck" rows="7" cols="61"></textarea></p>
							<p><input type="submit" name="submitButtonName" value="Abschicken"></p>
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<p></p>
					</td>
				</tr>
			</table>
		</div>
		<p></p>
		<p></p>
	</body>

</html>