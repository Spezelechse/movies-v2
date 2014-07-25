<?php
include 'config/config.php';
echo "<br><br>";

// ich geh mal davon aus das er hier Username und Passwort aus der Datenbank holt odeR?

if(isset($_POST['name'])&&isset($_POST['password'])){
	if(!isset($_SESSION['try'])){
		$_SESSION['try']=1;
	}

	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	
	$sql = "SELECT blid, UNIX_TIMESTAMP(starttime) as starttime FROM blacklist WHERE ip = :ip";
	$stmt = $dbc->prepare($sql);
	
	$stmt->bindParam( ':ip', $_SERVER['REMOTE_ADDR'] );
	
	if($stmt->execute()){
		$data = $stmt->fetch(PDO::FETCH_OBJ);
		
		if(isset($data)&&$data!=null){
			$blid = $data->blid;
		}
	
		if(isset($blid)){
			$starttime = $data->starttime;
			
			$wait=time()-$starttime;
			$wait=$wait/60;
			$wait=60-$wait;
			
			$wait = floor($wait);
			
			if($wait<=0){
				$sql = "DELETE FROM blacklist WHERE blid = :blid";
				$stmt = $dbc->prepare($sql);
				
				$stmt->bindParam( ':blid', $blid);
				
				$stmt->execute();
				$_SESSION['try']=0;
				//unset($sql, $stmt);
			}
			echo "Sie sind noch für ".$wait."min gesperrt.";
		}
		else{
			if($_SESSION['try']<=3){
				$sql = "SELECT uid, username, BIN(rights) as rights FROM user WHERE username = :username AND pword = :password";
				$stmt = $dbc->prepare($sql);
				
				$stmt->bindParam( ':username', $_POST['name'] );
				$pwd=md5($_POST['password']);
				$stmt->bindParam( ':password', $pwd);
				
				if($stmt->execute()){
					$data = $stmt->fetch(PDO::FETCH_OBJ);
					if(isset($data)&&$data!=null){
						$uid = $data->uid;
					}
					if(isset($uid)){
						echo "Login erfolgreich";
						unset($_SESSION['try']);
						$_SESSION['user']=array();
						$_SESSION['user']['logged']=1;
						$_SESSION['user']['id']=$uid;
						$_SESSION['user']['name']= $data->username;
						$_SESSION['user']['rights']= $data->rights;
						$_SESSION['user']['lastAction']=time();
						$_SESSION['overviewTyp']=0;
						$_SESSION['content']=4;
						forward("index.php");
					}
					else{
						echo "Login fehlgeschlagen<br>";
						echo "Versuch ".$_SESSION['try']." von 3";
						$_SESSION['try']++;
					}
				}
				unset($dbc, $stmt, $sql, $data);
			}
			else{
				echo "Login gesperrt<br>";
			
				$sql = "SELECT blid, UNIX_TIMESTAMP(starttime) as starttime FROM blacklist WHERE ip = :ip";
				$stmt = $dbc->prepare($sql);
				
				$stmt->bindParam( ':ip', $_SERVER['REMOTE_ADDR'] );
				
				if($stmt->execute()){
					$data = $stmt->fetch(PDO::FETCH_OBJ);
					
					if(isset($data)&&$data!=null){
						$blid = $data->blid;
					}

					if(!isset($blid)){
						echo "Die IP: ".$_SERVER['REMOTE_ADDR']." ist jetzt für 60 min gesperrt.";
						/*$stmt = null;
						$sql = null;*/
						
						$sql = "INSERT INTO blacklist (ip, try) VALUES (:ip, :try)";
						$stmt = $dbc->prepare($sql);
						
						$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
						$stmt->bindParam(':try', $_POST['name']);
						
						$stmt->execute();
						unset($_SESSION['try']);
						//unset($sql, $stmt);
					}
				}
				unset($dbc, $stmt, $sql, $data);
			}
		}
	}
}

echo "<br><br>";
?>