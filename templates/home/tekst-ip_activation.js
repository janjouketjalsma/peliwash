/*
* This function displays an activation form and processes and activation
*/
//init vars
var actaResponse;
var actbResponse;
var actcResponse;

function acta(){//Get the first step
	showoverlay();
	getData("index.php?page=activation","acta");
}
function actaProcess(){
	document.getElementById("overlaycontent").innerHTML=actaResponse;
}

function actb(){
	//Set style for overlay (and contents)
	document.getElementById("checkingact").innerHTML='<img width="30px" src="img/loading.gif" />';
	document.getElementById("ActAform").style.display="none";
	document.getElementById("submitbutton").value="Controleert gegevens";
	document.getElementById("submitbutton").disabled = true;
	//Collect data for request
	
	var code		= document.actaF.code.value;
	var name		= document.actaF.name.value;
	var email		= document.actaF.email.value;
	var POSTvars	= "code="+code+"&name="+name+"&email="+email;
	
	//Send request
	putData("index.php?page=activation","actb",POSTvars);
}

function actbProcess(){
	document.getElementById("overlaycontent").innerHTML=actbResponse;
}

function actc(){
	//Set style for overlay (and contents)
	document.getElementById("checkingact").innerHTML='<img width="30px" src="img/loading.gif" />';
	document.getElementById("ActBform").style.display="none";
	document.getElementById("submitbutton").value="Controleert gegevens";
	document.getElementById("submitbutton").disabled = true;
	//Collect data for request
	var usercode	= document.actbF.usercode.value;
	var userID		= document.actbF.userID.value;
	var usercode_challenge	= document.actbF.usercode_challenge.value;
	var POSTvars="usercode="+usercode+"&userID="+userID+"&usercode_challenge="+usercode_challenge;
	//Send request
	putData("index.php?page=activation","actc",POSTvars);	
}
function actcProcess(){
	document.getElementById("overlaycontent").innerHTML=actcResponse;
}