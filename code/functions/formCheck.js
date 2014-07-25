function blockenter(event){
	if (event && event.which == 13)
		return false;
	else
		return true;
}
function enterSubmit(event, form){
	if (event && event.which == 13)
		form.submit();
}
function checksetup(){
	var status = document.getElementById('check').value.split(",");
	var check = true;
	
	for(var x=0; x<status.length; x++){
		if(status[x]=='false'){
			check=false;
			break;
		}
	}

	if(check){
		document.getElementById('sub').style.visibility="visible";
	}
	else{
		document.getElementById('sub').style.visibility="hidden";
	}
}
function setCheckField(fieldnum, value){
	var status = document.getElementById('check').value.split(",");
	status[fieldnum]=value;
	
	document.getElementById('check').value = status.join(",");
	
	checksetup();
}
function checktext(value, div_id, fieldnum, not_needed, is_number, alternative_id, is_name){
	var value_length = value.length;
	if(value_length>0){
		if(is_number){
			pattern = eval("/\\d{"+value_length+"}/");
		}
		else if(is_name){
			pattern = eval("/([A-Za-z0-9ß-üÀ-Ü-_]){"+value_length+"}/");
		}
		else{
			pattern = eval("/([A-Za-z0-9ß-üÀ-Ü\+\.\?\&\(\)\|]|\\s|-|:|,|#|!|'|\"){"+value_length+"}/");	
		}
		
		if(!pattern.test(value)){
			document.getElementsByName(div_id)[0].style.backgroundColor="#FF6347";
			setCheckField(fieldnum, false);
			return false;
		}
		else{
			document.getElementsByName(div_id)[0].style.backgroundColor="#C0FF3E";
			setCheckField(fieldnum, true);
			return true;
		}
	}
	else{
		if(not_needed){
			document.getElementsByName(div_id)[0].style.backgroundColor="#FFFFFF";
			
			if(alternative_id!=""&&alternative_id!=undefined){
				setCheckField(fieldnum, checkselect(document.getElementsByName(alternative_id)[0].value,alternative_id,fieldnum));
			}
			else{
				setCheckField(fieldnum, true);
			}
			
			return true;
		}
		else{
			document.getElementsByName(div_id)[0].style.backgroundColor="#FFFFFF";
			setCheckField(fieldnum, false);
			return false;
		}
	}
}
function checkselect(value, div_id , fieldnum, not_needed, alternative_id){
	if(value==""){
		if(not_needed){
			document.getElementsByName(div_id)[0].style.backgroundColor="#FFFFFF";
			
			if(alternative_id!=""&&alternative_id!="undefined"){
				setCheckField(fieldnum,checktext(document.getElementsByName(alternative_id)[0].value, alternative_id, fieldnum));
			}
			else{
				setCheckField(fieldnum, true);
			}
			
			return true;
		}
		else{
			document.getElementsByName(div_id)[0].style.backgroundColor="#FFFFFF";
			setCheckField(fieldnum, false);
			return false;
		}
	}
	else{
		document.getElementsByName(div_id)[0].style.backgroundColor="#C0FF3E";
		setCheckField(fieldnum, true);
		return true;
	}	
}
function checkdate(values, fieldnum){
	var check=true;
	
	for(var x=0; x<3; x++){
		var value = document.getElementsByName(values[x])[0].value;
		if(value==""){
			check=false;
			break;
		}
	}
	
	if(check){
		document.getElementsByName(values[0])[0].style.backgroundColor="#C0FF3E";
		document.getElementsByName(values[1])[0].style.backgroundColor="#C0FF3E";
		document.getElementsByName(values[2])[0].style.backgroundColor="#C0FF3E";
		setCheckField(fieldnum, true);
	}
	else{
		document.getElementsByName(values[0])[0].style.backgroundColor="#FFFFFF";
		document.getElementsByName(values[1])[0].style.backgroundColor="#FFFFFF";
		document.getElementsByName(values[2])[0].style.backgroundColor="#FFFFFF";
		setCheckField(fieldnum, false);	
	}
}
function checkfile(value, div_id , fieldnum, message_id){
	if(value!=""){
		var filename = value.split("\\");
		value = filename[filename.length-1];
		
		var value_length = value.length-4;
		
		var pattern = eval("/([a-zA-Z0-9\-\_]){"+value_length+"}(\.)(([jJ][pP][gG])|([jJ][pP][eE][gG])|([pP][nN][gG]))/");

		if(pattern.test(value)){
			document.getElementById(message_id).innerHTML="";
			document.getElementsByName(div_id)[0].style.backgroundColor="#C0FF3E";
			setCheckField(fieldnum, true);
		}
		else{
			document.getElementById(message_id).style.color="#FF0000";
			document.getElementById(message_id).innerHTML="Die Anforderungen sind nicht erfüllt!!!";
			document.getElementsByName(div_id)[0].style.backgroundColor="#FF6347";
			setCheckField(fieldnum, false);	
		}
	}
	else{
		document.getElementById(message_id).innerHTML="";
		document.getElementsByName(div_id)[0].style.backgroundColor="#FFFFFF";
		setCheckField(fieldnum, false);	
	}
}
function checkRoleRActor(rolesvalue, actorsvalue, act_id, actnum, rol_id, rolnum){
	var roles = rolesvalue.split("\n")
	var actors = actorsvalue.split("\n");
	
	var rolesAmount = roles.length;
	var actorsAmount = actors.length;
	
	var checkRolesText = checktext(rolesvalue, 'roles', rolnum);
	
	var checkActorsText = checktext(actorsvalue, 'actors', actnum);
	
	if(roles[rolesAmount-1]==""){
		rolesAmount--;
	}
	if(actors[actorsAmount-1]==""){
		actorsAmount--;
	}
	
	var diff = rolesAmount-actorsAmount;
	
	if(diff==0){
		if(checkRolesText){
			document.getElementById(rol_id).innerHTML="";
			setCheckField(rolnum, true);
		}
		if(checkActorsText){
			document.getElementById(act_id).innerHTML="";
			setCheckField(actnum, true);
		}
	}
	else if(diff>0){
		if(checkActorsText){
			document.getElementById(act_id).style.color="#FF0000";
			document.getElementById(act_id).innerHTML="Zu wenig Schauspieler ausgewählt";
			setCheckField(actnum, false);
		}
	}
	else{
		if(checkRolesText){
			document.getElementById(rol_id).style.color="#FF0000";
			document.getElementById(rol_id).innerHTML="Zu wenig Rollen angegeben";
			setCheckField(rolnum, false);
		}
	}
}
function countSelectedOptions(selectField){
	var selectedOptions = new Array();
	
	if(selectField.selectedIndex>=0){
		while(selectField.selectedIndex>=0){
			selectedOptions.push(selectField.selectedIndex);
			selectField.options[selectField.selectedIndex].selected = false;
		}
		
		for(var x=0; x<selectedOptions.length; x++){
			selectField.options[selectedOptions[x]].selected = true;
		}
	}
	else{
		return 0;
	}
	
	return selectedOptions.length;
}
function checkBoth(first, first_check, second, second_check){
	var first_value=document.getElementsByName(first)[0].value;
	var second_value=document.getElementsByName(second)[0].value;
	
	if(first_check){
		document.getElementsByName(second)[0].style.visibility="hidden";
	}
	else{
		if(first_value==""){
			document.getElementsByName(second)[0].style.visibility="visible";
		}
	}
	
	if(second_check){
		document.getElementsByName(first)[0].style.visibility="hidden";
	}
	else{
		if(second_value==""){
			document.getElementsByName(first)[0].style.visibility="visible";
		}
	}
}
function checkPassword(value, div_id, fieldnum, secondValue){
	var value_length = value.length;
	if(value_length>=0){
		if(value_length<8){
			value_length=8;
		}
		pattern = eval("/([A-Za-z0-9ß-üÀ-Ü-?!:#+*.]){"+value_length+"}/");
		smallChar = eval("/[a-zß-ü]+/");
		bigChar = eval("/[A-ZÀ-Ü]+/");
		number = eval("/[0-9]+/");
		special = eval("/[-?!:#+*.]+/");
		
		if(pattern.test(value)&&smallChar.test(value)&&bigChar.test(value)&&number.test(value)&&special.test(value)){
			document.getElementsByName(div_id)[0].style.backgroundColor="#C0FF3E";
			setCheckField(fieldnum, true);
			return true;
		}
		else{
			document.getElementsByName(div_id)[0].style.backgroundColor="#FF6347";
			setCheckField(fieldnum, false);
			return false;
		}
	}
	else{
		document.getElementsByName(div_id)[0].style.backgroundColor="#FFFFFF";
		setCheckField(fieldnum, false);
		return false;
	}
}
function checkEquality(value1, value2, errorDiv, errorMessage){
	if(value1==value2){
		return true;
	}
	else{
		if(errorDiv!=""){
			document.getElementById(errorDiv).style.color="#FF6347";
			document.getElementById(errorDiv).style.fontSize="12px";
			if(errorMessage!=""){
				document.getElementById(errorDiv).innerHTML=errorMessage;
			}
			else{
				document.getElementById(errorDiv).innerHTML="Die Werte stimmen nicht überein";
			}
		}
		return false;
	}
}