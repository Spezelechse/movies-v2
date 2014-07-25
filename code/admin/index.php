<?php include 'config/config.php'; ?>
<div id="admin-wrapper">
    <div id="admin-menu" align="left">
    <h2>Verwaltung</h2><br />
    <br />
    <h3>Medien verwalten:</h3><br />
    <?php
		$rights=$_SESSION['user']['rights'];
		if(!preg_match("/[01]{".strlen($rights)."}/",$rights)){
			die("manipulation detectet rights");
		}
		$rights=str_split($rights,1);

		$options = array("hinzufügen","löschen","bearbeiten","ausgeliehen");
		
		$counter=1;
		foreach($options as $option){
			if($rights[$counter-1]==1){
				link_page($option, $counter, "admin-content");
				echo "<br>";
			}
			
			$counter++;
		}
		if($rights[0]==1){
			link_page("import", 11, "admin-content");
			echo " / ";
		}
		
		link_page("export", 10, "admin-content");
		echo "<br>";
		link_page("PDF erstellen", 12, "admin-content");
	?>
    <br /><br />
    <h3>Benutzer verwalten:</h3><br />
    <?php
		$options = array("hinzufügen","löschen","bearbeiten");
		foreach($options as $option){
			if($rights[$counter-1]==1){
				link_page($option, $counter, "admin-content");
				echo "<br>";
			}
			
			$counter++;
		}
	?>    
    <br />
    <h3>Einstellungen:</h3><br />
    <?php link_page("Benutzerdaten ändern", 8, "admin-content"); ?><br />
	<?php if($_SESSION['user']['id']==1) link_page("Webseite konfigurieren", 9, "admin-content"); ?><br />  
    </div>
    <div id="admin-content">
    <?php
	if(!isset($_SESSION['admin'])){
		$_SESSION['admin']=array();
	}
	$admin_pages = array("welcome.php","media/insert/index.php","media/delete/index.php","media/edit/index.php","media/borrow/index.php","user/add/index.php","user/delete/index.php","user/edit/index.php","user/edit_own/index.php","page_conf/index.php","media/export/index.php","media/import/index.php","media/createPDF/index.php");
	
	if(isset($_POST['admin-content'])&&ctype_digit($_POST['admin-content'])){
		$_SESSION['admin-content']=$_POST['admin-content'];;
	}
	else{
		if(!isset($_SESSION['admin-content'])){
			$_SESSION['admin-content']=0;
		}
	}
	
	include "admin/".$admin_pages[$_SESSION['admin-content']];
	?>
    </div>
</div>