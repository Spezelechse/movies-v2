<?php
include("functions/checkData.php");

if(isset($_POST['searchValue'])){
	$_SESSION['searchValue']=$_POST['searchValue'];
}
$searchValue=$_SESSION['searchValue'];

checkText($searchValue);

include "config/config.php";
$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "SELECT COUNT(*) as numRows FROM dvds WHERE title LIKE :value";

$stmt = $dbc->prepare($sql);

$searchValue = "%".$searchValue."%";

$stmt->bindParam(':value',$searchValue);	
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$numRows = $data['numRows'];

$sql = "SELECT * FROM dvds WHERE title LIKE :value ORDER BY title LIMIT ".$_SESSION['list_counter'].",".$Number_Of_List_Entries;
$stmt = $dbc->prepare($sql);

$searchValue = "%".$searchValue."%";

$stmt->bindParam(':value',$searchValue);

$query_result=$stmt->execute();
$final_list= $stmt->fetchAll();
$num=count($final_list);
$search = true;

include "list2.php";
?>
<br /><br />