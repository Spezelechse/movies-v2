Benutzerdaten ändern
<?php
include "config/config.php";
include "functions/checkData.php";

$setup_pages = array("edit.php", "check_and_update.php");

if(isset($_POST['setup'])&&ctype_digit($_POST['setup'])){
	$_SESSION['admin']['setup']=$_POST['setup'];
	$_SESSION['admin']['setup-typ']="usereditown";
}
else{
	if(!isset($_SESSION['admin']['setup'])||$_SESSION['admin']['setup-typ']!="usereditown"){
		$_SESSION['admin']['setup']=0;
		$_SESSION['admin']['setup-typ']="usereditown";
	}
}


include "admin/user/edit_own/".$setup_pages[$_SESSION['admin']['setup']];
?>