<html>
    <head>
         <LINK rel="stylesheet" href="admin/default.css" type="text/css" />
    </head>
    <body>

        <div id="overlay"></div>
        <div style="position:absolute;width:70%;height:100%;top:15%;left:15%">
            <form style="position:absolute; top:3px; right:3px;" action='?page=home'><input style="font-size: 20px;background-color: black;border:none;color:red;font-weight:bold" type='submit' value='X'/></form>
            <div style="position:relative;display:block;width:400px;"><div style="position:absolute;right:90px;top:85px;"><b>admin</b></div><img src='img/peliwash.png' /></div><br><br>
{if $authenticated}
{if !$backbutton}
<div style="float:right;width:210px; height: 300px;">
    {if $washokstatus}<b>Washokscherm</b>  <div id="washokonline">online</div><HR>
    {else}<b>Washokscherm</b><br><div id="washokoffline">offline</div> Neem contact op met JJ: 0641867444<br>
    {/if}
    <br>
    <b>Gebruikte wascapaciteit</b><br>
    <font style="font-size:10px;font-weight:bold;">Vandaag</font>
    <div style="height:18px;width:200px;border:1px solid #808080;position:relative;"><div style="width:{$capacitypercentagetoday*2}px;background-color:green;height:18px;position:absolute;left:0px;"></div><div style="position:absolute;left:0px;text-align:center;width:200px;">{$capacitypercentagetoday}%</div></div><br>
    <b>Geactiveerde bewoners</b><br>
    <div style="height:18px;width:200px;border:1px solid #808080;position:relative;"><div style="width:{$activeuserpercentage*2}px;background-color:green;height:18px;position:absolute;left:0px;"></div><div style="position:absolute;left:0px;text-align:center;width:200px;">{$activeusercount} ({$activeuserpercentage}%) </div></div><br>
    <br>
    <b>Bewoners in systeem</b><br>{$usercount}<br>
    <br>
     <form action="?page=admin" method="POST">
        <input type="hidden" name="user" value="{$user}"/>
        <input type="hidden" name="password" value="{$password}"/>
        <input type="hidden" name="task[]" value="defect"/>
        <b>Defecte machines</b><br>
        Machine A<input type="checkbox" {if in_array("Machine A",$defect)}checked="yes"{/if} name="defect[]" value="Machine A" /><br>
        Machine B<input type="checkbox" {if in_array("Machine B",$defect)}checked="yes"{/if} name="defect[]" value="Machine B" /><br>
        <input type="submit" value="Stel defecte machines in"/>
    </form>
    </div>


    <form action="?page=admin" method="POST">
    <label><b>Gebruikersbeheer</b></label><br>
    <input type="hidden" name="user" value="{$user}"/>
    <input type="hidden" name="password" value="{$password}"/>
	Bewoner
    {html_options name=userID options=$users.all_users}<br>
	Actie
    <select name="task[]">
		<option value="manual">Handleiding printen</option>
        <option value="delete_appointments">Wis alle wasafspraken</option>
        <option value="reset_user">Gebruiker resetten</option>
    </select><br>
    <input type="submit" value="Uitvoeren"/><br>
    </form><br>

    <form action="?page=admin" method="POST">
    <input type="hidden" name="user" value="{$user}"/>
    <input type="hidden" name="password" value="{$password}"/>
    <input type="hidden" name="task[]" value="booking_formdata"/>
    <label><b>Reservering maken</b></label><br>
    {html_options name=booking options=$book}
    <input type='submit' value='Volgende' />
    </form>
    <form action="?page=admin" method="POST">
    <input type="hidden" name="user" value="{$user}"/>
    <input type="hidden" name="password" value="{$password}"/>
    <input type="hidden" name="task[]" value="admin_email"/>
    <br><br>
    <div style="background-color:grey;padding:5px;margin-left:40px;width:262px;">
    <label>Probleem melden</label><br><br>
    <font style="font-size:10px;">
    <input type="radio" name="problem" value="scherm_uit"/>Er is een probleem met het scherm<br>
    <input type="radio" name="problem" value="traag"/>Het systeem werkt traag<br>
    <input type="radio" name="problem" value="resprobleem"/>Er is een probleem met het reserveren<br>
    <input type="radio" name="problem" value="anders"/>Anders (of ik heb een vraag)<br>
    <br>
    <textarea name="comment" cols="30" rows="6" style="font-size:12px;">beschrijf hier het probleem of je vraag over het systeem</textarea><br>
    </font>
    <br>
    <input type='submit' value='Meld nu!' />
    </form>
    </div>
	
	<div style="padding:5px;background-color:#400000" id="messages">
		<div style="text-align:center;color:white;font-size:20px;">Berichtenmanagement</div>
			Nieuw bericht
			<form action="?page=admin" method="POST">
			<input type="hidden" name="user" value="{$user}"/>
			<input type="hidden" name="password" value="{$password}"/>
			<input type="hidden" name="task[]" value="add_post"/>
			Titel:<input type="text" name="posttitle"/>
			Categorie:<select name="postcategory">
				<option value="peliwash">peliwash</option>
				<option value="Wassen">Wassen</option>
				<option value="Nieuws">Nieuws</option>
			</select><br>
			<textarea style="width:400px;height:200px;" name="postcontent"></textarea><br>
				<input type="submit" value="Opslaan"/>
			</form>
		Berichten<br>
		{foreach $posts as $post}
			<div style="color:red">Titel: {$post.title}<br><div style="font-size:12px;">Categorie: {$post.category}</div></div>
			<form action="?page=admin" method="POST">
			<input type="hidden" name="user" value="{$user}"/>
			<input type="hidden" name="password" value="{$password}"/>
			<input type="hidden" name="task[]" value="delete_post"/>
			<input type="hidden" name="postID" value="{$post.ID}"/>
			<input type="submit" value="wissen"/>
			</form>
		{/foreach}
	</div>
{else}
    <form action="?page=admin" method="POST">
    <input type="hidden" name="user" value="{$user}"/>
    <input type="hidden" name="password" value="{$password}"/>
    <input type="submit" value="Terug"/>
    </form>
{/if}
    {if isset($booking_formdata)}
    <b>Reserveren:</b><br><br>{$dateNL} om {$time}<br><br>
    <form action="?page=admin" method="POST">
    <input type="hidden" name="user" value="{$user}"/>
    <input type="hidden" name="password" value="{$password}"/>
    <input type="hidden" name="booking" value="{$booking}"/>
    <input type="hidden" name="task[]" value="make_booking"/>
    <label>Selecteer bewoner</label><br>
    {html_options name=userID options=$all_users}<br><br>
    <label>Selecteer machines</label><br>
    {html_checkboxes separator='<BR>' name=machines selected=$selected_machines values=$machines output=$machines}<br><br>
    <input type='submit' value='nu reserveren' />
    </form>
    {/if}


    
    {if isset($userinfo)}
    <b>Gebruiker:</b><br><br>
    <i><b>Info</b></i><br>
    <table style="border:1px solid black">
        <tr>
            <td>Naam</td><td>{$userinfo.firstname}</td>
        </tr>
        <tr>
            <td>Kamernummer</td><td>{$userinfo.roomnumber}</td>
        </tr>
        <tr>
            <td>Emailadres</td><td>{$userinfo.email}</td>
        </tr>
        <tr>
            <td>UserID</td><td>{$userinfo.userID}</td>
        </tr>
        <tr>
            <td>Handleiding</td>
            <td>
                <form action="?page=admin" method="POST" target="_blank">
                    <input type="hidden" name="user" value="{$user}"/>
                    <input type="hidden" name="password" value="{$password}"/>
                    <input type="hidden" name="task[]" value="manual"/>
                    <input type="hidden" name="userID" value="{$userinfo.userID}"/>
                    <input type="submit" value="printen"/>
                </form>
            </td>
        </tr>
        {if $userinfo.inactive == "TRUE"}
        <tr>
            <td>Activeringscode</td><td>{$userinfo.code}</td>
        </tr>
        {/if}
    </table>
    <br>
    <i><b>Acties</b></i><br>
    <form action="?page=admin" method="POST">
    <input type="hidden" name="user" value="{$user}"/>
    <input type="hidden" name="password" value="{$password}"/>
    <input type="hidden" name="userID" value="{$userinfo.userID}"/>
    <select name="task[]">
        <option value="delete_appointments">Wis alle wasafspraken</option>
        <option value="reset_user">Gebruiker resetten</option>
		<option value="manual">Handleiding printen</option>
    </select>
    <input type="hidden" name="task[]" value="userinfo"/>
    <input type="submit" name="task[]" value="Uitvoeren"/>
    </form>
    {/if}
    
{/if}
        </div>
</body>
</html>