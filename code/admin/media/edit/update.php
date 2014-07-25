<?php
include("admin/media/edit/check_data.php");

//ACTOR
$newActor = explode(chr(13)."\n", str_replace("\'","'",$_POST['actors']));
$newActorIds = insertOneParam("actor", "act_name", $newActor);

//echo "<br><br>Neue Schauspieler wurden hinzugefügt: ".$newActorIds;

$actorResult = includeNewAndSort(array(), $newActorIds, implode(",",$newActor), $_POST['roles']);

$actorIdsComplete = $actorResult[0];
$rolesComplete = str_replace("\'","'",$actorResult[1]);

//Date
$dateComplete=$_POST['prem_year']."-".$_POST['prem_month']."-".$_POST['prem_day'];

//Type
if($_POST['newtype']!=""&&isset($_POST['newtype'])){
	$newTyp = array($_POST['newtype']);
	$newTypId = insertOneParam("type", "type", $newTyp);
	
	//echo "<br><br>Neue Art wurde hinzugefügt: ".$newTypId;
	
	$typeIdComplete = $newTypId;
}
else{
	$type = explode(",",$_POST['type']);
	$typeIdComplete = $type[0];
}

//Owner
if(isset($_POST['newowner'])&&$_POST['newowner']!=""){
	$newOwner = array($_POST['newowner']);
	$newOwnerId = insertOneParam("owner", "owner", $newOwner);
	
	//echo "<br><br>Neuer Besitzer wurde hinzugefügt: ".$newOwnerId;
	
	$ownerIdComplete = $newOwnerId;
}
else{
	$owner = explode(",",$_POST['owner']);
	$ownerIdComplete = $owner[0];
}

//Borrower
if(isset($_POST['borrower'])&&$_POST['borrower']!=""){
	$newBorrower = array($_POST['borrower']);
	$newBorrowerId = insertOneParam("borrower", "bor_name", $newBorrower);
	
	//echo "<br><br>Neuer Ausleiher wurde hinzugefügt: ".$newBorrowerId;
	
	$borrowerIdComplete = $newBorrowerId;
	$borrowed=1;
}
else{
	$borrowerIdComplete = "";
	$borrowed=0;
}

//Genre
if(isset($_POST['newgenre'])&&$_POST['newgenre']!=""){
	$newGenre = explode(chr(13)."\n",str_replace("\'","'",$_POST['newgenre']));
	$newGenreIds = insertOneParam("genre","genre",$newGenre);
	
	//echo "<br><br>Neue Genre wurden hinzugefügt: ".$newGenreIds;
	
	$genreIdsComplete = includeNewAndSort($_POST['genre'], $newGenreIds, implode(",",$newGenre));
}
else{
	$genreIdsComplete = collectIdsFromSelect($_POST['genre']);
}

//Publisher
if(isset($_POST['newpublisher'])&&$_POST['newpublisher']!=""){
	$newPublisher = explode(chr(13)."\n", str_replace("\'","'",$_POST['newpublisher']));
	$newPublisherIds = insertOneParam("publisher" ,"pub_name", $newPublisher);
	
	//echo "<br><br>Neue Publisher wurden hinzugefügt: ".$newPublisherIds;
	
	$publisherIdsComplete = includeNewAndSort($_POST['publisher'], $newPublisherIds, implode(",",$newPublisher));
}
else{
	$publisherIdsComplete = collectIdsFromSelect($_POST['publisher']);
}

//Director
if(isset($_POST['newdirector'])&&$_POST['newdirector']!=""){
	$newDirector = explode(chr(13)."\n", str_replace("\'","'",$_POST['newdirector']));
	$newDirectorIds = insertOneParam("director", "dir_name", $newDirector);
	
	//echo "<br><br>Neue Regisseure wurden hinzugefügt: ".$newDirectorIds;
	
	$directorIdsComplete = includeNewAndSort($_POST['director'], $newDirectorIds, implode(",",$newDirector));
}
else{
	$directorIdsComplete = collectIdsFromSelect($_POST['director']);
}

//Cover
$coverSplit = preg_split("[\.]",$_SESSION['admin']['cover']);


$oldName = $_SESSION['admin']['cover'];

if($_FILES['coverimg']['name']!=""&&isset($_FILES['coverimg']['name'])){
	if($_POST['med_title']!=$_SESSION['admin']['oldTitle']){
		if(file_exists("cover/".$oldName)){
			unlink("cover/".$oldName);
		}
		if(file_exists("cover_big/".$oldName)){
			unlink("cover_big/".$oldName);
		}
	}
	$coverNameComplete=$_SESSION['admin']['filename'];
}
else if($_POST['med_title']!=$_SESSION['admin']['oldTitle']){
	$oldNameSplit=explode(".",$oldName);
	$newName = $_POST['med_title'];
	$newName = str_replace(" ","_",$newName);
	$newName = preg_replace("/[ß-üÀ-Ü\.\?\(\)\/\\\|]|:|,|#|!|'/","",$newName);
	$newName = $newName.".".$oldNameSplit[1];
	
	if(file_exists("cover/".$newName)){
			$extend=2;
			
			$splitName=explode(".",$newName);
			
			while(file_exists("cover/".$splitName[0]."-".$extend.".".$splitName[1])){
				$extend++;									 
			}
			
			$splitName[0]=$splitName[0]."-".$extend;
			$newName=implode(".",$splitName);
	}
	
	if(file_exists("cover/".$oldName)){
		rename("cover/".$oldName,"cover/".$newName);
	}
	if(file_exists("cover_big/".$oldName)){
		rename("cover_big/".$oldName,"cover_big/".$newName);
	}
	$coverNameComplete=$newName;
}
else{
	$coverNameComplete=$oldName;
}

//DVD
$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "UPDATE dvds SET title=:title, original_or_copy=:original_or_copy, genre=:genre, publisher=:publisher, director=:director,length=:length, fsk=:fsk, premiere=:premiere, num_disk=:num_disk, content=:content, cover=:cover, actors=:actors, roles=:roles, owner=:owner, type=:type, dvd_or_bluray=:dvd_or_bluray, borrower=:borrower, borrowed=:borrowed WHERE mid=".$_SESSION['admin']['details-med-id'].";";
$stmt = $dbc->prepare($sql);

$stmt->bindParam(':title', $_POST['med_title']);
$stmt->bindParam(':original_or_copy', $_POST['original_or_copy']);
$stmt->bindParam(':genre', $genreIdsComplete);
$stmt->bindParam(':publisher', $publisherIdsComplete);
$stmt->bindParam(':director', $directorIdsComplete);
$stmt->bindParam(':length', $_POST['length']); 
$stmt->bindParam(':fsk', $_POST['fsk']);
$stmt->bindParam(':premiere', $dateComplete);
$stmt->bindParam(':num_disk', $_POST['disks']);
$stmt->bindParam(':content', $_POST['med_content']);
$stmt->bindParam(':cover', $coverNameComplete); 
$stmt->bindParam(':actors', $actorIdsComplete); 
$stmt->bindParam(':roles', $rolesComplete);
$stmt->bindParam(':owner', $ownerIdComplete);
$stmt->bindParam(':type', $typeIdComplete);
$stmt->bindParam(':dvd_or_bluray', $_POST['dvd_or_bluray']);
$stmt->bindParam(':borrower', $borrowerIdComplete);
$stmt->bindParam(':borrowed', $borrowed);

if($stmt->execute()){
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