<b>Erweiterte Suche</b>
<?php
include "config/config.php";

if(!isset($_POST['searched'])){
unset($_SESSION['advancedSearchBackup']);
$con = mysql_connect ($host,$uname,$pword) ;
mysql_select_db($dbank);
mysql_query("SET NAMES 'utf8'");
?>
<div id="movie-search-wrapper">
	<form name="movie-search" method="post" action="index.php" enctype="multipart/form-data">
     <input name="check" id="check" type="hidden" value="true,true,true,true,true,true,true,true,true,true,true,true,true,true,true" />
    <div id="movie-search-title" align="center">
    	Titel: <input name="med_title" type="text" onkeyup="checktext(this.value, 'med_title', 0,true)" style="width:90%"/>		
    </div>
    <div id="movie-search-content">
   		<div id="left" style="float:left">
        	Beschreibung:<br>
            <textarea name="med_content" onkeyup="checktext(this.value, 'med_content', 1,true)" cols="45" rows="12"></textarea>
        </div>
        <div id="right" align="center">
            Publisher:<br>
            <select name="publisher[]" onchange="checkselect(this.value, 'publisher[]', 12, true)" multiple="multiple" size="5" style="width:339px;">
                <option value="" selected="selected"></option>
				<?php
                    $sql = "SELECT * FROM publisher ORDER BY pub_name";
                    $res = mysql_query($sql);
                    $num = mysql_num_rows($res);
                    
                    while ($dsatz = mysql_fetch_assoc($res))
                    {
                        echo "<OPTION VALUE=\"".$dsatz['pid']."\">".$dsatz['pub_name']."</OPTION>";
                    }
                ?>
            </select><br /><br />
           Regisseure:<br>
           <select name="director[]" onchange="checkselect(this.value, 'director[]', 13, true)" multiple="multiple" size="5" style="width:339px;">
          		<option value="" selected="selected"></option>
                <?php
                    $sql = "SELECT * FROM director ORDER BY dir_name";
                    $res = mysql_query($sql);
                    $num = mysql_num_rows($res);
                    
                    while ($dsatz = mysql_fetch_assoc($res))
                    {
                        echo "<OPTION VALUE=\"".$dsatz['did']."\">".$dsatz['dir_name']."</OPTION>";
                    }
                ?>
           </select> 
        </div>
    </div>
    <div id="movie-search-details">
    	<div id="movie-search-cast">
        	Darsteller:<br />
            <select name="actors[]" onchange="checkselect(this.value, 'actors[]', 2, true)" multiple="multiple" size="5" style="width:380px;">
                <option value="" selected="selected"></option>
				<?php
                    $sql = "SELECT * FROM actor ORDER BY act_name";
                    $res = mysql_query($sql);
                    $num = mysql_num_rows($res);
                    
                    while ($dsatz = mysql_fetch_assoc($res))
                    {
                        echo "<OPTION VALUE=\"".$dsatz['aid']."\">".$dsatz['act_name']."</OPTION>";
                    }
                ?>
            </select>
            <br /><br />
            Rollen:<br />
            <textarea name="roles" class="noresize5" onkeyup="checktext(this.value, 'roles', 3,true)" rows="17" cols="10"></textarea>
            <div id="info">Mehrere Rollen bitte durch einen Zeilenumbruch trennen.</div>
        </div>
        <div id="movie-search-infos">
        <table id="infos" cellpadding="0" cellspacing="0">
        <tr>
        	<td id="info-type" align="right" valign="top">
			Altersfreigabe:
			</td>
            <td id="info-value" align="left">
                <select name="fsk" onchange="checkselect(this.value, 'fsk', 4,true)">
                    <option value=""></option>
                    <?php
                        $ages = array("0","6","12","16","18");
                        for($x=0;$x<5;$x++){
                            echo "<option value=\"".$ages[$x]."\">".$ages[$x]."</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td id="info-type" align="right" valign="top">
			Premiere:
            </td>
            <td id="info-value" align="left">
                <select name="prem_day" onchange="checktext(this.value, 'prem_day', 5,true,true)">
                	<option value=""></option>
                    <?php 
                    for($x=1; $x<=31; $x++){
                        echo "<option value=\"".$x."\">".$x."</option>";
                    }
                    ?>
                </select>
                <select name="prem_month" onchange="checktext(this.value, 'prem_month', 5,true,true)">
                	<option value=""></option>
                    <?php
                    $month = array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
                    for($x=0; $x<=11; $x++){
                        echo "<option value=\"".($x+1)."\">".$month[$x]."</option>";
                    }
                    ?>
                </select>
                <select name="prem_year" onchange="checktext(this.value, 'prem_year', 5,true,true)">
                	<option value=""></option>
                    <?php 
                    for($x=date("Y"); $x>=1940; $x--){
                        echo "<option value=\"".$x."\">".$x."</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
			<td id="info-type" align="right" valign="top">					
			Art:
            </td>
            <td id="info-value" align="left">
                <select name="type" onchange="checkselect(this.value, 'type', 6, true)" style="width:200px;">
               		<option value=""></option>
					  <?php
                          $sql = "SELECT * FROM type ORDER BY type";
                          $res = mysql_query($sql);
                          $num = mysql_num_rows($res);
                          
                          while ($dsatz = mysql_fetch_assoc($res))
                          {
                              echo "<option value=\"".$dsatz['tid']."\">".$dsatz['type']."</option>"; 
                          }
                      ?>
                </select>
            </td>
        </tr>
        <tr>
            <td id="info-type" align="right" valign="top">
            Anzahl Disks:
            </td>
            <td id="info-value" align="left">
                <select name="disks" onchange="checkselect(this.value, 'disks', 7,true)" style="width:200px;">
                    <option value=""></option>
                    <?php
                        for($x=1; $x<100; $x++){
                            echo "<option value='".$x."'>".$x."</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td id="info-type" align="right" valign="top">
            Besitzer:
            </td>
            <td id="info-value" align="left">
                <select name="owner" onchange="checkselect(this.value, 'owner', 8, true)" style="width:200px;">
                	<option value=""></option>
					  <?php
                          $sql = "SELECT * FROM owner ORDER BY owner";
                          $res = mysql_query($sql);
                          $num = mysql_num_rows($res);
                          
                          while ($dsatz = mysql_fetch_assoc($res))
                          {
                              echo "<OPTION VALUE=\"".$dsatz['oid']."\">".$dsatz['owner']."</OPTION>";
                          }
                      ?>
               </select><br />
            </td>
        </tr>       
        <tr>
            <td id="info-type" align="right" valign="top">
            Original/Copy:         
            </td>
            <td id="info-value" align="left">
               <select name="original_or_copy" onchange="checkselect(this.value, 'original_or_copy', 9,true)" style="width:200px;">
               		<option value=""></option>
                    <?php
                        $ooc = array("Original","Copy");
                        for($x=0;$x<2;$x++){
                            echo "<OPTION VALUE=\"".$x."\">".$ooc[$x]."</OPTION>";
                        }
                    ?>
               </select>
            </td>
        </tr>        
        <tr>
       		<td id="info-type" align="right" valign="top">Laufzeit:</td>
        	<td id="info-value" align="left">
            <<input name="length_compare" type="radio" value="<">=<input name="length_compare" type="radio" value="LIKE">><input name="length_compare" type="radio" value=">">   
            <input name="length" type="text" onkeyup="checktext(this.value, 'length', 10,true,true)" style="width:98px;"/></td>
        </tr>
        <tr>
       		<td id="info-type" align="right" valign="top">DVD/Blu-ray:</td>
        	<td id="info-value" align="left">
            <select name="dvd_or_bluray" onchange="checkselect(this.value, 'dvd_or_bluray', 15)" style="width:200px;">
           		<option value=""></option>
				<?php
                $dob = array("DVD","Blu-ray","Datei");
                for($x=0;$x<=2;$x++){
                    echo "<OPTION VALUE=\"".$x."\">".$dob[$x]."</OPTION>";
                }
                ?>
            </select><br /><br />
            </td>
        </tr>
        <tr>
        	<td id="info-type" align="right" valign="top">Genres:</td>
            <td id="info-value" align="left">
               	<select name="genre[]" onchange="checkselect(this.value, 'genre[]', 11, true)" multiple="multiple" size="5" style="width:200px;">
                	<option value="" selected="selected"></option>
					<?php
                        $sql = "SELECT * FROM genre ORDER BY genre";
                        $res = mysql_query($sql);
                        $num = mysql_num_rows($res);
                        
						while ($dsatz = mysql_fetch_assoc($res))
                        {
                           	echo "<OPTION VALUE=\"".$dsatz['gid']."\">".$dsatz['genre']."</OPTION>";
						}
                    ?>
                </select>
            </td>
        </tr>       
        </table>
        </div>
    </div>
    <div id="movie-search-details" align="center">
        <input name="searched" type="hidden" value="true" />
        <input id="sub" type="submit" value="suche"/>
    </div>
    </form>
</div>
<?php
}
else{
	include "search/advancedSearch_Action.php";
}
?>