<p style="position:absolute; top:0px; left:50%; margin-left:-125px;font-weight: bold; width:250px; text-align: center; background-color: red;">{$error}</p>
<div style="color:black;font-weight:bold;font-size:18px;">
Activeren - Stap 1/2
</div>
<br>
<form name="actaF">
	<div id="ActAform">
    <label>Activeringscode*</label><br>
    <input type="text" name="code" value="{$code}"><br>
    <label>Voornaam*</label><br>
    <input type="text" name="name" value="{$name}"><br>
    <label>Emailadres*</label><br>
    <input type="text" name="email" value="{$email}"><br>
    <br></div>
    <input onclick="actb()" id="submitbutton" type="button" value="Opslaan, en ga door naar stap 2"/><span id="checkingact"></span>
	<input type="submit" disabled="TRUE" style="display:none";" />
</form>
