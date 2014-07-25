var scrolling = true;

function scrollingNeeded(){
	var oldOffSet = window.pageYOffset;
	window.scrollBy(0,1);
	if(oldOffSet!=window.pageYOffset){
		document.getElementById('scrollNavi').style.visibility="visible";
		window.scrollBy(0,-1);
		return;
	}
	window.scrollBy(0,-2);
	if(oldOffSet!=window.pageYOffset){
		document.getElementById('scrollNavi').style.visibility="visible";
		window.scrollBy(0,1);
		return;
	}
	window.scrollBy(0,1);
}
function scrollComplete(up,oldOffset)
{
	if(window.pageYOffset!=oldOffset){
		var pageOffSet = window.pageYOffset;
		if(up){
			var stepSize=-75;
		}
		else{
			var stepSize=75;
		}
		window.scrollBy(0,stepSize);
		window.setTimeout("scrollComplete("+up+","+pageOffSet+")", 1);
	}
}
function scrollSlow(up){
	if(scrolling){
		if(up){
			var stepSize=-2;
		}
		else{
			var stepSize=2;
		}
		window.scrollBy(0,stepSize);
		window.setTimeout("scrollSlow("+up+")", 30);
	}
	scrolling = true;
}
function scrollStop()
{
	scrolling=false;
}
function submitWithData(formName,conName,conId,actName,actId)
{
	var form = document.getElementsByName(formName)[0];

	//console.log(formName+" "+conName+" "+conId+" "+actName+" "+actId)
	document.getElementsByName(form.elements[0].name)[0].value=conId;
	document.getElementsByName(form.elements[0].name)[0].name=conName;
	
	document.getElementsByName(form.elements[1].name)[0].value=actId;
	document.getElementsByName(form.elements[1].name)[0].name=actName;
	
	
	document.getElementsByName(formName)[0].submit();
}

var scrollNaviVisibility;
function showBig(picture,width,height)
{
	scrollNaviVisibility=document.getElementById('scrollNavi').style.visibility;
	document.getElementById('scrollNavi').style.visibility="hidden";
	
	var marginTop = (window.innerHeight-height)/2;
	document.getElementById("big-picture-overlay").style.visibility="visible";
	$("#big-picture-overlay").fadeOut(0);
	document.getElementById("big-picture").style.backgroundImage="url(cover_big/"+picture+")";
	document.getElementById("big-picture").style.width=width+"px";
	document.getElementById("big-picture").style.height=height+"px";
	document.getElementById("big-picture").style.marginTop=marginTop+"px";
	
	$("#big-picture-overlay").fadeIn(800);
}
function hideBig()
{
	document.getElementById("big-picture-overlay").style.visibility="hidden";
	document.getElementById('scrollNavi').style.visibility=scrollNaviVisibility;
}
function showPDF(src)
{
	document.getElementById("pdf-overlay").style.visibility="visible";
	document.getElementById('pdf').src=src;
	$("#pdf-overlay").fadeOut(0);
	
	$("#pdf-overlay").fadeIn(800);
}
function hidePDF()
{
	window.location.replace('index.php');	
}
function showDiv(divName){
	document.getElementById(divName).style.visibility="visible";
	$("#"+divName).fadeOut(0);
	$("#"+divName).fadeIn(800);
}
function hideDiv(divName){
	$("#"+divName).fadeOut(400);
}
function importFromIMDB(){
	var text=document.getElementById("imdbData").value;
	if(document.getElementById("actors").value!=""){
		document.getElementById("actors").value+="\n";
		document.getElementById("roles").value+="\n";
	}
	var counter=0;
	
	for(x=0; x<text.length; x++){
		if(text.charCodeAt(x)==10){
			continue;
		}
		
		if(text.charCodeAt(x)==9){
			counter++;
			continue;
		}
		
		if(text.charCodeAt(x)==32){
			if(text.charCodeAt(x+1)==9||text.charAt(x+1)==""||text.charCodeAt(x+1)==40){
				continue;
			}
		}
		
		if(counter==0){continue;}
		if(counter==1){
			document.getElementById("actors").value+=text.charAt(x);
		}
		if(counter==2){continue;}
		if(counter==3){
			if(text.charCodeAt(x)==40){
				while(text.charCodeAt(x)!=41){
					x++;
				}
			}
			else{
				document.getElementById("roles").value+=text.charAt(x);
			}
			if(text.charCodeAt(x+1)==10){
				counter=0;
				document.getElementById("roles").value+="\n";
				document.getElementById("actors").value+="\n";
				continue;
			}
		}
	}
}

function changeOverviewTyp(value){
	console.log(value);
	if(value==0){
		document.getElementById("overviewTyp").value=1;
	}
	else{
		document.getElementById("overviewTyp").value=0;
	}
	
	document.overviewSelect.submit();
}

function showHint(str){
	var xmlhttp;
	
	if (str.length<=2){
		document.getElementById("searchHint").innerHTML="";
		document.getElementById("searchHint").style.visibility="hidden";
		return;
	}
	
	
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if(xmlhttp.responseText!=""){
				document.getElementById("searchHint").innerHTML=xmlhttp.responseText;
				document.getElementById("searchHint").style.visibility="visible";
			}
		}
	}
	
	xmlhttp.open("GET","search/getHint.php?hint="+str,true);
	xmlhttp.send();
}