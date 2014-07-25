<?php
if(isset($_POST['medId'])){
	$_SESSION['admin']['details-med-id']=$_POST['medId'];
}

$length = strlen($_SESSION['admin']['details-med-id']);
$pattern = "/\\d{".$length."}/";

if(!preg_match($pattern, $_SESSION['admin']['details-med-id'])){
	die("manipulation detectet film id");
}

$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "SELECT * FROM dvds WHERE mid = :id";
$stmt = $dbc->prepare($sql);

$stmt->bindParam(':id', $_SESSION['admin']['details-med-id']);

if($stmt->execute()){
	$data = $stmt->fetch(PDO::FETCH_BOTH);
}
else{
	die("Beim auslesen der Filmdaten ist ein Fehler aufgetreten");
}

$con = mysql_connect ($host,$uname,$pword) ;
mysql_select_db($dbank);
mysql_query("SET NAMES 'utf8'");

$data=str_replace("/","|",$data);
$data=str_replace("\\","|",$data);
$data=str_replace("– ","- ",$data);
$data=str_replace(";",",",$data);
$data=str_replace("`","'",$data);
//$data=str_replace(chr(10),"",$data);
//$data=str_replace(chr(13),"",$data);
?>
<div id="movie-edit-wrapper">
	<form name="movie-edit" method="post" action="index.php" enctype="multipart/form-data">
     <input name="check" id="check" type="hidden" value="true,true,true,true,true,true,true,true,true,true,true,true,true,true,true,true" />
    <div id="movie-edit-title" align="center">
    	<?php checkText($data['title'],true); $_SESSION['admin']['oldTitle']=$data['title'];?>
    	<input name="med_title" type="text" onkeyup="checktext(this.value, 'med_title', 0)" value="<?php echo $data['title']; ?>" style="width:94%"/>		
    </div>
    <div id="movie-edit-content">
   		<div id="left" style="float:left">
        	<?php checkText($data['content'],true); ?>
            <textarea name="med_content" onkeyup="checktext(this.value, 'med_content', 1)" cols="56" rows="24"><?php echo $data['content']; ?></textarea>
        </div>
        <div id="right" align="center">
        	<?php
				$cover =$data['cover'];
				$inputSplit = preg_split("[\.]",$cover);
				$length = strlen($inputSplit[0]);
				if(!preg_match(getPattern($length, 0, 3), $cover)){
					die("manipulation detectet ");
				}
				$_SESSION['admin']['cover']=$cover;
			?>
            <img id="movie-cover" style="float:none;" src="cover/<?php echo $cover; ?>" /><br />
            Es sind nur JPEG und PNG Bilder erlaubt und die Namen dürfen nur Buchstaben (keine Umlaute), Unter- und Bindestriche enthalten.<br />
            <input name="coverimg" type="file" onchange="checkfile(this.value, 'coverimg', 14, 'cover_message')" style="width:250px;"/>
            <div id="cover_message" style="height:15px;"></div>
        </div>
    </div>
    <div id="movie-edit-details">
    	<div id="movie-edit-cast">
        	<?php
			 $roles = str_replace(",","\n", $data['roles']);
				
			checkText($roles,true);
			
			$actors = getDataFromIds(explode(",", $data['actors']), "actor", "act_name", "aid");
			$actors = implode("\n",$actors);
			checkText($actors,true);
			?>
        
            <textarea name="roles" class="noresize2" onkeyup="checktext(this.value, 'roles', 2)" rows="17" cols="18"><?php echo $roles; ?></textarea><textarea name="actors" class="noresize2" onkeyup="checktext(this.value, 'actors', 3)" rows="17" cols="18"><?php echo $actors; ?></textarea>
        </div>
        <div id="movie-edit-infos">
        <table id="infos" cellpadding="0" cellspacing="0">
        <tr>
        	<td id="info-type" align="right" valign="top">
			Altersfreigabe:
			</td>
            <td id="info-value" align="left">
				<?php checkText($data['fsk'], true, true); ?>
                <select name="fsk" onchange="checkselect(this.value, 'fsk', 4)">
                <?php
                    $ages = array("0","6","12","16","18");
                    for($x=0;$x<5;$x++){
                        if($ages[$x]==$data['fsk']){
                            echo "<option value=\"".$ages[$x]."\" selected>".$ages[$x]."</option>";
                        }
                        else{
                            echo "<option value=\"".$ages[$x]."\">".$ages[$x]."</option>";
                        }
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
				<?php
                    $date = explode("-",$data['premiere']);
                    if($date[2]<=0||$date[2]>31){
                        die("manipulation detectet prem day");
                    }
                    if($date[1]<=0||$date[1]>12){
                        die("manipulation detectet prem mon");
                    }
                    if($date[0]<=1940||$date[0]>date("Y")){
                        die("manipulation detectet prem year");
                    }
                ?>            
                <select name="prem_day" onchange="checkdate(new Array('prem_day','prem_month','prem_year'), 5)">
                    <?php 
                    for($x=1; $x<=31; $x++){
                        if($x==$date[2]){
                            echo "<option value=\"".$x."\" selected>".$x."</option>";
                        }
                        else{
                            echo "<option value=\"".$x."\">".$x."</option>";
                        }
                    }
                    ?>
                </select>
                <select name="prem_month" onchange="checkdate(new Array('prem_day','prem_month','prem_year'), 5)">
                    <?php
                    $month = array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
                    for($x=0; $x<=11; $x++){
                        if(($x+1)==$date[1]){
                            echo "<option value=\"".($x+1)."\" selected>".$month[$x]."</option>";
                        }
                        else{
                            echo "<option value=\"".($x+1)."\">".$month[$x]."</option>";
                        }
                    }
                    ?>
                </select>
                <select name="prem_year" onchange="checkdate(new Array('prem_day','prem_month','prem_year'), 5)">
                    <?php 
                    for($x=date("Y"); $x>=1940; $x--){
                        if($x==$date[0]){
                            echo "<option value=\"".$x."\" selected>".$x."</option>";
                        }
                        else{
                            echo "<option value=\"".$x."\">".$x."</option>";
                        }
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
				<?php	
                    $type = getDataFromIds($data['type'], "type", "type", "tid");
                    $type = implode("",$type);
                    
                    checkText($type,true);
                ?>
                <select name="type" onchange="checkselect(this.value, 'type', 6, true, newtype)" style="width:200px;">
                  <?php
                      $sql = "SELECT * FROM type ORDER BY type";
                      $res = mysql_query($sql);
                      $num = mysql_num_rows($res);
                      
                      while ($dsatz = mysql_fetch_assoc($res))
                      {
                          if($dsatz['tid']==$data['type']){
                              echo "<option value=\"".$dsatz['tid'].",".$dsatz['type']."\" selected>".$dsatz['type']."</option>";
                          }
                          else{
                              echo "<option value=\"".$dsatz['tid'].",".$dsatz['type']."\">".$dsatz['type']."</option>";
                          }
                          
                      }
                  ?>
                </select>
            </td>
        </tr>
        <tr>
            <td id="info-type" align="right" valign="top">
            neue Art:
            </td>
            <td id="info-value" align="left">
            	<input type="text" name="newtype" onkeyup="checktext(this.value, 'newtype', 6, true, false, type)" style="width:194px;"/><br /><br />
            </td>
        </tr>
        <tr>
            <td id="info-type" align="right" valign="top">
            Anzahl Disks:
            </td>
            <td id="info-value" align="left">
            	<?php
                    checkText($data['num_disk'], true, true);
                ?>
                <select name="disks" onchange="checkselect(this.value, 'disks', 7)" style="width:200px;">
                <?php
                    for($x=1; $x<100; $x++){
                        if($data['num_disk']==$x){
                            echo "<option value='".$x."' selected>".$x."</option>";
                        }
                        else{
                            echo "<option value='".$x."'>".$x."</option>";
                        }
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
				<?php	
                    $owner = getDataFromIds($data['owner'], "owner", "owner", "oid");
                    $owner = implode("",$owner);
            
                    checkText($owner,true);
                ?>
                <select name="owner" onchange="checkselect(this.value, 'owner', 8, true, newowner)" style="width:200px;">
                  <?php
                      $sql = "SELECT * FROM owner ORDER BY owner";
                      $res = mysql_query($sql);
                      $num = mysql_num_rows($res);
                      
                      while ($dsatz = mysql_fetch_assoc($res))
                      {
                          if($data['owner']==$dsatz['oid']){
                              echo "<OPTION VALUE=\"".$dsatz['oid'].",".$dsatz['owner']."\">".$dsatz['owner']."</OPTION>";
                          }
                          else{
                              echo "<OPTION VALUE=\"".$dsatz['oid'].",".$dsatz['owner']."\">".$dsatz['owner']."</OPTION>";
                          }
                      }
                  ?>
               </select><br />
            </td>
        </tr>
            <td id="info-type" align="right" valign="top">
            neuer Besitzer:
            </td>
            <td id="info-value" align="left">
            	<input type="text" name="newowner" onkeyup="checktext(this.value, 'newowner', 8, true, false, owner)" style="width:194px;"/><br /><br />
            </td>
        </tr>        
        <tr>
            <td id="info-type" align="right" valign="top">
            Ausgeliehen an:
            </td>
            <td id="info-value" align="left">
            <?php
				checkText($data['borrower'],true,true);
				
                $borrower = getDataFromIds($data['borrower'], "borrower", "bor_name", "bid");
			?>
            	<input type="text" name="borrower" onkeyup="checktext(this.value, 'borrower', 8, true, false,'',true)" value="<?php echo $borrower[0]; ?>" style="width:194px;"/><br /><br />
            </td>
        </tr>
        <tr>
            <td id="info-type" align="right" valign="top">
            Original/Copy:         
            </td>
            <td id="info-value" align="left">
			   <?php
                    if($data['original_or_copy']<0&&$data['original_or_copy']>1){
                        die("manipulation detectet");
                    }
               ?>
               <select name="original_or_copy" onchange="checkselect(this.value, 'original_or_copy', 9)" style="width:200px;">
                    <?php
                        $ooc = array("Original","Copy");
                        for($x=0;$x<2;$x++){
                            if($data['original_or_copy']==$x){
                                echo "<OPTION VALUE=\"".$x."\" selected>".$ooc[$x]."</OPTION>";
                            }
                            else{
                                echo "<OPTION VALUE=\"".$x."\">".$ooc[$x]."</OPTION>";
                            }
                        }
                    ?>
               </select>
            </td>
        </tr>        
        <tr>
       		<td id="info-type" align="right" valign="top">Laufzeit:</td>
        	<td id="info-value" align="left"><?php checkText($data['length'], true, true); ?><input name="length" type="text" onkeyup="checktext(this.value, 'length', 10,false,true)" value="<?php echo $data['length']; ?>"  style="width:194px;"/></td>
        </tr>
        <tr>
       		<td id="info-type" align="right" valign="top">DVD/Blu-ray :</td>
        	<td id="info-value" align="left"><?php checkText($data['length'], true, true); ?>
            <select name="dvd_or_bluray" onchange="checkselect(this.value, 'dvd_or_bluray', 15)" style="width:200px;">
            <?php
			$dob = array("DVD","Blu-ray","Datei");
			for($x=0;$x<=2;$x++){
				if($data['dvd_or_bluray']==$x){
					echo "<OPTION VALUE=\"".$x."\" selected>".$dob[$x]."</OPTION>";
				}
				else{
					echo "<OPTION VALUE=\"".$x."\">".$dob[$x]."</OPTION>";
				}
			}
			?>
            </select><br /><br />
            </td>
        </tr>
        <tr>
        	<td id="info-type" align="right" valign="top">Genres:</td>
            <td id="info-value" align="left">
				<?php
                    $genre = getDataFromIds(explode(",", $data['genre']), "genre", "genre", "gid");
                    $genre = implode("",$genre);
					
					checkText($genre,true);
                ?>
               	<select name="genre[]" onchange="checkselect(this.value, 'genre[]', 11, true, 'newgenre')" multiple="multiple" size="5" style="width:200px;">
					<?php
                        $sql = "SELECT * FROM genre ORDER BY genre";
                        $res = mysql_query($sql);
                        $num = mysql_num_rows($res);
                        
						$genres = explode(",", $data['genre']);
						while ($dsatz = mysql_fetch_assoc($res))
                        {
							if($genres[0]==$dsatz['gid']){
								echo "<OPTION VALUE=\"".$dsatz['gid'].",".$dsatz['genre']."\" selected>".$dsatz['genre']."</OPTION>";
								array_shift($genres);
							}
							else{
                            	echo "<OPTION VALUE=\"".$dsatz['gid'].",".$dsatz['genre']."\">".$dsatz['genre']."</OPTION>";
							}
                        }
                    ?>
                </select>
                <textarea name="newgenre" class="noresize3" onkeyup="checktext(this.value, 'newgenre', 11, true, false, 'genre[]')" cols="22" rows="4"></textarea>
            </td>
        </tr>       
        </table>
        </div>
    </div>
    <div id="movie-edit-details" align="center">
    	<table id="infos" border="0" cellpadding="0" cellspacing="0" width="400">
        <tr>
        	<td id="info-type" align="right" valign="top">Publisher:</td>
            <td id="info-value" align="left">
				<?php
                    $publisher = getDataFromIds(explode(",", $data['publisher']), "publisher", "pub_name", "pid");
                    $publisher = implode("",$publisher);
					
                    checkText($publisher,true);
                ?>
               	<select name="publisher[]" onchange="checkselect(this.value, 'publisher[]', 12, true, 'newpublisher')" multiple="multiple" size="5" style="width:339px;">
					<?php
                        $sql = "SELECT * FROM publisher ORDER BY pub_name";
                        $res = mysql_query($sql);
                        $num = mysql_num_rows($res);
						
						$publisher = explode(",", $data['publisher']);
                        
                        while ($dsatz = mysql_fetch_assoc($res))
                        {
							if($publisher[0]==$dsatz['pid']){
								echo "<OPTION VALUE=\"".$dsatz['pid'].",".$dsatz['pub_name']."\" selected>".$dsatz['pub_name']."</OPTION>";
								array_shift($publisher);
							}
							else{
                            	echo "<OPTION VALUE=\"".$dsatz['pid'].",".$dsatz['pub_name']."\">".$dsatz['pub_name']."</OPTION>";
							}
                        }
                    ?>
                </select>
                <textarea name="newpublisher" class="noresize4" onkeyup="checktext(this.value, 'newpublisher', 12, true, false, 'publisher[]')" cols="40" rows="4"></textarea><br /><br />
            </td>
        </tr>
        <tr>
        	<td id="info-type" align="right" valign="top">Regisseure:</td>
            <td id="info-value" align="left">
            	<?php
                    $director = getDataFromIds(explode(",", $data['director']), "director", "dir_name", "did");
                    $director = implode("",$director);
					
                    checkText($director,true);
               ?>
               <select name="director[]" onchange="checkselect(this.value, 'director[]', 13, true, 'newdirector')" multiple="multiple" size="5" style="width:339px;">
					<?php
                        $sql = "SELECT * FROM director ORDER BY dir_name";
                        $res = mysql_query($sql);
                        $num = mysql_num_rows($res);
						
						$director = explode(",", $data['director']);
                        
                        while ($dsatz = mysql_fetch_assoc($res))
                        {
							if($director[0]==$dsatz['did']){
								echo "<OPTION VALUE=\"".$dsatz['did'].",".$dsatz['dir_name']."\" selected>".$dsatz['dir_name']."</OPTION>";
								array_shift($director);
							}
							else{
								echo "<OPTION VALUE=\"".$dsatz['did'].",".$dsatz['dir_name']."\">".$dsatz['dir_name']."</OPTION>";
							}
                            
                        }
                    ?>
               </select> 
               <textarea name="newdirector" class="noresize4" onkeyup="checktext(this.value, 'newdirector', 13, true, false, 'director[]')" cols="40" rows="4"></textarea>           
            </td>
        </tr> 
        </table>
        <input name="setup" type="hidden" value="2" />
        <input id="sub" type="submit" value="updaten"/>
    </div>
    </form>
</div>