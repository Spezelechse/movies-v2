<?php
$colNames= array("actors","type","genre","publisher","director","fsk","YEAR(premiere)");
$detailNames= array("Darsteller","Art","Genre","Publisher","Regisseur","Altersfreigabe","Premierejahr");
if(isset($_POST['detailData'])){
	$_SESSION['detailData']=$_POST['detailData'];
}
$detailData = explode(",",$_SESSION['detailData']);

include "functions/checkData.php";
include "config/config.php";
checkText($detailData[0],true);
checkText($detailData[1],true,true);
checkText($detailData[2],true,true);

echo "<h2>Einträge mit ".$detailNames[$detailData[2]].": ".$detailData[0]."</h2>";

$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql ="SELECT COUNT(*) as numRows FROM dvds WHERE ";
$sql .=$colNames[$detailData[2]]." like CONCAT( :value, ',%' ) ";
$sql .="OR ".$colNames[$detailData[2]]." like CONCAT( '%,', :value, ',%' ) ";
$sql .="OR ".$colNames[$detailData[2]]." like CONCAT( '%,', :value) ";
$sql .="OR ".$colNames[$detailData[2]]." like :value";

$stmt = $dbc->prepare($sql);

$stmt->bindParam(':value',$detailData[1]);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$numRows = $data['numRows'];

$sql ="SELECT mid, title, genre, cover FROM dvds WHERE ";
$sql .=$colNames[$detailData[2]]." like CONCAT( :value, ',%' ) ";
$sql .="OR ".$colNames[$detailData[2]]." like CONCAT( '%,', :value, ',%' ) ";
$sql .="OR ".$colNames[$detailData[2]]." like CONCAT( '%,', :value) ";
$sql .="OR ".$colNames[$detailData[2]]." like :value ORDER BY title LIMIT ".$_SESSION['list_counter'].",".$Number_Of_List_Entries;	

$stmt = $dbc->prepare($sql);

$stmt->bindParam(':value',$detailData[1]);

$query_result=$stmt->execute();
$final_list= $stmt->fetchAll();
$num=count($final_list);

include "list2.php";
?>