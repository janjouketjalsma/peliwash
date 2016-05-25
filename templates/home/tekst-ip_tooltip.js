// Temporary variables to hold mouse x-y pos.s
var tempX = 0
var tempY = 0
// Temporary variable to hold current element
var CurElement = ""
// Detect if the browser is IE or not.
// If it is not IE, we assume that the browser is NS.
var IE = document.all?true:false
// If NS -- that is, !IE -- then set up for mouse capture
if (!IE) document.captureEvents(Event.MOUSEMOVE)
// Set-up to use getMouseXY function onMouseMove
document.onmousemove = UpdateTooltipPosition;

function UpdateTooltipPosition(e) {
	if (IE) { // grab the x-y pos.s if browser is IE
	tempX = event.clientX + document.body.scrollLeft
	tempY = event.clientY + document.body.scrollTop
	} else {  // grab the x-y pos.s if browser is NS
	tempX = e.pageX
	tempY = e.pageY
	}  
	// catch possible negative values in NS4
	if (tempX < 0){tempX = 0}
	if (tempY < 0){tempY = 0}  

	tempX+=15;// SET OFFSET
	
	//Set position of current tooltip  
	if(CurElement!=""){//check if an element was selected
		document.getElementById(CurElement).style.left=tempX+"px";
		document.getElementById(CurElement).style.top=tempY+"px";
	}
	return true
}
function showtooltip(element){
	CurElement=element;
	document.getElementById(element).style.display="block";
}
function hidetooltip(element){
	CurElement=element;
	document.getElementById(element).style.display="none";
}