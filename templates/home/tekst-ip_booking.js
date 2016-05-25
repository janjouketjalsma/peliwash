/*
This script displays a booking form and processes the booking.
*/
//Init global response vars
var BookingFormResponse;
var BookingSubmitResponse;

function BookingForm(date,time){
	showoverlay();
	getData("index.php?page=booking&date="+date+"&time="+time,"BookingForm");
}
function BookingFormQ(){
	showoverlay();
	getData("index.php?page=booking&book="+document.quick.book.value,"BookingForm");
}
//Load Form
BookingFormProcess=function(){
	document.getElementById("overlaycontent").innerHTML=BookingFormResponse;
}
//Process booking
function BookingSubmitter(){
	//Set overlay style
	document.getElementById("creatingbooking").innerHTML='<img width="30px" src="img/loading.gif" />';
	document.getElementById("bookingform").style.display="none";
	document.getElementById("submitbutton").value="Bezig";
	document.getElementById("submitbutton").disabled = true;
	//Set GET vars
	var date		= document.booking.date.value;
	var time		= document.booking.time.value;
	var usercode	= document.booking.usercode.value;
	//Set POST vars
	var POSTvars	= "date="+date+"&time="+time+"&usercode="+usercode+checkboxValues("bookings[]");
	//Send data
	putData("index.php?page=booking","BookingSubmit",POSTvars);
}

BookingSubmitProcess=function(){
	document.getElementById("overlaycontent").innerHTML=BookingSubmitResponse;
}

function checkboxValues(name){
	var nodes = document.getElementsByName(name);
	var n;
	var values="";
	for (var i = 0; n = nodes[i]; i++) {
		if (n.checked == true) {
			values+="&"+name+"="+encodeURI(n.value);
		}
	}
	return values;
}
