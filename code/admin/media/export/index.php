<center>Medien exportieren</center>
<?php
$_SESSION['admin']['setup-typ']="exp";
include 'config/config.php';
include 'functions/mysql_prepared.php';
include 'check_and_export.php';
	
if(!isset($_POST['id'])||$_POST['id']==0){

	$con = mysql_connect ($host,$uname,$pword) ;
	mysql_select_db($dbank);
	mysql_query("SET NAMES 'utf8'");
	
	$res=mysql_query("SELECT mid,title FROM dvds ORDER BY title");
?>
<form action="index.php" method="post">
<table id='list' border="0" cellpadding="0" cellspacing="0">
<tr>
	<td id='head_row'>Nr.</td>
    <td id='head_row'>Titel</td>
    <td id='head_row'>exportieren</td>
</tr>
<?php
	$counter=1;
	while ($dsatz = mysql_fetch_assoc($res))
	{
		echo "<td align='right' id='row0'>".$counter."</td>";
		echo "<td id='row0'>".$dsatz['title']."</td>";
		echo "<td id='row0'><input type='checkbox' value='".$dsatz['mid'].",".$dsatz['title']."' name='id[]'></td>";
		echo "</tr>";
		$counter++;
	}
?>
</table>
<input type="hidden" name="setup" value="1" />
<input type="submit" value="exportieren">
</form>
<?php
}
?>