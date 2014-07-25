<?php
checkText($_POST['name'], true, false, true);
checkText($_POST['surname'], true, false, true);
checkText($_POST['username'], true, false, true);
checkPassword($_POST['password']);
checkPassword($_POST['password_rwd']);

if($_POST['password']!=$_POST['password_rwd']){
	die("manipulation detected pwd equal");
}

$rights = array("0","0","0","0","0","0","0");

if(isset($_POST['rights'])){
	checkSelect($_POST['rights'], false, false);
	foreach($_POST['rights'] as $right){
		$rights[$right]=1;	
	}
}

$rights = implode("",$rights);
$rights = $rights;

$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

if($_POST['password']!=""){
	$sql = "UPDATE user SET username=:username, pword=:pword, surname=:surname, name=:name, rights=:rights WHERE uid=".$_SESSION['admin']['user-id'].";";
}
else{
	$sql = "UPDATE user SET username=:username, surname=:surname, name=:name, rights=:rights WHERE uid=".$_SESSION['admin']['user-id'].";";
}
$stmt = $dbc->prepare($sql);

$stmt->bindParam(':username', $_POST['username']);
if($_POST['password']!=""){
	$stmt->bindParam(':pword', md5($_POST['password']));
}
$stmt->bindParam(':surname', $_POST['surname']);
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':rights', $rights);


if($stmt->execute()){
	$dbc->query("UPDATE user SET rights = b'".$rights."' WHERE uid=".$_SESSION['admin']['user-id']);
	echo "<br><br>Erfolgreich geupdatet";
	unset($_SESSION['admin']);
	forward("index.php",2000);	
}
else{
	$err=$stmt->errorInfo();
	
	echo "<br><br>Es ist ein Fehler aufgetreten<br><br>";
	echo $err[2];
}
?>