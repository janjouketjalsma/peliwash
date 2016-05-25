
{if $Nieuws}
	<h1>Nieuws</h1><a onClick="viewPosts('Nieuws')">Alle nieuwsberichten</a><br>
	<ul>
		{foreach $Nieuws as $postitem=>$postinfo}
		{if $postinfo@index < 4}
		<li>
			<a onClick="viewPost('{$postinfo.ID}')">
			<h2>{$postinfo.title}</h2>
			</a>
			<p>{$postinfo.date|date_format:"%d/%m/%y"} | {$postinfo.contents|truncate:40:".."}</p>
		</li>
		{/if}
		{/foreach}
	</ul><br>
{/if}
{if $peliwash}
	<h1>peliwashinfo </h1><a onClick="viewPosts('peliwash')">Alle peliwashinfo</a><br>
	<ul>
		{foreach $peliwash as $postitem=>$postinfo}
		{if $postinfo@index < 2}
		<li>
			<a onClick="viewPost('{$postinfo.ID}')">
			<h2>{$postinfo.title}</h2>
			</a>
			<p>{$postinfo.contents|truncate:40:".."}</p>
		</li>
		{/if}
		{/foreach}
	</ul><br>
{/if}
{if $Wassen}
	<h1>Help</h1><a onClick="viewPosts('Wassen')">Alle artikelen</a><br>
	<ul>
		{foreach $Wassen as $postitem=>$postinfo}
		{if $postinfo@index < 2}
		<li>
			<a onClick="viewPost('{$postinfo.ID}')">
			<h2>{$postinfo.title}</h2>
			</a>
			<p>{$postinfo.contents|truncate:40:".."}</p>
		</li>
		{/if}
		{/foreach}
	</ul><br>
{/if}

