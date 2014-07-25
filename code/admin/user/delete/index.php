Benutzer löschen

<?php 
$_SESSION['admin']['setup-typ']="userdel";
include 'config/config.php';
include 'check_and_delete.php';

if(!isset($_POST['id'])||$_POST['id']==0){

	$con = mysql_connect ($host,$uname,$pword) ;
	mysql_select_db($dbank);
	mysql_query("SET NAMES 'utf8'");
	
	$res=mysql_query("SELECT uid,name,surname FROM user WHERE uid!=1 ORDER BY name");
?>
<form action="index.php" method="post">
<table id='list' border="0" cellpadding="0" cellspacing="0">
<tr>
	<td id='head_row'>Nr.</td>
    <td id='head_row'>Name</td>
    <td id='head_row'>Löschen</td>
</tr>
<?php
	$counter=1;
	while ($dsatz = mysql_fetch_assoc($res))
	{
		echo "<td align='right' id='row0'>".$counter."</td>";
		echo "<td id='row0'>".$dsatz['name']." ".$dsatz['surname']."</td>";
		echo "<td id='row0'><input type='checkbox' value='".$dsatz['uid'].",".$dsatz['name'].",".$dsatz['surname']."' name='id[]'></td>";
		echo "</tr>";
		$counter++;
	}
?>
</table>
<input type="hidden" name="setup" value="1" />
<input type="submit" value="Löschen">
</form>
<?php
}
?>