<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 	<title>
    <?php
		include("config/page_settings.php");
		include "functions/function.php";
		echo $Title;
	?>
    </title>
 	<link rel="stylesheet" type="text/css" href="style/style.css" />
    <link rel="shortcut icon" href="favicon.ico"” type="image/x-icon">
    <script type="text/javascript" charset="utf-8" language="JavaScript" src="functions/formCheck.js"></script>
    <script type="text/javascript" charset="utf-8" language="JavaScript" src="functions/helpers.js"></script>
    <script type="text/javascript" src="jquery/jquery-1.4.4.min.js"></script>
</head>
<body onload="scrollingNeeded()">
<?php
checkSession($Session_Life_Time);
?>
<div id="big-picture-overlay" onclick="hideBig()" align="center">
	<div id="big-picture"></div>
</div>
<div id="pdf-overlay" onclick="hidePDF()" align="center">
	<iframe id="pdf" frameborder="0" width="1000" height="900"></iframe>
</div>
<form name="submitForm" method="post" action="index.php" style="visibility:hidden">
<input name="subContent" type="hidden" value=""/>
<input name="subAction" type="hidden" value=""/>
</form>	
<div id="wrapper">
    <div id="header">
        <div id="login" align="center">
        	<div id="login-wrapper">
				<?php if(!isset($_SESSION['user']['logged'])){ ?>
                <form id="loginForm" name="login" action="index.php" method="post">
                    <input name="name" type="text" onfocus="this.value=''" size="15" maxlength="30" style="width:100px;" value="Name..." />
                    <input name="password" type="password" onfocus="this.value=''" onkeypress="enterSubmit(event, document.getElementById('loginForm'));" onsize="15" maxlength="40" style="width:100px;" value="Passwort" />
                    <input name="content" type="hidden" value="1" />
                    <a href="#" onclick="document.login.submit()">
                        <div id="loginSubmit"></div>
                    </a>
                </form>
               
                <?php 
                }
                else{
                    echo "Eingeloggt als: <b>".$_SESSION['user']['name']."</b><br>";
                    link_page("Logout",5, "content");
                }
                ?>
            </div>
        </div>    	
        <div id="title" align="center">
        	<?php
			$chars="abcdefghijklmnopqrstuvwxyzäöüABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÜß.-+#? ,&!:0123456789";
			$char=array();
			
			for($x=0;$x<mb_strlen($chars,'UTF-8');$x++){
				$char[mb_substr($chars,$x,1,'UTF-8')]=$x+1;
			}
			
			$charWidth=array();
			$charHeight=0;
			$titleWidth=0;
			
			for($x=0; $x<mb_strlen($Title,'UTF-8'); $x++){
				$chr=mb_substr($Title,$x,1,'UTF-8');
				
				if($char[$chr]<10){
					$letter="style/spiegelfont/sf_0".$char[$chr].".png";
				}
				else if($char[$chr]<=mb_strlen($chars,'UTF-8')){
					$letter="style/spiegelfont/sf_".$char[$chr].".png";
				}
				
				$letterData = imageCreateFromPNG($letter);
				$breite = imageSX($letterData);
				$hoehe = imageSY($letterData); 
				imagedestroy($letterData);
			
				if($x==0){
					$charHeight=$hoehe;
				}
				$charWidth[$x]=$breite;
				$titleWidth+=$breite;
			}
			?>
			
			<div id="title_value" style="width:<?php echo $titleWidth;?>px;">
			<?php
			for($x=0; $x<mb_strlen($Title,'UTF-8'); $x++){
				$chr=mb_substr($Title,$x,1,'UTF-8');
				
				if($char[$chr]<10){
					$letter="style/spiegelfont/sf_0".$char[$chr].".png";
				}
				else if($char[$chr]<=mb_strlen($chars,'UTF-8')){
					$letter="style/spiegelfont/sf_".$char[$chr].".png";
				}
				
				echo "<div style='background-image:url(".$letter."); width:".$charWidth[$x]."px; height:".$charHeight."px; float:left;'></div>";
			}
			?>	
          </div>
       </div> 
    </div>