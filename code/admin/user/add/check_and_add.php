<?php
include("functions/checkData.php");

if(isset($_POST['setup'])){
	checkText($_POST['name'], true, false, true);
	checkText($_POST['surname'], true, false, true);
	checkText($_POST['username'], true, false, true);
	checkPassword($_POST['password'],true);
	checkPassword($_POST['password_rwd'],true);
	checkSelect($_POST['rights'], false, false);
	
	if($_POST['password']!=$_POST['password_rwd']){
		die("manipulation detected pwd equal");
	}

	$rights = array("0","0","0","0","0","0","0");
	
	if($_POST['rights']!=""&&isset($_POST['rights'])){
		foreach($_POST['rights'] as $right){
			$rights[$right]=1;	
		}
	}
	
	$rights = implode("",$rights);
	$rights = $rights;

	include("config/config.php");
	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	$dbc->query("SET NAMES 'utf8'");
	
	$sql = "INSERT INTO user (username,pword,surname,name) VALUES (:username,:pword,:surname,:name)";
	$stmt = $dbc->prepare($sql);
	
	$stmt->bindParam(':username', $_POST['username']);
	$pwd=md5($_POST['password']);
	$stmt->bindParam(':pword', $pwd);
	$stmt->bindParam(':surname', $_POST['surname']);
	$stmt->bindParam(':name', $_POST['name']);

	
	if($stmt->execute()){
		$id = $dbc->lastInsertId();
		$dbc->query("UPDATE user SET rights = b'".$rights."' WHERE uid=".$id);
		
		echo "<br><br>Erfolgreich hinzugefügt";
		unset($_SESSION['admin']);
		forward("index.php",2000);	
	}
	else{
		$err=$stmt->errorInfo();
		
		echo "<br><br>Es ist ein Fehler aufgetreten<br><br>";
		echo $err[2];
	}
}
?>