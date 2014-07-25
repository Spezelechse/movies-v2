<?php
/** Wird hier ausgeswählt, was für ein regulärer ausdruck verwendet wird?
 *  @param length1 	->  länge des zu überprüfenden Strings oder der Id bei einem Select-Feld
 *  @param length2	->  länge des zu überprüfenden Names in einem Select-Feld
 *  @param typ		->  Art des Ausdrucks (0 = Text, 1 = Integer, 2 = Select-Feld, 3 = JPEG Bild)
 *	@return pattern ->  gibt den gewünschten Ausdruck zurück
 */
function getPattern($length1, $length2, $typ){
	if($typ==0){
		$pattern = "/([A-Za-z0-9ß-üÀ-Ü\.\+\?\&\(\)\|]|\s|-|:|,|#|!|'|\"){".($length1+1)."}/";
	}
	else if($typ==1){
		$pattern = "/\\d{".$length1."}/";
	}
	else if($typ==2){
		$pattern = "/^(\\d){".$length1."}(,)([A-Za-z0-9ß-üÀ-Ü\.\?\+\(\)\|]|\s|-|:|,|#|!|'|\"){".$length2."}/";
	}
	else if($typ==3){
		$pattern = "/([a-zA-Z0-9\-\_]){".$length1."}(\.)(([jJ][pP][gG])|([jJ][pP][eE][gG])|([pP][nN][gG]))/";
	}
	else if($typ==4){
		$pattern = "/^([A-Za-z0-9ß-üÀ-Ü]){".$length1."}([A-Za-z0-9ß-üÀ-Ü\.\+\?\&\(\)\|]|\s|-|:|,|#|!|'|\"){".$length2."}/";
	}
	else if($typ==5){
		$pattern = "/([A-Za-z0-9ß-üÀ-Ü\-_ ]){".$length1."}/";
	}
	else if($typ==6){
		$pattern = "/(([0-9]){4}(-)([0-1][0-9])(-)([0-2][0-9])){1}/";
	}
	return $pattern;
}

/** Überprüft die ausgewählten Werte in einem Select-Feld
 *  @param input		 	->  Die ausgewählten Werte
 *  @param is_needed		->  true, wenn das Select-Feld nicht leer sein darf 
 */
function checkSelect($input, $is_needed=false,  $split=true){
	if(isset($input)){
		if(!is_array($input)){
			$input = array($input);
		}
		for($x=0; $x<count($input); $x++){
			$inputSplit = preg_split("[,]", $input[$x]);

			$length1 = strlen($inputSplit[0]);
			
			if($length1==0){
				die("manipulation detectet sel0");
			}
		
			if($split){
				$length2 = strlen($inputSplit[1]);
				
				if(($length2==0&&$split)){
					die("manipulation detectet sel0");
				}
				
				if(!preg_match(getPattern($length1, $length2, 2),$input[$x])){
					die("manipulation detectet sel1");
				}				
			}
			else{
				if(!$split&&!preg_match(getPattern($length1, 0, 1),$input[$x])){
					die("manipulation detectet sel2");
				}
			}
		}
	}
	else if($is_needed){
		die("manipulation detectet sel2");
	}
}

/** Überprüft den Inhalt eines Textfeldes oder einer Textarea
 *  @param input 		-> Der Inhalt
 *  @param is_needed	-> true, wenn das Feld nicht leer sein darf
 *  @param is_int		-> true, wenn das Feld nur eine Zahl als Eingabe erhält
 */
function checkText($input, $is_needed=false, $is_int=false, $is_name=false, $is_file=false, $is_date=false){
	if(isset($input)&&$input!=""){
		$input=str_replace("\'","'",$input);
		$length = strlen($input)-1;
		
		if($is_int){
			if(!preg_match(getPattern(($length+1), 0, 1), $input)){
				die("manipulation detectet number");
			}
		}
		else if($is_name){
			if(!preg_match(getPattern(($length+1), 0, 5), $input)){
				die("manipulation detectet name");
			}
		}
		else if($is_file){
			$file_split=preg_split("[.]",$input);
			$length=strlen($file_split[0]);
			if(!preg_match(getPattern(($length), 0, 3), $input)){
				die("manipulation detectet file");
			}
		}
		else if($is_date){
			if(!preg_match(getPattern(($length+1), 0, 6), $input)){
				die("manipulation detectet file");
			}
		}
		else{
			$x=0;
			$max=ceil($length/500);
			$inputArray=array();
			
			while($x<$max){
				$inputArray[$x]=substr($input, $x*500,500);				
				$x++;
			}

			if(count($inputArray)==1){
				if(!preg_match(getPattern($length, 0, 0), $input)){
					die("manipulation detectet text1");
				}
			}
			else{
				$length = strlen($inputArray[0])-1;
				
				if(!preg_match(getPattern($length, 0, 0), $inputArray[0])){
					die("manipulation detectet text2");
				}
				
				for($x=1;$x<count($inputArray);$x++){
					$length = strlen($inputArray[$x]);
					$length--;
					
					if(!preg_match(getPattern(0, $length, 4), $inputArray[$x], $match)){
						die("manipulation detectet text3");
					}
				}
			}
		}
	}
	else if($is_needed){
		die("manipulation detectet text4");
	}
	
	return true;
}

/**
*/
function checkPassword($value, $is_needed=false){
	if($value!=""){
		$value_length=strlen($value);
		
		if($value_length<8){
			die("manipulation detectet pwd length");
		}
		
		$pattern = "/([A-Za-z0-9ß-üÀ-Ü\-?!:#+*.]){".$value_length."}/";
		$smallChar = "/[a-zß-ü]+/";
		$bigChar = "/[A-ZÀ-Ü]+/";
		$number = "/[0-9]+/";
		$special = "/[-?!:#+*.]+/";

		if(!preg_match($pattern, $value)){
			die("manipulation detectet pwd1");
		}
		
		if(!preg_match($smallChar, $value)||!preg_match($bigChar, $value)||!preg_match($number, $value)||!preg_match($special, $value)){
			die("manipulation detectet pwd2");
		}
	}
	else{
		if($is_needed){
			die("manipulation detectet pwd3");
		}
	}
}

/** Ändert die Bildgröße und speicher das Bild
 *  @param name 		-> Name des Bildes  
 *  @param org			-> Das Original des Bildes
 *  @param maxHeight 	-> Maximale Höhe
 *	@param maxWidth		-> Maximale Breite
 *	@param folder		-> Ordner in dem es gespeichert wird
 */
function resizeImageAndSave($name, $org, $maxHeight, $maxWidth, $folder, $png_or_jpg){
	$width = imageSX($org);
	$height = imageSY($org);
		
	if($maxHeight<$height){
		$heightRate = $height/$maxHeight;
	}
	else{
		$heightRate = 1;
	}
		
	if($maxWidth<$width){
		$widthRate = $width/$maxWidth;
	}
	else{
	 	$widthRate = 1;
	}
	
	if($widthRate<$heightRate){
		$rate=$heightRate;
	}
	else{
		$rate=$widthRate;
	}
	
	$newWidth = $width/$rate;
	$newHeight = $height/$rate;

	/*$trans = imageCreateTrueColor($width, $height);
	$black = imageColorAllocate ($trans, 0, 0, 0);
	imagecolortransparent($trans,$white,black);
	imagecopy($trans, $org, 0,0,0,0,$width, $height);*/

	$new = imageCreateTrueColor($newWidth, $newHeight);
	//$black = imageColorAllocate ($new, 0, 0, 0);
	//imagecolortransparent($new,$black);
	$color = imagecolorallocate($new,249,171,4);
	imagefill($new,0,0,$color);
	imageCopyResampled($new, $org,
					   0, 0,
					   0, 0,
					   $newWidth, $newHeight,
					   $width, $height);

	if($png_or_jpg==0){
		imagePNG($new, $folder."/".$name);
	}
	else if($png_or_jpg==1){
		imageJPEG($new, $folder."/".$name);
	}
	
	chmod($folder."/".$name,0755);
	
	imagedestroy($new);
}

/** Überprüft den Namen und Typ des Bildes und speichert es dann mit dem Namen des Films in die cover-Verzeichnisse
 *  @param input 		-> das $_FILES Object des Bildes  
 *  @param is_needed	-> true, wenn das Feld nicht leer sein darf
 */
function checkImage($input, $is_needed=false, $fileName, $overwrite=false){
	if(isset($input['name'])&&$input['name']!=""){
		$inputSplit = preg_split("[\.]",$input['name']);
		$png_or_jpg=0;

		$name = $fileName.".".$inputSplit[1];
	
		if(file_exists("cover/".$name)&&!$overwrite){
			$extend=2;
			
			while(file_exists("cover/".$fileName."-".$extend.".".$inputSplit[1])){
				$extend++;									 
			}
			
			$name=$fileName."-".$extend.".".$inputSplit[1];
		}	
		
		$_SESSION['admin']['filename']=$name;
		
		$length = strlen($inputSplit[0]);
		
		if(preg_match(getPattern($length, 0, 3), $input['name'])){
			if(preg_match("[([jJ][pP][gG])|([jJ][pP][eE][gG])]", $inputSplit[1])){
				$org = imageCreateFromJPEG($input['tmp_name']);
				$png_or_jpg = 1;
			}
			else if(preg_match("([pP][nN][gG])", $inputSplit[1])){
				$org = imageCreateFromPNG($input['tmp_name']);
			}
			
			resizeImageAndSave($name, $org, 300, 250, "cover", $png_or_jpg);
			resizeImageAndSave($name, $org, 800, 600, "cover_big", $png_or_jpg);
	
			imagedestroy($org);
		}
		else{
			die("manipulation detectet img0");
		}
	}
	else if($is_needed){
		die("manipulation detectet img1");
	}
}
?>