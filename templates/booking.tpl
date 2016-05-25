<div style="color:black;font-weight:bold;font-size:18px;">
Nieuwe reservering
</div>
<div style="color:black;font-weight:bold;">{$dateNL} {$time}</div>
<div id="bookingform">
<form name="booking">
<input type='hidden' name='date' value='{$date}'/>
<input type='hidden' name='time' value='{$time}'/>
<table>
	<tr>
		<td id='description'>Machine(s) reserveren</td><td>{html_checkboxes name='bookings' selected=$selected_machines values=$machines output=$machines separator='<br />'}</td>
	</tr>
<tr><td id='description'>Gebruikerscode</td><td><input onkeypress="return checkEnter(event)" type='password' name='usercode'/></td></tr>
<tr>
	<td id='description'>Bevestigingsmail sturen<br>(standaard)</td>
	<td>
		<input type='checkbox' checked disabled/>
	</td>
</tr>
</table><br>
</div>
<input onclick="BookingSubmitter()" id="submitbutton" type='button' value='Nu reserveren'/><span id="creatingbooking"></span>
<input type="submit" disabled="TRUE" style="display:none";" />
</form>
<p style="position:absolute; top:0px; left:50%; margin-left:-150px;font-weight: bold; width:300px; text-align: center; background-color: red;">{$message}</p>
</div>