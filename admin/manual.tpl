{if $authenticated}
<html>
    <head>
        {literal}
        <script language="Javascript1.2">
  <!--
  function printpage() {
  window.print();
  }
  //-->
</script>
        {/literal}
		    <style type="text/css">

    #printable { display: none; }

    @media print
    {
        #non-printable { display: none; }
        #printable { display: block; }
    }
    </style>
    </head>
    <body onload="printpage()">
	<div id="non-printable" style="height:100%;text-align:center;"><br><br><br><br><br><a href="">TERUG naar admin</a><br><br>
	</div>
<div style="padding:30px;height:90%;font-family:Verdana;font-size:11px;">
        <img height="90px" src="img/peliwash.png"><br>
        <hr>
        <p>{$date}</p>
        <br>
        <br>
        <p>Beste (nieuwe) bewoner van {$userinfo.roomnumber},<br></p>
        <p>Om gebruik te kunnen maken van de wasfaciliteiten op de peliwash moet je een wasafspraak gaan maken.
        Een wasafspraak maak je online, via onze website.<br><br>
        Deze brief geeft je meer informatie over het activeren en het gebruik van onze website.
        <br><br></p>


        <p><u>Activeren</u><br>
        -  Ga naar <i>www.peliwash.nl</i><br>
        -  Klik op account activeren<br>
        -  Volg de stappen 1 t/m 3
        <br></p>

        <p><u>Jouw gegevens</u><br>
        -  <font style="font-size:10px;font-weight:bold;;">peliwash</font> activeringscode:  {$userinfo.code}<br>
        -  <font style="font-size:10px;font-weight:bold;;">peliwash</font> gebruikerscode: _______________________ (zie bevestigings-email na activeren)
        <br></p>

        <p><u>Wassen op reguliere tijden</u><br>
        Voor het wassen heb je wasmuntjes nodig, deze kun je tijdens spreekuren bij het beheer halen.
        Een wasmuntje kost &euro; 0,50 en is goed voor een wasbeurt van 1 machine.
        Vanaf de tijd waarop je reserveert tot 15 minuten daarna kun je in het washok je was in de machine stoppen. Een wasmachine reserveer je altijd voor 1 uur en 15 minuten.
        Als je later dan 15 minuten na aanvang van je wasbeurt komt kan het zijn dat iemand anders de machine in gebruik genomen heeft.<br>
		<p><u>Wassen voor 6:30 uur of na 00:00 uur</u><br>
		Als je buiten de tijden van het systeem wilt wassen hoef je niet te reserveren.
        <br></p>
        <p><u>Drogen</u><br>
        Aansluitend aan je wasbeurt mag je een uur en een 15 minuten gebruik maken van de droger.
        Als je maar 1 machine gereserveerd hebt moet je overleggen met de andere bewoner over het delen van de droger.
        <br></p>
        <p><u>Reserveren</u> (online)<br>
        Op de homepage zie je een overzicht van dagen en tijden waarop gewassen kan worden.
        Zodra je op een tijd klikt kun je kiezen welke wasmachines je wilt reserveren.
        In het reserveringsscherm kies je welke wasmachines je wilt reserveren.
        Met je activeringscode kun je <u>niet</u> reserveren, gebruik hiervoor de gebruikerscode die je na het activeren per email hebt gekregen.<br>
        <u>Let op!</u> Je kunt per dag maximaal {$daylimit} wasmachines reserveren.
        Per periode van {$period} dagen kun je maximaal {$prelimit} wasmachines reserveren.
        <br></p>
        <p><u>Reserveren</u> (offline / ver vooruit)<br>
        Mocht je geen toegang hebben tot internet of verder vooruit willen reserveren dan {$prebooking} dagen, dan kun je tijdens spreekuren bij de beheerder reserveren.
        <br></p>
        <p>Mocht je nog vragen hebben dan ben je tijdens spreekuurtijden welkom bij het beheer.</p>
        <br>
        <p>Met vriendelijke groet,</p>

        <p>Student Beheer peliwash<br><br>
		studentbeheer_peliwash@hotmail.com

        </p>
</div>
</body>
</html>
{/if}
