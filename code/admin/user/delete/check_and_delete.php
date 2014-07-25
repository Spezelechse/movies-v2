<?php
if(isset($_POST['id'])&&$_POST['setup']==1){
	
	echo "<br><br>";
	
	$input=$_POST['id'];
	
	$names="";
	$ids="";
	
	foreach($input as $element){
		$inputSplit = explode(",",$element);
		
		$ids.=$inputSplit[0].",";
		$names.=$inputSplit[1]." ".$inputSplit[2]."<br>";
	}
	
	$ids=substr($ids,0,strlen($ids)-1);
	
	echo $names;
?>

<br><br>Die Benutzer wirklich löschen?<br><br>

<form id="confirm" name="confirm" action="index.php" method="post">
<input type="hidden" name="setup" value="2">
<input type="hidden" name="id" value="<?php echo $ids; ?>">
<input type="button" value="nein" onClick="document.confirm.setup.value=''; document.confirm.id.value=''; document.confirm.submit();">
<input type="submit" value="ja">
</form>

<?php
}
if(isset($_POST['id'])&&$_POST['setup']==2){
	
	$ids = explode(",",$_POST['id']);
	
	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	$dbc->query("SET NAMES 'utf8'");
	
	$sql = "DELETE FROM user WHERE uid=:id;";
	$stmt = $dbc->prepare($sql);
	
	$countAktion=0;
	$counter=0;
	
	foreach($ids as $id){
		$length = strlen($id);
		
		if(preg_match("/\\d{".$length."}/",$id)){
			$stmt->bindParam(':id',$id);
			
			if($stmt->execute()){
				
				$num = $stmt->rowCount();
				if($num>0){
					$countAktion++;
				}
			}
			else{
				$err=$stmt->errorInfo();
				
				echo "<br><br>Es ist ein Fehler aufgetreten<br><br>";
				echo $err[2];
			}
		}
		else{
			die("manipulation detected");
		}
		
		$counter++;
	}
	
	if($countAktion==count($ids)){	
		echo "<br><br>Löschen erfolgreich";
		forward("index.php",2000);
	}
	else{
		$diff=count($ids)-$countAktion;
		echo "<br><br>Löschen fehlgeschlagen (".$diff.". User wurden nicht gelöscht)";
	}
}
?>