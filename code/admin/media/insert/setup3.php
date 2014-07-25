Medium hinzufügen (3/4)
<?php 
include("check_setup2.php");

$con = mysql_connect ($host,$uname,$pword) ;
mysql_select_db($dbank);
mysql_query("SET NAMES 'utf8'");

?>
<br /><br />
 <form action="index.php" method="post" enctype="multipart/form-data">
 <input name="setup" type="hidden" value="3" />
 <input name="check" id="check" type="hidden" value="false,true,false,false,false,false" />
 <table>
  <tr>
   <td style="padding-right:20px;">Original/Copy:</td>
   <td>
   	   <select name="original_or_copy"  onchange="checkselect(this.value, 'original_or_copy', 0)" style="width:200px;">
       	<option value=""></option>
   		<OPTION VALUE="0">Original</OPTION>
        <OPTION VALUE="1">Copy</OPTION>
       </select>
  </td>
  </tr>
  <tr>
  	<td>Anzahl Disks:</td>
    <td>
    	<select name="disks" onchange="checkselect(this.value, 'disks', 1, true)" style="width:200px;">
    	<option value=""></option>
        <?php
			for($x=1; $x<100; $x++){
				echo "<option value='".$x."'>".$x."</option>";
			}
		?>
        </select>
    </td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr>
   <td>Art:</td>
   <td>
   	   <select name="type"  onchange="checkBoth('type',checkselect(this.value, 'type', 2),'newtype',false);" style="width:200px;">
      	 <option value=""></option>
		  <?php
              $sql = "SELECT * FROM type ORDER BY type";
              $res = mysql_query($sql);
              $num = mysql_num_rows($res);
              
              while ($dsatz = mysql_fetch_assoc($res))
              {
                  echo "<OPTION VALUE=\"".$dsatz['tid'].",".$dsatz['type']."\">".$dsatz['type']."</OPTION>";
              }
          ?>
       </select>
  </td>
  </tr>
  <tr>
  	<td>wenn die Art nicht vorhanden ist bitte hier eintragen:</td>
    <td><input name="newtype" type="text"  onkeypress="return blockenter(event);" onkeyup="checkBoth('type',false,'newtype',checktext(this.value, 'newtype', 2));"  style="width:195px;"/></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
    <tr>
   <td>Besitzer:</td>
   <td>
   	   <select name="owner"  onchange="checkBoth('owner',checkselect(this.value, 'owner', 5),'newowner',false);" style="width:200px;">
      	 <option value=""></option>
		  <?php
              $sql = "SELECT * FROM owner ORDER BY owner";
              $res = mysql_query($sql);
              $num = mysql_num_rows($res);
              
              while ($dsatz = mysql_fetch_assoc($res))
              {
                  echo "<OPTION VALUE=\"".$dsatz['oid'].",".$dsatz['owner']."\">".$dsatz['owner']."</OPTION>";
              }
          ?>
       </select>
  </td>
  </tr>
  <tr>
  	<td>wenn der Besitzer nicht vorhanden ist bitte hier eintragen:</td>
    <td><input name="newowner" type="text"  onkeypress="return blockenter(event);" onkeyup="checkBoth('owner',false,'newowner',checktext(this.value, 'newowner', 5));"  style="width:195px;"/></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr>
   <td>Medium:</td>
   <td>   	   
       <select name="dvd_or_bluray"  onchange="checkselect(this.value, 'dvd_or_bluray', 3)" style="width:200px;">
       	<option value=""></option>
   		<OPTION VALUE="0">DVD</OPTION>
        <OPTION VALUE="1">Blu-ray</OPTION>
        <OPTION VALUE="2">Datei</OPTION>
       </select>
   </td>
  </tr>
  <tr>
  <td colspan="3">
  	Es sind nur JPEG und PNG Bilder erlaubt und die Namen dürfen nur Buchstaben (keine Umlaute), Unter- und Bindestriche enthalten.
  </td>
  </tr>
  <tr>
   <td>Cover:<div id="cover_message" class="notice" style="float:right;"></div></td>
   <td><input name="coverimg" type="file" onchange="checkfile(this.value, 'coverimg', 4, 'cover_message')"/></td>
  </tr>
  <tr>
   <td><input type="reset" value="Reset" onclick="window.location.replace('index.php');"/></td>
   <td><input id="sub" type="submit" value="Weiter" style="visibility:hidden;" /></td>
  </tr>
 </table>
 </form>
