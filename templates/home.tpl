<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <TITLE>peliwash </TITLE>
        <meta name="google-site-verification" content="rdZlXuTCPaNAhCpdTEnrA8gJAKwsFdQflybFnEFhu_c" />
        <LINK rel="stylesheet" href="templates/home/default.css" type="text/css">
        {literal}

        <!-- Javascripts -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script language="javascript" type="text/javascript" src="Javascripts/jquery.flow.1.2.js"></script>
        <script type="text/javascript" src="templates/home/javascripts.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_tooltip.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_ajax.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_booking.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_slots.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_overlay.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_activation.js" ></script>
		<script type="text/javascript" src="templates/home/tekst-ip_posts.js" ></script>
		<script type="text/javascript"> 
		//Disable enter key
			function checkEnter(e){
			 e = e || event;
			 return (e.keyCode || event.which || event.charCode || 0) !== 13;
			}
		</script>
        {/literal}
		<script type="text/javascript">
			//CANCEL SCRIPT
			// Read a page's GET URL variables and return them as an associative array.
			function getUrlVars()
			{
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

				for(var i = 0; i < hashes.length; i++)
				{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}

				return vars;
			}
			var urlVars=getUrlVars();
			var CancelformResponse;
			var getvars=location.search;
			function checkCancel(){
				if(urlVars["page"]=="cancel"){
					//Cancel it!
					showoverlay();
					getData("index.php"+getvars+"&form=TRUE","Cancelform");
					
					document.getElementById("overlaycontent").innerHTML='{$cancel}';
				}
			}
			function CancelformProcess(){
				document.getElementById("overlaycontent").innerHTML=CancelformResponse;
			}
			var GocancelResponse;
			function Gocancel(){
				putData("index.php?page=cancel","Gocancel",getvars);
			}
			function GocancelProcess(){
				document.getElementById("overlaycontent").innerHTML=GocancelResponse;
			}
			
			//GLOBAL Init script
			function initAll(){
				checkCancel();
				DayUpdaterCheck();
			}
		</script>
    </head>
    <body onload="initAll()">
		<!-- Info voor tooltips -->
		<div id="mtime" style="visibility:  hidden;display:none">0</div>
		<div id="lastupdated" style="display:none">{$lastupdated}</div>
		<div id="slotinfo" class="tooltip"></div>
		<div id="activeren" style="text-align:left" class="tooltip">
			<p class="tooltiptitle">Beginnen met Wassen</p>
			Klik hier om je account te activeren. Nadat je account geactiveerd is kun je reserveren.
		</div>
        <!-- einde tooltips -->
        <!-- begin content -->
        <div id="topnav">peliwash
			<div id="topnav-right">
				<!-- quickmenu -->
					<div class='quickmenu'>
						<form name="quick">
							Reserveren
							<span id="quickmenu"></span>
							<button onClick="BookingFormQ()">>></button>
						</form>
					</div>
			</div>
        </div>
        <div id='container'>
            <div id='header'>
                <div id="counter"></div>
                <div onmouseover="showtooltip('activeren')" onmouseout="hidetooltip('activeren')" onclick="acta()" style="position:absolute; top:10px;right:0px;"><img height="100px" src="templates/home/peliwash_activeren.png" /></div>
                <div title="peliwash "style="display:block;margin-top:20px;"><img height="80px" src='img/peliwash.png' /></div>
				<div style="position:relative;margin-top:12px;color:white;font-size:22px;font-weight:bold;">
					<div style="position:absolute;left:0px;">Wassen</div>
					<div style="position:absolute;right:0px;width:290px">peliwash</div>
				</div>
				
            </div>
        </div>
        <!-- main div -->
        <div id='main_outer'>
            <div id='main_inner'>
                <div id="slideapp">
                    <!-- sliding div -->
                    <div id="jFlowSlide">
                        <div id="slides">
                        {foreach name=counter from=$slots key=day item=slot}
                            {if $smarty.foreach.counter.index == 0 || $smarty.foreach.counter.index is div by 3}
                            <div class="slide-wrapper">
                                <div id="myController"><span class="jFlowControl"></span></div>
                                <table><tr>
                            {/if}
                                    <td class='noday'>
                                        <noscript>Voor deze website is Javascript nodig</noscript>
                                        <div style="text-align:center;width:100%"><img width="30px" src="img/loading.gif" /></div>
                                    </td>
                            {if $smarty.foreach.counter.index == 2 || $smarty.foreach.counter.iteration is div by 3}
                                </tr></table>
                            </div>
                            {/if}
                            {/foreach}
                            {if $smarty.foreach.counter.iteration is not div by 3}
                                </tr></table>
                            </div>
                            {/if}
                        </div>
                    </div>
                    <div id="myController" class="controller1">
                        <span title="Vorige drie dagen" class="jFlowPrev"><img src="templates/home/pijllinks.png"/></span><br>
                    </div>
                    <div id="myController" class="controller2">
                        <span title="Volgende drie dagen" class="jFlowNext"><img src="templates/home/pijlrechts.png"/></span>
                    </div>
                </div>
                <!-- hier meer andere dingen -->
                <div class="peliwashinfo">{$peliwashposts}</div>
		</div>
        </div>
		<div id="response"></div>
        <div style="float:right;color:#808080"><a href="?page=admin">admin</a></div>
        <!--message overlay-->
        <div id="overlay" onclick="hideoverlay()">
        </div>
        <div id="overlaycontainer">
			<a onclick="hideoverlay()" style="font-size:30px;color:red;font-weight:bold;float:right;cursor:pointer"><div style="padding:5px;background-color:grey">X</div></a>
			<div id="overlaycontent"></div>
        </div>
        <div id="selectedslide" style="visibility:  hidden">0</div>
    </body>
</html>
