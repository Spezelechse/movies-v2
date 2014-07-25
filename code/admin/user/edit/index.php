Benutzer bearbeiten
<?php
include "config/config.php";
include "functions/checkData.php";

$setup_pages = array("list.php", "edit.php", "check_and_update.php");

if(isset($_POST['setup'])&&ctype_digit($_POST['setup'])){
	$_SESSION['admin']['setup']=$_POST['setup'];
	$_SESSION['admin']['setup-typ']="useredit";
}
else{
	if(!isset($_SESSION['admin']['setup'])||$_SESSION['admin']['setup-typ']!="useredit"){
		$_SESSION['admin']['setup']=0;
		$_SESSION['admin']['setup-typ']="useredit";
	}
}


include "admin/user/edit/".$setup_pages[$_SESSION['admin']['setup']];
?>