<?php
checkText($_POST['name'], true, false, true);
checkText($_POST['surname'], true, false, true);
checkText($_POST['username'], true, false, true);
checkPassword($_POST['password']);
checkPassword($_POST['password_rwd']);
checkPassword($_POST['password_old']);

if($_POST['password']!=$_POST['password_rwd']){
	die("manipulation detected pwd equal");
}


$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

if($_POST['password']!=""){
	$sql = "SELECT pword FROM user WHERE uid = :id";
	$stmt = $dbc->prepare($sql);
	
	$stmt->bindParam(':id', $_SESSION['admin']['user-id']);
	
	if($stmt->execute()){
		$data = $stmt->fetch(PDO::FETCH_BOTH);
		if($data['pword']!=md5($_POST['password_old'])){
			echo "Das alte Passwort stimmt nicht, bitte korrigieren !!!";
			forward("index.php",2000);
		}
	}
	else{
		die("Beim auslesen der Benutzerdaten ist ein Fehler aufgetreten");
	}	
	
	$sql = "UPDATE user SET username=:username, pword=:pword, surname=:surname, name=:name WHERE uid=".$_SESSION['admin']['user-id'].";";
}
else{
	$sql = "UPDATE user SET username=:username, surname=:surname, name=:name WHERE uid=".$_SESSION['admin']['user-id'].";";
}
$stmt = $dbc->prepare($sql);

$stmt->bindParam(':username', $_POST['username']);
if($_POST['password']!=""){
	$pwd=md5($_POST['password']);
	$stmt->bindParam(':pword', $pwd);
}
$stmt->bindParam(':surname', $_POST['surname']);
$stmt->bindParam(':name', $_POST['name']);


if($stmt->execute()){
	echo "<br><br>Erfolgreich geupdatet";
	session_destroy();
	forward("index.php",2000);	
}
else{
	$err=$stmt->errorInfo();
	
	echo "<br><br>Es ist ein Fehler aufgetreten<br><br>";
	echo $err[2];
}
?>