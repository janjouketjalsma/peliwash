var TickerHidden=null;
var TickerStep=400/50;
function HideNewsticker(){
    document.getElementById("pelinieuws").style.left="-" + TickerHidden + "px";
    if(TickerHidden<405){
        TickerHidden+=TickerStep;
        setTimeout("HideNewsticker()",0);
    }
}
function ShowNewsticker(){
        document.getElementById("pelinieuws").style.left="-" + TickerHidden + "px";
    if(TickerHidden>0){
        TickerHidden-=TickerStep;
        setTimeout("ShowNewsticker()",0);
    }
}


