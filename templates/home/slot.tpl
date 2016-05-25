{if $slotinfo.status == 'available'}
<a onmouseout="resetslot()" onmouseover="slottimer('?r=slot_tooltip&time={$time}&date={$slotinfo.date}')" data-tooltip="tooltip"  href="?page=booking&date={$slotinfo.date}&time={$time}">
<div class='tijd_vrij'>
{else}
<div onmouseout="resetslot()" onmouseover="slottimer('?r=slot_tooltip&time={$time}&date={$slotinfo.date}')" data-tooltip="tooltip" class='tijd_bezet'>
{/if}
<font class="hover">{$time}</font>
    
    {foreach from=$slotinfo.machine_status key=machine item=status}
        {if $status == 'beschikbaar'}
        <img class='mach' src='img/Ico/{$machine}_normaal.png' />
        {else}
        <img class='mach' src='img/Ico/{$machine}_bezet.png' />
        {/if}
    {/foreach}
</div>
{if $slotinfo.status == 'available'}
</a>
{/if}