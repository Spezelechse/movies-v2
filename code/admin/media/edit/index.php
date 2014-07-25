Medium bearbeiten
<?php
include "config/config.php";
include "functions/mysql_prepared.php";
include "functions/prepare_data.php";
include "functions/checkData.php";

$setup_pages = array("list.php", "edit.php", "update.php");

if(isset($_POST['setup'])&&ctype_digit($_POST['setup'])){
	$_SESSION['admin']['setup']=$_POST['setup'];
	$_SESSION['admin']['setup-typ']="edit";
}
else{
	if(!isset($_SESSION['admin']['setup'])||$_SESSION['admin']['setup-typ']!="edit"){
		$_SESSION['admin']['setup']=0;
		$_SESSION['admin']['setup-typ']="edit";
	}
}


include "admin/media/edit/".$setup_pages[$_SESSION['admin']['setup']];
?>