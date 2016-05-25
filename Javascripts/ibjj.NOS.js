/**
 * IBJJ NOS resizer
 * J.J.Tjalsma 2011
 */
var CurrentStep=0;
var NosSelectedElement="MediaPlayer";
var CurY=220;
var CurX=510;
var CurSize=900;
var GoalY=25;
var GoalX=1470;
var GoalSize=270;
var Step=4;
var StepY=Math.abs(CurY - GoalY)/(100/Step);
var StepX=Math.abs(CurX - GoalX)/(100/Step);
var StepSize=Math.abs(CurSize - GoalSize)/(100/Step);
var NosSpeed=50;//Range 0 to 100
function NosSchrink(){
    document.getElementById(NosSelectedElement).style.position="absolute";
    if(CurrentStep<100){
        document.getElementById(NosSelectedElement).style.top=CurY-(StepY) + "px";
        document.getElementById(NosSelectedElement).style.left=CurX-(StepX) + "px";
        document.getElementById(NosSelectedElement).style.width=CurSize-(StepSize) + "px";
        document.getElementById(NosSelectedElement).style.height=((CurSize-(StepSize))*0.6) + "px";
        CurY-=StepY;
        CurX+=StepX;
        CurSize-=StepSize;
        CurrentStep+=Step;
        setTimeout("NosSchrink()",100-NosSpeed);
    }else{
        setTimeout("HideNewsticker()",500);
        newXMLfaderContent("load");
    }
}
function NosGrow(){
    document.getElementById(NosSelectedElement).style.position="absolute";
    if(CurrentStep>0){
        document.getElementById(NosSelectedElement).style.top=CurY-(StepY) + "px";
        document.getElementById(NosSelectedElement).style.left=CurX-(StepX) + "px";
        document.getElementById(NosSelectedElement).style.width=CurSize+(StepSize) + "px";
        document.getElementById(NosSelectedElement).style.height=((CurSize+(StepSize))*0.6) + "px";
        CurY+=StepY;
        CurX-=StepX;
        CurSize+=StepSize;
        CurrentStep-=Step;
        setTimeout("NosGrow()",100-NosSpeed);
    }else{
        //Toon newsticker
        setTimeout("ShowNewsticker()",200);
        setTimeout("NosSchrink()",50000);
    }
}
