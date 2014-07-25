<?php
	/*$host="localhost";
	$uname="root";
	$dbank="movies2";
	$pword="gojira";*/
	include("../config/config.php");
	
	$rowLength=22;
try{
	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbc->query("SET NAMES 'utf8'");
	
	$query="SELECT title, mid FROM dvds WHERE title LIKE :guess ORDER BY title LIMIT 0,6";
	$stmt=$dbc->prepare($query);
	
	$guess="%".$_GET['hint']."%";
	
	$stmt->bindParam(':guess',$guess);
	
	$stmt->execute();
	$list= $stmt->fetchAll();

	foreach($list as $elem){
		echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"6\",\"medId\",".$elem[1].");'>";
		
		while(strlen($elem[0])>$rowLength){
			$part=substr($elem[0],0,$rowLength);
			$elem[0]=substr($elem[0],$rowLength);
			
			echo $part;
			if(substr($part,$rowLength-1,1)!=" "&&substr($elem[0],0,1)!=" "){
				echo "-";
			}
			echo "<br>";
		}
		
		echo $elem[0];
		
		echo "</a><br>";
		echo "<div id='hintSpacer'>&nbsp;</div>";
	}
	
	$stmt=null;
	$dbc=null;
} 
catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>