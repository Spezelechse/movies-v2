<?php
session_start();
if(isset($_SESSION['user']['downloadFile'])&&$_SESSION['user']['downloadFile']!=""){
	header('Content-Type: text/plain; charset=UTF-8');
	header("Content-Disposition: attachment; filename=\"".$_SESSION['user']['downloadFile']."\"");
	
	readfile("download/".$_SESSION['user']['downloadFile']);
	
	unlink("download/".$_SESSION['user']['downloadFile']);
}
?>