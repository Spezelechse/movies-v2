<?php

// Hier wird die Session ID wieder gel�scht und es geht zurueck auf die Startseite
session_destroy();
forward("index.php");
?>