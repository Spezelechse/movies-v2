<?php
include "functions/checkData.php";
$_POST=str_replace("\'","'",$_POST);
$_POST=str_replace('\"','"',$_POST);

$actors=$_POST['actors'];
$roles=$_POST['roles'];
$fsk=$_POST['fsk'];
$prem_day=$_POST['prem_day'];
$prem_month=$_POST['prem_month'];
$prem_year=$_POST['prem_year'];
$content=$_POST['dvd_content'];

checkText($content, true);
checkText($fsk, true, true);
checkText($_POST['length'], true, true);
checkText($actors, true);
checkText($prem_day, true, true);
checkText($prem_month, true, true);
checkText($prem_year, true, true);
checkText($roles, true);

if(!($fsk==0||$fsk==6||$fsk==12||$fsk==16||$fsk==18)){
	die("manipulation detectet fsk");
}
if($prem_day<=0||$prem_day>31){
	die("manipulation detectet prem day");
}
if($prem_month<=0||$prem_month>12){
	die("manipulation detectet prem mon");
}
if($prem_year<=1940||$prem_year>date("Y")){
	die("manipulation detectet prem year");
}

$actorsArray = preg_split("[\n]", $actors);
$rolesArray = preg_split("[\n]", $roles);

$diff=count($rolesArray)-count($actorsArray);

if($diff!=0){
	die("manipulation detectet diff");
}

unset($_POST['check'], $_POST['setup']);

$_SESSION['admin']['setup2']=$_POST;
?>