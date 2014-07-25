<?php
    include 'config/config.php';

	$con = mysql_connect ($host,$uname,$pword) ;
	mysql_select_db($dbank);
	mysql_query("SET NAMES 'utf8'");
	
	$res=mysql_query("SELECT mid,title FROM dvds ORDER BY title");
?>
<table id='list' border="0" cellpadding="0" cellspacing="0">
<tr>
	<td id='head_row'>Nr.</td>
    <td id='head_row'>Titel</td>
    <td id='head_row'>&nbsp;</td>
</tr>
<?php
	$counter=1;
	while ($dsatz = mysql_fetch_assoc($res))
	{
		echo "<td align='right'  id='row0'>".$counter."</td>";
		echo "<td  id='row0'>".$dsatz['title']."</td>";
		echo "<td id='row0'><a href='#' onclick='submitWithData(\"submitForm\",\"setup\",\"1\",\"medId\",".$dsatz['mid'].");'>edit</a></td>";
		echo "</tr>";
		$counter++;
	}
?>
</table>
<form name="editForm" method="post" action="index.php" style="visibility:hidden">
<input name="setup" type="hidden" value="1" />
<input name="medId" type="hidden" value="" />
</form>