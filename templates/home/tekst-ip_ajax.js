function getData(url,processname){
	//document.getElementById("response").innerHTML+=processname+" - "+url+"</br>";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		window['xmlhttp' + processname]=new XMLHttpRequest();
	}else{// code for IE6, IE5
		window['xmlhttp' + processname]=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	window['xmlhttp' + processname].onreadystatechange=function(){
		if (window['xmlhttp' + processname].readyState==4 && window['xmlhttp' + processname].status==200){
			window[processname + 'Response']=window['xmlhttp' + processname].responseText;
			eval(processname+'Process()');
		}
	}
	window['xmlhttp' + processname].open("GET",url,true);
	window['xmlhttp' + processname].send();

}
function putData(url,processname,params){
	params=params;
	//document.getElementById("response").innerHTML=processname+" - "+url+params;
		if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		window['xmlhttp' + processname]=new XMLHttpRequest();
	}else{// code for IE6, IE5
		window['xmlhttp' + processname]=new ActiveXObject("Microsoft.XMLHTTP");
	}
	window['xmlhttp' + processname].open("POST",url,true);
	//Send the proper header information along with the request
	window['xmlhttp' + processname].setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	window['xmlhttp' + processname].setRequestHeader("Content-length", params.length);
	window['xmlhttp' + processname].setRequestHeader("Connection", "close");

	window['xmlhttp' + processname].onreadystatechange=function(){
		if (window['xmlhttp' + processname].readyState==4 && window['xmlhttp' + processname].status==200){
			window[processname + 'Response']=window['xmlhttp' + processname].responseText;
			eval(processname+'Process()');
		}
	}
	window['xmlhttp' + processname].send(params);

}