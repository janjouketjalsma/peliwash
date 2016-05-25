<html>
    <head>
		<style>
		.bubble{
			opacity:0.05;
			z-index:-1;
			left:0px;
			background-color:#00FFFF;
			position:absolute;
			-webkit-animation-timing-function: linear;
			-webkit-animation-name:bubble;
			-webkit-animation-duration: 20s;
		}		
		@-webkit-keyframes bubble{
			0% {
				margin-left:0px;
				bottom:-10%;
			}
			25%{
				margin-left:-30px;
			}
			75%{
				margin-left:30px;
			}
			100% {
				bottom:105%;
				margin-left:0px;
			}
		}
		</style>
        <link rel="stylesheet" href="templates/washok/default.css" type="text/css"/>
        {literal}
        <script type="text/javascript" src="Javascripts/ibjj.xmlhttpfade.js"></script>
        <script type="text/javascript" src="Javascripts/clock.js"></script>
        <script type="text/javascript" src="Javascripts/ibjj.peliwashfooter.js"></script>
        <script type="text/javascript" src="Javascripts/ibjj.NOS.js"></script>
        <script type="text/javascript" src="Javascripts/ibjj.newsticker.js"></script>
        <script type="text/javascript" src="Javascripts/raphael-min.js"></script>
        <script type="text/javascript">
        function start(){
            loadFooter();
            startTime();
            initXMLFader();
            //NosSchrink();
            newXMLfaderContent("load");
        }
        </script>
        {/literal}
    </head>
    <body onload="start()">
        <!--<input type="submit" onclick="movePlayer()"/>-->
        <div id="maincontainer">
            <div id="switcher"></div>
			{literal}
            <script>
				var DivId=1;
				function createDiv() { 
					var CurId=DivId;
					DivId++;
					var divTag = document.createElement("div"); 
					divTag.className = "bubble"; 
					divTag.style.color = "white"; 
					divTag.style.left = Math.floor(Math.random()*1922); 
					divTag.style.position = "absolute";
					var size=Math.floor(Math.random()*100);
					divTag.style.WebkitBorderRadius=size + "px";
					divTag.style.width=size + "px";
					divTag.style.height=size + "px";
					divTag.setAttribute('id', CurId);
					document.body.appendChild(divTag); 
					setTimeout(function(){destroyDiv(CurId)},20000);
					setTimeout("createDiv()",Math.floor(Math.random()*4000));
				} 
				function destroyDiv(pId){
					var node = document.getElementById(pId);
					node.parentNode.removeChild(node);
				}
				createDiv();
			</script>
			{/literal}
			<div id="date">{$data.1.date} <br><span style="font-size:25px; font-weight:bold"id="clock"/><br></div>
            
            <div id="main">
                <object style="position:absolute;top:25px;left:1380px;" id="MediaPlayer" classid="CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6" standby="Loading Windows Media Player components..." type="application/x-oleobject">
                    <param name="ShowDisplay" value="false">
                    <PARAM NAME="ShowStatusBar" VALUE="0">
                    <param name="wmode" value="opaque">
                    <param name="autoStart" value="false">
                    <param name="showControls" value="false">
                    <param name="WindowlessVideo" value="-1">
                    <!--<iframe id="player"src="http://nos.nl/journaal24.html" width="570" height="325" frameborder="0"  style="overflow:hidden;" scrolling="no"></iframe>-->
                    <embed id="Player" type="application/x-mplayer2" src="http://livestreams.omroep.nl/nos/journaal24-sb" height="176px" width="290px" name="MediaPlayer" showcontrols="0" showstatusbar="0" showdisplay="0" autostart="1" wmode="transparent">
                </object>
                
                
                <!--<div id="pelinieuws">
                    <p style="font-weight:bold;font-size: 25px; color:white;">Pelinieuws</p>
                    {$pelinieuws}
                </div>-->
                <div style="position:absolute;right:10px;top:10px;"id="clock_id"></div><script>draw_clock()</script>
            </div>
            <div id="footeroverlay">
            </div>
            <div id="footer">
            </div>
        </div>
        {$offline}
    </body>
</html>