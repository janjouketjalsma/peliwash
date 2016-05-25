/*
This script handles the overlay
*/
function showoverlay(){
	document.getElementById("overlay").style.display="block";
	document.getElementById("overlaycontainer").style.display="block";
	document.getElementById("overlaycontent").innerHTML='<div style="text-align:center;width:100%"><img width="30px" src="img/loading.gif" /></div>';
}
function hideoverlay(){
	document.getElementById("overlay").style.display="none";
	document.getElementById("overlaycontainer").style.display="none";
	document.getElementById("overlaycontent").innerHTML="";
}