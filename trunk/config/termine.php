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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Setup Schritt 2</title>
	<meta name="author" content="Jochen Koubek">
	<!-- Date: 2009-09-28 -->
	
	<script type="text/javascript">

		function deleteRow(){
		  var tab = document.getElementById("tabelle");
		  letzteZeile = tab.rows.length;
		  tab.deleteRow(letzteZeile-1);
		}
		
		function addRow()
		{ /* A quick example on how to add and delete table rows. Enjoy coding! */ 

		  	var tab = document.getElementById("tabelle");
		  	var newTR = document.createElement("tr");
		  	zeile = tab.rows.length;
			
			for (j=1;j<=7;j++) {
				var newTD = document.createElement("td");
				inhalt = tab.firstChild.lastChild.previousSibling.childNodes[j-1].firstChild.value;
				switch (j) {
					case 1: groesse="3";
						inhalt++;
						break;
					case 2: groesse="3";
						break;
					case 5: groesse="3";
						break;
					default: groesse="19";
				}
				newTD.innerHTML="<td align='center'><input type='text' name='input"+zeile+j+"' id='"+zeile+j+"' size='"+ groesse +"' value='"+inhalt+"' onkeydown='inputKey(event,"+zeile + j+")'</td>";;
				newTR.appendChild(newTD);
			}
			
			tab.appendChild(newTR);
	
		}
		
		function inputKey(event,id) {
			if (event.keyCode == 38) {
				addWeek(id);
			}
			else if (event.keyCode == 40) {
				subtractWeek(id);
				
			}
			else
				return null;
		}
		
		function addWeek(id) {
			mysqlDatum = document.getElementById(id).value;	
			if (isDate(mysqlDatum)){			
				jsDate = mysqlTimeStampToDate(mysqlDatum);		
				jsDate2 = new Date(jsDate.getTime()+7*864E5); 
				mysqlDatum2 = dateToMysqlTimeStamp(jsDate2);
				document.getElementById(id).value=mysqlDatum2;
			}
		}
		
		function subtractWeek(id) {
			mysqlDatum = document.getElementById(id).value;				
			if (isDate(mysqlDatum)){			
				jsDate = mysqlTimeStampToDate(mysqlDatum);
				jsDate2 = new Date(jsDate.getTime()-7*24 * 60 * 60 * 1000); 			
				mysqlDatum2 = dateToMysqlTimeStamp(jsDate2);
				document.getElementById(id).value=mysqlDatum2;
			}
		}
		
		function isDate(dateStr) {
			var parts = String(dateStr).split(/[- :]/);
			if (typeof parts[1] == "undefined") {
				window.status="Kein Datum";
				return false;
			}	
			else
				return true;
		}
		
		function mysqlTimeStampToDate(timestamp) {
					    var date = new Date("Aug 28, 2008 23:30:00");  //Dummy-Wert, weil ein leerer Konstruktor zu merkwürdigem Verhalten führt.
					    var parts = String(timestamp).split(/[- :]/);  
					    date.setFullYear(parts[0]);  
					    date.setMonth(parts[1] - 1);  
					    date.setDate(parts[2]);  
					    date.setHours(parts[3]);  
					    date.setMinutes(parts[4]);  
					    date.setSeconds(parts[5]);  
					    date.setMilliseconds(0);  
					    return date;  
				// var regex="^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$";
				// 			    var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
				// 			    return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
		  }
		
		function dateToMysqlTimeStamp(date1) {
		  return date1.getFullYear() + '-' +
		    (date1.getMonth() < 9 ? '0' : '') + (date1.getMonth()+1) + '-' +
		    (date1.getDate() < 10 ? '0' : '') + date1.getDate() + ' ' +
			date1.getHours() +':' + date1.getMinutes() + ':' + date1.getSeconds();
		}
		
	</script>
	<link href="../css/webstyle_atmIn.css" rel="stylesheet" media="screen">
</head>
<body>
	

	<?php
		require "../config/connect.php"; 
		import_request_variables("P");

		$arr = $_POST;
		$rs = mysql_query("describe parameter");
		$anzahlTabellenSpalten = mysql_num_rows($rs);

		// Debug: Zeige alle Variablen
		// echo "<p>";
		// foreach ($arr as $key=>$value) {
		// 	  echo $key . " => ".$value."<br />\n";
		// }
		// echo "</p>\n";	

		if(isset($button)) {			
			$anzahlTabellenZeilen = (count($arr)-1)/$anzahlTabellenSpalten;
			echo "<p>Anzahl Aufgaben: $anzahlTabellenZeilen</p>";
			
			if(!mysql_query("DELETE FROM $database.parameter")){
				error_db();
			}
			else { 
				echo "<p>Parameter geputzt<br>Neue Werte eintragen: ";
				for($i=1;$i<=$anzahlTabellenZeilen;$i++) {
					$zeile = "input".$i."1";
					if (isset($$zeile)) {
						updateParameter($i);
						echo "$i...";
					}
					else {
						$anzahlTabellenZeilen++;
					}
				}
				echo "</p>";
			}	
		}
		
		function updateParameter($row) {
			$rs = mysql_query("describe parameter");
			$anzahlTabellenSpalten = mysql_num_rows($rs);
			$query="INSERT INTO parameter VALUES (";
			for ($i=1; $i<=$anzahlTabellenSpalten; $i++) {				
				$variable = "input".$row.($i);
				$query .= "'". $_POST[$variable] . "', ";
			}
			$query = substr($query, 0, -2);
			$query .= ")";
			if (!mysql_query($query)){
				error_db();
				echo "<p>$query</p>";
			}
		}

	?>
	
	<form action="termine.php" method="post" id="formular" name="terminFormular">
		<h1>Termine</h1>
		<p>Wochen mit [Hoch] und [Runter] verändern.</p>
	<?php 

//Tabellenkopf-Namen der Tabelle 'parameter' einzeln in das Array $spaltenName lesen
		print "<table id='tabelle' border='1' cellpadding='3' cellspacing='5'>";
		print "<tr>";
		$rs=mysql_query("desc parameter", $con);
		$anzahlTabellenSpalten = mysql_num_rows($rs);
		for ($i=1; $i<=$anzahlTabellenSpalten; $i++) {
			$rs_array=mysql_fetch_row($rs);
			$spaltenName[$i]=$rs_array[0];
			print "<th><b>$spaltenName[$i]</b></th>";
		}	
		print "</tr>\n";
		
//Daten fuer die Termine aus Tabelle 'parameter' lesen
		$rs = mysql_query("SELECT * FROM parameter", $con);
		$anzahlAufgaben = mysql_num_rows($rs);
		for($i=1;$i<=$anzahlAufgaben;$i++) {
			print "<tr id='$i'>";
			$row = mysql_fetch_row($rs);
			for ($j=1;$j<=$anzahlTabellenSpalten;$j++){
				print "<td><input type='text' name='input$i$j' id='$i$j' size='".strlen($row[$j-1])."' value='".$row[$j-1]."' onkeydown='inputKey(event,$i$j)' /></td>";
			}
			print "</tr>\n";
		}	
		print "</table>";
	?>

<p>	
	<a href="javascript:deleteRow()">Letzten Termin l&ouml;schen</a> | 
	<a href="javascript:addRow()">Neuen Termin einf&uuml;gen</a> |
	<a href="javascript:document.terminFormular.submit()">Alle eintragen</a>	
</p>
	<input type="hidden" name="button" value="send">
	</form>

<p>Weiter zum <a href='../admin'>Admin-Bereich</a></p>
	
</body>
</html>
