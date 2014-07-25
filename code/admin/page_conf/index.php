Webseite konfigurieren
<br /><br />
<?php
include("functions/checkData.php");
$_SESSION['admin']['setup-typ']="pageconf";

if(!isset($_POST['setup'])||$_POST['setup']!=1){
//Config auslesen und bearbeiten
$conf = file("config/page_settings.php");

$conf_values = array();
$conf_names = array();
$counter = 0;

foreach($conf as $row){
	if(substr($row,0,1)=="$")
	{
		$rowSplit = explode("=",$row);
		$conf_values[$counter]=substr($rowSplit[1],0,strlen($rowSplit[1])-2);
		$conf_names[$counter]=substr($rowSplit[0],1,strlen($rowSplit[0])-1);
		
		$counter++;
	}
}

unset($conf);
?>

<table border="0" cellpadding="0" cellspacing="0">
<form name="conf_update" method="post" action="index.php">
<?php
$check_values="";
for($x=0; $x<count($conf_values); $x++){
	$check_values.="true,";
}
$check_values=substr($check_values,0,strlen($check_values)-1);
echo '<input name="check" id="check" type="hidden" value="'.$check_values.'">';

$counter=0;
foreach($conf_values as $value){
	$length=strlen($value);
	echo "<tr>";
	echo "<td align='right'>";
	echo str_replace("_"," ",$conf_names[$counter]).":";
	echo "</td>";
	echo "<td style='padding-left:30px;'>";

	if(preg_match("/true|false/",$value)){
		echo "<select name='".$conf_names[$counter]."'>";
		if($value=='true'){
			echo "<option value='true' selected>ja</options>";
			echo "<option value='false'>nein</options>";
		}
		else{
			echo "<option value='true'>ja</options>";
			echo "<option value='false' selected>nein</options>";
		}
		echo "</select>";
	}
	else if(preg_match("/\\d{".$length."}/",$value)){
		echo "<input name='".$conf_names[$counter]."' onkeypress='return blockenter(event);' onkeyup=\"checktext(this.value, '".$conf_names[$counter]."', ".$counter.", false, true)\" type='text' value='".$value."'>";
	}
	else{
		$value=substr($value,1,$length-1);
		$value=substr($value,0,$length-2);
		if(checkText($value,true)){
			echo "<input name='".$conf_names[$counter]."' onkeypress='return blockenter(event);' onkeyup=\"checktext(this.value, '".$conf_names[$counter]."', ".$counter.")\"  type='text' value='".$value."'>";
		}
		else{
			echo "&nbsp;";
		}
	}
	
	echo "</td>";
	echo "</tr>";
	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	$counter++;
}
?>
<tr>
<td colspan="2" align="center">
<input name="setup" type="hidden" value="1" />
<input id="sub" type="submit" value="updaten"/>
</td>
</tr>
</form>
</table>
<?php
}
else{
	include("admin/page_conf/check_and_update.php");
}
?>