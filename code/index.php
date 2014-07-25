<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
session_regenerate_id(true);
unset($_SESSION['listNeeded']);
include "header.php";
?>

<div id="navi">
	<?php include "navi.php"; ?>
</div>

<div id="content" align="center">
	<?php 
	if($Public||isset($_SESSION['user']['logged'])){
		//Array mit den zu ladenen Seiten
		$pages = array("listAll.php", "login.php", "search/search.php", "search/advancedSearch.php","admin/index.php", "logout.php", "details.php","specialList.php","detailDataList.php");
		//Weitergabe der zu ladenden Seite
		
		if(isset($_POST['content'])&&ctype_digit($_POST['content'])){
			$_SESSION['content']=$_POST['content'];
			$_SESSION['list_counter']=0;
		}
		else{
			if(!isset($_SESSION['content'])){
				$_SESSION['content']=0;
				$_SESSION['list_counter']=0;
			}
		}
	
		if($_SESSION['content']!=1){
			unset($_SESSION['try']);
		}
		
		if(isset($_POST['list_counter'])){
			$_SESSION['list_counter']=$_POST['list_counter'];
		}
		
		if($_SESSION['content']!=6){
			unset($_SESSION['details-med-id']);
		}
		
		if($_SESSION['content']!=3){
			unset($_SESSION['advancedSearchBackup']);
		}
			
		include $pages[$_SESSION['content']];
	}
	else{
		//Array mit den zu ladenen Seiten
		$pages = array("not_public.php", "login.php");
		//Weitergabe der zu ladenden Seite
		if(isset($_POST['content'])&&ctype_digit($_POST['content'])){
			$content=$_POST['content'];
			
			if($content>1){
				$content=0;
			}
			
			$_SESSION['content']=$content;
			$_SESSION['list_counter']=0;
		}
		else{
			if(!isset($_SESSION['content'])){
				$_SESSION['content']=0;
				$_SESSION['list_counter']=0;
			}
		}
	
		if($_SESSION['content']!=1){
			unset($_SESSION['try']);
		}
	
		include $pages[$_SESSION['content']];
	}
	?>  
</div>
	
<?php include "footer.php";?>