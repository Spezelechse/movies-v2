<?php
function link_page($linktext, $pagenum, $inputname, $actName="", $actId=0){
	echo '<a href="#" onclick="submitWithData(\'submitForm\',\''.$inputname.'\',\''.$pagenum.'\',\''.$actName.'\',\''.$actId.'\');">'.$linktext.'</a>';
}

function forward($link, $delay=0){
	echo "<script language =\"JavaScript\">";
	echo "window.setTimeout(\"window.location.replace('".$link."')\", ".$delay.");";
	echo "</script>";	
}

function spacer($width,$height){
	echo "<img width='".$width."' height='".$height."' src='style/images/spacer.gif'>";
}

function checkSession($lifeTime){
	if(isset($_SESSION['user'])&&isset($lifeTime)&&$lifeTime>=0){
		$diff=time()-$_SESSION['user']['lastAction'];
		$diff=$diff/60;
		
		if($diff>$lifeTime&&$lifeTime>0){
			include "logout.php";
		}
		else{
			$_SESSION['user']['lastAction']=time();
		}
	}
}
?>