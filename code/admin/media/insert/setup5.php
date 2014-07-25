<?php
include "functions/mysql_prepared.php";
include "functions/prepare_data.php";

//GENRE
//man muss bei 'chr(13)."\n"' aufteilen da neben dem Zeichen für den Zeilenumbruch auch eines für Carriage return (Wagenrücklauf) eingefügt wird und das für Fehler in der Datenbank sorgen kann
if(isset($_SESSION['admin']['setup1']['newgenre'])&&$_SESSION['admin']['setup1']['newgenre']!=""){
	$newGenre = explode(chr(13)."\n",str_replace("\'","'",$_SESSION['admin']['setup1']['newgenre']));
	$newGenreIds = insertOneParam("genre","genre",$newGenre);
	
	//echo "<br><br>Neue Genre wurden hinzugefügt: ".$newGenreIds;
	
	$genreIdsComplete = includeNewAndSort($_SESSION['admin']['setup1']['genre'], $newGenreIds, implode(",",$newGenre));
}
else{
	$genreIdsComplete = collectIdsFromSelect($_SESSION['admin']['setup1']['genre']);
}

//PUBLISHER
if(isset($_SESSION['admin']['setup1']['newpublisher'])&&$_SESSION['admin']['setup1']['newpublisher']!=""){
	$newPublisher = explode(chr(13)."\n", str_replace("\'","'",$_SESSION['admin']['setup1']['newpublisher']));
	$newPublisherIds = insertOneParam("publisher" ,"pub_name", $newPublisher);
	
	//echo "<br><br>Neue Publisher wurden hinzugefügt: ".$newPublisherIds;
	
	$publisherIdsComplete = includeNewAndSort($_SESSION['admin']['setup1']['publisher'], $newPublisherIds, implode(",",$newPublisher));
}
else{
	$publisherIdsComplete = collectIdsFromSelect($_SESSION['admin']['setup1']['publisher']);
}

//DIRECTOR
if(isset($_SESSION['admin']['setup1']['newdirector'])&&$_SESSION['admin']['setup1']['newdirector']!=""){
	$newDirector = explode(chr(13)."\n", str_replace("\'","'",$_SESSION['admin']['setup1']['newdirector']));
	$newDirectorIds = insertOneParam("director", "dir_name", $newDirector);
	
	//echo "<br><br>Neue Regisseure wurden hinzugefügt: ".$newDirectorIds;
	
	$directorIdsComplete = includeNewAndSort($_SESSION['admin']['setup1']['director'], $newDirectorIds, implode(",",$newDirector));
}
else{
	$directorIdsComplete = collectIdsFromSelect($_SESSION['admin']['setup1']['director']);
}

//ACTOR
	$actors = explode(chr(13)."\n", str_replace("\'","'",$_SESSION['admin']['setup2']['actors']));
	$actorIds = insertOneParam("actor", "act_name", $actors);
	
	//echo "<br><br>Neue Schauspieler wurden hinzugefügt: ".$actorIds;
	
	$actorResult = includeNewAndSort(array(), $actorIds, implode(",",$actors), $_SESSION['admin']['setup2']['roles']);
	
	$actorIdsComplete = $actorResult[0];
	$rolesComplete = str_replace("\'","'",$actorResult[1]);

//TYP
if(isset($_SESSION['admin']['setup3']['newtype'])&&$_SESSION['admin']['setup3']['newtype']!=""){
	$newTyp = array(str_replace("\'","'",$_SESSION['admin']['setup3']['newtype']));
	$newTypId = insertOneParam("type", "type", $newTyp);
	
	//echo "<br><br>Neue Art wurde hinzugefügt: ".$newTypId;
	
	$typeIdComplete = $newTypId;
}
else{
	$type = explode(",",$_SESSION['admin']['setup3']['type']);
	$typeIdComplete = $type[0];
}

//OWNER
if(isset($_SESSION['admin']['setup3']['newowner'])&&$_SESSION['admin']['setup3']['newowner']!=""){
	$newOwner = array(str_replace("\'","'",$_SESSION['admin']['setup3']['newowner']));
	$newOwnerId = insertOneParam("owner", "owner", $newOwner);
	
	//echo "<br><br>Neuer Besitzer wurde hinzugefügt: ".$newOwnerId;
	
	$ownerIdComplete = $newOwnerId;
}
else{
	$owner = explode(",",$_SESSION['admin']['setup3']['owner']);
	$ownerIdComplete = $owner[0];
}

//PRIME
$day = $_SESSION['admin']['setup2']['prem_day'];
$month = $_SESSION['admin']['setup2']['prem_month'];
$year = $_SESSION['admin']['setup2']['prem_year'];

$primeComplete = $year."-".$month."-".$day;

//DVD
$borrowed=0;

$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "INSERT INTO dvds (title, original_or_copy, genre, publisher, director,length, fsk, premiere, num_disk, content, cover, actors, roles, borrowed, borrower, owner, type, dvd_or_bluray) VALUES (:title, :original_or_copy, :genre, :publisher, :director, :length, :fsk, :premiere, :num_disk, :content, :cover, :actors, :roles, :borrowed, :borrower, :owner, :type, :dvd_or_bluray)";
$stmt = $dbc->prepare($sql);

$title=str_replace("\'","'",$_SESSION['admin']['setup1']['title']);
$stmt->bindParam(':title', $title);
$stmt->bindParam(':original_or_copy', $_SESSION['admin']['setup3']['original_or_copy']);
$stmt->bindParam(':genre', $genreIdsComplete);
$stmt->bindParam(':publisher', $publisherIdsComplete);
$stmt->bindParam(':director', $directorIdsComplete);
$stmt->bindParam(':length', $_SESSION['admin']['setup2']['length']); 
$stmt->bindParam(':fsk', $_SESSION['admin']['setup2']['fsk']);
$stmt->bindParam(':premiere', $primeComplete);
$stmt->bindParam(':num_disk', $_SESSION['admin']['setup3']['disks']);
$dvdContent=str_replace("\'","'",$_SESSION['admin']['setup2']['dvd_content']);
$stmt->bindParam(':content', $dvdContent);
$stmt->bindParam(':cover', $_SESSION['admin']['filename']); 
$stmt->bindParam(':actors', $actorIdsComplete); 
$stmt->bindParam(':roles', $rolesComplete);
$stmt->bindParam(':borrowed', $borrowed);
$stmt->bindParam(':borrower', $borrowed);
$stmt->bindParam(':owner', $ownerIdComplete);
$stmt->bindParam(':type', $typeIdComplete);
$stmt->bindParam(':dvd_or_bluray', $_SESSION['admin']['setup3']['dvd_or_bluray']);

if($stmt->execute()){
	echo "<br><br>Erfolgreich hinzugefügt";
	unset($_SESSION['admin']);
	forward("index.php",2000);	
}
else{
	$err=$stmt->errorInfo();
	
	echo "<br><br>Es ist ein Fehler aufgetreten<br><br>";
	echo $err[2];
}
?>