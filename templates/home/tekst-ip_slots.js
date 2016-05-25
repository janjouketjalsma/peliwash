/*
This script checks for modified slots using a custom load.js
if there are modified slots, the whole day view is refreshed
*/
var DayUpdaterResponse="";
var DayLoaderResponse="";
var QuickmenuResponse="";
var DayLoaderTime="";//local version update time
//Check for updates
function DayUpdaterCheck(){
	getData("load.php?r=mtime","DayUpdater");
}

//Process DayUpdaterCheck's response
DayUpdaterProcess=function(){
	var curVersionTime = parseInt(document.getElementById("mtime").innerHTML);//set days version time
	var curTime=Math.round((new Date()).getTime() / 1000);//set current time
	var curAge=curTime - DayLoaderTime;//calculate age by comparison of local times
	var maxAge=300;//Set maximun allowed age of days (in seconds)
	if(parseInt(DayUpdaterResponse) > curVersionTime){
		document.getElementById("mtime").innerHTML=parseInt(DayUpdaterResponse);//Update mtime field
		DayLoaderInit();//update days
	}else if(curAge > maxAge){
		DayLoaderInit();//update days
	}else{
		setTimeout("DayUpdaterCheck()",5000);//set new timer
	}
}

//Load (updated) days and (updated) quickmenu
function DayLoaderInit(){
	getData("load.php?r=days","DayLoader");
	getData("load.php?r=quickmenu","Quickmenu");
}

var DayLoaderProcess=function(){
	DayLoaderTime=Math.round((new Date()).getTime() / 1000);//Set local version update time
	document.getElementById("slides").innerHTML=DayLoaderResponse; 
	$("#myController").jFlow({
	slides: "#slides",  // the div where all sliding divs are nested in
	controller: ".jFlowControl", // must be class, use . sign
	slideWrapper : "#jFlowSlide", // must be id, use # sign
	selectedWrapper: "jFlowSelected",  // just pure text, no sign
	width: "675px",  // this is the width for the content-slider
	height: "474px",  // this is the height for the content-slider
	duration: 1000,  // time in miliseconds to transition one slide
	prev: ".jFlowPrev", // must be class, use . sign
	next: ".jFlowNext" // must be class, use . sign
	});
	setTimeout("DayUpdaterCheck()",5000);//Set future checks
}

var QuickmenuProcess=function(){
	document.getElementById("quickmenu").innerHTML=QuickmenuResponse; 
}
/*
This script loads tooltips for the slots			
*/
//Temporary variables for date and time of selected slot
var selectedDate="";
var selectedTime="";
var slotTipResponse="";
//Slotinfo show and timer function
function slotTip(date,time){
	selectedDate=date;
	selectedTime=time;
	showtooltip("slotinfo");
	document.getElementById("slotinfo").innerHTML='<div style="text-align:center;width:100%"><img width="30px" src="img/loading.gif" /></div>';
	pretimer=setTimeout("slotTipLoad()",200);
}
//Slotinfo loader
function slotTipLoad(){
	getData('load.php?r=slot_tooltip&date='+selectedDate+'&time='+selectedTime,"slotTip");
}

//Slotinfo processor
var slotTipProcess=function(){
	document.getElementById("slotinfo").innerHTML=slotTipResponse;
}

//Slotinfo hide function
function cancelslot(){
	hidetooltip("slotinfo");
	clearTimeout(pretimer);
}