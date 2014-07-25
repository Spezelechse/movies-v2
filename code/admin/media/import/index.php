<center>Medien importieren</center>
<br /><br />
<?php
if(isset($_FILES['import_file']['tmp_name'])&&$_FILES['import_file']['tmp_name']!=""){
	include("functions/checkData.php");
	include "functions/mysql_prepared.php";
	
	$db_cols=array('title','num_disk','dvd_or_bluray','original_or_copy','cover','content','actors','roles','fsk','premiere','type','length','genre','publisher','director','owner','borrower','borrowed');
	$value_typ=array(0,1,1,1,2,0,0,0,1,3,0,1,0,0,0,1,1,1); //0=TEXT,1=ZAHL,2=DATEI,3=DATUM
	$db_multi_tbl=array(6=>'actor',10=>'type',12=>'genre',13=>'publisher',14=>'director');
	$db_multi_tbl_cols=array(6=>'act_name',10=>'type',12=>'genre',13=>'pub_name',14=>'dir_name');
	
	$file=fopen($_FILES['import_file']['tmp_name'],"r");
	$counter=0;
	$check=0;
	
	include "config/config.php";
	$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
	$dbc->query("SET NAMES 'utf8'");
	
	$sql = "INSERT INTO dvds (title, original_or_copy, genre, publisher, director,length, fsk, premiere, num_disk, content, cover, actors, roles, borrowed, borrower, owner, type, dvd_or_bluray) VALUES (:title, :original_or_copy, :genre, :publisher, :director, :length, :fsk, :premiere, :num_disk, :content, :cover, :actors, :roles, :borrowed, :borrower, :owner, :type, :dvd_or_bluray)";
	$stmt = $dbc->prepare($sql);
	
	while($file_content=fgets($file)){
		//Informationen vorbereiten
		$file_content=utf8_encode($file_content);
		$file_content=explode("//", $file_content);
		$file_content[5]=str_replace("\line\\","\n",$file_content[5]);
		$file_content[15]=$_POST['owner'];
		$file_content[16]="0";
		$file_content[17]="0";
		
		if(file_exists($file_content[4])){
		}
		
		//Informationen validieren und eintragen
		foreach($db_cols as $key => $value){
			if(isset($db_multi_tbl[$key])){
				$multi_data=explode(",",$file_content[$key]);
				
				foreach($multi_data as $data){
					checkText($data,true);
				}
				
				$multi_data_ids = insertOneParam($db_multi_tbl[$key],$db_multi_tbl_cols[$key],$multi_data);
				
				$stmt->bindParam(':'.$value, $multi_data_ids);
				unset($multi_data_ids);
			}
			else{
				if($value_typ[$key]==0){
					checkText($file_content[$key],true);
				}
				if($value_typ[$key]==1){
					checkText($file_content[$key],true,true);
				}
				if($value_typ[$key]==2){
					checkText($file_content[$key],true,false,false,true);
				}
				if($value_typ[$key]==3){
					checkText($file_content[$key],true,false,false,false,true);
				}
				$stmt->bindParam(':'.$value, $file_content[$key]);
			}
		}
		
		if($stmt->execute()){
			echo "<br><br>".$file_content[0]." Erfolgreich importiert";
			$check++;
			
		}
		else{
			$err=$stmt->errorInfo();
			
			echo "<br><br>Bei ".$file_content[0]." ist ein Fehler aufgetreten<br><br>";
			echo $err[2];
		}
		echo "<br><br>";
		$counter++;
	}
	if($check==$counter){
		forward("index.php",2000);	
	}
}
else{
	$con = mysql_connect ($host,$uname,$pword) ;
	mysql_select_db($dbank);
	mysql_query("SET NAMES 'utf8'");
	?>
	<form action="index.php" method="post" enctype="multipart/form-data">
	Besitzer: 
	<select name="owner"  onchange="checkBoth('owner',checkselect(this.value, 'owner', 5),'newowner',false);" style="width:180px;">
	<option value=""></option>
	  <?php
		  $sql = "SELECT * FROM owner ORDER BY owner";
		  $res = mysql_query($sql);
		  $num = mysql_num_rows($res);
		  
		  while ($dsatz = mysql_fetch_assoc($res))
		  {
			  echo "<OPTION VALUE=\"".$dsatz['oid']."\">".$dsatz['owner']."</OPTION>";
		  }
		  mysql_close($con);
	  ?>
	</select><br /><br />
	<input type="file" name="import_file" />
	<br /><br />
	<input type="submit" value="hochladen"/>
	</form>
	<?php
}
?>