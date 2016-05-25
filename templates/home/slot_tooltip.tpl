<div style="color:black;font-weight:bold;font-size:18px;">
Informatie
</div>
<div style="font-size:10px;margin-top:5px;margin-bottom:10px;color:black;font-weight:bold;">{$day} {$time}</div>
<table style="text-align:left">
    {if $slotinfo.status == 'available'}
		{foreach from=$slotinfo.available_machines item=machine}
		<tr>
			<td>
				<font class="green">
				{$machine}&nbsp;
				</font>
			</td>
			<td>vrij</td>
		</tr>
		{/foreach}
    {/if}
    {if count($slotinfo.owner)>0}
    {foreach from=$slotinfo.owner key=machine item=owner}
    <tr>
	<td><font style="color:red;font-weight: bold;">{$machine}</font></td><td>&nbsp;{$owner}</td>
    </tr>
    {/foreach}
    {/if}
</table>
<br>
{if $slotinfo.status == 'available'}
<div id="resmogelijk">Reserveren mogelijk</div>
{else}
<div id="resnietmogelijk">Reserveren niet mogelijk</div>
{/if}