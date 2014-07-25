<?php
$lf=0;
if($query_result){	
	if($num>0){
		$con = mysql_connect ($host,$uname,$pword) ;
		mysql_select_db($dbank);
		mysql_query("SET NAMES 'utf8'");
		
		$res = mysql_query("Select * from genre");
		
		while($genre = mysql_fetch_assoc($res)){
			$genres[''.$genre['gid']] = $genre['genre'];
		}
	
		unset($genre, $res);
		mysql_close($con);
		
		$numPage = $num;
		?>
		<form name="overviewSelect" id="overviewSelect" method="post" action="index.php">
        <input type="hidden" name="searched" value="<?php echo $_POST['searched'];?>" />
        <input type="hidden" name="overviewTyp" id="overviewTyp" value="0" />
        <?php
		if(isset($_POST['overviewTyp'])){
			$_SESSION['overviewTyp']=$_POST['overviewTyp'];
		}
		?>
    	</form>
		<?php
		
		if(isset($search)) echo "<h4>Treffer: ".($_SESSION['list_counter']+1)."-".($_SESSION['list_counter']+$numPage)." von ".$numRows."</h4>";
		else echo "<h4>Einträge: ".($_SESSION['list_counter']+1)."-".($_SESSION['list_counter']+$numPage)." von ".$numRows."</h4>";
		
		$_SESSION['listNeeded']=true;
		
		if($_SESSION['overviewTyp']==1){
			echo "<div id=\"allCoverWrapper\">";
			
			$lf=1+$_SESSION['list_counter'];
			
			foreach($final_list as $data){
				if(isset($data['mid'])){
					echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"6\",\"medId\",".$data['mid'].");'>";
					echo "<div id=\"cover-wrapper\"><div id=\"cover\" style=\"background-image:url(cover/".$data['cover'].");\"></div></div>";
					echo "</a>";
					$lf = $lf + 1;
				}
			}
			echo "</div>";
		}
		else{
			echo "<table id = 'list' border = '0' cellspacing = '0' cellpadding = '0'>";
			echo "<tr><td id='head_row'> Nr. </td>";
			echo "<td style=\"min-width:500px;\" id='head_row'>Name</td>";
			echo "<td id='head_row'>Genre</td>";
			echo "</tr>";
			
			$lf=1+$_SESSION['list_counter'];
			
			foreach($final_list as $data){
			
				if(isset($data['mid'])){
					echo"<td id='row0' align='right'>".$lf."</td>";
					echo"<td id='row0'><a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"6\",\"medId\",".$data['mid'].");'>" . $data['title'] . "</a></td>";
					echo"<td id='row0'>";
					$genreIds = preg_split("[,]", $data["genre"]);
					
					$counter=0;
					foreach($genreIds as $genreId){
						echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$genres[''.$genreId].",".$genreId.",2\");'>".$genres[''.$genreId]."</a>";
						$counter++;
						if($counter<count($genreIds)){
							echo ", ";
						}
					};	
					echo "</td>";
					echo"</tr>";
					$lf = $lf + 1;
				}
			}
			
			echo "</table>";
		}
	}
	else{
		if($search)	echo "Die Suche ergab keine Treffer<br><br>";
		else  echo "Keine Einträge vorhanden";
	}
}
else{
	if($search) echo "Bei der Suche ist ein Fehler aufgetreten";
	else echo "Bei der Abfrage ist ein Fehler aufgetreten";
}

unset($dbc, $stmt, $data, $sql);

$checkLf = $lf-$_SESSION['list_counter']-1;

$next=$_SESSION['list_counter']+$Number_Of_List_Entries;
$previous=$_SESSION['list_counter']-$Number_Of_List_Entries;

if($_SESSION['list_counter']>0){
	echo "<a href='#' id='previous' onclick='submitWithData(\"submitForm\",\"list_counter\",\"".$previous."\",\"\",\"0\");'>zurück</a>";
}
if($checkLf==$Number_Of_List_Entries){
	echo "<a href='#' id='next' onclick='submitWithData(\"submitForm\",\"list_counter\",\"".$next."\",\"\",\"0\");'>weiter</a>";
}
?>