Medium hinzufügen (2/4)
<?php 
include("check_setup1.php");

$con = mysql_connect ($host,$uname,$pword) ;
mysql_select_db($dbank);
mysql_query("SET NAMES 'utf8'");

?>
<br /><br />
 <form name="setup2" action="index.php" method="post">
 <input name="setup" type="hidden" value="2" />
 <input name="check" id="check" type="hidden" value="false,false,false,false,false,false" />
 <table>
  <tr>
   <td>Länge (in min):</td>
   <td><input name="length" type="text" onkeypress="return blockenter(event);" onkeyup="checktext(this.value, 'length', 0, false, true);" ons/></td>
  </tr>
  <tr>
   <td>FSK:</td>
   <td>
   	<select name="fsk"  onchange="checkselect(this.value, 'fsk', 1)">
    	<option value=""></option>
    	<option value="0">0</option>
        <option value="6">6</option>
        <option value="12">12</option>
        <option value="16">16</option>
        <option value="18">18</option>
   	</select>
   </td>
  </tr>
  <tr>
   <td>Premiere:</td>
   <td>
   	<select name="prem_day" onchange="checkdate(new Array('prem_day','prem_month','prem_year'), 2)">
   		<option value="">Tag</option>
    	<?php 
		for($x=1; $x<=31; $x++){
			echo "<option value=\"".$x."\">".$x."</option>";
		}
		?>
   	</select>
    <select name="prem_month" onchange="checkdate(new Array('prem_day','prem_month','prem_year'), 2)">
   	 	<option value="">Monat</option>
    	<?php
		$month = array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
		for($x=0; $x<=11; $x++){
			echo "<option value=\"".($x+1)."\">".$month[$x]."</option>";
		}
		?>
   	</select>
    <select name="prem_year" onchange="checkdate(new Array('prem_day','prem_month','prem_year'), 2)">
    	<option value="">Jahr</option>
    	<?php 
		for($x=date("Y"); $x>=1940; $x--){
			echo "<option value=\"".$x."\">".$x."</option>";
		}
		?>
   	</select>
   </td>
  </tr>
  <tr>
  <td colspan="2" style="font-size:14px;">
  Die Rollen müssen in der selben Reihenfolge wie die Schauspieler und jeweils in einer neuen Zeile angegeben werden.<br /><br />
  </td>
  </tr>
  <tr>
   <td>Schauspieler <input type="button" value="von IMDB importieren" onClick="showDiv('imdbImportOverlay')"/></td>
   <td>Rollen</td>
  </tr>
  <tr>
   <td>
   	 <textarea name="actors" id="actors" rows="10" cols="20" onkeyup="checkRoleRActor(document.getElementsByName('roles')[0].value, this.value, 'actors_check', 3, 'roles_check', 4);"></textarea>
   </td>
   <td>
  	 <textarea name="roles" id="roles" rows="10" cols="25" onkeyup="checkRoleRActor(this.value, document.getElementsByName('actors')[0].value, 'actors_check', 3, 'roles_check', 4);"></textarea>
   </td>
  </tr>
  <tr>
  	<td><div id="actors_check" class="notice" style="height:20px;"></div></td>
    <td><div id="roles_check" class="notice"></div></td>
  </tr>
  <tr>
  <td colspan="2">Inhalt:</td>
  </tr>
  <tr>
   <td colspan="2"><textarea name="dvd_content" cols="75" rows="10" onkeyup="checktext(this.value, 'dvd_content', 5, false)"></textarea></td>
  </tr>
  <tr>
   <td colspan="2" align="center"><input id="sub" type="submit" style="visibility:hidden;"  value="Weiter" /></td>
  </tr>
 </table>
 </form>
 <div id="imdbImportOverlay">
    <div id="importData" align="center">
    <textarea id="imdbData" cols="40" rows="40"></textarea><br />
    <input type="button" value="import" onClick="importFromIMDB(); hideDiv('imdbImportOverlay'); checkRoleRActor(document.getElementsByName('roles')[0].value, document.getElementsByName('actors')[0].value, 'actors_check', 3, 'roles_check', 4);"/>
    </div>
</div>
