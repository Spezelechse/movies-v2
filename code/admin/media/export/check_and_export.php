<?php
if(isset($_POST['id'])&&$_POST['setup']==1){
	
	echo "<br><br>";
	
	$input=$_POST['id'];
	
	$names="";
	$ids="";
	
	foreach($input as $element){
		$inputSplit = explode(",",$element);
		
		$ids.=$inputSplit[0].",";
		$names.=$inputSplit[1]."<br>";
	}
	
	$ids=substr($ids,0,strlen($ids)-1);
	
	echo $names;
?>

<br><br>Die Einträge wirklich exportieren?<br><br>

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
	$export_content="";

	$exportZip = new ZipArchive;
	$zipName=$_SESSION['user']['name'].'_EXPORT_'.date("d-m-Y_H-i-s").'.zip';
	$res = $exportZip->open('download/'.$zipName, ZipArchive::CREATE);

	if($res==true){

		$counter=0;
		echo "<br><br><b>Einträge werden exportiert:</b><br><br>";
		foreach($ids as $id){
			$length = strlen($id);
			
			if(preg_match("/\\d{".$length."}/",$id)){
				$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
				$dbc->query("SET NAMES 'utf8'");
				
				$sql = "SELECT * FROM dvds WHERE mid = :id";
				$stmt = $dbc->prepare($sql);
				
				$stmt->bindParam(':id', $id);
				
				if($stmt->execute()){
					$data = $stmt->fetch(PDO::FETCH_ASSOC);
					
					$export_content.=$data['title']."//".$data['num_disk']."//".$data['dvd_or_bluray']."//".$data['original_or_copy'];
	
					$export_content.="//".$data['cover'];
					
					$exportZip->addFile("cover/".$data['cover'], "cover/".$data['cover']);
					$exportZip->addFile("cover_big/".$data['cover'], "cover_big/".$data['cover']);
					
					$export_content.="//".str_replace(array("\n",chr(13)),array("\line\\",""),$data['content']);
					
					
					$actorIds = explode(",", $data['actors']);
					$actors = getDataFromIds($actorIds, "actor", "act_name", "aid");
					$actors = implode(",", $actors);
					$export_content.="//".$actors."//".$data['roles'];
					
					$export_content.="//".$data['fsk']."//".$data['premiere'];
					
					$type = getDataFromIds($data['type'], "type", "type", "tid");
					$type = implode("",$type);
					$export_content.="//".$type;
					
					$export_content.="//".$data['length']; 
					
					$genreIds = explode(",", $data['genre']);
					$genres = getDataFromIds($genreIds, "genre", "genre", "gid");
					$genres= implode(",", $genres);
					$export_content.="//".$genres;
	
					$publisherIds = explode(",", $data['publisher']);
					$publisher = getDataFromIds($publisherIds, "publisher", "pub_name", "pid");
					$publisher = implode(",", $publisher);
					$export_content.="//".$publisher;
	
					$directorIds = explode(",", $data['director']);
					$director = getDataFromIds($directorIds, "director", "dir_name", "did");
					$director = implode(",", $director);
					$export_content.="//".$director;
					
					if($counter<(count($ids)-1)){
						$export_content.="\n";
						$counter++;
					}
					echo $data['title']." <font color='#00CC00'>erledigt</font><br>";
				}
				else{
					die("query error");
				}
			}
			else{
				die("manipulation detectet id");
			}
		}
		echo "<br><br><b>Exportieren erfolgreich</b><br><br>";
		
		$file=fopen("download/data.txt","w+");
		$export_content=utf8_decode($export_content);
		fwrite($file, $export_content);
		fclose($file);
		
		$exportZip->addFile("download/data.txt", "data.txt");
		
		$_SESSION['user']['downloadFile']=$zipName;
		echo "<br><br>";
		
		echo "<a href='download.php' target='_blank'>Datei runterladen</a>";
	}
	else{
		echo "Archiv konnte nicht erstellt werden.";
	}
	
	$exportZip->close();
	unlink("download/data.txt");
}
?>