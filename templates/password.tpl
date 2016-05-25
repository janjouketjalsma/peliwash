<p style="position:absolute; top:0px; left:50%; margin-left:-125px;font-weight: bold; width:250px; text-align: center; background-color: red;">{$error}</p>
<div style="color:black;font-weight:bold;font-size:18px;">
Wachtwoord instellen - Stap 2/2
</div>
<br>
<label>Nieuwe code</label>
<form name="actbF">
<div id="ActBform">
<input style="display:inline;width:30px;" value="{$userID}" DISABLED type="text" /><input style="display:inline; width:70px;" type='password' name="usercode" /><br>
<div style="margin-left:10px;">
    <font size="1">
        <u>Let op: Alle tekens anders dan a-z, A-Z, 0-9, @, .,) of ( worden gefilterd uit je gebruikerscode</u><br>
        <b>Voorbeeld:</b><br>
    Als je in het lege vak het woord "peli" invult, wordt je  volledige code:<br>{$userID}peli</font>
</div>
<input type="hidden" name="usercode_challenge" value="{$code}"/>
<input type="hidden" name="userID" value="{$userID}"/>
<br><br></div>
<input onclick="actc()" id="submitbutton" type="button" value="Voltooien"/><span id="checkingact"></span>
</form>
