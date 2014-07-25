<?php
include "config/config.php";
include "functions/mysql_prepared.php";

if(isset($_POST['medId'])&&$_POST['medId']!=""){
	$_SESSION['details-med-id']=$_POST['medId'];
}

$length = strlen($_SESSION['details-med-id']);
$pattern = "/\\d{".$length."}/";

if(!preg_match($pattern, $_SESSION['details-med-id'])){
	die("manipulation detectet film id");
}

$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "SELECT * FROM dvds WHERE mid = :id";
$stmt = $dbc->prepare($sql);

$stmt->bindParam(':id', $_SESSION['details-med-id']);

if($stmt->execute()){
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
}
else{
	die("Beim auslesen der Filmdaten ist ein Fehler aufgetreten");
}
?>
<div id="movie-wrapper">

    <div id="movie-content" align="left">    
        <div id="movie-title" align="left">
            <?php 
                echo $data['title']; 
                echo " (";
                echo $data['num_disk']." ";
                if($data['dvd_or_bluray']==0){
                    echo "DVD";
                }
                else if($data['dvd_or_bluray']==1){
                    echo "Blu-ray";
                }
                else{
                    echo "Datei";
                }
                
                if($data['num_disk']>1){
                    if($data['dvd_or_bluray']<=1){
                        echo "s";
                    }
                    else{
                        echo "en";
                    }
                }
                echo ")";
                
                $owner = getDataFromIds($data['owner'], "owner", "owner", "oid");
                $owner = implode("",$owner);
                echo "<u>von: ".$owner."</u>";
            ?>
        </div>
        <?php
			$coverSplit = preg_split("[\.]",$data['cover']);
			
			//echo "<br>".$coverSplit[1]."<br>";
		
			if(file_exists("cover_big/".$data['cover'])){
				if(preg_match("[([jJ][pP][gG])|([jJ][pP][eE][gG])]", $coverSplit[1])){
					$cover = imageCreateFromJPEG("cover_big/".$data['cover']);
				}
				else if(preg_match("([pP][nN][gG])", $coverSplit[1])){
					$cover = imageCreateFromPNG("cover_big/".$data['cover']);
				}
	
				$breite = imageSX($cover);
				$hoehe = imageSY($cover); 
				imagedestroy($cover);
			}
		?>
   		<img id="movie-cover" onclick="showBig(<?php echo "'".$data['cover']."',".$breite.",".$hoehe; ?>)" src="cover/<?php echo $data['cover']; ?>" />
        <?php echo nl2br($data['content']); ?>    
    </div>
    <div id="movie-details">
       		<div id="details-header"></div>
            <div id="details-header2" align="left">Besetzung:</div>
            <div id="details-header3" align="left">Filmdaten:</div>
            <div id="movie-cast">
                    <table id="cast" border="0" cellpadding="0" cellspacing="0">
                    <?php 
                        $roles = explode(",", $data['roles']);
						$actorIds = explode(",", $data['actors']);
                        $actors = getDataFromIds($actorIds, "actor", "act_name", "aid");
                        
                        for($x=0; $x<count($actors); $x++){
                            echo "<tr>";
                            echo "<td id='movie-actor' align='right' valign='top'>";
							echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$actors[$x].",".$actorIds[$x].",0\");'>".$actors[$x]."</a>";
							echo "</td>";
                            echo "<td id='movie-as' align='center' valign='top'>als</td>";
                            echo "<td id='movie-role' align='left' valign='top'>".$roles[$x]."</td>";
                            echo "</tr>";
                        }
                    ?>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    </table>
                    <div id="details-content3"></div>
            </div>
			<div id="movie-infos" align="left">
				<?php
                echo "<b>Altersfreigabe:</b> ";
                echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$data['fsk'].",".$data['fsk'].",5\");'>".$data['fsk']."</a><br>";
				
                $date = explode("-",$data['premiere']);
                echo "<b>Premiere:</b> ".$date[2].".".$date[1].".<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$date[0].",".$date[0].",6\");'>".$date[0]."</a><br>";;
                                
                $type = getDataFromIds($data['type'], "type", "type", "tid");
                $type = implode("",$type);
				
                echo "<b>Art:</b> ";
                echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$type.",".$data['type'].",1\");'>".$type."</a>";
				echo "<br>";
				
                echo "<b>Laufzeit:</b> ".$data['length']." min."; 
				spacer(438,10);
                
				$genreIds = explode(",", $data['genre']);
                $genres = getDataFromIds($genreIds, "genre", "genre", "gid");
                echo "<br><b>Genre:</b> ";
				$counter=0;
				foreach($genres as $genre){
					echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$genre.",".$genreIds[$counter].",2\");'>".$genre."</a>";
					$counter++;
					if($counter<count($genres)){
						echo ", ";
					}
				}

				$publisherIds = explode(",", $data['publisher']);
                $publisher = getDataFromIds($publisherIds, "publisher", "pub_name", "pid");
                echo "<br><b>Publisher:</b> ";
				$counter=0;
				foreach($publisher as $pub){
					echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$pub.",".$publisherIds[$counter].",3\");'>".$pub."</a>";
					$counter++;
					if($counter<count($publisher)){
						echo ", ";
					}
				}

				$directorIds = explode(",", $data['director']);
                $director = getDataFromIds($directorIds, "director", "dir_name", "did");
                echo "<br><b>Regisseure:</b> ";
				$counter=0;
				foreach($director as $dir){
					echo "<a href='#' onclick='submitWithData(\"submitForm\",\"content\",\"8\",\"detailData\",\"".$dir.",".$directorIds[$counter].",4\");'>".$dir."</a>";
					$counter++;
					if($counter<count($director)){
						echo ", ";
					}
				}
                ?>
           </div>
      </div>
</div>