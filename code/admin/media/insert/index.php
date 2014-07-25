<?php
$setup_pages = array("setup1.php", "setup2.php", "setup3.php", "setup4.php", "setup5.php");

if(isset($_POST['setup'])&&ctype_digit($_POST['setup'])){
	$_SESSION['admin']['setup']=$_POST['setup'];
	$_SESSION['admin']['setup-typ']="add";
}
else{
	if(!isset($_SESSION['admin']['setup'])||$_SESSION['admin']['setup-typ']!="add"){
		$_SESSION['admin']['setup']=0;
		$_SESSION['admin']['setup-typ']="add";
	}
}


include "admin/media/insert/".$setup_pages[$_SESSION['admin']['setup']];
?>