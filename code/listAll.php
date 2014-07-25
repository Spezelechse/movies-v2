<?php
unset($_SESSION['admin-content'],$_SESSION['admin']);

include "config/config.php";
$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql ="SELECT COUNT(*) as numRows FROM dvds";

$stmt = $dbc->prepare($sql);

$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$numRows = $data['numRows'];

$sql = "SELECT mid,title,genre, cover FROM dvds ORDER BY title LIMIT ".$_SESSION['list_counter'].",".$Number_Of_List_Entries;

$stmt = $dbc->prepare($sql);
$query_result=$stmt->execute();
$final_list= $stmt->fetchAll();
$num=count($final_list);

include "list2.php";
?>