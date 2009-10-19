<?php
// Foot including last date of change of current task and support-mail-address
		$currentFile = $_SERVER["SCRIPT_NAME"];
		$parts = Explode('/', $currentFile);
		$currentFile = $parts[count($parts) - 1]; 				
		print "<p>Aktualisiert am ".date("d.m.Y", filemtime($currentFile))." von <a href=\"mailto:".$admin_mailaddress."\">".$admin_name."</a></p>";
?>