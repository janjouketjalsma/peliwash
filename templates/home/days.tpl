{foreach name=counter from=$slots key=day item=slot}
{if $smarty.foreach.counter.index == 0 || $smarty.foreach.counter.index is div by 3}
<div {if $smarty.foreach.counter.index==$selected}jFlowSelected{/if} class="slide-wrapper">
    <div id="myController"><span class="jFlowControl"></span></div>
    <table><tr>
{/if}
        <td class='day'>
            <p class='day'>{$day}</p>
            {foreach from=$slot key=time item=slotinfo}
            {if $slotinfo.status == 'available'}
            <a onmouseout="cancelslot()" onmouseover="slotTip('{$slotinfo.date}','{$time}')" onclick="BookingForm('{$slotinfo.date}','{$time}')">
            <div class='tijd_vrij'>
            {else}
            <div onmouseout="cancelslot()" onmouseover="slotTip('{$slotinfo.date}','{$time}')" class='tijd_bezet'>
            {/if}
			<div class="padtop">
                <font class="hover">&nbsp;&nbsp;{$time}</font>
            </div>
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
            {/foreach}
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