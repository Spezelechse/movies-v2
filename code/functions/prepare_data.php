<?php
function includeNewAndSort($old, $newIds, $newNames, $roles=""){
	$oldIds = "";
	$oldNames = "";
	
	if(count($old)!=0){
		foreach($old as $elem){
			$oldSplit = explode(",",$elem);
			$oldIds .= $oldSplit[0].",";
			$oldNames .= $oldSplit[1].",";
		}
	}

	$arrNames = explode(",",strtolower($oldNames.$newNames));
	$arrIds = explode(",",$oldIds.$newIds);
	
	if($roles!=""){
		$arrRoles=explode(chr(13)."\n",$roles);
		
		array_multisort($arrNames, SORT_ASC, SORT_STRING, $arrIds, $arrRoles);
		
		$res[0] = implode(",",$arrIds);
		$res[1] = implode(",",$arrRoles);
	}
	else{
		array_multisort($arrNames, SORT_ASC, SORT_STRING, $arrIds);
		$res = implode(",",$arrIds);
	}
	
	return $res;
}

function collectIdsFromSelect($input){
	$ids = "";
	
	if(count($input)!=0){
		foreach($input as $elem){
			$inputSplit = explode(",",$elem);
			$ids .= $inputSplit[0].",";
		}
		
		$ids = substr($ids, 0, strlen($ids)-1);
	}
	
	
	return $ids;
}
?>