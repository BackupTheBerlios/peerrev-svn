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

<?php 
	import_request_variables("p", "postv_");
	if (strtoupper($postv_input) == "GLOBAL THERMONUCLEAR WAR")
	header('Location: http://deathball.net/notpron/levelone.htm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<link href="../css/webstyle.css" rel="stylesheet" media="screen">
		
		<title>WOPR</title>
		<link href="webstyle.css" rel="stylesheet" media="screen">
		<style type="text/css" media="screen"><!--body{background: black;}
			p { font-family: "Courier New", Courier, Monaco, monospace; color: #00ff00}
			input {background: black; border: 0; color: #00ff00; width: 25em;}
			--></style>
	</head>

	<body class="user" id="bottom" onload="javascript:document.joshua.input.focus()">
		<div align="left">
			<p>Greetings Professor Falken.</p>
			<p>Shall we play a game? </p>
			<form id="FormName" action="bottom.php" method="post" name="joshua">
				<input type="text" name="input" size="84">
			</form>
			
		</div>
	</body>

</html>
