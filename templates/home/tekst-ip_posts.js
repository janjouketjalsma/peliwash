//This script gets posts and displays them in the overlay.

var singlepostResponse;//This will be the response var for viewPost

function viewPost(request){
	showoverlay();
	getData("index.php?page=post&action=singlepost&q="+request,"singlepost");
}

function singlepostProcess(){
	document.getElementById("overlaycontent").innerHTML=singlepostResponse;
}

var catpostsResponse;//This will be the response var for viewPosts

function viewPosts(request){
	showoverlay();
	getData("index.php?page=post&action=viewcat&q="+request,"catposts");
}

function catpostsProcess(){
	document.getElementById("overlaycontent").innerHTML=catpostsResponse;
}