/*
 * XML loading, fading contentswitcher
 * by J.J. Tjalsma
 */
var SelectedDiv; //The ID of the DOM-element to use for the content
var ViewTime; //How long each slide is displayed (excluding load- and fadetime)
var FadeState; //current opacity
var SlideTime; //Time in milliseconds until next load
var FadeSpeed; //Time between steps (lower is quicker)
var CurrentSlide;//Getal 0 tot onbeperkt van de huidige dia, reset na laatste dia
var LoadEnabled;
function initXMLFader(){
    FadeState = 100;
    SelectedDiv = "switcher";
    ViewTime=6000;
    FadeSpeed=30;
    Content=null;
    CurrentSlide=0;
    //XMLfaderDisappear();
}
function XMLfaderDisappear(){
    if(FadeState>=0){
        document.getElementById(SelectedDiv).style.opacity=FadeState/100;
        FadeState-=10;
        setTimeout("XMLfaderDisappear()",FadeSpeed);
    }else{
        if(LoadEnabled){
            newXMLfaderContent("fill");
        }
    }
}

function newXMLfaderContent(status){
    if(status=="load"){
      //Zet laden van nieuwe items aan
      LoadEnabled=true;
        if (window.XMLHttpRequest ){// code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp3=new XMLHttpRequest();
        }else{// code for IE6, IE5
          xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp3.onreadystatechange=function(){
          if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
              Content=xmlhttp3.responseText;
              if(Content.length<=2){
                  //De laatste dia is gevonden (reactie van 3 tekens of minder)
                  XMLfaderDisappear();
                  //reset currentcounter
                  CurrentSlide=0;
                  //Zet laden van nieuwe items uit
                  //LoadEnabled=false;
                  //Toon nieuws op groot scherm
                  //setTimeout("NosGrow()",2000);
              }else{
                  CurrentSlide+=1;
                  XMLfaderDisappear();
              }
          }
        }
        xmlhttp3.open("GET","load.php?r=washokcontent&q="+CurrentSlide,true);
        xmlhttp3.send();
    }
    if(status=="fill"){
            document.getElementById(SelectedDiv).innerHTML=Content;
            setTimeout("XMLfaderAppear()",200);
    }
}
function XMLfaderAppear(){
    if(FadeState<=100){
        document.getElementById(SelectedDiv).style.opacity=FadeState/100;
        FadeState+=10;
        setTimeout("XMLfaderAppear()",FadeSpeed);
    }else{
        setTimeout("newXMLfaderContent('load')",ViewTime);
    }
}


