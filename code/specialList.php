<?php
include("functions/checkData.php");

if(isset($_POST['typ'])){
	$_SESSION['typ']=$_POST['typ'];
}
$typ = $_SESSION['typ'];
checkText($typ,true, true);

include "config/config.php";
$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$typName = array("Film","Serie","Sonstiges");
$typNameMultiple = array("Filme","Serien","Sonstiges");

if($typ<=1){
	$typ = $typName[$typ];
	
	$sql ="SELECT COUNT(*) as numRows FROM dvds as t1 ";
	$sql .="join type as t2 on ";
	$sql .="(t2.type like :value ";
	$sql .="AND t1.type like t2.tid)";
	
	$stmt = $dbc->prepare($sql);
	
	$typ = "%".$typ."%";
	
	$stmt->bindParam(':value',$typ);	
	$stmt->execute();
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	$numRows = $data['numRows'];
		
	$sql ="SELECT t1.mid, t1.title, t1.genre, t1.cover FROM dvds as t1 ";
	$sql .="join type as t2 on ";
	$sql .="(t2.type like :value ";
	$sql .="AND t1.type like t2.tid) ORDER BY t1.title LIMIT ".$_SESSION['list_counter'].",".$Number_Of_List_Entries;
	$stmt = $dbc->prepare($sql);
	
	$typ = "%".$typ."%";
	
	$stmt->bindParam(':value',$typ);	
}
else{
	$typ1 = $typName[0];
	$typ2 = $typName[1];
	
	$sql ="SELECT COUNT(*) as numRows FROM dvds as t1 ";
	$sql .="join type as t2 on ";
	$sql .="(t2.type NOT like :value1 ";
	$sql .="AND t2.type NOT LIKE :value2 ";
	$sql .="AND t1.type like t2.tid)";
	
	$stmt = $dbc->prepare($sql);
	
	$typ1 = "%".$typ1."%";
	$typ2 = "%".$typ2."%";
	
	$stmt->bindParam(':value1',$typ1);
	$stmt->bindParam(':value2',$typ2);
	$stmt->execute();
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	$numRows = $data['numRows'];
		
	$sql ="SELECT t1.mid, t1.title, t1.genre, t1.cover FROM dvds as t1 ";
	$sql .="join type as t2 on ";
	$sql .="(t2.type NOT like :value1 ";
	$sql .="AND t2.type NOT LIKE :value2 ";
	$sql .="AND t1.type like t2.tid) ORDER BY t1.title LIMIT ".$_SESSION['list_counter'].",".$Number_Of_List_Entries;
	$stmt = $dbc->prepare($sql);
	
	$typ1 = "%".$typ1."%";
	$typ2 = "%".$typ2."%";
	
	$stmt->bindParam(':value1',$typ1);
	$stmt->bindParam(':value2',$typ2);
}

echo "<h2>".$typNameMultiple[$_SESSION['typ']]."</h2>";

$query_result=$stmt->execute();
$final_list= $stmt->fetchAll();
$num=count($final_list);

include "list2.php";
?>