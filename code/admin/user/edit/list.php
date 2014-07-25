<?php
    include 'config/config.php';

	$con = mysql_connect ($host,$uname,$pword) ;
	mysql_select_db($dbank);
	mysql_query("SET NAMES 'utf8'");
	
	$res=mysql_query("SELECT uid,name,surname FROM user WHERE uid!=1 ORDER BY name");
?>
<table id='list' border="0" cellpadding="0" cellspacing="0">
<tr>
	<td id='head_row'>Nr.</td>
    <td id='head_row'>Name</td>
    <td id='head_row'>&nbsp;</td>
</tr>
<?php
	$counter=1;
	while ($dsatz = mysql_fetch_assoc($res))
	{
		echo "<td align='right' id='row0'>".$counter."</td>";
		echo "<td id='row0'>".$dsatz['name']." ".$dsatz['surname']."</td>";
		echo "<td id='row0'><a href='#' onclick='submitWithData(\"submitForm\",\"setup\",\"1\",\"userId\",".$dsatz['uid'].");'>edit</a></td>";
		echo "</tr>";
		$counter++;
	}
?>
</table>
<form name="editForm" method="post" action="index.php" style="visibility:hidden">
<input name="setup" type="hidden" value="1" />
<input name="userId" type="hidden" value="" />
</form>