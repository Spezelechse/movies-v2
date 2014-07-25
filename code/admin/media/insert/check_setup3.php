<?php
include "functions/checkData.php";
$_POST=str_replace("\'","'",$_POST);
$_POST=str_replace('\"','"',$_POST);

$ooc=$_POST['original_or_copy'];
$dob=$_POST['dvd_or_bluray'];
$disks=$_POST['disks'];

if((isset($_POST['type'])&&$_POST['type']!="")||(isset($_POST['newtype'])&&$_POST['newtype']!="")){
	if(isset($_POST['type'])&&$_POST['type']!=""){
		checkSelect($_POST['type'], true);
	}
	else{
		checkText($_POST['newtype'], true);
	}
}
else{
	die("manipulation detectet");
}

if((isset($_POST['owner'])&&$_POST['owner']!="")||(isset($_POST['newowner'])&&$_POST['newowner']!="")){
	if(isset($_POST['owner'])&&$_POST['owner']!=""){
		checkSelect($_POST['owner'], true);
	}
	else{
		checkText($_POST['newowner'], true);
	}
}
else{
	die("manipulation detectet");
}

if($ooc!=0&&$ooc!=1){
	die("manipulation detectet");
}
if($dob!=0&&$dob!=1&&$dob!=2){
	die("manipulation detectet");
}
if(($disks<1||$disks>99)&&$disks!=""){
	die("manipulation detectet");
}

$imgName = $_SESSION['admin']['setup1']['title'];
$imgName = str_replace(" ","_",$imgName);
$imgName = preg_replace("/[ß-üÀ-Ü\.\?\(\)\/\\\|]|:|,|#|!|'/","",$imgName);

checkImage($_FILES['coverimg'], true, $imgName);

unset($_POST['check'], $_POST['setup']);

$_SESSION['admin']['setup3']=$_POST;
?>