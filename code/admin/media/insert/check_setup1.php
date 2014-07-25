<?php
include "functions/checkData.php";
$_POST=str_replace("\'","'",$_POST);
$_POST=str_replace('\"','"',$_POST);

checkText($_POST['title'], true);

if($_POST['newgenre']==""){
	checkSelect($_POST['genre'], true);
}
else{
	checkSelect($_POST['genre']);
	checkText($_POST['newgenre']);
}

if($_POST['newpublisher']==""){
	checkSelect($_POST['publisher'], true);
}
else{
	checkSelect($_POST['publisher']);
	checkText($_POST['newpublisher']);
}

if($_POST['newdirector']==""){
	checkSelect($_POST['director'], true);
}
else{
	checkSelect($_POST['director']);
	checkText($_POST['newdirector']);
}

unset($_POST['check'], $_POST['setup']);

$_SESSION['admin']['setup1']=$_POST;
?>