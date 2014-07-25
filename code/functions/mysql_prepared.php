<?php
/** Fügt irgendetwas in die Datenbank ein, aber was genau?
 *  @param tbl 			-> der Tabellennamen 
 *  @param paramName	-> der Name des Parameters in der Tabelle
 *  @param values		-> die Daten die eingefügt werden müssen
 *	@retun added_ids	-> ein String mit den Ids der eingefügten Daten für die weitere Verarbeitung
 */
function insertOneParam($tbl, $paramName, $values){
	include "config/config.php";
	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	$dbc->query("SET NAMES 'utf8'");
	
	$sql = "INSERT INTO ".$tbl." (".$paramName.") VALUES (:value)";
	$stmt = $dbc->prepare($sql);
	
	$checkSql = "SELECT * FROM ".$tbl." WHERE BINARY ".$paramName."= :checkValue";
	$checkStmt = $dbc->prepare($checkSql);
	
	$added_ids="";
	
	foreach($values as $value){		
		$checkStmt->bindParam(':checkValue', $value);
	
		if($checkStmt->execute()){
			$data = $checkStmt->fetch(PDO::FETCH_NUM);
			
			$oid = $data[0];
			if(isset($oid)){
				$added_ids.=$oid.",";
			}
			else{
				$stmt->bindParam(':value', $value);
				$stmt->execute();
				
				$id = $dbc->lastInsertId();
				$added_ids.=$id.",";
			}
		}
	}
	unset($dbc, $stmt, $checkStmt, $data);
	
	$added_ids = substr($added_ids, 0, strlen($added_ids)-1);
	return $added_ids;
}

function getDataFromIds($ids, $tbl, $param, $idParam){
	include "config/config.php";
	if(!is_array($ids)){
		$ids = array($ids);
	}

	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	$dbc->query("SET NAMES 'utf8'");
	
	$sql = "SELECT ".$param." FROM ".$tbl." WHERE ".$idParam." = :id";

	$stmt = $dbc->prepare($sql);
	
	$counter=0;
	foreach($ids as $id){
		$stmt->bindParam(':id', $id);
		
		if($stmt->execute()){
			$data = $stmt->fetch(PDO::FETCH_NUM);
			$ids[$counter]=$data[0];
		}
		else{
			die("error with: ".$idParam);
		}
		
		$counter++;
	}
	unset($dbc, $stmt, $data);
	return $ids;
}
?>