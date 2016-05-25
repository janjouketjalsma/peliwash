                <div id="tijdsslot">Tijd<br><b>{$data.1.time} - {$data.2.time}</b></div>

                <img style="margin-left:20px;margin-right:30px;margin-top:15px;float:left;" src="img/peliwash.png" height="80px"/>

                <!-- A -->
                <div id="profile_base">
                    <div id="profile_pic">
                        <div class="profile_{$data.1.status.MachineA|default:"Vrij"}" id="profile_machineid">A <font style="font-size:20px;">({$data.1.status.MachineA|default:"Vrij"})</font></div>
                        {if !empty($data.1.facebook.MachineA)}
                        <img class="facebook" src="http://graph.facebook.com/{$data.1.facebook.MachineA1}/picture?type=large"/>
                        {else}
                        <img class="facebook" src="img/noprofile.jpg"/>
                        {/if}
                    </div>
                </div>
                <div class="userinfo">
                    {if !empty($data.1.status.MachineA)}
                    <table>
                        <tr>
                            <td class="grey">Naam</td><td>{$data.1.owner.MachineA}</td>
                        </tr>
                        <tr>
                            <td class="grey">Kamer</td><td>{$data.1.roomnumber.MachineA|default:"n.v.t."}</td>
                        </tr>
                    </table>
                    {/if}
                </div>
                <!-- B -->
                <div id="profile_base">
                    <div id="profile_pic">
                        <div class="profile_{$data.1.status.MachineB|default:"Vrij"}" id="profile_machineid">B <font style="font-size:20px;">({$data.1.status.MachineB|default:"Vrij"})</font></div>
                        {if !empty($data.1.facebook.MachineB)}
                        <img class="facebook" src="http://graph.facebook.com/{$data.1.facebook.MachineB}/picture?type=large"/>
                        {else}
                        <img class="facebook" src="img/noprofile.jpg"/>
                        {/if}
                    </div>
                </div>
                <div class="userinfo">
                    {if !empty($data.1.status.MachineB)}
                    <table>
                        <tr>
                            <td class="grey">Naam</td><td>{$data.1.owner.MachineB}</td>
                        </tr>
                        <tr>
                            <td class="grey">Kamer</td><td>{$data.1.roomnumber.MachineB|default:"n.v.t."}</td>
                        </tr>
                    </table>
                    {/if}
                </div>
