<?php
unset($_POST['setup'], $_POST['check']);

$file = fopen("config/page_settings.php","w");

$names=array_keys($_POST);
$config="<?php\n";

$counter=0;
foreach($_POST as $param){
	$length=strlen($param);
	if(preg_match("/true|false/",$param)){
		$config.="$".$names[$counter]."=".$param.";\n";
	}
	else if(preg_match("/\\d{".$length."}/",$param)){
		$config.="$".$names[$counter]."=".$param.";\n";
	}
	else if(checkText($param,true)){
		$config.="$".$names[$counter]."=\"".$param."\";\n";
	}
	else{
		die("manipulation detectet update");
	}
	
	$counter++;
}

$config.="?>";

if(fwrite($file,$config)!=0){
	echo "Update erfolgreich";
	forward("index.php",2000);
}
else{
	echo "Update erfolgreich";
}
?>