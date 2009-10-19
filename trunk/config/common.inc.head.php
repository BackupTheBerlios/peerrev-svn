<!--
/* Copyright 2007 Andrea Knaut - v0.1.2. 

For contributors see contributors.txt.

This file is part of the software 'Review Your Peers'.

'Review Your Peers' is free software; you can redistribute it and/or modify
it under the terms of the MIT License.

You should have received a copy of the MIT License
along with this program. See COPYING.txt.
The license-text is based on http://www.opensource.org/licenses/mit-license.php. */
-->

<?php

/**
  BEGIN CONFIGURATION AREA
Global variables that are used by the php-scripts
  1. you have to enter a valid SMTP-server-address instead of "mailserver.server.com", AUTHENTIFICATION is not supported yet
  2. you have to enter your e-mail-address instead of "username@servername.domain" for bug-reports sent correctly
  3. $aufgabeNummer has to be updated during the course as often as an assignment is finished depending on your course 
  4. $uploadserver - hier bitte den absoluten Pfad für die Upload-Verzeichnisse festlegen - wird in mailen_auftraege.php per Mail verschickt
  5. $peerrevserver - hier bitte den absoluten Pfad für das gesamte Gutachtersystem vergeben

**/
##Timezone
  date_default_timezone_set('Europe/Berlin');
  $datum = date("Y-m-d H:i:s");

##Windows Only: enter mailserver-address you are able to use - check also mailen_auftraege.php
//  ini_set("SMTP","mailserver");