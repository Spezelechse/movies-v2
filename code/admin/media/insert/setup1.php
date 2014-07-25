Medium hinzufügen (1/4)
<br /><br />
<?php 
if($_SESSION['admin']['setup']!=0) unset($_SESSION['admin']['setup']); 

$con = mysql_connect ($host,$uname,$pword) ;
mysql_select_db($dbank);
mysql_query("SET NAMES 'utf8'");
?>
 <form action="index.php" method="post">
 <input name="setup" type="hidden" value="1" />
 <input name="check" id="check" type="hidden" value="false,false,false,false" />
 <table>
  <tr>
   <td>Title</td>
   <td colspan="2"><input name="title" type="text" onkeypress="return blockenter(event);" onkeyup="checktext(this.value, 'title', 0)"/></td>
  </tr>
  <tr>
   <td>Genre</td>
   <td>Publisher</td>
   <td>Regisseur</td>
  </tr>
  <tr>
   <td>
   	<select name="genre[]" multiple="multiple" size="10" onchange="checkselect(this.value, 'genre[]', 1, true, 'newgenre')">
    	<?php
        	$sql = "SELECT * FROM genre ORDER BY genre";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			
			while ($dsatz = mysql_fetch_assoc($res))
			{
				echo "<OPTION VALUE=\"".$dsatz['gid'].",".$dsatz['genre']."\">".$dsatz['genre']."</OPTION>";
			}
		?>
    </select>
   </td>
   <td>
   	<select name="publisher[]" multiple="multiple" size="10" onchange="checkselect(this.value, 'publisher[]', 2, true, 'newpublisher')">
       	<?php
        	$sql = "SELECT * FROM publisher ORDER BY pub_name";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			
			while ($dsatz = mysql_fetch_assoc($res))
			{
				echo "<OPTION VALUE=\"".$dsatz['pid'].",".$dsatz['pub_name']."\">".$dsatz['pub_name']."</OPTION>";
			}
		?>
   	</select>
   </td>
   <td>
   <select name="director[]" multiple="multiple" size="10" onchange="checkselect(this.value, 'director[]', 3, true, 'newdirector')">
		<?php
			$sql = "SELECT * FROM director ORDER BY dir_name";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			
			while ($dsatz = mysql_fetch_assoc($res))
			{
				echo "<OPTION VALUE=\"".$dsatz['did'].",".$dsatz['dir_name']."\">".$dsatz['dir_name']."</OPTION>";
			}
        ?>
   </select>
   </td>
  </tr>
  <tr>
  	<td colspan="3">Nicht vorhandene Genre, Publisher oder Regisseure können hier angegeben werden.<br />Immer nur einen pro Zeile.</td>
  </tr>
  <tr>
  	<td><textarea name="newgenre" class="noresize" id="area" onkeyup="checktext(this.value, 'newgenre', 1, true, false, 'genre[]')"></textarea></td>
    <td align="center"><textarea name="newpublisher" class="noresize" id="area" onkeyup="checktext(this.value, 'newpublisher', 2, true, false, 'publisher[]')"></textarea></td>
    <td><textarea name="newdirector" id="area" class="noresize" onkeyup="checktext(this.value, 'newdirector', 3, true, false, 'director[]')"></textarea></td>
  </tr>
  <tr>
   <td><input type="reset" value="Reset" onclick="window.location.replace('index.php');"/></td>
   <td colspan="2"><input id="sub" type="submit" style="visibility:hidden;" value="Weiter" /></td>
  </tr>
 </table>
 </form>
