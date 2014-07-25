<?php

// Hier wird die Session ID wieder gelöscht und es geht zurueck auf die Startseite
session_destroy();
forward("index.php");
?>