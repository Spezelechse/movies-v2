Medium hinzufügen (4/4) - Übersicht
<?php
include("check_setup3.php"); 
?>

<br /><br />
<?php //Setup1 ?>
<b>Titel:</b> <?php echo $_SESSION['admin']['setup1']['title']; ?> <br />
<b>Genre:</b> 
<?php
if(isset($_SESSION['admin']['setup1']['genre'])){
	$genres = "";
	foreach($_SESSION['admin']['setup1']['genre'] as $genre){
		$genre = preg_split("[,]",$genre);
		$genres .= $genre[1].", ";
	}
	echo $genres;
}
?><br />
<b>Publisher:</b> 
<?php 
if(isset($_SESSION['admin']['setup1']['publisher'])){
	$publisher = "";
	foreach($_SESSION['admin']['setup1']['publisher'] as $pub){
		$pub = preg_split("[,]",$pub);
		$publisher .= $pub[1].", ";
	}
	echo $publisher;
}
?><br />
<b>Regisseur(e):</b> 
<?php
if(isset($_SESSION['admin']['setup1']['director'])){
	$directors = "";
	foreach($_SESSION['admin']['setup1']['director'] as $director){
		$director = preg_split("[,]",$director);
		$directors .= $director[1].", ";
	}
	echo $directors;
}
?><br />
<b>Neues Genre:</b> <?php echo $_SESSION['admin']['setup1']['newgenre']; ?><br />
<b>Neuer Publisher:</b> <?php echo $_SESSION['admin']['setup1']['newpublisher']; ?><br />
<b>Neuer Regisseur:</b> <?php echo $_SESSION['admin']['setup1']['newdirector']; ?><br />
<br /><br />
<?php //Setup2 ?>
<b>Länge:</b><?php echo $_SESSION['admin']['setup2']['length']; ?><br />
<b>FSK:</b><?php echo $_SESSION['admin']['setup2']['fsk']; ?><br />
<b>Premiere:</b><?php echo $_SESSION['admin']['setup2']['prem_day'].".".$_SESSION['admin']['setup2']['prem_month'].".".$_SESSION['admin']['setup2']['prem_year'];?><br />
<b>Schauspieler als Rolle:</b><br />
<?php
$roles = explode("\n",$_SESSION['admin']['setup2']['roles']);
$actors = explode("\n",$_SESSION['admin']['setup2']['actors']);

for($x=0; $x<count($roles); $x++){
	echo $actors[$x]." als ".$roles[$x]."<br>";
}
?><br />
<b>Inhalt:</b><?php echo nl2br($_SESSION['admin']['setup2']['dvd_content']); ?><br />
<br /><br />
<?php //Setup3 ?>
<b>Original/Copy:</b><?php echo $_SESSION['admin']['setup3']['original_or_copy']; ?><br />
<b>Anzahl Disks:</b><?php echo $_SESSION['admin']['setup3']['disks']; ?><br />
<b>Art:</b>
<?php 
$typ = preg_split("[,]",$_SESSION['admin']['setup3']['type']);
echo $typ[1]; 
?><br />
<b>neue Art:</b>
<?php 
echo $_SESSION['admin']['setup3']['newtype'];
?><br />
<b>DVD/Bluray:</b><?php echo $_SESSION['admin']['setup3']['dvd_or_bluray']; ?><br />
<b>Cover:</b><?php echo $_SESSION['admin']['filename']; ?><br />
<br /><br />
<?php  
if($_SESSION['admin']['setup3']['newowner']!=""){
	$owner=$_SESSION['admin']['setup3']['newowner'];
}
else{
	$ownerSel=explode(",",$_SESSION['admin']['setup3']['owner']);
	$owner= $ownerSel[1];
}
?>
<b>Besitzer:</b><?php echo $owner; ?>
<br /><br />
<br><img src="cover_big/<?php echo $_SESSION['admin']['filename']; ?>">
<br /><br />
<form action="index.php" method="post">
<input name="setup" type="hidden" value="4" />
<input type="submit" value="eintragen" />
</form>