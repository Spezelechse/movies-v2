PDF Liste erzeugen
<br><br>

<?php
include("config/config.php");
//Laden der Genrenamen in ein Array mit der Id als Schlüssel
$con = mysql_connect ($host,$uname,$pword) ;
mysql_select_db($dbank);
mysql_query("SET NAMES 'utf8'");

$res = mysql_query("Select * from genre");

while($genre = mysql_fetch_assoc($res)){
	$genres[''.$genre['gid']] = $genre['genre'];
}

unset($genre, $res);
mysql_close($con);

//Laden der Einträge
$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "SELECT title,genre,dvd_or_bluray FROM dvds ORDER BY title";
$stmt = $dbc->prepare($sql);

if($stmt->execute()){
	require("fpdf/fpdf.php");
	
	//Formatieren des PDF Headers und Footers
	class MyPDF extends FPDF{
		function Header(){
			global $Title;
			$this->SetFont("Helvetica","B",12);
			
			$this->Cell(0, 10, "Liste vom ".date("d.m.Y"),0,2,"R",0);
		}
		function Footer(){
			$this->SetY(-20);
			$this->SetFont("Helvetica","B",10);
			$this->Cell(0, 10, "Seite ".$this->PageNo()."/{nb}","",0,"R",0);
		}
	}
	
	//Erstellen des PDFs
	$pdf = new MyPDF();
	$pdf->SetCreator("Movies V2.0 created by Spezelechse");
	$pdf->SetAuthor($_SESSION['user']['name']);
	$pdf->SetTitle($Title);
	$pdf->SetDisplayMode("real","single");
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf->SetFont("Helvetica","B",26);
	
	$pdf->Cell(190, 17, $Title,0,2,"C",0);
	
	$pdf->SetLineWidth(0.2);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetFont("Helvetica","B",14);
	$pdf->SetFillColor(190,190,190);
	
	//Kopf der Tabelle
	$pdf->Cell(15, 12, "Nr.","LTR",0,"L",1);
	$pdf->Cell(105, 12, "Titel","LTR",0,"L",1);
	$pdf->Cell(70, 12, "Genre","LTR",0,"L",1);
	$pdf->Ln();
	
	$pdf->SetFont("Helvetica","",12);
	
	//Erstellen der Tabellenreihen
	$counter=1;
	while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
		//Austauschen der Gerne-Ids mit den Genrenamen
		$genreIds = split(",", $data["genre"]);
		$genre=array();
		foreach($genreIds as $key => $genreId){
			$genre[$key]=$genres[''.$genreId];
		}
		
		//Höhe der Tabellenspalten
		$height=8;
		
		if($data['dvd_or_bluray']==0) $dvd_or_bluray="DVD";
		else if($data['dvd_or_bluray']==1) $dvd_or_bluray="Bluray";
		
		//Lösung für das Problem mit mehreren Zeilen in der Spalte "Titel"
		$title=split(" ",$data['title']." [".$dvd_or_bluray."]");
		$title_counter=0;
		$title_rows=array("");
		
		foreach($title as $tit){
			$buffer = $title_rows[$title_counter].$tit." ";
			if(strlen($buffer)<=50){
				$title_rows[$title_counter]=$buffer;
			}
			else{
				$title_counter++;
				$title_rows[$title_counter]=$tit." ";
			}
		}
		$title_counter++;
		
		$title=implode("\n",$title_rows);
		$title=substr($title,0,strlen($title)-1);
		
		//Lösung für das Problem mit mehreren Zeilen in der Spalte "Genre"
		$genre_rows=array("");
		$genre_counter=0;
		foreach($genre as $gen){
			$buffer = $genre_rows[$genre_counter].$gen.", ";
			if(strlen($buffer)<=35){
				$genre_rows[$genre_counter]=$buffer;
			}
			else{
				$genre_counter++;
				$genre_rows[$genre_counter]=$gen.", ";
			}
		}
		$genre_counter++;
		
		$genre=implode("\n",$genre_rows);
		$genre=substr($genre,0,strlen($genre)-2);

		
		if($genre_counter<$title_counter) $multiplier=$title_counter;
		else $multiplier=$genre_counter;
		
		//Erstellen und formatieren einer Reihe mit einem Datensatz
		$pdf->Cell(15, $height*$multiplier, $counter.".",1,0,"R",0);
		if($genre_counter<$title_counter){
			$pdf->MultiCell(105, $height, utf8_decode($title),1,"L",0);
			$y=$pdf->GetY()-($height*$multiplier);
			$pdf->SetXY(130,$y);
			$pdf->MultiCell(70, $height*$multiplier, utf8_decode($genre),1,"L",0);
		}
		else if($genre_counter>$title_counter){
			$pdf->MultiCell(105, $height*$multiplier, utf8_decode($title),1,"L",0);
			$y=$pdf->GetY()-($height*$multiplier);
			$pdf->SetXY(130,$y);
			$pdf->MultiCell(70, $height, utf8_decode($genre),1,"L",0);
		}
		else{
			$pdf->MultiCell(105, $height, utf8_decode($title),1,"L",0);
			$y=$pdf->GetY()-($height);
			$pdf->SetXY(130,$y);
			$pdf->MultiCell(70, $height, utf8_decode($genre),1,"L",0);
		}
		
		$counter++;
	}
	
	//Speichern des PDF unterm dem in page_settings.php angegeben Titel
	$pdf->Output("download/".$Title.".pdf");
}
else{
	echo "Bei der Abfrage ist ein Fehler aufgetreten";
}
unset($dbc, $stmt, $data, $sql, $_SESSION['admin-content']);
?>

<script type="text/javascript">
	showPDF('<?php echo "download/".$Title.".pdf"; ?>');
</script>