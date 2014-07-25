<?php
unset($_POST['check']);
if(!isset($_SESSION['advancedSearchBackup'])){
	$_SESSION['advancedSearchBackup']=$_POST;
}

$mysql_first_fields = array("med_title","med_content","fsk","prem_day","prem_month","prem_year","type","disks","owner","original_or_copy","length","dvd_or_bluray");
$mysql_first_cols = array("title","content","fsk","DAY(premiere)","MONTH(premiere)","YEAR(premiere)","type","num_disks","owner","original_or_copy","length","dvd_or_bluray");
$mysql_second_fields = array("publisher","director","actors","roles","genre");
$mysql_second_cols = array("publisher","director","actors","roles","genre");

//Erzeugen des Queries
$mysql_first_query = " WHERE ";
$counter=0;
foreach($mysql_first_fields as $db_key => $post_key){
	if(isset($_SESSION['advancedSearchBackup'][$post_key])&&$_SESSION['advancedSearchBackup'][$post_key]!=""){
		if($post_key=="length"){
			if($counter>0) $query_part = "AND ";
			else $query_part = "";
			
			$query_part .= $mysql_first_cols[$db_key]." ".$_SESSION['advancedSearchBackup']['length_compare']." :value".$counter." ";
			$mysql_first_query .= $query_part;
			$counter++;
		}
		else{
			if($counter>0) $query_part = "AND ";
			else $query_part = "";
			
			$query_part .= $mysql_first_cols[$db_key]." LIKE :value".$counter." ";
			$mysql_first_query .= $query_part;
			$counter++;
		}
	}
	else{
		unset($mysql_first_fields[$db_key], $mysql_first_cols[$db_key]);
	}
}

if($counter>0) $mysql_first_query_complete = "SELECT mid, title, publisher, director, actors, roles, genre, cover FROM dvds".$mysql_first_query." ORDER BY title";
else $mysql_first_query_complete = "SELECT mid, title, publisher, director, actors, roles, genre, cover FROM dvds ORDER BY title";

//Erstellen des ersten Teilergebnisses
include "config/config.php";
$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$stmt = $dbc->prepare($mysql_first_query_complete);

$counter=0;
foreach($mysql_first_fields as $field){
	$value = $_SESSION['advancedSearchBackup'][$field];
	$length = strlen($value);
	if(!preg_match("/\\d{".$length."}/",$value)){
		$value = "%".$value."%";
	}

	$stmt->bindParam(":value".$counter,$value);
	
	unset($value);
	$counter++;
}
echo "<br>";

$query_result=$stmt->execute();
$list= $stmt->fetchAll();

//Herrausfiltern der auf die Multiplen Suchbegriffe passenden Einträge
$multipleNeeded=false;
if($query_result){
	$counter=0;
	$final_list=array();
	foreach($list as $item){
		$num_search_values=0;
		$num_found_values=0;
		foreach($mysql_second_fields as $db_key => $post_key){
			if(isset($_SESSION['advancedSearchBackup'][$post_key])&&$_SESSION['advancedSearchBackup'][$post_key]!=""&&$_SESSION['advancedSearchBackup'][$post_key]!=array("")){	
				$multipleNeeded=true;
				if(is_array($_SESSION['advancedSearchBackup'][$post_key])){
					$db_col_data = $item[$post_key];
					$db_col_data = explode(",",$db_col_data);
					foreach($_SESSION['advancedSearchBackup'][$post_key] as $value){
						foreach($db_col_data as $data){
							if($data==$value){
								$num_found_values++;
								break;
							}				
						}
						$num_search_values++;
					}
				}
				else{
					$db_col_data = $item[$post_key];
					
					$search_data = explode(chr(13)."\n",$_SESSION['advancedSearchBackup'][$post_key]);
					foreach($search_data as $search){
						if(stripos($db_col_data,$search)){
							$num_found_values++;
						}
						$num_search_values++;
					}
				}
			}
		}
		if($num_search_values==$num_found_values){
			array_push($final_list,$item);
		}
		$counter++;
	}
}
if(!$multipleNeeded){
	$final_list=$list;
}

$num=count($final_list);
$numRows=$num;
$search=true;
include("list2.php");
?>