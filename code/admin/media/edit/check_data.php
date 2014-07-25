<?php
$_POST=str_replace("\'","'",$_POST);
$_POST=str_replace('\"','"',$_POST);

checkText($_POST['med_title'],true);

checkText($_POST['med_content'],true);

checkText($_POST['roles'],true);

checkText($_POST['actors'],true);

checkText($_POST['borrower']);

$fsk=$_POST['fsk'];
checkText($fsk,true,true);
if(!($fsk==0||$fsk==6||$fsk==12||$fsk==16||$fsk==18)){
	die("manipulation detectet fsk");
}

$prem_day=$_POST['prem_day'];
checkText($prem_day,true,true);
if($prem_day<=0||$prem_day>31){
	die("manipulation detectet prem day");
}

$prem_month=$_POST['prem_month'];
checkText($prem_month,true,true);
if($prem_month<=0||$prem_month>12){
	die("manipulation detectet prem mon");
}

$prem_year=$_POST['prem_year'];
checkText($prem_year,true,true);
if($prem_year<=1940||$prem_year>date("Y")){
	die("manipulation detectet prem year");
}

if($_POST['newtype']==""){
	checkSelect($_POST['type'],true);
}
else{
	checkSelect($_POST['type']);
	checkText($_POST['newtype']);
}

$disks=$_POST['disks'];
checkText($disks,true,true);
if(($disks<1||$disks>99)){
	die("manipulation detectet");
}

if($_POST['newowner']==""){
	checkSelect($_POST['owner'],true);
}
else{
	checkSelect($_POST['owner']);
	checkText($_POST['newowner']);
}

$ooc=$_POST['original_or_copy'];
checkText($ooc,true,true);
if($ooc!=0&&$ooc!=1){
	die("manipulation detectet");
}

$dob=$_POST['dvd_or_bluray'];
checkText($dob,true,true);
if($dob!=0&&$dob!=1&&$dob!=2){
	die("manipulation detectet");
}

checkText($_POST['length'],true,true);

if($_POST['newgenre']==""){
	checkSelect($_POST['genre'],true);
}
else{
	checkSelect($_POST['genre']);
	checkText($_POST['newgenre']);
}

if($_POST['newpublisher']==""){
	checkSelect($_POST['publisher'],true);
}
else{
	checkSelect($_POST['publisher']);
	checkText($_POST['newpublisher']);
}

if($_POST['newdirector']==""){
	checkSelect($_POST['director'],true);
}
else{
	checkSelect($_POST['director']);
	checkText($_POST['newdirector']);
}

if($_FILES['coverimg']['name']!=""){
	$coverSplit=explode(".",$_SESSION['admin']['cover']);
	
	if($_POST['med_title']==$_SESSION['admin']['oldTitle']){
		checkImage($_FILES['coverimg'], false, $coverSplit[0], true);
	}
	else{
		$imgName = $_POST['med_title'];
		$imgName = str_replace(" ","_",$imgName);
		$imgName = preg_replace("/[ß-üÀ-Ü\.\?\(\)\/\\\|]|:|,|#|!|'/","",$imgName);
		
		checkImage($_FILES['coverimg'], false, $imgName);
	}
}
?>