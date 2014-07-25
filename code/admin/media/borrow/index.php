Medium verliehen
<?php 
$_SESSION['admin']['setup-typ']="bor";
include 'config/config.php';
include 'functions/mysql_prepared.php';
include 'check_and_update.php';
	
if(!isset($_POST['id'])||$_POST['id']==0){

	$con = mysql_connect ($host,$uname,$pword) ;
	mysql_select_db($dbank);
	mysql_query("SET NAMES 'utf8'");
	
	$res=mysql_query("SELECT mid,title,borrower FROM dvds WHERE borrowed=1 ORDER BY title");
	
	if(mysql_num_rows($res)>0)
	{
?>
<form action="index.php" method="post">
<table id='list' border="0" cellpadding="0" cellspacing="0">
<tr id='head_row'>
	<td>Nr.</td>
    <td>Titel</td>
    <td style="padding-right:30px;">ausgeliehen an</td>
    <td>zurück gegeben</td>
</tr>
<?php
	$counter=1;
	while ($dsatz = mysql_fetch_assoc($res))
	{
		if($counter%2==0){
			echo "<tr id='row0'>";
		}
		else{
			echo "<tr id='row1'>";
		}
		echo "<td align='right'>".$counter."</td>";
		echo "<td>".$dsatz['title']."</td>";
		echo "<td>".implode(getDataFromIds($dsatz['borrower'], "borrower", "bor_name", "bid"),"")."</td>";
		echo "<td><input type='checkbox' value='".$dsatz['mid'].",".$dsatz['title']."' name='id[]'></td>";
		echo "</tr>";
		$counter++;
	}
?>
</table>
<br />
<input type="hidden" name="setup" value="1" />
<input type="submit" value="updaten">
</form>
<?php
	}
	else{
		echo "<br><br>Keine Medien ausgeliehen<br>";
	}
}
?>