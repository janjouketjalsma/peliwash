<div style="color:black;font-weight:bold;font-size:18px;">
Reservering gemaakt
</div>
<div style="color:black;font-size:12px;">
<b>Datum:</b> {$dateNL}<br>
<b>Tijd:</b> {$time}<br><br>
<b>Reservering voor:</b><br>
{html_table loop=$machines table_attr='border="0" cellspacing="3px"' td_attr='style="font-size:12px;"'}
</div><br>
<div style="color:green;font-weight:bold;">{$emailmessage.0}</div><div style="color:red">{$emailmessage.1}</div>
{$gcal}