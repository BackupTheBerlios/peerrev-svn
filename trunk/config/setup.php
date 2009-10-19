<!--
--/* Copyright 2005, 2006 Jochen Koubek. 2007 Andrea Knaut. - v0.1.2. 
--For contributors see contributors.txt.

--This file is part of the software 'Gutachtersystem'.

--'Gutachtersystem' is free software; you can redistribute it and/or modify
--it under the terms of the MIT License.

--You should have received a copy of the MIT License
--along with this program. See COPYING.txt.
--The license-text is based on http://www.opensource.org/licenses/mit-license.php. */
-->

<!--
Diese Datei legt die RYP-Datenbank und den Nutzer an, über den später die Transaktionen ablaufen werden.
Die Informationen für den DB-Root müssen vorliegen, können aber auch aus einem Webformular gewonnen werden.

Gleiches gilt für alle Variablen im Kopfteil. Sie werden zunächst fest eingetragen, um später über ein Webinterfaceübernommen zu werden.

-->
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Setup Schritt 1</title>
	<meta name="author" content="Jochen Koubek">
	<link href="../css/webstyle_admin.css" rel="stylesheet" media="screen">
</head>
<body>
	<h1>Einrichten des Systems</h1>
	
<?php
	import_request_variables("P");
	date_default_timezone_set('Europe/Berlin');


//*********************************************************************************
// Testen der setup-Variablen.
//*********************************************************************************

$dbms = "127.0.0.1:3306"; 			// IP-address or url of dbms-server
$login = "$loginRoot";				// database-user with rights to insert, update and delete tables
$password = "$passwordRoot";		// password of that user
$con = mysql_connect($dbms, $login, $password);

if (!$con) {
	exit("<p>Fehler bei der Server-Verbindung. Das Setup wurde abgebrochen.</p>");
}
else { 
	echo "<p>Verbindung zum MySQL-Server erfolgreich hergestellt.</p>";
	$error = false;

//*********************************************************************************
// Überprüfe die Eingaben. Nicht fool-proof aber einigermaßen hilfreich.
//*********************************************************************************

	if ($database == "")
		exit ("<p>Bitte den Namen f&uuml;r eine Datenbank angeben. Das Setup wurde abgebrochen.</p>");
	 
	if (mysql_select_db ($database, $con)) {
		exit ("<p>Die Datenbank <i>$database</i> gibt es bereits. Ein &Uuml;berschreiben kann zu Datenverlust f&uuml;hren. Das Setup wurde  abgebrochen.</p>");
	 }
			
	if (($loginUser == "") OR ($passwordUser == ""))
		exit ("<p>Bitte einen Nutzer und Passwort f&uuml;r die Nutzung der Datenbank <i>$database</i> angeben. Das Setup wurde abgebrochen.</p>");

	if (mysql_num_rows(mysql_query("SELECT user FROM mysql.user WHERE user='$loginUser'")) > 0)
		exit ("<p>Den Benutzer <i>$loginUser</i> gibt es bereits. Die Zuordnung zu einer anderen Datenbank kann zu Integrit&auml;tsproblemen f&uuml;hren. Das Setup wurde abgebrochen.</p>");
		
	if (($adminName == "") OR ($adminEmail == ""))
		exit ("<p>Bitte einen Kursleiter und seine Emailadresse angeben. Das Setup wurde abgebrochen.</p>");
	
	if ($adminPass == "")
		exit ("<p>Ein Passwort ist notwendig, um den Administrator-Bereich zu sch&uuml;tzen.</p>");
	
	if ($anfangsDatum == "")
		exit ("<p>Ein Anfangsdatum ist notwendig. Ansonsten wird der Editieraufwand zu gross. Das Setup wurde abgebrochen</p>");

	if(!strtotime($anfangsDatum))
		exit ("<p>Das Anfangsdatum hat kein verst&auml;ndliches Format."); 

	$anfangsDatum = date("Y-m-d H:i:s", strtotime($anfangsDatum));
		
	if ($aufgabenAnzahl == "")
		exit ("<p>Bitte die (gesch&auml;tzte) Aufgabenzahl angeben. Ansonsten wird der Editieraufwand zu gross. Das Setup wurde abgebrochen.</p>");

//*********************************************************************************
// Lege die .htaccess-Dateien an.
//*********************************************************************************

	$parentDir = dirname(dirname($_SERVER['SCRIPT_FILENAME']));

	$dir = "admin"; 
	$user = "admin"; 

	// Daten für .htaccess erstellen 
	$htaccess = 'AuthType Basic
	AuthUserFile '.$parentDir.'/'.$dir.'/.htpasswd 
	AuthName "Administration"
	order deny,allow
	allow from all
	require valid-user'; 

	// Daten für .htpasswd erstellen 
	$htpasswd = $user.':'.crypt($adminPass, substr(md5(uniqid(rand())), 0, 2)); 

	// .htaccess erstellen 
	print "<p><b>htaccess</b><br />";
	$handle = fopen($parentDir.'/'.$dir.'/.htaccess', 'w'); 
	if (fwrite($handle, $htaccess))
		print ".htaccess geschrieben.<br />";
	fclose($handle); 

	// .htpasswd erstellen 
	$handle = fopen($parentDir.'/'.$dir.'/.htpasswd', 'w'); 
	if (fwrite($handle, $htpasswd))
		print ".htpasswd geschrieben.";
	fclose($handle); 
	print "</p>";


//*********************************************************************************
//Lege die Datenbank '$database' an
//*********************************************************************************
	print "<p><b>Datenbank $database</b><br />";
	  if (!mysql_select_db ($database, $con)) {
		print "Datenbank <i>$database</i> wird angelegt ... ";
		$query="CREATE DATABASE `$database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
		if (!mysql_query($query, $con)){
			print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
			$error = true;	
		}
		else {
			print "Datenbank <i>$database</i> ist angelegt ... ";
		}
	  }
	
	  if (mysql_select_db ($database, $con)) {
		print ("Datenbank <i>$database</i> ist da.");
	  }
	  else {
		$error = true;
	  }
	print "</p>";
	

//*********************************************************************************
//Lege den Nutzer '$loginUser' mit Passwort '$user_pw' an
//*********************************************************************************

	if (!$error) {
		print "<p><b>Nutzer $loginUser</b><br />";
		print ("Nutzer <i>$loginUser</i> wird angelegt ... ");
		$query = "CREATE USER '$loginUser'@'localhost' IDENTIFIED BY '$passwordUser'"; 
		if (!mysql_query($query, $con))
			print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");	

		$query = "REVOKE ALL PRIVILEGES ON *.* FROM '$loginUser'@'localhost'";
		if (!mysql_query($query, $con))
			print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");	

		$query = "REVOKE GRANT OPTION ON * . * FROM '$loginUser'@'localhost'";	
		if (!mysql_query($query, $con))
			print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");	

		$query = "GRANT SELECT,INSERT,UPDATE,DELETE ON $database.* TO '$loginUser'@'localhost' IDENTIFIED BY '$passwordUser' WITH  MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;";
		if (!mysql_query($query, $con)) {
			print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");	
			$error = true;
		}
	}

	if (mysql_num_rows(mysql_query("select user from mysql.user where user='$loginUser'")) != 0) {
		print ("Nutzer <i>$loginUser</i> ist da.");
	}
	else {
		$error = true;
	}
	  print "</p>";		

//*********************************************************************************
// commin.inc.php anlegen
//*********************************************************************************

	if (!$error) {
		print "<p><b>common.inc.php</b><br />";
		if (file_exists("common.inc.php")){
			if (unlink("common.inc.php")){
				echo "Alte </i>common.inc.php</i> gel&ouml;scht ... ";
			}
		}
		$line = file_get_contents("common.inc.head.php");
		
		$line .= "##webadmin-e-mail\n";
		$line .= "  \$admin_mailaddress='$adminEmail';\n";
		$line .= "  \$admin_name = '$adminName';\n\n";

		$line .= "##Server\n";		
		// $line .= "  \$uploadDir = '".substr(getcwd(),0,-6)."upload/';\n"; // //für den internen Gebrauch
 		$line .= "  \$uploadDir = '".dirname(getcwd())."/upload';\n"; // //für den internen Gebrauch
		$line .= "  \$uploadServer = 'http://".$_SERVER['SERVER_NAME']."/".basename(dirname(getcwd()))."/upload';\n"; //für den externen Gebrauch in den Gutachteraufträgen
		$line .= "  \$server = 'http://".$_SERVER['SERVER_NAME']."/".basename(dirname(getcwd()))."';\n";
		$line .= "##Datenbank-Login\n";
		$line .= "  \$dbms = \"127.0.0.1:3306\";\n"; // IP-address or url of dbms-server
		$line .= "  \$database = \"$database\";\n"; // name of the database
		$line .= "  \$login = \"$loginUser\";\n"; //User with restricted access rights
		$line .= "  \$password = \"$passwordUser\";\n\n"; // password of that user
		$line .= file_get_contents("common.inc.foot.php");

		$connectFile = fopen("common.inc.php", "w"); //Die Datei muss natürlich auch lesbar sein.
	 	if(!$connectFile){
			echo "<p>Fehler beim &Ouml;ffnen</p>";
			$error = true;
		}
		if (!fwrite($connectFile, $line)) {
			echo "<p>$line</p>";
			echo "<p>Fehler beim Schreiben</p>";
			$error = true;
		}
		else {
			echo " Neue common.inc.php geschrieben";
		}
		fclose($connectFile);
		print "</p>";
	}


//*********************************************************************************
// Upload- und Aufgabenordner anlegen. Nur für *ix-Systeme
//*********************************************************************************

	if (!$error) {
		print "<p>";
		print "<b>Upload-Ordner</b><br />";
		if (!file_exists("../upload")) {
			echo "Versuche, Upload-Ordner anzulegen...";
			if (!mkdir ("../upload", 0777)){	
				echo "Fehler beim Anlegen des Upload-Ordners.</p>";
				$error = true;
			}
			else { 
				echo "Upload-Ordner angelegt.<br />";
			}
		}
		else {
			if (rmdir("../upload")){
				echo "Alter </i>Aufgabenordner</i> wurde gel&ouml;scht.<br />";
			}
			else {
				echo "Alter Upload-Ordner wurde nicht gel&ouml;scht.<br />";
			}
		}
		for ($i=1;$i<=$aufgabenAnzahl;$i++) {
			if (!file_exists("../upload/aufgabe$i")) {
				if (!mkdir ("../upload/aufgabe$i", 0777)) {
					echo "Fehler beim Anlegen des aufgabe$i-Ordners";
					$error = true;
				}
				else
					echo "Ordner f&uuml;r aufgabe$i wurde angelegt...";
			}
		}
		print "</p>";
	}


//*********************************************************************************
// Lege ggfs. Tabellen an	
//*********************************************************************************

if (!$error) {
	print "<p><b>Tabellen</b><br />";
	if (!mysql_query("DROP TABLE IF EXISTS $database.parameter;")) {
		print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
		$error=$true;
	}
	
	$query="CREATE TABLE IF NOT EXISTS $database.parameter (
	  		aufgabeNummer int(3) NOT NULL,
	  		dateiendung varchar(5) NOT NULL default '',
	  		aufgabeStellenDatum datetime NOT NULL default '0000-00-00 00:00:00',
	  		aufgabeAbgebenDatum datetime NOT NULL default '0000-00-00 00:00:00',
	  		gutachtenAnzahl int(2) NOT NULL default '0',
	  		gutachtenVerteilenDatum datetime NOT NULL default '0000-00-00 00:00:00',
	  		gutachtenAbgebenDatum datetime NOT NULL default '0000-00-00 00:00:00',
	  		PRIMARY KEY  (aufgabeNummer)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	if (!mysql_query($query, $con)) {
		print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
		$error=$true;
	}
	else {
		print("Tabelle <i>parameter</i> ist angelegt...");		
	}

//*********************************************************************************
	$query="CREATE TABLE IF NOT EXISTS $database.auftraege (
	  		id int(5) NOT NULL auto_increment,
	  		aufgabenr int(2) default NULL,
	  		gutachter int(10) default NULL,
	  		dokument varchar(32) NOT NULL default '',
	  		gutachten mediumtext NOT NULL,
	  		punkte int(1) NOT NULL default '-1',
	  		datum datetime NOT NULL default '0000-00-00 00:00:00',
	  		PRIMARY KEY  (id)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	if (!mysql_query($query, $con)) {
		print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
		$error=$true;
	}
	else {
		print("Tabelle <i>auftraege</i> ist angelegt...");		
	}

//*********************************************************************************
	$query="CREATE TABLE IF NOT EXISTS $database.bemerkungen (
	  		id int(4) NOT NULL auto_increment,
	  		bemerkung varchar(255) NOT NULL default '',
	  		datum datetime NOT NULL default '0000-00-00 00:00:00',
	  		PRIMARY KEY  (id)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	if (!mysql_query($query, $con)) {
		print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
		$error=$true;
	}
	else {
		print("Tabelle <i>bemerkungen</i> ist angelegt...");		
	}

//*********************************************************************************

	$query="CREATE TABLE IF NOT EXISTS $database.dokumente (
	  		id int(5) NOT NULL auto_increment,
	  		autor int(10) NOT NULL default '0',
	  		dokument varchar(50) NOT NULL default '',
	  		aufgabenr int(2) NOT NULL default '0',
	  		anmerkung mediumtext NOT NULL,
	  		validiert char(1) NOT NULL default 'n',
	  		punkte int(2) NOT NULL default '0',
	  		zusatzpunkte int(2) NOT NULL default '0',
	  		datum datetime NOT NULL default '0000-00-00 00:00:00',
	  		PRIMARY KEY  (id)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	if (!mysql_query($query, $con)) {
		print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
		$error=$true;
	}
	else {
		print("Tabelle <i>dokumente</i> ist angelegt...");		
	}

//*********************************************************************************

	$query="CREATE TABLE IF NOT EXISTS $database.gutachter (
	  		matrikelnr int(10) NOT NULL default '0',
	  		name varchar(50) NOT NULL default '',
	  		email varchar(50) NOT NULL default '',
			gruppe int(2) NOT NULL default '1',
	  		anmerkung mediumtext,
	  		datum datetime NOT NULL default '0000-00-00 00:00:00',
	  		PRIMARY KEY  (matrikelnr)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

		if (!mysql_query($query, $con)) {
			print("<p>" . mysql_errno() . ": " . mysql_error() . "</p>");
			$error=$true;
		}
		else {
			print("Tabelle <i>gutachter</i> ist angelegt.");		
		}
	print "</p>";
}


//*********************************************************************************
//Tabelle Parameter füllen
//*********************************************************************************

  if (!$error) {
	print "<p><b>Initialisation</b><br />";
	$dateiendung = "zip";
	if ($gutachtenAnzahl == "")
		$gutachtenAnzahl = 3;
			
	if ($gruppenAnzahl == "")
		$gruppenAnzahl = 1; 

	$aufgabeStellenDatum = array();
	$aufgabeAbgebenDatum = array();
	$gutachtenVerteilenDatum = array();
	$gutachtenAbgebenDatum = array();

	$aufgabeRhythmus = 1; //Eine Aufgabe pro 1 Woche
	$datumsFormat = "Y-m-j H:i:s";

	for ($i=0;$i<$aufgabenAnzahl;$i++) {
		$aufgabeStellenDatum[$i] = date($datumsFormat, strtotime ( '+'.$i.' week', strtotime ( $anfangsDatum ) ) );
		$aufgabeAbgebenDatum[$i] = date($datumsFormat, strtotime ( '+'.($i+1).' week', strtotime ( $anfangsDatum ) ) );
		$gutachtenVerteilenDatum[$i] = date($datumsFormat, strtotime ( '+'.($i+1).' week' , strtotime ( $anfangsDatum ) ) );
		$gutachtenAbgebenDatum[$i] = date($datumsFormat, strtotime ( '+'.($i+2).' week' , strtotime ( $anfangsDatum ) ) );
	}

	$query="INSERT INTO $database.parameter VALUES ";
		
	for ($i=0;$i<$aufgabenAnzahl;$i++) {
		$query .= "($i+1,'$dateiendung','$aufgabeStellenDatum[$i]','$aufgabeAbgebenDatum[$i]','$gutachtenAnzahl','$gutachtenVerteilenDatum[$i]','$gutachtenAbgebenDatum[$i]'), ";
	}	
	$query = substr($query,0,-2);

	if (!mysql_query($query, $con)) {
		print(mysql_errno() . ": " . mysql_error());
	}
	else {
		print("Die Tabelle <i>Parameter</i> ist mit Terminen gef&uuml;llt.");	
	}
	print "<p>";
	
	echo "<p><b>Konfiguration</b><br />Weiter zu den <a href='termine.php'>Terminen</a></p>";
  }
}

?>
</body>
</html>
